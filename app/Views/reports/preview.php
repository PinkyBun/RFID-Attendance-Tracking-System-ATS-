<?php 
/** @var array $report */
/** @var array $filters */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto pb-12 print:p-0">
    <!-- Action Bar (Hidden on Print) -->
    <div class="flex justify-between items-center mb-8 print:hidden">
        <div class="flex gap-2">
            <a href="<?= url_to('reports.index') ?>" class="btn btn-ghost btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Change Filters
            </a>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="btn btn-outline btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 012 2v8a2 2 0 01-2 2H9a2 2 0 01-2-2v-8a2 2 0 012-2h6z" /></svg>
                Print Report
            </button>
            <a href="<?= url_to('reports.pdf') ?>?subject_name=<?= urlencode($filters['subject_name']) ?>&section_id=<?= $filters['section_id'] ?>&report_date=<?= $filters['report_date'] ?>" class="btn btn-primary btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Download PDF
            </a>
        </div>
    </div>

    <!-- Report Canvas -->
    <div class="card bg-base-100 shadow-2xl rounded-none print:shadow-none print:border-none border border-base-200">
        <div class="card-body p-12 print:p-4">
            <!-- Header -->
            <div class="text-center mb-12 border-b-2 border-base-content pb-8">
                <h2 class="text-3xl font-black uppercase mb-1">Attendance Report</h2>
                <div class="text-xl font-bold opacity-70"><?= $report['subject']['name'] ?> (<?= $report['subject']['code'] ?>)</div>
                <div class="mt-4 flex justify-center gap-8 text-sm font-semibold italic">
                    <span>Section: <?= $report['section']['name'] ?></span>
                    <span>Date: <?= date('F d, Y', strtotime($report['date'])) ?></span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-compact w-full border border-base-300">
                    <thead class="bg-base-200/50">
                        <tr>
                            <th class="border border-base-300 bg-neutral text-neutral-content w-12 text-center">No.</th>
                            <th class="border border-base-300 bg-neutral text-neutral-content">Student Name</th>
                            <th class="border border-base-300 bg-neutral text-neutral-content text-center">Time In</th>
                            <th class="border border-base-300 bg-neutral text-neutral-content text-center">Time Out</th>
                            <th class="border border-base-300 bg-neutral text-neutral-content text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($report['records'] as $record): ?>
                        <tr class="border-b border-base-300">
                            <td class="border border-base-300 text-center font-mono text-xs"><?= $i++ ?></td>
                            <td class="border border-base-300 font-bold"><?= $record['last_name'] ?>, <?= $record['first_name'] ?></td>
                            <td class="border border-base-300 text-center text-xs">
                                <?= $record['time_in'] ? date('h:i A', strtotime($record['time_in'])) : '---' ?>
                            </td>
                            <td class="border border-base-300 text-center text-xs">
                                <?= $record['time_out'] ? date('h:i A', strtotime($record['time_out'])) : '---' ?>
                            </td>
                            <td class="border border-base-300 text-center">
                                <span class="font-bold text-[10px] uppercase">
                                    <?php 
                                        if ($record['is_manual']) echo "Manual";
                                        else if ($record['status'] == 'on_time') echo "On Time";
                                        else if ($record['status'] == 'late') echo "Late";
                                        else if ($record['status'] == 'incomplete') echo "Incomplete";
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Summary Footer -->
            <div class="mt-12 grid grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-base-200/30 rounded-xl border border-base-200 print:bg-white print:border-base-content print:rounded-none">
                <div class="text-center">
                    <div class="text-[10px] uppercase font-black opacity-50">Total Students</div>
                    <div class="text-xl font-bold"><?= $report['summary']['total'] ?></div>
                </div>
                <div class="text-center">
                    <div class="text-[10px] uppercase font-black text-success">On Time</div>
                    <div class="text-xl font-bold"><?= $report['summary']['on_time'] ?></div>
                </div>
                <div class="text-center">
                    <div class="text-[10px] uppercase font-black text-warning">Late</div>
                    <div class="text-xl font-bold"><?= $report['summary']['late'] ?></div>
                </div>
                <div class="text-center">
                    <div class="text-[10px] uppercase font-black text-error">Incomplete</div>
                    <div class="text-xl font-bold"><?= $report['summary']['incomplete'] ?></div>
                </div>
            </div>

            <!-- Print Timestamp -->
            <div class="mt-12 text-[10px] opacity-30 italic text-right hidden print:block">
                Generated on: <?= date('Y-m-d H:i:s') ?> by RFID Attendance System
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .navbar, .drawer-side, .footer, .btn, .alert { display: none !important; }
    .card { border: none !important; box-shadow: none !important; width: 100% !important; max-width: 100% !important; margin: 0 !important; }
    .card-body { padding: 0 !important; }
    table { width: 100% !important; border-collapse: collapse !important; }
    th, td { border: 1px solid black !important; padding: 4px !important; color: black !important; }
    .bg-neutral { background-color: #f3f4f6 !important; color: black !important; }
    .text-success { color: green !important; }
    .text-warning { color: orange !important; }
    .text-error { color: red !important; }
}
</style>

<?= $this->endSection() ?>
