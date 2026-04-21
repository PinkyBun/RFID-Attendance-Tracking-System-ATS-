<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiveSessionModel extends Model
{
    protected $table            = 'active_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['subject_id', 'schedule_id', 'session_date', 'opened_at', 'closed_at', 'is_active'];

    // Dates
    protected $useTimestamps = false; // Using custom opened_at and closed_at

    public function openSession(int $subjectId, int $scheduleId, string $date)
    {
        // Close any currently active sessions first
        $this->where('is_active', 1)->set(['is_active' => 0, 'closed_at' => date('Y-m-d H:i:s')])->update();

        // Create new session
        return $this->insert([
            'subject_id'   => $subjectId,
            'schedule_id'  => $scheduleId,
            'session_date' => $date,
            'opened_at'    => date('Y-m-d H:i:s'),
            'is_active'    => 1
        ]);
    }

    public function closeSession(int $id)
    {
        return $this->update($id, [
            'is_active' => 0,
            'closed_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getCurrentSession()
    {
        return $this->select('active_sessions.*, subjects.name as subject_name, subjects.code as subject_code, subject_schedules.start_time, subject_schedules.end_time')
                    ->join('subjects', 'subjects.id = active_sessions.subject_id')
                    ->join('subject_schedules', 'subject_schedules.id = active_sessions.schedule_id')
                    ->where('active_sessions.is_active', 1)
                    ->first();
    }
}
