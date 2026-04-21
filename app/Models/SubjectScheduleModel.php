<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectScheduleModel extends Model
{
    protected $table            = 'subject_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['subject_id', 'day_of_week', 'start_time', 'end_time'];

    // Dates
    protected $useTimestamps = false; // No timestamp fields in this table

    public function getSchedulesForSubject(int $subjectId)
    {
        return $this->where('subject_id', $subjectId)->findAll();
    }

    public function getTodaySchedules()
    {
        $today = date('D'); // Mon, Tue, etc.
        return $this->select('subject_schedules.*, subjects.name as subject_name, subjects.code as subject_code')
                    ->join('subjects', 'subjects.id = subject_schedules.subject_id')
                    ->where('day_of_week', $today)
                    ->findAll();
    }
}
