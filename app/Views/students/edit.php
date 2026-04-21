<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto pb-12">
    <div class="mb-6">
        <a href="<?= url_to('students.show', $student['id']) ?>" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Profile
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-6">Edit Student: <?= $student['first_name'] ?> <?= $student['last_name'] ?></h2>
            
            <form action="<?= url_to('students.update', $student['id']) ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b border-base-200 pb-8">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">First Name</span></label>
                        <input type="text" name="first_name" value="<?= old('first_name', $student['first_name']) ?>" class="input input-bordered focus:input-primary" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Last Name</span></label>
                        <input type="text" name="last_name" value="<?= old('last_name', $student['last_name']) ?>" class="input input-bordered focus:input-primary" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Student Number</span></label>
                        <input type="text" name="student_number" value="<?= old('student_number', $student['student_number']) ?>" class="input input-bordered focus:input-primary" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">RFID Tag UID</span></label>
                        <input type="text" name="rfid_uid" value="<?= old('rfid_uid', $student['rfid_uid']) ?>" class="input input-bordered focus:input-primary" required />
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-4">
                            <span class="label-text font-semibold">Status</span> 
                            <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-success" <?= old('is_active', $student['is_active']) ? 'checked' : '' ?> />
                            <span class="text-sm">Active (can tap card)</span>
                        </label>
                    </div>

                    <?php if ($student['type'] == 'irregular'): ?>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Year Level</span></label>
                            <select name="year_level" class="select select-bordered focus:select-primary" required>
                                <?php for($i=1; $i<=4; $i++): ?>
                                    <option value="<?= $i ?>" <?= old('year_level', $student['year_level']) == $i ? 'selected' : '' ?>>Year <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Manual Enrollment Section for Irregular students or correction for Regular -->
                <div class="mb-4">
                    <h3 class="font-bold text-lg mb-2">Subject Enrollment</h3>
                    <p class="text-xs text-base-content/50 mb-4">Enrollment is usually automatic for regular students. You can manually adjust subjects here if needed.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-2 bg-base-200/20 rounded-xl">
                    <?php foreach ($subjects as $subject): ?>
                        <label class="label cursor-pointer justify-start gap-4 p-3 bg-base-100 rounded-lg hover:bg-base-200/50 transition-colors border border-base-200">
                            <input type="checkbox" name="subject_ids[]" value="<?= $subject['id'] ?>" class="checkbox checkbox-primary checkbox-sm" <?= in_array($subject['id'], $enrolled_ids) ? 'checked' : '' ?> />
                            <div>
                                <div class="text-xs font-bold text-primary"><?= $subject['code'] ?></div>
                                <div class="text-sm font-medium"><?= $subject['name'] ?></div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="card-actions justify-end mt-12 pt-6 border-t border-base-200">
                    <a href="<?= url_to('students.show', $student['id']) ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-12 shadow-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
