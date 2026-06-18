<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold">RFID Assignment</h2>
        <p class="text-base-content/60">Assign RFID physical cards to students awaiting registration.</p>
    </div>
</div>

<div class="card bg-base-100 shadow rounded-2xl overflow-hidden mb-8">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-base-200/50">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-12 text-base-content/40">All students are currently assigned an RFID.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr class="hover" id="row-<?= $student['id'] ?>">
                                <td class="font-mono text-xs"><?= esc($student['student_number']) ?></td>
                                <td class="font-bold border-none"><?= esc($student['last_name'] . ', ' . $student['first_name']) ?></td>
                                <td>
                                    <span class="badge badge-sm badge-outline"><?= esc(ucfirst($student['type'])) ?></span>
                                </td>
                                <td class="text-right">
                                    <button onclick="openAssignModal(<?= $student['id'] ?>, '<?= esc(addslashes($student['first_name'] . ' ' . $student['last_name'])) ?>')" class="btn btn-sm btn-primary">Assign RFID</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Assign RFID Modal -->
<dialog id="rfid_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box text-center p-8">
        <div class="w-20 h-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
        </div>
        
        <h3 class="font-black text-2xl mb-2">Assigning Card for</h3>
        <p id="student_name_label" class="text-lg font-semibold text-primary mb-6">Student Name</p>
        
        <p class="text-base-content/60 mb-6">Please tap the physical RFID card on the reader now.</p>
        
        <div id="status_alert" class="alert hidden mb-4 text-left"></div>

        <div class="relative w-full max-w-xs mx-auto">
            <span class="loading loading-bars loading-lg text-primary opacity-50 absolute left-1/2 -translate-x-1/2 -top-12 pointer-events-none"></span>
            <!-- Hidden input simulating the wedge tap -->
            <input type="text" id="rfid_capture_input" class="input input-bordered w-full opacity-0 absolute pointer-events-none" autocomplete="off" />
        </div>

        <div class="modal-action justify-center mt-12">
            <button class="btn btn-ghost" onclick="closeAssignModal()">Cancel</button>
        </div>
    </div>
</dialog>

<script>
    let activeStudentId = null;
    const rfidModal = document.getElementById('rfid_modal');
    const captureInput = document.getElementById('rfid_capture_input');
    const nameLabel = document.getElementById('student_name_label');
    const statusAlert = document.getElementById('status_alert');

    function openAssignModal(id, name) {
        activeStudentId = id;
        nameLabel.textContent = name;
        captureInput.value = '';
        statusAlert.classList.add('hidden');
        statusAlert.className = 'alert hidden mb-4 text-left';
        rfidModal.showModal();
        
        // Ensure input is focused for scanner string
        setTimeout(() => {
            captureInput.focus();
        }, 100);
    }

    function closeAssignModal() {
        rfidModal.close();
        activeStudentId = null;
    }

    // Keep it focused just in case
    document.addEventListener('click', () => {
        if(activeStudentId && rfidModal.open) captureInput.focus();
    });
    setInterval(() => {
        if(activeStudentId && rfidModal.open && document.activeElement !== captureInput){
            captureInput.focus();
        }
    }, 500);

    captureInput.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') {
            const uid = captureInput.value.trim();
            captureInput.value = ''; // clear right away
            if (!uid) return;
            
            statusAlert.classList.remove('hidden', 'alert-error', 'alert-success');
            statusAlert.classList.add('alert-info', 'flex');
            statusAlert.innerHTML = '<span class="loading loading-spinner text-info shrink-0"></span> <span class="text-sm">Processing...</span>';

            try {
                const formData = new FormData();
                formData.append('student_id', activeStudentId);
                formData.append('rfid_uid', uid);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                const response = await fetch('<?= url_to('students.rfid_assign.capture') ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    statusAlert.className = 'alert alert-success text-left mb-4 text-sm flex gap-2';
                    statusAlert.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span class="font-bold">${result.message}</span>`;
                    
                    // remove row after success
                    const row = document.getElementById('row-' + activeStudentId);
                    if (row) {
                        row.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                        setTimeout(() => row.remove(), 500);
                    }

                    setTimeout(() => {
                        closeAssignModal();
                    }, 1500);
                } else {
                    statusAlert.className = 'alert alert-error text-left mb-4 text-sm flex gap-2';
                    statusAlert.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span class="font-bold">${result.message}</span>`;
                    captureInput.focus();
                }
            } catch (err) {
                statusAlert.className = 'alert alert-error text-left mb-4 text-sm';
                statusAlert.innerText = 'Network error. Please try again.';
                captureInput.focus();
            }
        }
    });
</script>

<?= $this->endSection() ?>
