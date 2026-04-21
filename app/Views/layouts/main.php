<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> | RFID Attendance</title>
    <!-- Tailwind and DaisyUI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js for Dashboard (optional but good for premium feel) -->
    <!-- Tom Select (Searchable Dropdowns) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/tomselect-theme.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .drawer-side .menu { @apply bg-base-100 p-4 w-80 min-h-full border-r border-base-200; }
    </style>
</head>
<body class="bg-base-200 min-h-screen">

<div class="drawer lg:drawer-open">
    <input id="main-drawer" type="checkbox" class="drawer-toggle" />
    
    <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="navbar bg-base-100 shadow-sm lg:hidden">
            <div class="flex-none">
                <label for="main-drawer" class="btn btn-square btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </label>
            </div>
            <div class="flex-1 px-4 text-xl font-bold">RFID Attendance</div>
        </div>

        <!-- Desktop Header / Status Bar -->
        <div class="hidden lg:flex navbar bg-base-100 px-8 border-b border-base-200 justify-between items-center h-20">
            <h1 class="text-2xl font-bold"><?= $title ?? 'Dashboard' ?></h1>
            
            <div class="flex items-center gap-2">
                <!-- Theme Selector -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-3M9.707 3.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414-1.414l-3-3z" /></svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[50] p-2 shadow-2xl bg-base-300 rounded-box w-52 mt-4 max-h-96 overflow-y-auto">
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Default" value="default"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Light" value="light"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Dark" value="dark"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Cupcake" value="cupcake"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Corporate" value="corporate"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Synthwave" value="synthwave"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Cyberpunk" value="cyberpunk"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Retro" value="retro"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Valentine" value="valentine"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Aqua" value="aqua"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Luxury" value="luxury"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Dracula" value="dracula"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Night" value="night"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Coffee" value="coffee"/></li>
                        <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="Winter" value="winter"/></li>
                    </ul>
                </div>

                <div class="divider divider-horizontal mx-0"></div>

                <!-- Admin Info -->
                <a href="<?= url_to('profile') ?>" class="flex items-center gap-3 hover:bg-base-200 p-2 rounded-xl transition-all cursor-pointer">
                    <div class="flex flex-col items-end">
                        <span class="text-sm font-bold leading-tight"><?= session()->get('user_name') ?></span>
                        <span class="text-[10px] uppercase tracking-tighter opacity-50">Teacher</span>
                </div>
                <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-9 shadow-sm">
                        <span><?= substr(session()->get('user_name'), 0, 1) ?></span>
                    </div>
                </div>
                </a>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="p-4 lg:p-8">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div role="alert" class="alert alert-success shadow-lg mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div role="alert" class="alert alert-error shadow-lg mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
                <div role="alert" class="alert alert-warning shadow-lg mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span><?= session()->getFlashdata('warning') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
                <div role="alert" class="alert alert-info shadow-lg mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?= session()->getFlashdata('info') ?></span>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div> 

    <!-- Sidebar / Drawer Side -->
    <div class="drawer-side z-20">
        <label for="main-drawer" class="drawer-overlay"></label> 
        <div class="menu bg-base-100 p-0 text-base-content min-h-full w-72 flex flex-col border-r border-base-200">
            <!-- Sidebar Header -->
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary text-primary-content rounded-lg flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-1.637A10.025 10.025 0 0110 11.235V9a6 6 0 00-6-6H4" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-lg leading-none">RFID ATS</h2>
                    <p class="text-xs text-base-content/50 mt-1">Attendance System</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <ul class="menu flex-1 px-4 gap-1">
                <li class="menu-title">Main</li>
                <li><a href="<?= url_to('dashboard') ?>" class="<?= url_is('dashboard') ? 'active' : '' ?>">Dashboard</a></li>
                
                <li class="menu-title mt-4">Attendance Engine</li>
                <li><a href="<?= url_to('session.index') ?>" class="<?= url_is('attendance/session*') ? 'active' : '' ?>">Class Sessions</a></li>
                <li><a href="<?= url_to('rfid.live') ?>" class="<?= url_is('attendance/rfid*') ? 'active' : '' ?>">Live Tap View</a></li>
                <li><a href="<?= url_to('attendance.index') ?>" class="<?= url_is('attendance') ? 'active' : '' ?>">Attendance Records</a></li>
                <li><a href="<?= url_to('attendance.manual') ?>" class="<?= url_is('attendance/manual*') ? 'active' : '' ?>">Manual Entry</a></li>

                <li class="menu-title mt-4">Management</li>
                <li><a href="<?= url_to('students.index') ?>" class="<?= url_is('students*') ? 'active' : '' ?>">Students</a></li>
                <li><a href="<?= url_to('subjects.index') ?>" class="<?= url_is('subjects*') ? 'active' : '' ?>">Subjects</a></li>
                <li><a href="<?= url_to('sections.index') ?>" class="<?= url_is('sections*') ? 'active' : '' ?>">Sections</a></li>

                <li class="menu-title mt-4">Analysis</li>
                <li><a href="<?= url_to('reports.index') ?>" class="<?= url_is('reports*') ? 'active' : '' ?>">Reports</a></li>
            </ul>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-base-200">
                <a href="<?= url_to('logout') ?>" class="btn btn-ghost btn-block justify-start text-error gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Global Confirmation Modal -->
