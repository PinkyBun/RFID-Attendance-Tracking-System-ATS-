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
        <div id="sidebarMenu" class="menu bg-base-100 p-0 text-base-content min-h-full w-72 flex flex-col border-r border-base-200 transition-all duration-300">
            <!-- Sidebar Header -->
            <div class="p-4 flex items-center justify-between border-b border-base-200/50">
                <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                    <div class="w-10 h-10 shrink-0 bg-primary text-primary-content rounded-lg flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-1.637A10.025 10.025 0 0110 11.235V9a6 6 0 00-6-6H4" />
                        </svg>
                    </div>
                    <div class="sidebar-text transition-opacity duration-300">
                        <h2 class="font-bold text-lg leading-none">RFID ATS</h2>
                        <p class="text-[10px] text-base-content/50 mt-1 uppercase tracking-widest font-bold">Attendance</p>
                    </div>
                </div>
                <button id="sidebarToggleBtn" class="btn btn-sm btn-ghost btn-circle shrink-0 tooltip tooltip-right" data-tip="Toggle Sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <ul class="menu flex-1 px-3 gap-1 mt-4">
                <li>
                    <a href="<?= url_to('dashboard') ?>" class="<?= url_is('dashboard') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary transition-colors flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                
                <li>
                    <details class="group" <?= url_is('attendance*') ? 'open' : '' ?>>
                        <summary class="<?= url_is('attendance*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary transition-colors flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            <span class="sidebar-text">Attendance</span>
                        </summary>
                        <ul class="sidebar-text mt-1 space-y-1">
                            <li><a href="<?= url_to('session.index') ?>" class="<?= url_is('attendance/session*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">Class Sessions</a></li>
                            <li><a href="<?= url_to('rfid.live') ?>" class="<?= url_is('attendance/rfid*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">Live Tap View</a></li>
                            <li><a href="<?= url_to('attendance.index') ?>" class="<?= url_is('attendance') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">Records</a></li>
                            <li><a href="<?= url_to('attendance.manual') ?>" class="<?= url_is('attendance/manual*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">Manual Entry</a></li>
                        </ul>
                    </details>
                </li>

                <li>
                    <details class="group" <?= url_is('students*') ? 'open' : '' ?>>
                        <summary class="<?= url_is('students*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary transition-colors flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <span class="sidebar-text">Students</span>
                        </summary>
                        <ul class="sidebar-text mt-1 space-y-1">
                            <li><a href="<?= url_to('students.index') ?>" class="<?= url_is('students') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">All Students</a></li>
                            <li><a href="<?= url_to('students.import') ?>" class="<?= url_is('students/import') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">Import</a></li>
                            <li><a href="<?= url_to('students.rfid_assign') ?>" class="<?= url_is('students/rfid-assign') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">RFID Assignment</a></li>
                        </ul>
                    </details>
                </li>

                <li>
                    <details class="group" <?= (url_is('subjects*') || url_is('sections*')) ? 'open' : '' ?>>
                        <summary class="<?= (url_is('subjects*') || url_is('sections*')) ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary transition-colors flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="sidebar-text">Settings</span>
                        </summary>
                        <ul class="sidebar-text mt-1 space-y-1">
                            <li><a href="<?= url_to('subjects.index') ?>" class="<?= url_is('subjects*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">Subjects</a></li>
                            <li><a href="<?= url_to('sections.index') ?>" class="<?= url_is('sections*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary">Sections</a></li>
                        </ul>
                    </details>
                </li>

                <li>
                    <a href="<?= url_to('reports.index') ?>" class="<?= url_is('reports*') ? 'active' : '' ?> hover:bg-primary/10 hover:text-primary transition-colors flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="sidebar-text">Reports</span>
                    </a>
                </li>
            </ul>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-base-200">
                <a href="<?= url_to('logout') ?>" class="btn btn-ghost btn-block justify-start text-error gap-3 hover:bg-error hover:text-error-content transition-colors overflow-hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="sidebar-text">Logout</span>
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
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebarMenu');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        let isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

        function syncSidebar() {
            if (isCollapsed) {
                sidebar.classList.replace('w-72', 'w-20');
                sidebarTexts.forEach(el => el.classList.add('hidden'));
                // Prevent details from opening manually and close if open
                sidebar.querySelectorAll('details').forEach(det => {
                    det.dataset.wasOpen = det.open;
                    det.open = false;
                    det.style.pointerEvents = 'none';
                    // Center the icons
                    const summary = det.querySelector('summary');
                    if(summary) summary.classList.add('justify-center');
                });
                sidebar.querySelectorAll('ul > li > a').forEach(a => {
                    a.classList.add('justify-center');
                });
            } else {
                sidebar.classList.replace('w-20', 'w-72');
                setTimeout(() => {
                    sidebarTexts.forEach(el => el.classList.remove('hidden'));
                }, 100);
                sidebar.querySelectorAll('details').forEach(det => {
                    det.style.pointerEvents = 'auto';
                    if(det.dataset.wasOpen === 'true') {
                        det.open = true;
                    }
                    const summary = det.querySelector('summary');
                    if(summary) summary.classList.remove('justify-center');
                });
                sidebar.querySelectorAll('ul > li > a').forEach(a => {
                    a.classList.remove('justify-center');
                });
            }
        }

        syncSidebar();

        toggleBtn.addEventListener('click', () => {
            isCollapsed = !isCollapsed;
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            syncSidebar();
        });
    });

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
