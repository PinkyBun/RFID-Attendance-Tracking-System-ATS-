<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto flex flex-col gap-8">
    
    <!-- Top Bar: Session Info & Clock -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-base-100 p-8 rounded-3xl shadow-xl border border-base-200 gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tight uppercase"><?= $session['subject_code'] ?></h1>
                <p class="text-base-content/60 font-medium"><?= $session['subject_name'] ?></p>
            </div>
        </div>

        <div class="divider md:divider-horizontal"></div>

        <div class="text-center md:text-right">
            <div id="live-clock" class="text-4xl font-black font-mono tracking-tighter text-primary">00:00:00</div>
            <div class="text-sm font-bold opacity-40 uppercase tracking-widest"><?= date('l, F d') ?></div>
        </div>

        <div class="flex flex-col gap-2">
            <form id="end-session-form" action="<?= url_to('session.close', $session['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-error btn-outline shadow-sm w-full" 
                    onclick="return confirmAction({
                        title: 'End Class Session?',
                        message: 'Ending this session will mark all students who have not timed out as Incomplete. This cannot be undone.',
                        confirmText: 'End Session',
                        confirmClass: 'btn-error',
                        formId: 'end-session-form'
                    })">
                    End Session
                </button>
            </form>
            <div class="flex gap-2">
                <a href="<?= url_to('attendance.manual') ?>" class="btn btn-ghost btn-sm flex-1">Manual Entry</a>
                <button id="toggle-simulator" class="btn btn-primary btn-sm btn-outline flex-1">Simulator</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Main Tap Area -->
        <div class="lg:col-span-3 flex flex-col gap-6">
            <div class="card bg-base-100 shadow-2xl rounded-3xl overflow-hidden border-2 border-primary/20">
                <div class="card-body items-center text-center p-12">
                    <div id="tap-status-icon" class="w-32 h-32 bg-base-200 rounded-full flex items-center justify-center mb-6 transition-all duration-300">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    </div>
                    
                    <h2 id="tap-message" class="text-2xl font-bold mb-2">Ready for Card Tap</h2>
                    <p id="tap-submessage" class="text-base-content/50 mb-8">Please place your card near the reader</p>

                    <div class="form-control w-full max-w-xs">
                        <!-- Hidden but active input to capture RFID UID -->
                        <input type="text" id="rfid-input" class="absolute opacity-0 pointer-events-none" autocomplete="off" autofocus />
                        <div class="flex flex-col items-center">
                            <span class="loading loading-ring loading-lg text-primary opacity-20"></span>
                            <span class="text-[10px] uppercase font-bold tracking-widest mt-2 opacity-30">Scanner Active</span>
                        </div>
                    </div>
                </div>
                
                <!-- Feedback Strip -->
                <div id="tap-feedback-strip" class="bg-primary h-4 transition-all duration-300 opacity-0"></div>
            </div>

            <div id="last-tap-card" class="card bg-base-100 shadow-xl rounded-2xl hidden animate-in slide-in-from-bottom-4 duration-500">
                <div class="card-body p-6 flex-row items-center gap-6">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-xl w-16">
                            <span id="last-tap-initials" class="text-2xl font-bold">--</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-xs uppercase font-bold text-primary tracking-widest" id="last-tap-type">Time In</div>
                        <div class="text-xl font-black" id="last-tap-name">Student Name</div>
                        <div class="text-sm opacity-60" id="last-tap-time">00:00 AM</div>
                    </div>
                    <div id="last-tap-status">
                        <span class="badge badge-success lg:badge-lg">On Time</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Taps List -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-xl rounded-2xl h-full border border-base-200 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-6 bg-base-100/50 border-b border-base-200">
                        <h3 class="font-bold text-lg">Recent Taps</h3>
                    </div>
                    <div class="overflow-y-auto max-h-[600px]">
                        <table class="table table-sm table-zebra w-full" id="recent-taps-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_taps as $tap): ?>
                                    <tr>
                                        <td class="font-bold"><?= $tap['first_name'] ?> <?= $tap['last_name'] ?></td>
                                        <td class="font-mono text-[10px]"><?= format_attendance_time($tap['time_out'] ?? $tap['time_in']) ?></td>
                                        <td>
                                            <span class="badge badge-xs <?= get_status_badge_class($tap['status']) ?>">
                                                <?= $tap['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($recent_taps)): ?>
                                    <tr id="no-taps-row">
                                        <td colspan="3" class="text-center py-8 opacity-40 italic">Waiting for taps...</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simulation Panel (Hidden by default) -->
<div id="simulation-panel" class="fixed top-0 right-0 h-full w-80 bg-base-100 shadow-2xl border-l border-base-200 z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="p-6 border-b border-base-200 flex justify-between items-center bg-primary text-primary-content">
        <h3 class="font-bold text-lg uppercase tracking-tight">Tap Simulator</h3>
        <button id="close-simulator" class="btn btn-ghost btn-circle btn-sm">✕</button>
    </div>
    <div class="p-4 bg-base-200 text-[10px] uppercase font-bold tracking-widest opacity-60">
        Registered Students
    </div>
    <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3">
        <?php foreach ($students as $student): ?>
            <div class="card bg-base-200 shadow-sm border border-base-300">
                <div class="p-3 flex justify-between items-center gap-2">
                    <div class="overflow-hidden">
                        <div class="font-bold text-xs truncate"><?= $student['first_name'] ?> <?= $student['last_name'] ?></div>
                        <div class="text-[10px] opacity-60 font-mono"><?= $student['rfid_uid'] ?></div>
                    </div>
                    <button class="btn btn-xs btn-primary shadow-sm simulate-btn" data-uid="<?= $student['rfid_uid'] ?>">Tap</button>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="divider text-[10px] uppercase font-bold opacity-30">Edge Cases</div>
        
        <button class="btn btn-outline btn-error btn-sm simulate-btn" data-uid="UNKNOWN_CARD_123">
            Unknown RFID Card
        </button>
        <button class="btn btn-outline btn-warning btn-sm simulate-btn" data-uid="">
            Empty UID
        </button>
    </div>
    <div class="p-6 border-t border-base-200 text-center">
        <p class="text-[10px] opacity-40 uppercase font-black">Hardware Testing Mode</p>
    </div>
</div>

<!-- Sound Effects -->
<audio id="sound-success" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3"></audio>
<audio id="sound-error" src="https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3"></audio>

<script>
    const rfidInput = document.getElementById('rfid-input');
    const statusIcon = document.getElementById('tap-status-icon');
    const message = document.getElementById('tap-message');
    const submessage = document.getElementById('tap-submessage');
    const feedbackStrip = document.getElementById('tap-feedback-strip');
    const lastTapCard = document.getElementById('last-tap-card');
    const recentTable = document.getElementById('recent-taps-table').getElementsByTagName('tbody')[0];
    const noTapsRow = document.getElementById('no-taps-row');
    const simulationPanel = document.getElementById('simulation-panel');
    const toggleSimBtn = document.getElementById('toggle-simulator');
    const closeSimBtn = document.getElementById('close-simulator');

    // Simulator Toggle
    toggleSimBtn.addEventListener('click', () => {
        simulationPanel.classList.remove('translate-x-full');
    });
    
    closeSimBtn.addEventListener('click', () => {
        simulationPanel.classList.add('translate-x-full');
    });

    // Handle simulation buttons
    document.querySelectorAll('.simulate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const uid = this.getAttribute('data-uid');
            processTap(uid);
        });
    });

    // Keep input focused at all times
    document.addEventListener('click', () => rfidInput.focus());
    setInterval(() => rfidInput.focus(), 1000);

    // Live Clock
    setInterval(() => {
        const now = new Date();
        document.getElementById('live-clock').textContent = now.toLocaleTimeString('en-US', { hour12: false });
    }, 1000);

    // Handle Input
    rfidInput.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') {
            const uid = rfidInput.value.trim();
            if (uid) {
                processTap(uid);
            }
            rfidInput.value = '';
        }
    });

    async function processTap(uid) {
        try {
            const formData = new FormData();
            formData.append('rfid_uid', uid);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            const response = await fetch('<?= url_to('rfid.tap') ?>', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                showSuccess(result);
                document.getElementById('sound-success').play();
            } else {
                showError(result.message);
                document.getElementById('sound-error').play();
            }
        } catch (err) {
            showError("System connection error.");
        }
    }

    function showSuccess(result) {
        statusIcon.className = "w-32 h-32 bg-success text-success-content rounded-full flex items-center justify-center mb-6 scale-110 shadow-lg transition-all border-4 border-base-100";
        statusIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
        
        message.textContent = "Welcome/Goodbye!";
        message.className = "text-2xl font-black text-success";
        submessage.textContent = result.message;
        feedbackStrip.className = "bg-success h-4 opacity-100";

        // Update Last Tap Card
        lastTapCard.classList.remove('hidden');
        document.getElementById('last-tap-name').textContent = result.data.student_name;
        document.getElementById('last-tap-initials').textContent = result.data.student_name.split(' ').map(n=>n[0]).join('');
        document.getElementById('last-tap-type').textContent = result.data.tap_type == 'time_in' ? 'TIME IN' : 'TIME OUT';
        document.getElementById('last-tap-time').textContent = result.data.time;
        
        const statusBadge = document.getElementById('last-tap-status');
        if (result.data.status) {
            statusBadge.innerHTML = `<span class="badge ${getStatusClass(result.data.status)} lg:badge-lg uppercase font-bold">${result.data.status.replace('_', ' ')}</span>`;
        } else {
            statusBadge.innerHTML = '';
        }

        // Add to recent table
        if (noTapsRow) noTapsRow.remove();
        const row = recentTable.insertRow(0);
        row.className = "animate-in fade-in slide-in-from-left-2 duration-300";
        row.innerHTML = `
            <td class="font-bold">${result.data.student_name}</td>
            <td class="font-mono text-[10px]">${result.data.time}</td>
            <td><span class="badge badge-xs ${getStatusClass(result.data.status || 'manual')}">${result.data.status || 'OK'}</span></td>
        `;

        resetAfterDelay();
    }

    function showError(msg) {
        statusIcon.className = "w-32 h-32 bg-error text-error-content rounded-full flex items-center justify-center mb-6 scale-110 shadow-lg transition-all border-4 border-base-100";
        statusIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>`;
        
        message.textContent = "Access Denied";
        message.className = "text-2xl font-black text-error";
        submessage.textContent = msg;
        feedbackStrip.className = "bg-error h-4 opacity-100";

        resetAfterDelay();
    }

    function resetAfterDelay() {
        setTimeout(() => {
            statusIcon.className = "w-32 h-32 bg-base-200 rounded-full flex items-center justify-center mb-6 transition-all duration-300";
            statusIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>`;
            message.textContent = "Ready for Card Tap";
            message.className = "text-2xl font-bold mb-2";
            submessage.textContent = "Please place your card near the reader";
            feedbackStrip.className = "bg-primary h-4 transition-all duration-300 opacity-0";
        }, 3000);
    }

    function getStatusClass(status) {
        switch (status) {
            case 'on_time': return 'badge-success';
            case 'late':    return 'badge-warning';
            case 'incomplete': return 'badge-error';
            default:        return 'badge-neutral';
        }
    }
</script>

<?= $this->endSection() ?>
