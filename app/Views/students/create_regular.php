<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-6">
        <a href="<?= url_to('students.index') ?>" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to List
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-2">Register Regular Student</h2>
            <p class="text-base-content/60 mb-8 font-medium italic">Regular students are automatically enrolled in all subjects assigned to their section.</p>
            
            <form action="<?= url_to('students.store.regular') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">First Name</span></label>
                        <input type="text" name="first_name" value="<?= old('first_name') ?>" class="input input-bordered focus:input-primary px-4 py-2" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Last Name</span></label>
                        <input type="text" name="last_name" value="<?= old('last_name') ?>" class="input input-bordered focus:input-primary px-4 py-2" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Student Number</span></label>
                        <input type="text" name="student_number" value="<?= old('student_number') ?>" placeholder="e.g. 2024-10001" class="input input-bordered focus:input-primary px-4 py-2" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">RFID Tag UID</span>
                        </label>
                        <div class="join w-full">
                            <input type="text" id="rfid_field" name="rfid_uid" value="<?= old('rfid_uid') ?>" placeholder="Tap card now..." class="input input-bordered join-item w-full focus:input-primary px-4 py-2" required />
                            <button type="button" class="btn btn-primary join-item px-6" onclick="focusRfid()">Scan</button>
                        </div>
                    </div>

                    <div class="form-control col-span-full">
                        <label class="label">
                            <span class="label-text font-semibold">Assign Section</span>
                        </label>
                        <select name="section_id" class="select select-bordered w-full focus:select-primary" required>
                            <option value="" disabled selected>Select the student's regular section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= $section['id'] ?>" <?= old('section_id') == $section['id'] ? 'selected' : '' ?>>
                                    <?= $section['name'] ?> (<?= $section['course'] ?> - Year <?= $section['year_level'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="card-actions justify-end mt-12 pt-6 border-t border-base-200">
                    <a href="<?= url_to('students.index') ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-12 shadow-lg">Register & Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function focusRfid() {
        document.getElementById('rfid_field').focus();
    }
</script>

<?= $this->endSection() ?>
