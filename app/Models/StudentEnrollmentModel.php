<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentEnrollmentModel extends Model
{
    protected $table            = 'student_enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['student_id', 'subject_id', 'enrolled_at'];

    // Dates
    protected $useTimestamps = false; // Using custom enrolled_at

    public function enrollStudent(int $studentId, int $subjectId)
    {
        return $this->insert([
            'student_id'  => $studentId,
            'subject_id'  => $subjectId,
            'enrolled_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function bulkEnroll(int $studentId, array $subjectIds)
    {
        $data = [];
        $now = date('Y-m-d H:i:s');
        foreach ($subjectIds as $subjectId) {
            $data[] = [
                'student_id'  => $studentId,
                'subject_id'  => $subjectId,
                'enrolled_at' => $now
            ];
        }
        if (!empty($data)) {
            return $this->insertBatch($data);
        }
        return false;
    }

    public function getEnrolledSubjects(int $studentId)
    {
        return $this->select('student_enrollments.*, subjects.code, subjects.name')
                    ->join('subjects', 'subjects.id = student_enrollments.subject_id')
                    ->where('student_id', $studentId)
                    ->findAll();
    }
}
