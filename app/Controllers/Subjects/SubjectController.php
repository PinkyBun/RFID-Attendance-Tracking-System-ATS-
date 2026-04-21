<?php

namespace App\Controllers\Subjects;

use App\Controllers\BaseController;
use App\Models\SubjectModel;
use App\Models\SectionModel;
use App\Models\SubjectScheduleModel;

class SubjectController extends BaseController
{
    protected $subjectModel;
    protected $sectionModel;
    protected $scheduleModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
        $this->sectionModel = new SectionModel();
        $this->scheduleModel = new SubjectScheduleModel();
    }

    public function index()
    {
        $subjects = $this->subjectModel->getSubjectsWithSchedulesPaginated(10);
        
        $data = [
            'title'    => 'Manage Subjects',
            'subjects' => $subjects,
            'pager'    => $this->subjectModel->pager
        ];
        return view('subjects/index', $data);
    }

    public function create()
    {
        $data = [
            'title'    => 'Create Subject',
            'sections' => $this->sectionModel->findAll()
        ];
        return view('subjects/create', $data);
    }

    public function store()
    {
        $rules = [
            'code'       => 'required|alpha_numeric_space|max_length[20]',
            'name'       => 'required',
            'year_level' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $subjectId = $this->subjectModel->insert([
            'code'       => $this->request->getPost('code'),
            'name'       => $this->request->getPost('name'),
            'section_id' => $this->request->getPost('section_id') ?: null,
            'year_level' => $this->request->getPost('year_level'),
            'is_active'  => 1
        ]);

        $days   = $this->request->getPost('day_of_week');
        $starts = $this->request->getPost('start_time');
        $ends   = $this->request->getPost('end_time');

        if (!empty($days)) {
            $schedules = [];
            foreach ($days as $index => $day) {
                if (!empty($day) && !empty($starts[$index]) && !empty($ends[$index])) {
                    $schedules[] = [
                        'subject_id'  => $subjectId,
                        'day_of_week' => $day,
                        'start_time'  => $starts[$index],
                        'end_time'    => $ends[$index]
                    ];
                }
            }
            if (!empty($schedules)) {
                $this->scheduleModel->insertBatch($schedules);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to save subject or schedules.');
        }

        return redirect()->to('/subjects')->with('success', 'Subject created successfully.');
    }

    public function edit($id)
    {
        $subject = $this->subjectModel->find($id);
        if (!$subject) {
            return redirect()->to('/subjects')->with('error', 'Subject not found.');
        }

        $data = [
            'title'     => 'Edit Subject',
            'subject'   => $subject,
            'sections'  => $this->sectionModel->findAll(),
            'schedules' => $this->scheduleModel->where('subject_id', $id)->findAll()
        ];

        return view('subjects/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'code'       => 'required|alpha_numeric_space|max_length[20]',
            'name'       => 'required',
            'year_level' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->subjectModel->update($id, [
            'code'       => $this->request->getPost('code'),
            'name'       => $this->request->getPost('name'),
            'section_id' => $this->request->getPost('section_id') ?: null,
            'year_level' => $this->request->getPost('year_level'),
            'is_active'  => $this->request->getPost('is_active') ?? 1
        ]);

        // Simple approach: Delete old schedules and insert new ones
        // In a real app, you might want more granular updates to preserve IDs, 
        // but for this system, refreshing schedules on edit is safe and simple.
        $this->scheduleModel->where('subject_id', $id)->delete();

        $days   = $this->request->getPost('day_of_week');
        $starts = $this->request->getPost('start_time');
        $ends   = $this->request->getPost('end_time');

        if (!empty($days)) {
            $schedules = [];
            foreach ($days as $index => $day) {
                if (!empty($day) && !empty($starts[$index]) && !empty($ends[$index])) {
                    $schedules[] = [
                        'subject_id'  => $id,
                        'day_of_week' => $day,
                        'start_time'  => $starts[$index],
                        'end_time'    => $ends[$index]
                    ];
                }
            }
            if (!empty($schedules)) {
                $this->scheduleModel->insertBatch($schedules);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to update subject or schedules.');
        }

        return redirect()->to('/subjects')->with('success', 'Subject updated successfully.');
    }

    public function destroy($id)
    {
        $this->subjectModel->delete($id);
        return redirect()->to('/subjects')->with('success', 'Subject deleted successfully.');
    }

    public function removeSchedule($id)
    {
        $this->scheduleModel->delete($id);
        return $this->response->setJSON(['success' => true]);
    }
}
