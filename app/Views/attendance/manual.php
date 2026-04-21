<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-6">
        <a href="<?= url_to('attendance.index') ?>" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Records
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-2">Manual Attendance Entry</h2>
            <p class="text-base-content/60 mb-8 italic">Use this form to record attendance for students who forgot their cards or for correcting errors.</p>
            
            <form action="<?= url_to('attendance.manual.store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Select Student</span></label>
                        <select name="student_id" class="select select-bordered focus:select-primary" required>
                            <option value="" disabled selected>Choose a student...</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id'] ?>"><?= $student['last_name'] ?>, <?= $student['first_name'] ?> (<?= $student['student_number'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Subject</span></label>
                        <select name="subject_id" class="select select-bordered focus:select-primary" required>
                            <option value="" disabled selected>Choose a subject...</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>"><?= $subject['code'] ?> - <?= $subject['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Date</span></label>
                        <input type="date" name="session_date" value="<?= date('Y-m-d') ?>" class="input input-bordered focus:input-primary" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Status Override</span></label>
                        <select name="status" class="select select-bordered focus:select-primary" required>
                            <option value="on_time">On Time</option>
                            <option value="late">Late</option>
                            <option value="manual" selected>Manual Entry / Excused</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Time In</span></label>
                        <input type="time" name="time_in" class="input input-bordered focus:input-primary" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Time Out</span></label>
                        <input type="time" name="time_out" class="input input-bordered focus:input-primary" />
                    </div>
                </div>

                <div class="form-control mb-8">
                    <label class="label"><span class="label-text font-semibold">Notes / Reason for Manual Entry</span></label>
                    <textarea name="notes" class="textarea textarea-bordered h-24 focus:textarea-primary" placeholder="e.g. Forgot RFID card, authorized late arrival..."></textarea>
                </div>

                <div class="card-actions justify-end pt-6 border-t border-base-200">
                    <a href="<?= url_to('attendance.index') ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-12 shadow-lg">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
