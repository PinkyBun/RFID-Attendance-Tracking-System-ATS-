<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Models\AttendanceRecordModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\ActiveSessionModel;

class AttendanceController extends BaseController
{
    protected $attendanceModel;
    protected $studentModel;
    protected $subjectModel;
    protected $sessionModel;

    public function __construct()
    {
        $this->attendanceModel = new AttendanceRecordModel();
        $this->studentModel = new StudentModel();
        $this->subjectModel = new SubjectModel();
        $this->sessionModel = new ActiveSessionModel();
        
        helper('attendance');
    }

    public function index()
    {
        $filters = [
            'subject_id'    => $this->request->getGet('subject_id'),
            'start_date'    => $this->request->getGet('start_date') ?: date('Y-m-d'),
            'end_date'      => $this->request->getGet('end_date') ?: date('Y-m-d'),
            'status'        => $this->request->getGet('status'),
            'student_query' => $this->request->getGet('q'),
        ];

        $data = [
            'title'    => 'Attendance Records',
            'records'  => $this->attendanceModel->getRecordsForReportPaginated($filters, 10),
            'pager'    => $this->attendanceModel->pager,
            'subjects' => $this->subjectModel->findAll(),
            'filters'  => $filters
        ];

        return view('attendance/index', $data);
    }

    public function manual()
    {
        $data = [
            'title'    => 'Manual Attendance Entry',
            'students' => $this->studentModel->orderBy('last_name', 'ASC')->findAll(),
            'subjects' => $this->subjectModel->where('is_active', 1)->findAll()
        ];
        return view('attendance/manual', $data);
    }

    public function storeManual()
    {
        $rules = [
            'student_id'   => 'required',
            'subject_id'   => 'required',
            'session_date' => 'required',
            'status'       => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Logic check: Manual entry doesn't strictly need a session ID,
        // but we'll try to find an active or matching one, or leave null.
        $this->attendanceModel->insert([
            'session_id'   => null, // Manual doesn't belong to a specific live session
            'student_id'   => $this->request->getPost('student_id'),
            'subject_id'   => $this->request->getPost('subject_id'),
            'session_date' => $this->request->getPost('session_date'),
            'time_in'      => $this->request->getPost('session_date') . ' ' . $this->request->getPost('time_in'),
            'time_out'     => $this->request->getPost('time_out') ? $this->request->getPost('session_date') . ' ' . $this->request->getPost('time_out') : null,
            'status'       => 'manual',
            'is_manual'    => 1,
            'notes'        => $this->request->getPost('notes'),
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/attendance')->with('success', 'Manual attendance record saved.');
    }

    public function edit($id)
    {
        $record = $this->attendanceModel->find($id);
        if (!$record) return redirect()->to('/attendance')->with('error', 'Record not found.');

        $data = [
            'title'   => 'Edit Record',
            'record'  => $record,
            'student' => $this->studentModel->find($record['student_id']),
            'subject' => $this->subjectModel->find($record['subject_id'])
        ];

        return view('attendance/edit', $data);
    }

    public function update($id)
    {
        $this->attendanceModel->update($id, [
            'status'    => $this->request->getPost('status'),
            'notes'     => $this->request->getPost('notes'),
            'is_manual' => 1
        ]);

        return redirect()->to('/attendance')->with('success', 'Record updated.');
    }
}
