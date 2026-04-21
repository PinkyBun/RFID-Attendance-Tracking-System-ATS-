<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['rfid_uid', 'student_number', 'first_name', 'last_name', 'type', 'section_id', 'year_level', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findByRfid(string $rfidUid)
    {
        return $this->where('rfid_uid', $rfidUid)->first();
    }

    public function findByStudentNumber(string $studentNumber)
    {
        return $this->where('student_number', $studentNumber)->first();
    }

    public function getRegularStudents()
    {
        return $this->select('students.*, sections.name as section_name')
                    ->join('sections', 'sections.id = students.section_id', 'left')
                    ->where('students.type', 'regular')
                    ->findAll();
    }

    public function getIrregularStudents()
    {
        return $this->where('type', 'irregular')->findAll();
    }
}
