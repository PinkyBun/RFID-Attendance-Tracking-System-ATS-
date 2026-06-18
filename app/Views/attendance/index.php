<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Filter Section -->
<div class="card bg-base-100 shadow rounded-2xl mb-8 overflow-visible">
    <div class="card-body">
        <form method="GET" action="<?= url_to('attendance.index') ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div class="form-control">
                <label class="label"><span class="label-text font-bold text-xs uppercase">Subject</span></label>
                <select name="subject_id" class="select select-sm select-bordered w-full">
                    <option value="">All Subjects</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>" <?= $filters['subject_id'] == $subject['id'] ? 'selected' : '' ?>>
                            <?= $subject['code'] ?> - <?= $subject['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text font-bold text-xs uppercase">Start Date</span></label>
                <input type="date" name="start_date" value="<?= $filters['start_date'] ?>" class="input input-sm input-bordered" />
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text font-bold text-xs uppercase">End Date</span></label>
                <input type="date" name="end_date" value="<?= $filters['end_date'] ?>" class="input input-sm input-bordered" />
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text font-bold text-xs uppercase">Search Student</span></label>
                <input type="text" name="q" value="<?= $filters['student_query'] ?>" placeholder="Name or Student #" class="input input-sm input-bordered" />
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-1">Filter</button>
                <a href="<?= url_to('attendance.index') ?>" class="btn btn-sm btn-ghost">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card bg-base-100 shadow rounded-2xl overflow-hidden">
    <div class="card-body p-0">
        <div class="px-8 py-4 border-b border-base-200 flex justify-between items-center bg-base-100/50">
            <h3 class="font-bold text-lg">Attendance Records</h3>
            <div class="flex gap-2">
                <a href="<?= url_to('attendance.manual') ?>" class="btn btn-xs btn-outline">Add Manual Entry</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200/50">
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-20 text-base-content/40 italic">No records found for the selected filters.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $record): ?>
                            <tr class="hover group">
                                <td class="text-xs font-medium"><?= date('M d, Y', strtotime($record['session_date'])) ?></td>
                                <td>
                                    <div class="font-bold"><?= $record['first_name'] ?> <?= $record['last_name'] ?></div>
                                    <div class="text-[10px] opacity-40"><?= $record['student_number'] ?></div>
                                </td>
                                <td><span class="badge badge-sm badge-ghost font-mono"><?= $record['subject_code'] ?></span></td>
                                <td class="font-mono text-xs italic"><?= format_attendance_time($record['time_in']) ?></td>
                                <td class="font-mono text-xs italic"><?= format_attendance_time($record['time_out']) ?></td>
                                <td>
                                    <span class="badge badge-sm <?= get_status_badge_class($record['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $record['status'])) ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="<?= url_to('attendance.edit', $record['id']) ?>" class="btn btn-ghost btn-xs text-info">Edit</a>
                                        <a href="<?= url_to('students.show', $record['student_id']) ?>" class="btn btn-ghost btn-xs">Profile</a>
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

<?= $this->endSection() ?>
