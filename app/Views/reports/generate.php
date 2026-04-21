<?php 
/** @var array $subjects */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Generate Attendance Report</h1>
        <p class="text-base-content/60">Select the criteria below to generate a printable attendance report.</p>
    </div>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body p-8">
            <form action="<?= url_to('reports.preview') ?>" method="POST" id="reportForm">
                <?= csrf_field() ?>

                <div class="space-y-6">
                    <!-- Subject Selection -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Subject</span>
                        </label>
                        <select name="subject_name" id="subject_select" class="select select-bordered w-full" required>
                            <option value="">Search Subject...</option>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?= htmlspecialchars($s['name']) ?>"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Section Selection (Filtered) -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Section</span>
                        </label>
                        <select name="section_id" id="section_select" class="select select-bordered w-full" required disabled>
                            <option value="">Select a subject first...</option>
                        </select>
                    </div>

                    <!-- Date Selection -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Specific Date</span>
                        </label>
                        <input type="date" name="report_date" value="<?= date('Y-m-d') ?>" class="input input-bordered w-full" required />
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="btn btn-primary w-full shadow-lg gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Generate Report Preview
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = new TomSelect('#subject_select', {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });

    const sectionSelect = new TomSelect('#section_select', {
        create: false,
        placeholder: 'Select Section',
    });

    subjectSelect.on('change', function(value) {
        if (!value) {
            sectionSelect.disable();
            sectionSelect.clearOptions();
            return;
        }

        // Fetch sections for this subject
        fetch(`<?= base_url('reports/sections') ?>/${encodeURIComponent(value)}`)
            .then(response => response.json())
            .then(data => {
                sectionSelect.clearOptions();
                data.forEach(section => {
                    sectionSelect.addOption({
                        value: section.id,
                        text: section.name
                    });
                });
                sectionSelect.enable();
                if (data.length > 0) {
                    sectionSelect.setValue('');
                }
            })
            .catch(error => console.error('Error fetching sections:', error));
    });
});
</script>

<?= $this->endSection() ?>
