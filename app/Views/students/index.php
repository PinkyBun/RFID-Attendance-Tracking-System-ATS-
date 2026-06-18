<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-base-content/60">View and manage all registered students</p>
    </div>
    <div class="flex gap-2">
        <button onclick="export_modal.showModal()" class="btn btn-success text-success-content shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            Export
        </button>
        <a href="<?= url_to('students.create.regular') ?>" class="btn btn-primary shadow-lg">
            Register Regular
        </a>
        <a href="<?= url_to('students.create.irregular') ?>" class="btn btn-outline btn-primary">
            Register Irregular
        </a>
    </div>
</div>

<dialog id="export_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Export Enrolled Students</h3>
        <form action="<?= url_to('students.export') ?>" method="GET">
            <div class="form-control w-full mb-4">
                <label class="label"><span class="label-text font-bold">Select Subject</span></label>
                <select name="subject_id" class="select select-bordered w-full" required>
                    <option value="" disabled selected>Select Subject...</option>
                    <?php foreach($subjects as $sub): ?>
                        <option value="<?= $sub['id'] ?>"><?= esc($sub['code']) ?> - <?= esc($sub['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control w-full mb-6">
                <label class="label"><span class="label-text font-bold">Select Section</span></label>
                <select name="section_id" class="select select-bordered w-full" required>
                    <option value="" disabled selected>Select Section...</option>
                    <?php foreach($sections as $sec): ?>
                        <option value="<?= $sec['id'] ?>"><?= esc($sec['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-action mt-6">
                <button type="button" class="btn text-base-content/70" onclick="export_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-primary px-8 text-primary-content">Download Excel</button>
            </div>
        </form>
    </div>
</dialog>

<div class="card bg-base-100 shadow rounded-2xl overflow-hidden">
    <div class="card-body p-0">
        <!-- Search/Filter Bar (Placeholder UI) -->
        <div class="p-4 bg-base-200/20 border-b border-base-200 flex flex-col md:flex-row gap-4 items-center">
            <div class="relative w-full md:w-96">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-base-content/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" id="studentSearch" placeholder="Search by name or student #" class="input input-sm input-bordered w-full pl-10" onkeyup="filterTable()">
            </div>
            <div class="flex gap-2 w-full md:w-auto shrink-0">
                <select class="select select-sm select-bordered w-36 shrink-0">
                    <option disabled selected>Type</option>
                    <option>Regular</option>
                    <option>Irregular</option>
                </select>
                <select class="select select-sm select-bordered w-36 shrink-0">
                    <option disabled selected>Year</option>
                    <option>1st</option>
                    <option>2nd</option>
                    <option>3rd</option>
                    <option>4th</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full" id="studentTable">
                <thead class="bg-base-200/50">
                    <tr>
                        <th>Student Number</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Section / Level</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-12 text-base-content/40">No students registered yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr class="hover border-b border-base-200 <?= !$student['is_active'] ? 'opacity-50 grayscale-[0.8]' : '' ?>">
                                <td class="font-mono text-xs"><?= $student['student_number'] ?></td>
                                <td>
                                    <div class="font-bold"><?= $student['last_name'] ?>, <?= $student['first_name'] ?></div>
                                    <div class="text-xs opacity-50"><?= $student['rfid_uid'] ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-sm <?= $student['type'] == 'regular' ? 'badge-primary' : 'badge-secondary' ?>">
                                        <?= ucfirst($student['type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($student['type'] == 'regular'): ?>
                                        <div class="font-semibold text-sm"><?= $student['section_name'] ?></div>
                                    <?php else: ?>
                                        <div class="text-sm">Year <?= $student['year_level'] ?> (Irr)</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-sm <?= $student['is_active'] ? 'badge-success' : 'badge-ghost' ?>">
                                        <?= $student['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="<?= url_to('students.show', $student['id']) ?>" class="btn btn-ghost btn-xs text-primary">View</a>
                                        <a href="<?= url_to('students.edit', $student['id']) ?>" class="btn btn-ghost btn-xs text-info">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($pager)): ?>
            <div class="px-8 py-6 border-t border-base-200 bg-base-200/10 flex justify-center">
                <?= $pager->links('default', 'daisyui_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById('studentSearch');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('studentTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdName = tr[i].getElementsByTagName('td')[1];
            const tdNum = tr[i].getElementsByTagName('td')[0];
            if (tdName || tdNum) {
                const textName = tdName.textContent || tdName.innerText;
                const textNum = tdNum.textContent || tdNum.innerText;
                if (textName.toLowerCase().indexOf(filter) > -1 || textNum.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<?= $this->endSection() ?>
