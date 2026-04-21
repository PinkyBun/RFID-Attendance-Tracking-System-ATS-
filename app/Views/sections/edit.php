<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="<?= url_to('sections.index') ?>" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to List
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-4">Edit Section: <?= $section['name'] ?></h2>
            
            <form action="<?= url_to('sections.update', $section['id']) ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Section Name</span>
                    </label>
                    <input type="text" name="name" value="<?= old('name', $section['name']) ?>" class="input input-bordered w-full focus:input-primary" required />
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Course / Program</span>
                    </label>
                    <input type="text" name="course" value="<?= old('course', $section['course']) ?>" class="input input-bordered w-full focus:input-primary" required />
                </div>

                <div class="form-control w-full mb-8">
                    <label class="label">
                        <span class="label-text font-semibold">Year Level</span>
                    </label>
                    <select name="year_level" class="select select-bordered w-full focus:select-primary" required>
                        <?php for($i=1; $i<=4; $i++): ?>
                            <option value="<?= $i ?>" <?= old('year_level', $section['year_level']) == $i ? 'selected' : '' ?>>Year <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="card-actions justify-end gap-2">
                    <a href="<?= url_to('sections.index') ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-8">Update Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
