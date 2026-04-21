<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat bg-base-100 shadow rounded-2xl">
        <div class="stat-figure text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div class="stat-title text-base-content/60">Total Students</div>
        <div class="stat-value text-primary"><?= $stats['total_students'] ?></div>
        <div class="stat-desc">Registered in system</div>
    </div>
    
    <div class="stat bg-base-100 shadow rounded-2xl">
        <div class="stat-figure text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <div class="stat-title text-base-content/60">Subjects</div>
        <div class="stat-value text-secondary"><?= $stats['total_subjects'] ?></div>
        <div class="stat-desc">Handled by you</div>
    </div>

    <div class="stat bg-base-100 shadow rounded-2xl">
        <div class="stat-figure text-accent">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div class="stat-title text-base-content/60">Today's Taps</div>
        <div class="stat-value text-accent"><?= $stats['today_taps'] ?></div>
        <div class="stat-desc"><?= date('F d, Y') ?></div>
    </div>

    <div class="stat bg-base-100 shadow rounded-2xl">
        <div class="stat-figure text-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="stat-title text-base-content/60">Active Session</div>
        <div class="stat-value text-info text-2xl">
            <?= $stats['active_session'] ? $stats['active_session']['subject_code'] : 'None' ?>
        </div>
        <div class="stat-desc"><?= $stats['active_session'] ? 'Currently Tapping' : 'No class active' ?></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activity -->
    <div class="lg:col-span-2">
        <div class="card bg-base-100 shadow rounded-2xl overflow-hidden">
            <div class="card-body p-0">
                <div class="px-6 py-4 border-b border-base-200 flex justify-between items-center bg-base-100/50">
                    <h3 class="font-bold text-lg">Recent Taps</h3>
                    <a href="<?= url_to('attendance.index') ?>" class="btn btn-sm btn-ghost text-primary">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_taps)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-base-content/40">No taps recorded yet today.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_taps as $tap): ?>
                                    <tr>
                                        <td>
                                            <div class="font-bold"><?= $tap['first_name'] ?> <?= $tap['last_name'] ?></div>
                                        </td>
                                        <td class="font-mono text-xs italic">
                                            <?= format_attendance_time($tap['time_out'] ?? $tap['time_in']) ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm <?= $tap['time_out'] ? 'badge-secondary' : 'badge-primary' ?>">
                                                <?= $tap['time_out'] ? 'OUT' : 'IN' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= get_status_badge_class($tap['status']) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $tap['status'])) ?>
                                            </span>
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

    <!-- Quick Actions -->
    <div class="flex flex-col gap-6">
        <div class="card bg-primary text-primary-content shadow-xl rounded-2xl">
            <div class="card-body">
                <h3 class="card-title">Quick Start</h3>
                <p class="text-sm opacity-90">Ready to start a new class? Select a subject to begin receiving RFID taps.</p>
                <div class="card-actions justify-end mt-4">
                    <a href="<?= url_to('session.select') ?>" class="btn btn-secondary btn-sm">Activate Class</a>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow rounded-2xl">
            <div class="card-body">
                <h3 class="font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    System Status
                </h3>
                <div class="flex flex-col gap-3 mt-2">
                    <div class="flex justify-between items-center text-sm">
                        <span>RFID Bridge</span>
                        <span class="badge badge-success badge-sm">Online</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span>Database</span>
                        <span class="badge badge-success badge-sm">Connected</span>
                    </div>
                </div>
                <div class="divider my-1"></div>
                <p class="text-xs text-base-content/50">Last Backup: Never</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
