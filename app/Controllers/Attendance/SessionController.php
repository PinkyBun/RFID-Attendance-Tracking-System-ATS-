<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Models\SubjectModel;
use App\Models\SubjectScheduleModel;
use App\Models\ActiveSessionModel;
use App\Models\AttendanceRecordModel;

class SessionController extends BaseController
{
    protected $subjectModel;
    protected $scheduleModel;
    protected $sessionModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
        $this->scheduleModel = new SubjectScheduleModel();
        $this->sessionModel = new ActiveSessionModel();
        $this->attendanceModel = new AttendanceRecordModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Class Sessions',
            'sessions' => $this->sessionModel->select('active_sessions.*, subjects.name as subject_name, subjects.code as subject_code')
                                            ->join('subjects', 'subjects.id = active_sessions.subject_id')
                                            ->orderBy('opened_at', 'DESC')
                                            ->findAll()
        ];
        return view('attendance/session_index', $data);
    }

    public function select()
    {
        $data = [
            'title'          => 'Activate Class',
            'subjects'       => $this->subjectModel->where('is_active', 1)->findAll(),
            'current_session'=> $this->sessionModel->getCurrentSession()
        ];
        return view('attendance/session_select', $data);
    }

    public function open()
    {
        $subjectId = $this->request->getPost('subject_id');
        $scheduleId = $this->request->getPost('schedule_id');
        
        if (!$subjectId || !$scheduleId) {
            return redirect()->back()->with('error', 'Please select both a subject and a schedule slot.');
        }

        $this->sessionModel->openSession((int)$subjectId, (int)$scheduleId, date('Y-m-d'));

        return redirect()->to('/attendance/rfid')->with('success', 'Class session activated. Students can now tap their cards.');
    }

    public function close($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->sessionModel->closeSession($id);
        
        // Batch mark remaining students as incomplete
        $this->attendanceModel->markIncomplete($id);

        $db->transComplete();

        return redirect()->to('/attendance/session')->with('success', 'Session closed. All pending records updated to "Incomplete".');
    }

    public function current()
    {
        $session = $this->sessionModel->getCurrentSession();
        return $this->response->setJSON([
            'active' => !empty($session),
            'session' => $session
        ]);
    }
    
    /**
     * AJAX: Get schedules for a specific subject
     */
    public function getSchedules($subjectId)
    {
        $schedules = $this->scheduleModel->where('subject_id', $subjectId)->findAll();
        return $this->response->setJSON($schedules);
    }
}
