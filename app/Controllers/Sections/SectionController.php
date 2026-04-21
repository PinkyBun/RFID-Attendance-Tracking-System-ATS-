<?php

namespace App\Controllers\Sections;

use App\Controllers\BaseController;
use App\Models\SectionModel;

class SectionController extends BaseController
{
    protected $sectionModel;

    public function __construct()
    {
        $this->sectionModel = new SectionModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Manage Sections',
            'sections' => $this->sectionModel->getAllWithStudentCountPaginated(10),
            'pager'    => $this->sectionModel->pager
        ];

        return view('sections/index', $data);
    }

    public function create()
    {
        return view('sections/create', ['title' => 'Create Section']);
    }

    public function store()
    {
        $rules = [
            'name'       => 'required|is_unique[sections.name]',
            'course'     => 'required',
            'year_level' => 'required|numeric|greater_than[0]|less_than[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $this->sectionModel->insert([
            'name'       => $this->request->getPost('name'),
            'course'     => $this->request->getPost('course'),
            'year_level' => $this->request->getPost('year_level'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/sections')->with('success', 'Section created successfully.');
    }

    public function edit($id)
    {
        $section = $this->sectionModel->find($id);
        if (!$section) {
            return redirect()->to('/sections')->with('error', 'Section not found.');
        }

        return view('sections/edit', [
            'title'   => 'Edit Section',
            'section' => $section
        ]);
    }

    public function update($id)
    {
        $rules = [
            'name'       => "required|is_unique[sections.name,id,{$id}]",
            'course'     => 'required',
            'year_level' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $this->sectionModel->update($id, [
            'name'       => $this->request->getPost('name'),
            'course'     => $this->request->getPost('course'),
            'year_level' => $this->request->getPost('year_level')
        ]);

        return redirect()->to('/sections')->with('success', 'Section updated successfully.');
    }

    public function destroy($id)
    {
        // Add check if students exist in section before deleting?
        $this->sectionModel->delete($id);
        return redirect()->to('/sections')->with('success', 'Section deleted successfully.');
    }
}
