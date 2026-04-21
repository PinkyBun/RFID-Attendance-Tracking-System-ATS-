<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\ActiveSessionModel;
use App\Models\AttendanceRecordModel;

class DashboardController extends BaseController
{
    protected $studentModel;
    protected $subjectModel;
    protected $sessionModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->subjectModel = new SubjectModel();
        $this->sessionModel = new ActiveSessionModel();
        $this->attendanceModel = new AttendanceRecordModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'total_students' => $this->studentModel->countAllResults(),
                'total_subjects' => $this->subjectModel->countAllResults(),
                'today_taps'     => $this->attendanceModel->where('session_date', date('Y-m-d'))->countAllResults(),
                'active_session' => $this->sessionModel->getCurrentSession(),
            ],
            'recent_taps' => $this->attendanceModel->select('attendance_records.*, students.first_name, students.last_name')
                                                  ->join('students', 'students.id = attendance_records.student_id')
                                                  ->orderBy('attendance_records.updated_at', 'DESC')
                                                  ->limit(5)
                                                  ->findAll(),
        ];

        return view('dashboard/index', $data);
    }
}
