<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceRecordModel extends Model
{
    protected $table            = 'attendance_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'session_id', 'student_id', 'subject_id', 'session_date', 'time_in', 'time_out', 
        'status', 'is_cross_section', 'cross_section_note', 'is_manual', 'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function logTimeIn(array $data)
    {
        return $this->insert($data);
    }

    public function logTimeOut(int $sessionId, int $studentId)
    {
        $record = $this->findBySessionAndStudent($sessionId, $studentId);
        if ($record) {
            return $this->update($record['id'], [
                'time_out' => date('Y-m-d H:i:s')
            ]);
        }
        return false;
    }

    public function markIncomplete(int $sessionId)
    {
        return $this->where('session_id', $sessionId)
                    ->where('time_out', null)
                    ->set(['status' => 'incomplete'])
                    ->update();
    }

    public function getRecordsForSession(int $sessionId)
    {
        return $this->select('attendance_records.*, students.first_name, students.last_name, students.student_number')
                    ->join('students', 'students.id = attendance_records.student_id')
                    ->where('session_id', $sessionId)
                    ->orderBy('attendance_records.updated_at', 'DESC')
                    ->findAll();
    }

    public function getRecordsForReportPaginated(array $filters = [], int $perPage = 10)
    {
        $this->select('attendance_records.*, students.first_name, students.last_name, students.student_number, subjects.code as subject_code')
             ->join('students', 'students.id = attendance_records.student_id')
             ->join('subjects', 'subjects.id = attendance_records.subject_id');

        if (!empty($filters['subject_id'])) {
            $this->where('attendance_records.subject_id', $filters['subject_id']);
        }
        if (!empty($filters['start_date'])) {
            $this->where('attendance_records.session_date >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->where('attendance_records.session_date <=', $filters['end_date']);
        }
        if (!empty($filters['status'])) {
            if (is_array($filters['status'])) {
                $this->whereIn('attendance_records.status', $filters['status']);
            } else {
                $this->where('attendance_records.status', $filters['status']);
            }
        }
        if (!empty($filters['student_query'])) {
            $this->groupStart()
                 ->like('students.first_name', $filters['student_query'])
                 ->orLike('students.last_name', $filters['student_query'])
                 ->orLike('students.student_number', $filters['student_query'])
                 ->groupEnd();
        }

        return $this->orderBy('attendance_records.session_date', 'DESC')
                    ->orderBy('students.last_name', 'ASC')
                    ->paginate($perPage);
    }

    public function findBySessionAndStudent(int $sessionId, int $studentId)
    {
        return $this->where('session_id', $sessionId)
                    ->where('student_id', $studentId)
                    ->first();
    }
}
