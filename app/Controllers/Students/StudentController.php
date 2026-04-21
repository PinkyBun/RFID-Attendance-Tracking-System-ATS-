<?php

namespace App\Controllers\Students;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\SectionModel;
use App\Models\SubjectModel;
use App\Models\StudentEnrollmentModel;
use App\Models\AttendanceRecordModel;

class StudentController extends BaseController
{
    protected $studentModel;
    protected $sectionModel;
    protected $subjectModel;
    protected $enrollmentModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->sectionModel = new SectionModel();
        $this->subjectModel = new SubjectModel();
        $this->enrollmentModel = new StudentEnrollmentModel();
        $this->attendanceModel = new AttendanceRecordModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Manage Students',
            'students' => $this->studentModel->select('students.*, sections.name as section_name')
                                              ->join('sections', 'sections.id = students.section_id', 'left')
                                              ->orderBy('students.last_name', 'ASC')
                                              ->paginate(10),
            'pager'    => $this->studentModel->pager
        ];
        return view('students/index', $data);
    }

    public function show($id)
    {
        $student = $this->studentModel->find($id);
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }

        $section = $student['section_id'] ? $this->sectionModel->find($student['section_id']) : null;
        
        $data = [
            'title'      => 'Student Profile',
            'student'    => $student,
            'section'    => $section,
            'enrollments'=> $this->enrollmentModel->getEnrolledSubjects($id),
            'attendance' => $this->attendanceModel->select('attendance_records.*, subjects.name as subject_name')
                                                 ->join('subjects', 'subjects.id = attendance_records.subject_id')
                                                 ->where('student_id', $id)
                                                 ->orderBy('session_date', 'DESC')
                                                 ->limit(20)
                                                 ->findAll()
        ];

        return view('students/show', $data);
    }

    public function createRegular()
    {
        $data = [
            'title'    => 'Register Regular Student',
            'sections' => $this->sectionModel->findAll()
        ];
        return view('students/create_regular', $data);
    }

    public function storeRegular()
    {
        $rules = [
            'rfid_uid'       => 'required|is_unique[students.rfid_uid]',
            'student_number' => 'required|is_unique[students.student_number]',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'section_id'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $section = $this->sectionModel->find($this->request->getPost('section_id'));
        
        $db = \Config\Database::connect();
        $db->transStart();

        $studentId = $this->studentModel->insert([
            'rfid_uid'       => $this->request->getPost('rfid_uid'),
            'student_number' => $this->request->getPost('student_number'),
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'type'           => 'regular',
            'section_id'     => $this->request->getPost('section_id'),
            'year_level'     => $section['year_level'],
            'is_active'      => 1
        ]);

        // Auto-enroll in all subjects belonging to this section
        $subjects = $this->subjectModel->where('section_id', $this->request->getPost('section_id'))->findAll();
        if (!empty($subjects)) {
            $subjectIds = array_column($subjects, 'id');
            $this->enrollmentModel->bulkEnroll((int)$studentId, $subjectIds);
        }

        $db->transComplete();

        return redirect()->to('/students')->with('success', 'Regular student registered and auto-enrolled successfully.');
    }

    public function createIrregular()
    {
        $data = [
            'title'    => 'Register Irregular Student',
            'subjects' => $this->subjectModel->findAll()
        ];
        return view('students/create_irregular', $data);
    }

    public function storeIrregular()
    {
        $rules = [
            'rfid_uid'       => 'required|is_unique[students.rfid_uid]',
            'student_number' => 'required|is_unique[students.student_number]',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'year_level'     => 'required',
            'subject_ids'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $studentId = $this->studentModel->insert([
            'rfid_uid'       => $this->request->getPost('rfid_uid'),
            'student_number' => $this->request->getPost('student_number'),
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'type'           => 'irregular',
            'section_id'     => null,
            'year_level'     => $this->request->getPost('year_level'),
            'is_active'      => 1
        ]);

        // Manual enrollment
        $subjectIds = $this->request->getPost('subject_ids');
        if (!empty($subjectIds)) {
            $this->enrollmentModel->bulkEnroll((int)$studentId, $subjectIds);
        }

        $db->transComplete();

        return redirect()->to('/students')->with('success', 'Irregular student registered successfully.');
    }

    public function edit($id)
    {
        $student = $this->studentModel->find($id);
        if (!$student) return redirect()->to('/students')->with('error', 'Student not found.');

        $data = [
            'title'      => 'Edit Student',
            'student'    => $student,
            'sections'   => $this->sectionModel->findAll(),
            'subjects'   => $this->subjectModel->findAll(),
            'enrolled_ids' => array_column($this->enrollmentModel->where('student_id', $id)->findAll(), 'subject_id')
        ];

        return view('students/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'rfid_uid'       => "required|is_unique[students.rfid_uid,id,{$id}]",
            'student_number' => "required|is_unique[students.student_number,id,{$id}]",
            'first_name'     => 'required',
            'last_name'      => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $updateData = [
            'rfid_uid'       => $this->request->getPost('rfid_uid'),
            'student_number' => $this->request->getPost('student_number'),
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'is_active'      => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($this->request->getPost('year_level')) {
            $updateData['year_level'] = $this->request->getPost('year_level');
        }

        $this->studentModel->update($id, $updateData);

        // Update enrollment if subject_ids are provided (manual refresh)
        $subjectIds = $this->request->getPost('subject_ids');
        if ($subjectIds !== null) {
            $this->enrollmentModel->where('student_id', $id)->delete();
            if (!empty($subjectIds)) {
                $this->enrollmentModel->bulkEnroll((int)$id, $subjectIds);
            }
        }

        $db->transComplete();

        return redirect()->to('/students/'.$id)->with('success', 'Student details updated.');
    }

    public function destroy($id)
    {
        $this->studentModel->delete($id);
        return redirect()->to('/students')->with('success', 'Student record deleted.');
    }
}
