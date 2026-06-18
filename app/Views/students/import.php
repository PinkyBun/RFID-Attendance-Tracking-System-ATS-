<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold">Import Students</h2>
        <p class="text-base-content/60">Upload an Excel or CSV file to perform a bulk import.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= url_to('students.import.template') ?>" class="btn btn-secondary shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
            Download Import Template
        </a>
        <a href="<?= url_to('students.index') ?>" class="btn btn-outline">Back to Students</a>
    </div>
</div>

<div class="card bg-base-100 shadow-xl border border-base-200">
    <div class="card-body">
        
        <?php if (session()->getFlashdata('import_errors')): ?>
            <div class="alert alert-error shadow-lg mb-6 items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="font-bold">Import Errors</h3>
                    <div class="text-sm mt-1 max-h-40 overflow-y-auto pr-4">
                        <ul class="list-disc list-inside">
                        <?php foreach (session()->getFlashdata('import_errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="alert alert-info shadow-none bg-info/10 text-info-content mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h3 class="font-bold">Required File Structure</h3>
                <div class="text-sm">Your file must include the following column headers (case-insensitive):</div>
                <div class="font-mono text-sm mt-2 bg-base-100 p-3 rounded-lg border border-base-300 overflow-x-auto whitespace-nowrap opacity-90 shadow-inner text-base-content font-bold">
                    Student Name | Student ID | Year Level | Section | Student Type | Subject
                </div>
                <div class="text-xs mt-2 opacity-80">
                    * Make sure <strong>Student Type</strong> is either "Regular" or "Irregular".<br>
                    * <strong>Subject</strong> allows comma-separated names if irregular. For Regulars, it auto-enrolls if matching section exists.
                </div>
            </div>
        </div>

        <form action="<?= url_to('students.import.process') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
            <?= csrf_field() ?>
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-bold">Select Excel/CSV File</span>
                </label>
                <input type="file" name="excel_file" class="file-input file-input-bordered file-input-primary w-full max-w-md" accept=".xls,.xlsx,.csv" required />
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-8">Process Import</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
