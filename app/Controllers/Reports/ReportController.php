<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;
use App\Models\AttendanceRecordModel;
use App\Models\SubjectModel;
use App\Models\SectionModel;
use App\Models\StudentModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends BaseController
{
    protected $attendanceModel;
    protected $subjectModel;
    protected $sectionModel;
    protected $studentModel;

    public function __construct()
    {
        $this->attendanceModel = new AttendanceRecordModel();
        $this->subjectModel = new SubjectModel();
        $this->sectionModel = new SectionModel();
        $this->studentModel = new StudentModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Attendance Reports',
            'subjects' => $this->subjectModel->select('name')->distinct()->findAll()
        ];

        return view('reports/generate', $data);
    }

    /**
     * AJAX endpoint to fetch sections for a given subject name
     */
    public function getSections($subjectName)
    {
        $subjectName = urldecode($subjectName);
        $sections = $this->subjectModel->select('sections.id, sections.name')
                                       ->join('sections', 'sections.id = subjects.section_id')
                                       ->where('subjects.name', $subjectName)
                                       ->findAll();
        
        return $this->response->setJSON($sections);
    }

    /**
     * Show report preview in browser
     */
    public function preview()
    {
        $subjectName = $this->request->getPost('subject_name');
        $sectionId   = $this->request->getPost('section_id');
        $date        = $this->request->getPost('report_date');

        if (!$subjectName || !$sectionId || !$date) {
            return redirect()->back()->with('error', 'All filters are required.');
        }

        $reportData = $this->getReportData($subjectName, $sectionId, $date);

        if (empty($reportData['records'])) {
            return redirect()->back()->with('error', 'No attendance records found for the selected criteria.');
        }

        $data = [
            'title'      => 'Report Preview',
            'report'     => $reportData,
            'filters'    => [
                'subject_name' => $subjectName,
                'section_id'   => $sectionId,
                'report_date'  => $date
            ]
        ];

        return view('reports/preview', $data);
    }

    /**
     * Download report as PDF
     */
    public function pdf()
    {
        $subjectName = $this->request->getGet('subject_name');
        $sectionId   = $this->request->getGet('section_id');
        $date        = $this->request->getGet('report_date');

        $reportData = $this->getReportData($subjectName, $sectionId, $date);

        // Setup Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        
        $dompdf = new Dompdf($options);
        
        // Render view to HTML
        $html = view('reports/pdf_template', ['report' => $reportData]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = "Attendance_Report_{$reportData['subject']['code']}_{$reportData['section']['name']}_{$date}.pdf";
        
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setBody($dompdf->output())
                              ->noCache();
    }

    /**
     * Internal helper to collect report data
     */
    private function getReportData($subjectName, $sectionId, $date)
    {
        $subject = $this->subjectModel->where('name', $subjectName)
                                      ->where('section_id', $sectionId)
                                      ->first();
        
        $section = $this->sectionModel->find($sectionId);

        if (!$subject) return null;

        $records = $this->attendanceModel->select('attendance_records.*, students.first_name, students.last_name, students.student_number')
                                         ->join('students', 'students.id = attendance_records.student_id')
                                         ->where('attendance_records.subject_id', $subject['id'])
                                         ->where('attendance_records.session_date', $date)
                                         ->orderBy('students.last_name', 'ASC')
                                         ->findAll();

        // Calculate Summary
        $summary = [
            'total'      => count($records),
            'on_time'    => 0,
            'late'       => 0,
            'incomplete' => 0,
            'manual'     => 0
        ];

        foreach ($records as $r) {
            if ($r['status'] == 'on_time') $summary['on_time']++;
            if ($r['status'] == 'late') $summary['late']++;
            if ($r['status'] == 'incomplete') $summary['incomplete']++;
            if ($r['is_manual']) $summary['manual']++;
        }

        return [
            'subject' => $subject,
            'section' => $section,
            'date'    => $date,
            'records' => $records,
            'summary' => $summary
        ];
    }

    public function export()
    {
        // Keep existing CSV export if needed, or update it
        $records = $this->attendanceModel->select('attendance_records.*, students.first_name, students.last_name, students.student_number, subjects.code as subject_code')
                                        ->join('students', 'students.id = attendance_records.student_id')
                                        ->join('subjects', 'subjects.id = attendance_records.subject_id')
                                        ->orderBy('session_date', 'DESC')
                                        ->findAll();

        $filename = 'attendance_report_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Student #', 'Name', 'Subject', 'Time In', 'Time Out', 'Status']);

        foreach ($records as $row) {
            fputcsv($output, [
                $row['session_date'],
                $row['student_number'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['subject_code'],
                $row['time_in'],
                $row['time_out'],
                $row['status']
            ]);
        }

        fclose($output);
        exit;
    }
}
