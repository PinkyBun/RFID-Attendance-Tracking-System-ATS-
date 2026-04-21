<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table            = 'sections';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'course', 'year_level'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getAllWithStudentCountPaginated(int $perPage = 10)
    {
        return $this->select('sections.*, COUNT(students.id) as student_count')
                    ->join('students', 'students.section_id = sections.id AND students.type = "regular" AND students.is_active = 1', 'left')
                    ->groupBy('sections.id')
                    ->paginate($perPage);
    }
}
