<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Models\ActiveSessionModel;
use App\Models\AttendanceRecordModel;
use App\Models\StudentModel;
use App\Libraries\RfidProcessor;

class RfidController extends BaseController
{
    protected $sessionModel;
    protected $attendanceModel;
    protected $studentModel;
    protected $rfidProcessor;

    public function __construct()
    {
        $this->sessionModel = new ActiveSessionModel();
        $this->attendanceModel = new AttendanceRecordModel();
        $this->studentModel = new StudentModel();
        $this->rfidProcessor = new RfidProcessor();
    }

    public function live()
    {
        $session = $this->sessionModel->getCurrentSession();
        if (!$session) {
            return redirect()->to('/attendance/session/select')->with('error', 'Please activate a class session first.');
        }

        $data = [
            'title'   => 'Live RFID Tapping',
            'session' => $session,
            'students' => $this->studentModel->where('is_active', 1)->findAll(),
            'recent_taps' => $this->attendanceModel->select('attendance_records.*, students.first_name, students.last_name')
                                                  ->join('students', 'students.id = attendance_records.student_id')
                                                  ->where('session_id', $session['id'])
                                                  ->orderBy('attendance_records.updated_at', 'DESC')
                                                  ->limit(5)
                                                  ->findAll()
        ];
        
        return view('attendance/rfid_tap', $data);
    }

    public function tap()
    {
        $rfidUid = $this->request->getPost('rfid_uid');
        
        if (empty($rfidUid)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No RFID UID received.'
            ]);
        }

        $result = $this->rfidProcessor->process($rfidUid);
        
        return $this->response->setJSON($result);
    }
}
