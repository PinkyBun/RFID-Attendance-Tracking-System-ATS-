<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex items-center justify-between">
    <a href="<?= url_to('students.index') ?>" class="btn btn-ghost btn-sm gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to Students
    </a>
    <div class="flex gap-2">
        <a href="<?= url_to('students.edit', $student['id']) ?>" class="btn btn-info btn-sm">Edit Profile</a>
        <form id="delete-student-form" action="<?= url_to('students.destroy', $student['id']) ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-error btn-sm"
                onclick="return confirmAction({
                    title: 'Delete Student Profile?',
                    message: 'Are you sure you want to delete this student? This will permanently remove their profile and all associated enrollment and attendance history.',
                    confirmText: 'Delete Permanently',
                    confirmClass: 'btn-error',
                    formId: 'delete-student-form'
                })">Delete</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
    <!-- Student Info Card -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body items-center text-center pb-8 border-b border-base-100">
                <div class="avatar placeholder mb-4">
                    <div class="bg-primary text-primary-content rounded-full w-24">
                        <span class="text-3xl font-bold"><?= substr($student['first_name'], 0, 1) ?><?= substr($student['last_name'], 0, 1) ?></span>
                    </div>
                </div>
                <h2 class="text-2xl font-bold leading-tight"><?= $student['first_name'] ?> <?= $student['last_name'] ?></h2>
                <div class="flex gap-2 mt-1">
                    <span class="badge badge-sm <?= $student['type'] == 'regular' ? 'badge-primary' : 'badge-secondary' ?>"><?= ucfirst($student['type']) ?></span>
                    <span class="badge badge-sm <?= $student['is_active'] ? 'badge-success' : 'badge-ghost' ?>"><?= $student['is_active'] ? 'Active' : 'Inactive' ?></span>
                </div>
                <div class="divider opacity-50"></div>
                
                <div class="w-full space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-base-content/50">Student #</span>
                        <span class="font-mono font-bold"><?= $student['student_number'] ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-base-content/50">RFID UID</span>
                        <span class="font-mono font-bold"><?= $student['rfid_uid'] ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-base-content/50">Level</span>
                        <span class="font-bold">Year <?= $student['year_level'] ?></span>
                    </div>
                    <?php if ($student['type'] == 'regular'): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-base-content/50">Section</span>
                            <span class="font-bold text-primary"><?= $section['name'] ?? 'N/A' ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="font-bold text-sm uppercase tracking-widest text-base-content/40 mb-4">Enrolled Subjects</h3>
                <div class="space-y-2">
                    <?php if (empty($enrollments)): ?>
                        <p class="text-sm italic text-base-content/40">Not enrolled in any subjects.</p>
                    <?php else: ?>
                        <?php foreach ($enrollments as $enroll): ?>
                            <div class="flex items-center gap-3 p-2 bg-base-200/50 rounded-lg hover:bg-base-200 transition-colors">
                                <div class="badge badge-sm badge-outline font-bold text-[10px]"><?= $enroll['code'] ?></div>
                                <span class="text-xs truncate"><?= $enroll['name'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card bg-base-100 shadow-xl border border-base-200 h-full">
            <div class="card-body p-0">
                <div class="px-8 py-6 border-b border-base-200 bg-base-100/50">
                    <h3 class="text-xl font-bold">Attendance History</h3>
                    <p class="text-xs text-base-content/50 mt-1">Showing latest 20 records</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-200/30">
                                <th>Date & Subject</th>
                                <th>Time In / Out</th>
                                <th>Status</th>
                                <th class="text-right">Flags</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($attendance)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-base-content/20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        <p class="text-base-content/40">No attendance records found for this student.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($attendance as $record): ?>
                                    <tr class="hover">
                                        <td>
                                            <div class="text-sm font-bold"><?= date('M d, Y', strtotime($record['session_date'])) ?></div>
                                            <div class="text-xs opacity-50 truncate max-w-[150px]"><?= $record['subject_name'] ?></div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="text-base-content/40 w-4">IN</span>
                                                    <span class="font-mono"><?= format_attendance_time($record['time_in']) ?></span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="text-base-content/40 w-4">OUT</span>
                                                    <span class="font-mono"><?= format_attendance_time($record['time_out']) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm <?= get_status_badge_class($record['status']) ?>">
                                                <?= ucfirst($record['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex justify-end gap-1">
                                                <?php if ($record['is_cross_section']): ?>
                                                    <div class="tooltip tooltip-left" data-tip="Tapped in different section: <?= $record['cross_section_note'] ?>">
                                                        <span class="badge badge-warning badge-xs">CS</span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($record['is_manual']): ?>
                                                    <div class="tooltip tooltip-left" data-tip="Manually recorded/edited by teacher">
                                                        <span class="badge badge-info badge-xs">M</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
