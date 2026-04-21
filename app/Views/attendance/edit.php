<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto pb-12">
    <div class="mb-6">
        <a href="<?= url_to('attendance.index') ?>" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Records
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-4 text-info">Update Attendance Status</h2>
            
            <div class="bg-base-200/50 p-4 rounded-xl mb-8 border border-base-200">
                <div class="flex items-center gap-4">
                    <div class="avatar placeholder">
                        <div class="bg-info text-info-content rounded-full w-12">
                            <span><?= substr($student['first_name'], 0, 1) ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="font-black"><?= $student['first_name'] ?> <?= $student['last_name'] ?></div>
                        <div class="text-xs opacity-50"><?= $subject['code'] ?> — <?= date('M d, Y', strtotime($record['session_date'])) ?></div>
                    </div>
                </div>
            </div>

            <form action="<?= url_to('attendance.update', $record['id']) ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-control w-full mb-6">
                    <label class="label"><span class="label-text font-semibold">Attendance Status</span></label>
                    <select name="status" class="select select-bordered focus:select-primary select-lg w-full" required>
                        <?php 
                            $statuses = ['on_time' => 'On Time', 'late' => 'Late', 'incomplete' => 'Incomplete', 'absent' => 'Absent', 'manual' => 'Manual / Excused'];
                            foreach($statuses as $val => $label): 
                        ?>
                            <option value="<?= $val ?>" <?= $record['status'] == $val ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-control mb-8">
                    <label class="label"><span class="label-text font-semibold">Teacher Notes</span></label>
                    <textarea name="notes" class="textarea textarea-bordered h-32 focus:textarea-primary" placeholder="Reason for change or additional details..."><?= $record['notes'] ?></textarea>
                </div>

                <div class="card-actions justify-end pt-6 border-t border-base-200">
                    <a href="<?= url_to('attendance.index') ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-info px-12 shadow-lg">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
