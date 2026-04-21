<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-base-content/60">View history of all class sessions</p>
    </div>
    <a href="<?= url_to('session.select') ?>" class="btn btn-primary shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Activate New Session
    </a>
</div>

<div class="card bg-base-100 shadow rounded-2xl overflow-hidden">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200/50">
                    <tr>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Opened At</th>
                        <th>Closed At</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sessions)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-12 text-base-content/40 italic">No class sessions recorded yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sessions as $session): ?>
                            <tr>
                                <td>
                                    <div class="font-bold"><?= $session['subject_code'] ?></div>
                                    <div class="text-xs opacity-50"><?= $session['subject_name'] ?></div>
                                </td>
                                <td><?= date('M d, Y', strtotime($session['session_date'])) ?></td>
                                <td class="font-mono text-xs"><?= date('h:i A', strtotime($session['opened_at'])) ?></td>
                                <td class="font-mono text-xs">
                                    <?= $session['closed_at'] ? date('h:i A', strtotime($session['closed_at'])) : '---' ?>
                                </td>
                                <td>
                                    <?php if ($session['is_active']): ?>
                                        <span class="badge badge-info badge-sm animate-pulse">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-ghost badge-sm">Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($session['is_active']): ?>
                                        <a href="<?= url_to('rfid.live') ?>" class="btn btn-ghost btn-xs text-primary underline">Monitor</a>
                                    <?php else: ?>
                                        <a href="<?= url_to('attendance.index') ?>?subject_id=<?= $session['subject_id'] ?>&start_date=<?= $session['session_date'] ?>&end_date=<?= $session['session_date'] ?>" class="btn btn-ghost btn-xs">View Records</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
