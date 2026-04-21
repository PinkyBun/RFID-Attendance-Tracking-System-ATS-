<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto">
    <?php if ($current_session): ?>
        <div class="alert alert-info shadow-lg mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="flex-1">
                <h3 class="font-bold">Active Session Detected</h3>
                <div class="text-sm">You are currently monitoring: <span class="font-bold"><?= $current_session['subject_code'] ?> (<?= date('h:i A', strtotime($current_session['start_time'])) ?>)</span></div>
            </div>
            <div class="flex-none">
                <a href="<?= url_to('rfid.live') ?>" class="btn btn-sm">Go to Live View</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-xl rounded-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-2">Select Active Subject</h2>
            <p class="text-base-content/60 mb-8 font-medium">Choose which class you are teaching now to enable RFID tapping.</p>
            
            <form action="<?= url_to('session.open') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-control w-full mb-6">
                    <label class="label"><span class="label-text font-semibold">Subject</span></label>
                    <select id="subject_id" name="subject_id" class="select select-bordered select-lg w-full focus:select-primary" required onchange="loadSchedules()">
                        <option value="" disabled selected>Select a subject...</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>"><?= $subject['code'] ?> - <?= $subject['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="schedule_section" class="form-control w-full mb-8 hidden">
                    <label class="label"><span class="label-text font-semibold">Select Schedule Slot</span></label>
                    <div id="schedule_list" class="grid grid-cols-1 gap-3">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <div class="card-actions justify-end mt-8 pt-6 border-t border-base-200">
                    <button type="submit" id="submit_btn" class="btn btn-primary btn-lg px-12 shadow-lg" disabled>
                        Activate & Start Tapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    async function loadSchedules() {
        const subjectId = document.getElementById('subject_id').value;
        const section = document.getElementById('schedule_section');
        const list = document.getElementById('schedule_list');
        const submitBtn = document.getElementById('submit_btn');

        if (!subjectId) return;

        section.classList.remove('hidden');
        list.innerHTML = `<div class="flex justify-center p-4"><span class="loading loading-spinner loading-md text-primary"></span></div>`;
        submitBtn.disabled = true;

        try {
            const response = await fetch(`<?= base_url('attendance/session/schedules') ?>/${subjectId}`);
            const schedules = await response.json();

            list.innerHTML = '';
            
            if (schedules.length === 0) {
                list.innerHTML = `<div class="alert alert-warning text-xs">No schedules found for this subject. Please add a schedule first.</div>`;
                return;
            }

            schedules.forEach((sched, index) => {
                const label = document.createElement('label');
                label.className = "label cursor-pointer justify-start gap-4 p-4 bg-base-200/50 rounded-xl hover:bg-primary/10 border-2 border-transparent transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5";
                
                // Format times
                const start = sched.start_time;
                const end = sched.end_time;

                label.innerHTML = `
                    <input type="radio" name="schedule_id" value="${sched.id}" class="radio radio-primary" required onchange="document.getElementById('submit_btn').disabled = false" ${index === 0 && schedules.length === 1 ? 'checked' : ''}>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm text-primary uppercase">${sched.day_of_week}</span>
                        <span class="text-sm font-medium">${start} - ${end}</span>
                    </div>
                `;
                list.appendChild(label);
                
                if (index === 0 && schedules.length === 1) {
                    submitBtn.disabled = false;
                }
            });

        } catch (error) {
            list.innerHTML = `<div class="alert alert-error">Error loading schedules.</div>`;
        }
    }
</script>

<?= $this->endSection() ?>
