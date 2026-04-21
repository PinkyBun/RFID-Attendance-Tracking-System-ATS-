<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-6">
        <a href="<?= url_to('subjects.index') ?>" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to List
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-6">Edit Subject: <?= $subject['code'] ?></h2>
            
            <form action="<?= url_to('subjects.update', $subject['id']) ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Subject Code</span>
                        </label>
                        <input type="text" name="code" value="<?= old('code', $subject['code']) ?>" class="input input-bordered focus:input-primary px-4 py-2" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Subject Name</span>
                        </label>
                        <input type="text" name="name" value="<?= old('name', $subject['name']) ?>" class="input input-bordered focus:input-primary px-4 py-2" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Section Assignment</span>
                        </label>
                        <select name="section_id" class="select select-bordered focus:select-primary">
                            <option value="">None / Optional Section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= $section['id'] ?>" <?= old('section_id', $subject['section_id']) == $section['id'] ? 'selected' : '' ?>>
                                    <?= $section['name'] ?> (Year <?= $section['year_level'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control text-center md:text-left flex flex-col justify-center">
                         <label class="label cursor-pointer justify-start gap-4">
                            <span class="label-text font-semibold">Is Active</span> 
                            <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" <?= old('is_active', $subject['is_active']) ? 'checked' : '' ?> />
                        </label>
                    </div>
                </div>

                <div class="divider text-base-content/40 font-bold uppercase tracking-wider text-xs">Class Schedules</div>
                
                <div id="schedule-container" class="space-y-4 mb-8">
                    <?php if (empty($schedules)): ?>
                        <div class="schedule-row grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-base-200/30 p-4 rounded-xl relative border border-base-200">
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Day</span></label>
                                <select name="day_of_week[]" class="select select-sm select-bordered" required>
                                    <option value="Mon">Monday</option>
                                    <option value="Tue">Tuesday</option>
                                    <option value="Wed">Wednesday</option>
                                    <option value="Thu">Thursday</option>
                                    <option value="Fri">Friday</option>
                                    <option value="Sat">Saturday</option>
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Start Time</span></label>
                                <input type="time" name="start_time[]" class="input input-sm input-bordered" required />
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">End Time</span></label>
                                <input type="time" name="end_time[]" class="input input-sm input-bordered" required />
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="btn btn-sm btn-ghost text-error" onclick="removeRow(this)">Remove</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($schedules as $sched): ?>
                            <div class="schedule-row grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-base-200/30 p-4 rounded-xl relative border border-base-200">
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-xs">Day</span></label>
                                    <select name="day_of_week[]" class="select select-sm select-bordered" required>
                                        <?php 
                                            $days = ['Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday'];
                                            foreach ($days as $val => $label): 
                                        ?>
                                            <option value="<?= $val ?>" <?= $sched['day_of_week'] == $val ? 'selected' : '' ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-xs">Start Time</span></label>
                                    <input type="time" name="start_time[]" value="<?= $sched['start_time'] ?>" class="input input-sm input-bordered" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-xs">End Time</span></label>
                                    <input type="time" name="end_time[]" value="<?= $sched['end_time'] ?>" class="input input-sm input-bordered" required />
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" class="btn btn-sm btn-ghost text-error" onclick="removeRow(this)">Remove</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn btn-ghost btn-sm text-primary mb-8" onclick="addRow()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Schedule Day
                </button>

                <div class="card-actions justify-end pt-6 border-t border-base-200">
                    <a href="<?= url_to('subjects.index') ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-12 shadow-lg">Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="schedule-template">
    <div class="schedule-row grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-base-200/30 p-4 rounded-xl relative border border-base-200 animate-in fade-in duration-300">
        <div class="form-control">
            <label class="label"><span class="label-text text-xs">Day</span></label>
            <select name="day_of_week[]" class="select select-sm select-bordered" required>
                <option value="Mon">Monday</option>
                <option value="Tue">Tuesday</option>
                <option value="Wed">Wednesday</option>
                <option value="Thu">Thursday</option>
                <option value="Fri">Friday</option>
                <option value="Sat">Saturday</option>
            </select>
        </div>
        <div class="form-control">
            <label class="label"><span class="label-text text-xs">Start Time</span></label>
            <input type="time" name="start_time[]" class="input input-sm input-bordered" required />
        </div>
        <div class="form-control">
            <label class="label"><span class="label-text text-xs">End Time</span></label>
            <input type="time" name="end_time[]" class="input input-sm input-bordered" required />
        </div>
        <div class="flex justify-end">
            <button type="button" class="btn btn-sm btn-ghost text-error" onclick="removeRow(this)">Remove</button>
        </div>
    </div>
</template>

<script>
    function addRow() {
        const container = document.getElementById('schedule-container');
        const template = document.getElementById('schedule-template');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.schedule-row');
        if (rows.length > 1) {
            btn.closest('.schedule-row').remove();
        } else {
            alert('At least one schedule is required.');
        }
    }
</script>

<?= $this->endSection() ?>
