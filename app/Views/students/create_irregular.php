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
            <h2 class="card-title text-2xl mb-2">Register Irregular Student</h2>
            <p class="text-base-content/60 mb-8 font-medium italic">Manually select the subjects this student is enrolled in.</p>
            
            <form action="<?= url_to('students.store.irregular') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b border-base-200 pb-8">
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
                        <input type="text" name="student_number" value="<?= old('student_number') ?>" placeholder="e.g. 2024-IRR-001" class="input input-bordered focus:input-primary px-4 py-2" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Year Level</span></label>
                        <select name="year_level" class="select select-bordered focus:select-primary" required>
                            <option value="" disabled selected>Select Current Year Level</option>
                            <?php for($i=1; $i<=4; $i++): ?>
                                <option value="<?= $i ?>" <?= old('year_level') == $i ? 'selected' : '' ?>>Year <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-control col-span-full">
                        <label class="label"><span class="label-text font-semibold">RFID Tag UID</span></label>
                        <div class="join w-full">
                            <input type="text" id="rfid_field" name="rfid_uid" value="<?= old('rfid_uid') ?>" placeholder="Scan RFID card..." class="input input-bordered join-item w-full focus:input-primary px-4 py-2" required />
                            <button type="button" class="btn btn-primary join-item px-6" onclick="document.getElementById('rfid_field').focus()">Scan</button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-bold text-lg mb-2">Subject Selection</h3>
                    <p class="text-xs text-base-content/50 mb-4">Check all the subjects the student is attending under your instruction.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-2 bg-base-200/10 rounded-xl">
                    <?php if (empty($subjects)): ?>
                        <div class="col-span-full text-center py-4 italic text-base-content/40">No subjects available yet.</div>
                    <?php else: ?>
                        <?php foreach ($subjects as $subject): ?>
                            <label class="label cursor-pointer justify-start gap-4 p-3 bg-base-100 rounded-lg hover:bg-base-200/50 transition-colors border border-base-200">
                                <input type="checkbox" name="subject_ids[]" value="<?= $subject['id'] ?>" class="checkbox checkbox-primary checkbox-sm" <?= in_array($subject['id'], old('subject_ids') ?? []) ? 'checked' : '' ?> />
                                <div>
                                    <div class="text-xs font-bold text-primary"><?= $subject['code'] ?></div>
                                    <div class="text-sm font-medium"><?= $subject['name'] ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="card-actions justify-end mt-12 pt-6 border-t border-base-200">
                    <a href="<?= url_to('students.index') ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-12 shadow-lg">Register Irregular Student</button>
                </div>
            </form>
    </div>
</div>

<?= $this->endSection() ?>
