<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto pb-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Sidebar / Summary Card -->
        <div class="md:col-span-1">
            <div class="card bg-base-100 shadow-xl rounded-3xl border border-base-200 overflow-hidden">
                <div class="card-body items-center text-center p-8 bg-gradient-to-br from-primary/10 to-transparent">
                    <div class="avatar placeholder mb-4">
                        <div class="bg-primary text-primary-content rounded-full w-24 shadow-2xl">
                            <span class="text-4xl font-black"><?= substr($user['name'], 0, 1) ?></span>
                        </div>
                    </div>
                    <h2 class="text-xl font-black leading-tight"><?= $user['name'] ?></h2>
                    <p class="text-xs font-bold opacity-40 uppercase tracking-widest mt-1">Administrator</p>
                    <div class="divider"></div>
                    <div class="w-full text-left space-y-4">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-40" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
                            <span class="text-sm truncate"><?= $user['email'] ?></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-40" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg>
                            <span class="text-sm">Joined <?= date('M Y', strtotime($user['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms Area -->
        <div class="md:col-span-2 space-y-8">
            
            <!-- Personal Info Card -->
            <div class="card bg-base-100 shadow-xl rounded-3xl border border-base-200">
                <div class="card-body p-8">
                    <h3 class="card-title text-2xl font-black mb-6">Personal Information</h3>
                    
                    <form action="<?= url_to('profile.update') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-bold">Full Name</span></label>
                                <input type="text" name="name" value="<?= old('name', $user['name']) ?>" class="input input-bordered focus:input-primary" required />
                            </div>
                            
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-bold">Email Address</span></label>
                                <input type="email" name="email" value="<?= old('email', $user['email']) ?>" class="input input-bordered focus:input-primary" required />
                            </div>

                            <div class="card-actions justify-end mt-4">
                                <button type="submit" class="btn btn-primary px-8 shadow-lg">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Security Card -->
            <div class="card bg-base-100 shadow-xl rounded-3xl border border-base-200">
                <div class="card-body p-8">
                    <h3 class="card-title text-2xl font-black mb-6">Security & Password</h3>
                    
                    <form action="<?= url_to('profile.password') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-bold">Current Password</span></label>
                                <input type="password" name="current_password" class="input input-bordered focus:input-primary" required />
                                <label class="label"><span class="label-text-alt text-base-content/40 italic">Required to verify changes</span></label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="label"><span class="label-text font-bold">New Password</span></label>
                                    <input type="password" name="new_password" class="input input-bordered focus:input-primary" required />
                                </div>
                                <div class="form-control w-full">
                                    <label class="label"><span class="label-text font-bold">Confirm New Password</span></label>
                                    <input type="password" name="confirm_password" class="input input-bordered focus:input-primary" required />
                                </div>
                            </div>

                            <div class="card-actions justify-end mt-4">
                                <button type="submit" class="btn btn-neutral px-8">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