<dialog id="confirmation_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 id="modal_title" class="font-bold text-lg">Confirm Action</h3>
        <p id="modal_message" class="py-4 text-base-content/70">Are you sure you want to proceed?</p>
        <div class="modal-action">
            <button class="btn btn-ghost" onclick="confirmation_modal.close()">Cancel</button>
            <button id="modal_confirm_btn" class="btn btn-primary px-8">Confirm</button>
        </div>
    </div>
</dialog>

<script>
    // Theme setup
    const savedTheme = localStorage.getItem('ats-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);

    document.querySelectorAll('.theme-controller').forEach(controller => {
        controller.addEventListener('change', () => {
            const theme = controller.value;
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('ats-theme', theme);
        });
    });

    // Global Confirmation Helper
    window.confirmAction = function(options) {
        const modal = document.getElementById('confirmation_modal');
        const titleEl = document.getElementById('modal_title');
        const messageEl = document.getElementById('modal_message');
        const confirmBtn = document.getElementById('modal_confirm_btn');

        titleEl.textContent = options.title || 'Confirm Action';
        messageEl.textContent = options.message || 'Are you sure you want to proceed?';
        confirmBtn.className = `btn px-8 ${options.confirmClass || 'btn-primary'}`;
        confirmBtn.textContent = options.confirmText || 'Confirm';

        // Clear previous event listeners
        const newBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);

        newBtn.addEventListener('click', () => {
            if (options.formId) {
                document.getElementById(options.formId).submit();
            } else if (options.onConfirm) {
                options.onConfirm();
            }
            modal.close();
        });

        modal.showModal();
        return false;
    };

    // Initialize Tom Select on all dropdowns
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('select:not(.no-select)').forEach(el => {
            const ts = new TomSelect(el, {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxOptions: null,
                openOnFocus: true,
                selectOnTab: true,
                hidePlaceholder: true,
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">No results found for "' + escape(data.input) + '"</div>';
                    }
                }
            });

            // Only remove decorative styling classes, but KEEP size classes (sm, lg) 
            // so our CSS theme can still target the variants correctly.
            const classesToRemove = ['select', 'select-bordered', 'input', 'input-bordered'];
            ts.wrapper.classList.remove(...classesToRemove);
            
            // Re-apply w-full if it was on the original element
            if (el.classList.contains('w-full')) {
                ts.wrapper.classList.add('w-full');
            }
        });
    });
</script>
</body>
</html>
