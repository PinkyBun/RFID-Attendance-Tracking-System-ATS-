<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table            = 'subjects';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['code', 'name', 'section_id', 'year_level', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getSubjectsWithSchedulesPaginated(int $perPage = 10)
    {
        $subjects = $this->select('subjects.*, sections.name as section_name')
                         ->join('sections', 'sections.id = subjects.section_id', 'left')
                         ->paginate($perPage);
                         
        $scheduleModel = new SubjectScheduleModel();
        
        foreach ($subjects as &$subject) {
            $subject['schedules'] = $scheduleModel->where('subject_id', $subject['id'])->findAll();
        }
        
        return $subjects;
    }

    public function getBySection(int $sectionId)
    {
        return $this->where('section_id', $sectionId)->findAll();
    }
}
