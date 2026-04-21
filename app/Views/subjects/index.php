<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-base-content/60">Manage subjects and their weekly schedules</p>
    </div>
    <a href="<?= url_to('subjects.create') ?>" class="btn btn-primary shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        New Subject
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php if (empty($subjects)): ?>
        <div class="col-span-full card bg-base-100 shadow p-12 text-center text-base-content/40">
            No subjects found. Create your first subject to start tracking attendance.
        </div>
    <?php else: ?>
        <?php foreach ($subjects as $subject): ?>
            <div class="card bg-base-100 shadow-md hover:shadow-xl transition-shadow border border-base-200">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="badge badge-primary badge-sm mb-1"><?= $subject['code'] ?></div>
                            <h3 class="card-title text-xl leading-tight"><?= $subject['name'] ?></h3>
                        </div>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs btn-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-4 h-4 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M12 12h.01M12 12h.01M12 12h.01"></path></svg>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-32 border border-base-200">
                                <li><a href="<?= url_to('subjects.edit', $subject['id']) ?>" class="text-info">Edit</a></li>
                                <li>
                                    <form id="delete-subject-form-<?= $subject['id'] ?>" action="<?= url_to('subjects.destroy', $subject['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="text-error w-full text-left" 
                                            onclick="return confirmAction({
                                                title: 'Delete Subject?',
                                                message: 'Are you sure you want to delete this subject? All associated schedules and attendance records will be affected.',
                                                confirmText: 'Delete',
                                                confirmClass: 'btn-error',
                                                formId: 'delete-subject-form-<?= $subject['id'] ?>'
                                            })">Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 mt-2">
                        <div class="badge badge-outline badge-sm">Year <?= $subject['year_level'] ?></div>
                        <div class="badge badge-ghost badge-sm"><?= $subject['section_name'] ?? 'No Fixed Section' ?></div>
                    </div>

                    <div class="divider my-2 opacity-30"></div>

                    <div class="space-y-2">
                        <p class="text-xs font-bold text-base-content/50 uppercase tracking-widest">Schedules</p>
                        <?php if (empty($subject['schedules'])): ?>
                            <p class="text-xs italic text-error">No schedules assigned.</p>
                        <?php else: ?>
                            <?php foreach ($subject['schedules'] as $sched): ?>
                                <div class="flex justify-between items-center bg-base-200/50 p-2 rounded-lg text-sm">
                                    <span class="font-semibold text-primary"><?= $sched['day_of_week'] ?></span>
                                    <span class="font-mono text-xs">
                                        <?= date('h:i A', strtotime($sched['start_time'])) ?> - <?= date('h:i A', strtotime($sched['end_time'])) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (isset($pager)): ?>
    <div class="mt-12 flex justify-center">
        <?= $pager->links('default', 'daisyui_full') ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
