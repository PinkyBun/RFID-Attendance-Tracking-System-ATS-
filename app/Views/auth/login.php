<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="card bg-base-100 shadow-2xl">
    <div class="card-body">
        <div class="flex flex-col items-center mb-6">
            <div class="w-20 h-20 bg-primary text-primary-content rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-1.637A10.025 10.025 0 0110 11.235V9a6 6 0 00-6-6H4" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold">RFID Attendance</h1>
            <p class="text-base-content/60">Teacher Portal Login</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('login.post') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-semibold">Email Address</span>
                </label>
                <input type="email" name="email" placeholder="teacher@example.com" class="input input-bordered w-full focus:input-primary" required autofocus />
            </div>
            
            <div class="form-control w-full mt-4">
                <label class="label">
                    <span class="label-text font-semibold">Password</span>
                </label>
                <input type="password" name="password" placeholder="••••••••" class="input input-bordered w-full focus:input-primary" required />
            </div>

            <div class="form-control mt-8">
                <button type="submit" class="btn btn-primary btn-block text-lg">
                    Sign In
                </button>
            </div>
        </form>

        <div class="divider mt-6 text-base-content/40">v1.0.0</div>
    </div>
</div>
<?= $this->endSection() ?>
