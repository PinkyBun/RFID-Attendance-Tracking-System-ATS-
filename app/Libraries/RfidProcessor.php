<?php

namespace App\Libraries;

use App\Models\StudentModel;
use App\Models\ActiveSessionModel;
use App\Models\AttendanceRecordModel;
use App\Models\StudentEnrollmentModel;

class RfidProcessor
{
    protected $studentModel;
    protected $activeSessionModel;
    protected $attendanceModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->activeSessionModel = new ActiveSessionModel();
        $this->attendanceModel = new AttendanceRecordModel();
        $this->enrollmentModel = new StudentEnrollmentModel();
        
        // Load the helper
        helper('attendance');
    }

    /**
     * Processes an RFID tap.
     *
     * @param string $rfidUid
     * @return array [success => bool, message => string, data => array|null]
     */
    public function process(string $rfidUid)
    {
        // 1. Find Student
        $student = $this->studentModel->findByRfid($rfidUid);
        if (!$student) {
            return [
                'success' => false,
                'message' => 'RFID card not recognized. Please register first.'
            ];
        }

        if (!$student['is_active']) {
            return [
                'success' => false,
                'message' => 'This student account is inactive. Please contact your teacher.'
            ];
        }

        // 2. Find Active Session
        $session = $this->activeSessionModel->getCurrentSession();
        if (!$session) {
            return [
                'success' => false,
                'message' => 'No active class session. Teacher must select a subject first.'
            ];
        }

        // 3. Check existing record
        $existingRecord = $this->attendanceModel->findBySessionAndStudent($session['id'], $student['id']);

        if ($existingRecord) {
            // Already tapped in, check if they already tapped out
            if ($existingRecord['time_out'] !== null) {
                return [
                    'success' => false,
                    'message' => "You have already completed attendance for this session."
                ];
            }

            // Next expected action is TIME OUT. 
            // Check for double-tap confirmation flag in session.
            $sessionSvc = session();
            $pendingKey = 'pending_timeout_' . $student['rfid_uid'];
            
            if (!$sessionSvc->get($pendingKey)) {
                // First attempt to timeout after timing in: Warning/Rejection
                $sessionSvc->set($pendingKey, true);
                return [
                    'success' => false,
                    'message' => "You have already timed in for this session. Please tap again to time out."
                ];
            }

            // Second attempt: Update time out
            $sessionSvc->remove($pendingKey);
            $this->attendanceModel->logTimeOut($session['id'], $student['id']);
            
            return [
                'success' => true,
                'message' => "Time-out recorded for {$student['first_name']}.",
                'data' => [
                    'student_name' => "{$student['first_name']} {$student['last_name']}",
                    'tap_type' => 'time_out',
                    'time' => date('h:i A')
                ]
            ];
        }

        // 4. Initial tap: Check enrollment/eligibility
        $enrollment = $this->enrollmentModel->where('student_id', $student['id'])
                                           ->where('subject_id', $session['subject_id'])
                                           ->first();
        if (!$enrollment) {
            return [
                'success' => false,
                'message' => "You are not enrolled in this subject."
            ];
        }

        // Initial tap: TIME IN

        $isCrossSection = 0;
        $crossSectionNote = null;

        // Cross-section check for regular students
        if ($student['type'] === 'regular') {
            // Important: Subjects table should have section_id
            $subjectModel = new \App\Models\SubjectModel();
            $subject = $subjectModel->find($session['subject_id']);
            
            if ($subject && $subject['section_id'] != $student['section_id']) {
                $isCrossSection = 1;
                // Get section names for the note
                $sectionModel = new \App\Models\SectionModel();
                $actualSection = $sectionModel->find($student['section_id']);
                $classSection = $sectionModel->find($subject['section_id']);
                
                $actualName = $actualSection['name'] ?? 'Unknown';
                $className = $classSection['name'] ?? 'Unknown';
                
                $crossSectionNote = "Student from {$actualName} tapped in {$className} class.";
            }
        }

        // Calculate Status
        $now = date('Y-m-d H:i:s');
        $status = calculate_attendance_status($now, $session['start_time']);

        // Insert Record
        $recordData = [
            'session_id'       => $session['id'],
            'student_id'       => $student['id'],
            'subject_id'       => $session['subject_id'],
            'session_date'     => $session['session_date'],
            'time_in'          => $now,
            'status'           => $status,
            'is_cross_section' => $isCrossSection,
            'cross_section_note' => $crossSectionNote,
            'is_manual'        => 0,
            'created_at'       => $now,
            'updated_at'       => $now
        ];

        $this->attendanceModel->logTimeIn($recordData);

        return [
            'success' => true,
            'message' => "Welcome, {$student['first_name']}! You are marked as " . ($status == 'on_time' ? 'On Time' : 'Late') . ".",
            'data' => [
                'student_name' => "{$student['first_name']} {$student['last_name']}",
                'tap_type' => 'time_in',
                'status' => $status,
                'is_cross_section' => $isCrossSection,
                'time' => date('h:i A', strtotime($now))
            ]
        ];
    }
}
