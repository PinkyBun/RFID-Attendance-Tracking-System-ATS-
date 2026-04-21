<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-base-content/60">Manage student sections and departments</p>
    </div>
    <a href="<?= url_to('sections.create') ?>" class="btn btn-primary shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        New Section
    </a>
</div>

<div class="card bg-base-100 shadow rounded-2xl overflow-hidden">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-base-200/50">
                    <tr>
                        <th>Section Name</th>
                        <th>Course / Program</th>
                        <th>Year Level</th>
                        <th>Student Count</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sections)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-12 text-base-content/40">No sections found. Add your first section to begin.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sections as $section): ?>
                            <tr class="hover">
                                <td class="font-bold text-primary"><?= $section['name'] ?></td>
                                <td><?= $section['course'] ?></td>
                                <td>
                                    <div class="badge badge-outline">Year <?= $section['year_level'] ?></div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="radial-progress text-primary text-[10px]" style="--value:<?= min($section['student_count'] * 2, 100) ?>; --size:1.5rem; --thickness: 2px;" role="progressbar"></div>
                                        <span><?= $section['student_count'] ?> Students</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="join">
                                        <a href="<?= url_to('sections.edit', $section['id']) ?>" class="btn btn-ghost btn-sm join-item text-info">Edit</a>
                                        <form id="delete-section-form-<?= $section['id'] ?>" action="<?= url_to('sections.destroy', $section['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-ghost btn-sm join-item text-error"
                                                onclick="return confirmAction({
                                                    title: 'Delete Section?',
                                                    message: 'Are you sure you want to delete this section? This cannot be undone and may affect regular student records.',
                                                    confirmText: 'Delete',
                                                    confirmClass: 'btn-error',
                                                    formId: 'delete-section-form-<?= $section['id'] ?>'
                                                })">Delete</button>
                                        </form>
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
