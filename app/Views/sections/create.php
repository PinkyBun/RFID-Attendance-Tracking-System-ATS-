<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-6">
        <a href="<?= url_to('sections.index') ?>" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to List
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-6">Create New Section</h2>
            
            <form action="<?= url_to('sections.store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Section Name</span>
                        </label>
                        <input type="text" name="name" value="<?= old('name') ?>" placeholder="e.g. BSIT 2A" class="input input-bordered w-full focus:input-primary px-4 py-2" required />
                        <label class="label text-xs text-base-content/50 italic">Must be unique (e.g., Program + Year + Section)</label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Course / Program</span>
                        </label>
                        <input type="text" name="course" value="<?= old('course') ?>" placeholder="e.g. Bachelor of Science in Information Technology" class="input input-bordered w-full focus:input-primary px-4 py-2" required />
                    </div>

                    <div class="form-control w-full col-span-full">
                        <label class="label">
                            <span class="label-text font-semibold">Year Level</span>
                        </label>
                        <select name="year_level" class="select select-bordered w-full focus:select-primary" required>
                            <option value="" disabled selected>Select Year Level</option>
                            <option value="1" <?= old('year_level') == '1' ? 'selected' : '' ?>>1st Year</option>
                            <option value="2" <?= old('year_level') == '2' ? 'selected' : '' ?>>2nd Year</option>
                            <option value="3" <?= old('year_level') == '3' ? 'selected' : '' ?>>3rd Year</option>
                            <option value="4" <?= old('year_level') == '4' ? 'selected' : '' ?>>4th Year</option>
                        </select>
                    </div>
                </div>

                <div class="card-actions justify-end pt-6 border-t border-base-200">
                    <a href="<?= url_to('sections.index') ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-12 shadow-lg">Create Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
