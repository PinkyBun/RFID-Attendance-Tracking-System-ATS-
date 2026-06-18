<?php

namespace App\Controllers\Students;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\SectionModel;
use App\Models\SubjectModel;
use App\Models\StudentEnrollmentModel;
use App\Models\AttendanceRecordModel;

class StudentController extends BaseController
{
    protected $studentModel;
    protected $sectionModel;
    protected $subjectModel;
    protected $enrollmentModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->sectionModel = new SectionModel();
        $this->subjectModel = new SubjectModel();
        $this->enrollmentModel = new StudentEnrollmentModel();
        $this->attendanceModel = new AttendanceRecordModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Manage Students',
            'students' => $this->studentModel->select('students.*, sections.name as section_name')
                                              ->join('sections', 'sections.id = students.section_id', 'left')
                                              ->orderBy('students.last_name', 'ASC')
                                              ->paginate(10),
            'pager'    => $this->studentModel->pager,
            'subjects' => $this->subjectModel->findAll(),
            'sections' => $this->sectionModel->findAll()
        ];
        return view('students/index', $data);
    }

    public function show($id)
    {
        $student = $this->studentModel->find($id);
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }

        $section = $student['section_id'] ? $this->sectionModel->find($student['section_id']) : null;
        
        $data = [
            'title'      => 'Student Profile',
            'student'    => $student,
            'section'    => $section,
            'enrollments'=> $this->enrollmentModel->getEnrolledSubjects($id),
            'attendance' => $this->attendanceModel->select('attendance_records.*, subjects.name as subject_name')
                                                 ->join('subjects', 'subjects.id = attendance_records.subject_id')
                                                 ->where('student_id', $id)
                                                 ->orderBy('session_date', 'DESC')
                                                 ->limit(20)
                                                 ->findAll()
        ];

        return view('students/show', $data);
    }

    public function createRegular()
    {
        $data = [
            'title'    => 'Register Regular Student',
            'sections' => $this->sectionModel->findAll()
        ];
        return view('students/create_regular', $data);
    }

    public function storeRegular()
    {
        $rules = [
            'rfid_uid'       => 'required|is_unique[students.rfid_uid]',
            'student_number' => 'required|is_unique[students.student_number]',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'section_id'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $section = $this->sectionModel->find($this->request->getPost('section_id'));
        
        $db = \Config\Database::connect();
        $db->transStart();

        $studentId = $this->studentModel->insert([
            'rfid_uid'       => $this->request->getPost('rfid_uid'),
            'student_number' => $this->request->getPost('student_number'),
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'type'           => 'regular',
            'section_id'     => $this->request->getPost('section_id'),
            'year_level'     => $section['year_level'],
            'is_active'      => 1
        ]);

        // Auto-enroll in all subjects belonging to this section
        $subjects = $this->subjectModel->where('section_id', $this->request->getPost('section_id'))->findAll();
        if (!empty($subjects)) {
            $subjectIds = array_column($subjects, 'id');
            $this->enrollmentModel->bulkEnroll((int)$studentId, $subjectIds);
        }

        $db->transComplete();

        return redirect()->to('/students')->with('success', 'Regular student registered and auto-enrolled successfully.');
    }

    public function createIrregular()
    {
        $data = [
            'title'    => 'Register Irregular Student',
            'subjects' => $this->subjectModel->findAll()
        ];
        return view('students/create_irregular', $data);
    }

    public function storeIrregular()
    {
        $rules = [
            'rfid_uid'       => 'required|is_unique[students.rfid_uid]',
            'student_number' => 'required|is_unique[students.student_number]',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'year_level'     => 'required',
            'subject_ids'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $studentId = $this->studentModel->insert([
            'rfid_uid'       => $this->request->getPost('rfid_uid'),
            'student_number' => $this->request->getPost('student_number'),
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'type'           => 'irregular',
            'section_id'     => null,
            'year_level'     => $this->request->getPost('year_level'),
            'is_active'      => 1
        ]);

        // Manual enrollment
        $subjectIds = $this->request->getPost('subject_ids');
        if (!empty($subjectIds)) {
            $this->enrollmentModel->bulkEnroll((int)$studentId, $subjectIds);
        }

        $db->transComplete();

        return redirect()->to('/students')->with('success', 'Irregular student registered successfully.');
    }

    public function edit($id)
    {
        $student = $this->studentModel->find($id);
        if (!$student) return redirect()->to('/students')->with('error', 'Student not found.');

        $data = [
            'title'      => 'Edit Student',
            'student'    => $student,
            'sections'   => $this->sectionModel->findAll(),
            'subjects'   => $this->subjectModel->findAll(),
            'enrolled_ids' => array_column($this->enrollmentModel->where('student_id', $id)->findAll(), 'subject_id')
        ];

        return view('students/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'rfid_uid'       => "required|is_unique[students.rfid_uid,id,{$id}]",
            'student_number' => "required|is_unique[students.student_number,id,{$id}]",
            'first_name'     => 'required',
            'last_name'      => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $updateData = [
            'rfid_uid'       => $this->request->getPost('rfid_uid'),
            'student_number' => $this->request->getPost('student_number'),
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'is_active'      => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($this->request->getPost('year_level')) {
            $updateData['year_level'] = $this->request->getPost('year_level');
        }

        $this->studentModel->update($id, $updateData);

        // Update enrollment if subject_ids are provided (manual refresh)
        $subjectIds = $this->request->getPost('subject_ids');
        if ($subjectIds !== null) {
            $this->enrollmentModel->where('student_id', $id)->delete();
            if (!empty($subjectIds)) {
                $this->enrollmentModel->bulkEnroll((int)$id, $subjectIds);
            }
        }

        $db->transComplete();

        return redirect()->to('/students/'.$id)->with('success', 'Student details updated.');
    }

    public function destroy($id)
    {
        $this->studentModel->delete($id);
        return redirect()->to('/students')->with('success', 'Student record deleted.');
    }

    public function import()
    {
        $data = [
            'title' => 'Import Students',
        ];
        return view('students/import', $data);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // 1. Setup the main "Template" sheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template');

        // Set exact headers
        $headers = ['Student Name', 'Student ID', 'Year Level', 'Section', 'Student Type', 'Subject'];
        $colMap = ['A', 'B', 'C', 'D', 'E', 'F'];
        foreach ($headers as $index => $header) {
            $sheet->setCellValue($colMap[$index] . '1', $header);
        }

        // Apply Header Styling
        $headerStyle = $sheet->getStyle('A1:F1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9E1F2'); // Light Blue

        // Set Sample Data
        $sampleData = [
            ['Juan Dela Cruz', '2026-00001', '1st Year', 'BSIT 1D', 'Regular', 'SIA101'],
            ['Maria Santos', '2026-00002', '2nd Year', 'BSIT 2C', 'Irregular', 'HCI102, DB201'],
            ['Jose Rizal', '2026-00003', '3rd Year', 'BSIT 3B', 'Regular', 'NET201, SENG101'],
            ['Andres Bonifacio', '2026-00004', '4th Year', 'BSIT 4A', 'Irregular', 'HCI102, CAP201'],
            ['Gabriela Silang', '2026-00005', '3rd Year', 'BSCS 3A', 'Regular', 'SIA101']
        ];

        $rowNum = 2;
        foreach ($sampleData as $rowData) {
            foreach ($rowData as $colIndex => $value) {
                $sheet->setCellValue($colMap[$colIndex] . $rowNum, $value);
            }
            $rowNum++;
        }

        // Dropdown Validation for Year Level (C2:C100)
        $yearLevelValidation = $sheet->getDataValidation('C2');
        $yearLevelValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $yearLevelValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $yearLevelValidation->setAllowBlank(false);
        $yearLevelValidation->setShowDropDown(true);
        $yearLevelValidation->setFormula1('"1st Year,2nd Year,3rd Year,4th Year"');
        $yearLevelValidation->setSqref('C2:C100');

        // Dropdown Validation for Student Type (E2:E100)
        $studentTypeValidation = $sheet->getDataValidation('E2');
        $studentTypeValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $studentTypeValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $studentTypeValidation->setAllowBlank(false);
        $studentTypeValidation->setShowDropDown(true);
        $studentTypeValidation->setFormula1('"Regular,Irregular"');
        $studentTypeValidation->setSqref('E2:E100');

        // Auto-Size Columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 2. Setup the "Instructions" sheet
        $instructionSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Instructions');
        $spreadsheet->addSheet($instructionSheet);

        $instructions = [
            ['Import Guidelines:'],
            ['- Correct Column Order: Student Name, Student ID, Year Level, Section, Student Type, Subject. Do not alter the first row headers.'],
            ['- Student Type: Accepted values are exactly "Regular" or "Irregular". Use the dropdown in the column.'],
            ['- Year Level: Accepted values are exactly "1st Year", "2nd Year", "3rd Year", or "4th Year". Use the dropdown.'],
            ['- No RFID Provided: RFID UID is NOT included in this file. It will be assigned separately after import by tapping the physical card on the dashboard.'],
            ['- Unique Identification: The Student ID must be unique. If a Student ID already exists in the system, that row will be skipped and reported in the overview.'],
            ['- Required Fields: All columns are strictly required. Leaving any column blank will result in a processing error for that particular row.'],
            ['- Error Handling: Follow the error report shown on the web page after import. It will dictate the row number and the exact cause of failure so you can easily correct the source file and try again.']
        ];

        $instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $rowIdx = 1;
        foreach ($instructions as $inst) {
            $instructionSheet->setCellValue('A' . $rowIdx, $inst[0]);
            $rowIdx++;
        }
        $instructionSheet->getColumnDimension('A')->setWidth(100);

        // Set active sheet back to Template
        $spreadsheet->setActiveSheetIndex(0);

        // Download Response
        $filename = 'students_import_template.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function processImport()
    {
        $file = $this->request->getFile('excel_file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please upload a valid Excel file.');
        }

        $extension = $file->getClientExtension();
        if (!in_array($extension, ['xls', 'xlsx', 'csv'])) {
            return redirect()->back()->with('error', 'Invalid file format. Only Excel or CSV allowed.');
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading file: ' . $e->getMessage());
        }

        if (count($rows) < 2) {
            return redirect()->back()->with('error', 'File is empty or missing headers.');
        }

        // Expected headers mapping
        $headers = array_map('trim', array_map('strtolower', $rows[0]));
        $expected = ['student name', 'student id', 'year level', 'section', 'student type', 'subject'];
        
        $colMap = [];
        foreach ($expected as $exp) {
            $idx = array_search($exp, $headers);
            if ($idx === false) {
                return redirect()->back()->with('error', "Missing required column: '{$exp}'");
            }
            $colMap[$exp] = $idx;
        }

        $errors = [];
        $successCount = 0;
        $skipCount = 0;

        $db = \Config\Database::connect();
        
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Check if row is completely empty
            $isEmpty = true;
            foreach ($row as $cell) {
                if (!empty(trim((string)$cell))) { $isEmpty = false; break; }
            }
            if ($isEmpty) continue;

            $studentName = trim((string)($row[$colMap['student name']] ?? ''));
            $studentIdCode = trim((string)($row[$colMap['student id']] ?? ''));
            $yearLevel = trim((string)($row[$colMap['year level']] ?? ''));
            $sectionName = trim((string)($row[$colMap['section']] ?? ''));
            $studentType = strtolower(trim((string)($row[$colMap['student type']] ?? '')));
            $subjectStr = trim((string)($row[$colMap['subject']] ?? ''));

            if (empty($studentName) || empty($studentIdCode) || empty($studentType)) {
                $errors[] = "Row " . ($i + 1) . ": Missing Name, ID, or Type.";
                continue;
            }

            $lastName = '';
            $firstName = '';
            if (strpos($studentName, ',') !== false) {
                $parts = explode(',', $studentName, 2);
                $lastName = trim($parts[0]);
                $firstName = trim($parts[1]);
            } else {
                $parts = explode(' ', $studentName, 2);
                $firstName = trim($parts[0] ?? '');
                $lastName = trim($parts[1] ?? '');
                if (empty($lastName)) {
                    $lastName = $firstName;
                    $firstName = '';
                }
            }

            // Validate duplicate
            if ($this->studentModel->where('student_number', $studentIdCode)->first()) {
                $skipCount++;
                $errors[] = "Row " . ($i + 1) . ": Skipped. Student ID $studentIdCode already exists.";
                continue;
            }

            $db->transStart();

            // Find Section
            $sectionId = null;
            if (!empty($sectionName)) {
                $section = $this->sectionModel->where('name', $sectionName)->first();
                if (!$section) {
                    $errors[] = "Row " . ($i + 1) . ": Invalid Section '$sectionName'.";
                    $db->transRollback();
                    continue;
                }
                $sectionId = $section['id'];
                if (empty($yearLevel)) {
                    $yearLevel = $section['year_level'];
                }
            }

            $newStudent = [
                'student_number' => $studentIdCode,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'type' => $studentType === 'irregular' ? 'irregular' : 'regular',
                'section_id' => $sectionId,
                'year_level' => $yearLevel,
                'is_active' => 1
            ];

            $insertId = $this->studentModel->insert($newStudent);
            if (!$insertId) {
                $errors[] = "Row " . ($i + 1) . ": Failed to save student.";
                $db->transRollback();
                continue;
            }

            // Enrollment mapping
            if (!empty($subjectStr)) {
                $subjNames = array_map('trim', explode(',', $subjectStr));
                $subjectIds = [];
                foreach ($subjNames as $sName) {
                    $sub = $this->subjectModel->where('name', $sName)->orWhere('code', $sName)->first();
                    if ($sub) {
                        $subjectIds[] = $sub['id'];
                    }
                }
                if (!empty($subjectIds)) {
                    $this->enrollmentModel->bulkEnroll((int)$insertId, $subjectIds);
                }
            } else if ($studentType === 'regular' && $sectionId) {
                // Auto enroll in section subjects if subject col is empty
                $subjects = $this->subjectModel->where('section_id', $sectionId)->findAll();
                if (!empty($subjects)) {
                    $subjectIds = array_column($subjects, 'id');
                    $this->enrollmentModel->bulkEnroll((int)$insertId, $subjectIds);
                }
            }

            $db->transComplete();
            
            if ($db->transStatus() === false) {
                 $errors[] = "Row " . ($i + 1) . ": Database error during save.";
            } else {
                 $successCount++;
            }
        }

        session()->setFlashdata('import_errors', $errors);
        
        $msg = "Import complete. Successfully added: {$successCount}.";
        if ($skipCount > 0) $msg .= " Skipped: {$skipCount}.";
        if (count($errors) > 0) {
            return redirect()->to('/students/import')->with('warning', $msg . " See errors below.");
        }
        
        return redirect()->to('/students/import')->with('success', $msg);
    }

    public function rfidAssign()
    {
        $students = $this->studentModel->where('rfid_uid', null)
                                       ->orWhere('rfid_uid', '')
                                       ->orderBy('last_name', 'ASC')
                                       ->findAll();
        $data = [
            'title' => 'RFID Assignment',
            'students' => $students
        ];
        return view('students/rfid_assign', $data);
    }

    public function captureRfid()
    {
        $studentId = $this->request->getPost('student_id');
        $rfidUid = trim($this->request->getPost('rfid_uid'));

        if (empty($studentId) || empty($rfidUid)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing student ID or RFID UID.']);
        }

        // check if RFID is already taken
        $existing = $this->studentModel->where('rfid_uid', $rfidUid)->first();
        if ($existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'This RFID card is already assigned to another student.']);
        }

        $updated = $this->studentModel->update($studentId, ['rfid_uid' => $rfidUid]);
        
        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'RFID assigned successfully!']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to save RFID to database.']);
    }

    public function exportExcel()
    {
        $subjectId = $this->request->getGet('subject_id');
        $sectionId = $this->request->getGet('section_id');

        if (!$subjectId || !$sectionId) {
            return redirect()->back()->with('error', 'Subject and Section are required to export.');
        }

        $subject = $this->subjectModel->find($subjectId);
        $section = $this->sectionModel->find($sectionId);

        if (!$subject || !$section) {
            return redirect()->back()->with('error', 'Invalid subject or section.');
        }

        $students = $this->studentModel->select('students.*')
            ->join('student_enrollments', 'student_enrollments.student_id = students.id')
            ->where('student_enrollments.subject_id', $subjectId)
            ->where('students.section_id', $sectionId)
            ->groupBy('students.id') // Ensure unique students if enrolled in same subject mistakenly
            ->orderBy('students.last_name', 'ASC')
            ->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Student Name');
        $sheet->setCellValue('B1', 'Student ID');
        $sheet->setCellValue('C1', 'Year Level');
        $sheet->setCellValue('D1', 'Section');
        $sheet->setCellValue('E1', 'Student Type');
        $sheet->setCellValue('F1', 'Subject');

        $row = 2;
        foreach ($students as $stu) {
            $sheet->setCellValue('A' . $row, $stu['last_name'] . ', ' . $stu['first_name']);
            $sheet->setCellValue('B' . $row, $stu['student_number']);
            $sheet->setCellValue('C' . $row, $stu['year_level']);
            $sheet->setCellValue('D' . $row, $section['name']);
            $sheet->setCellValue('E' . $row, ucfirst($stu['type']));
            $sheet->setCellValue('F' . $row, $subject['code']);
            $row++;
        }

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $filename = "Students_{$subject['code']}_{$section['name']}.xlsx";
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
