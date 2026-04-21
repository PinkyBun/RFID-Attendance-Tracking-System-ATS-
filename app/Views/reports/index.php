<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-8">
    <div>
        <p class="text-base-content/60">Statistical summary of attendance performance</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline btn-sm gap-2 no-print">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
        Print Report
    </button>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <!-- Subject Attendance Rates -->
    <div class="xl:col-span-2 space-y-6">
        <div class="card bg-base-100 shadow rounded-2xl border border-base-200">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">Subject Attendance Performance</h3>
                <div class="space-y-6">
                    <?php foreach ($reports as $report): ?>
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="font-bold text-sm"><?= $report['subject']['code'] ?> — <?= $report['subject']['name'] ?></span>
                                <span class="text-xs font-mono"><?= $report['rate'] ?>% Attendance</span>
                            </div>
                            <progress class="progress progress-primary w-full h-3" value="<?= $report['rate'] ?>" max="100"></progress>
                            <div class="flex gap-4 mt-2 text-[10px] uppercase font-bold opacity-50">
                                <span class="text-success">On-Time: <?= $report['on_time'] ?></span>
                                <span class="text-warning">Late: <?= $report['late'] ?></span>
                                <span class="text-error">Missed/Inc: <?= $report['incomplete'] + $report['absent'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Detailed Stats Table -->
        <div class="card bg-base-100 shadow rounded-2xl overflow-hidden border border-base-200">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table table-sm w-full">
                        <thead class="bg-base-200/50">
                            <tr>
                                <th>Subject</th>
                                <th class="text-center">Total Records</th>
                                <th class="text-center text-success">On Time</th>
                                <th class="text-center text-warning">Late</th>
                                <th class="text-center text-error">Incomplete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td class="font-bold"><?= $report['subject']['code'] ?></td>
                                    <td class="text-center"><?= $report['total'] ?></td>
                                    <td class="text-center"><?= $report['on_time'] ?></td>
                                    <td class="text-center"><?= $report['late'] ?></td>
                                    <td class="text-center"><?= $report['incomplete'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Top Students & Legend -->
    <div class="space-y-6">
        <div class="card bg-primary text-primary-content shadow-xl rounded-2xl">
            <div class="card-body">
                <h3 class="card-title text-sm uppercase tracking-widest opacity-80">Top Attenders</h3>
                <div class="space-y-4 mt-2">
                    <?php foreach ($top_students as $student): ?>
                        <div class="flex items-center gap-3">
                            <div class="badge badge-secondary font-bold"><?= $student['attendance_count'] ?></div>
                            <div class="text-sm font-bold"><?= $student['first_name'] ?> <?= $student['last_name'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow rounded-2xl border border-base-200">
            <div class="card-body">
                <h3 class="font-bold text-sm mb-4 uppercase text-base-content/50">Report Legend</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="badge badge-success badge-xs mt-1"></div>
                        <div class="text-xs">
                            <span class="font-bold">On-Time:</span> Tapped within 15 mins of class start.
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="badge badge-warning badge-xs mt-1"></div>
                        <div class="text-xs">
                            <span class="font-bold">Late:</span> Tapped after the 15-min grace period.
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="badge badge-error badge-xs mt-1"></div>
                        <div class="text-xs">
                            <span class="font-bold">Incomplete:</span> Student tapped in but never tapped out before the session was closed.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none; }
        .drawer-side { display: none; }
        .drawer-content { padding: 0 !important; }
        .lg\:drawer-open > .drawer-toggle ~ .drawer-content { margin-left: 0 !important; }
        .card { shadow: none !important; border: 1px solid #ccc !important; }
        .navbar { display: none !important; }
    }
</style>

<?= $this->endSection() ?>
