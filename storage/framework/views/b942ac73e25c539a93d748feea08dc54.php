<?php $__env->startSection('title', 'Profil Pengguna'); ?>
<?php $__env->startSection('page_title', 'Profil Pengguna'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: User Summary Card -->
    <div class="lg:col-span-1 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex flex-col items-center justify-center text-center">
        <!-- Big Avatar Frame -->
        <div class="relative group">
            <div class="w-32 h-32 rounded-3xl overflow-hidden ring-4 ring-indigo-500/20 shadow-lg relative">
                <img src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff&size=256'); ?>" 
                    alt="Foto Profil" id="profileSummaryAvatar" class="w-full h-full object-cover">
            </div>
            <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
        
        <h3 class="text-lg font-bold heading-font mt-6"><?php echo e(Auth::user()->name); ?></h3>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 font-mono"><?php echo e(Auth::user()->email); ?></p>
        
        <span class="mt-4 px-3 py-1 bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 rounded-lg text-xs font-bold uppercase tracking-wider heading-font">
            <?php echo e(Auth::user()->role ? Auth::user()->role->display_name : 'Staff'); ?>

        </span>
        
        <div class="w-full border-t border-slate-100 dark:border-slate-800/60 my-6"></div>
        
        <div class="w-full text-left space-y-3 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-400">Terdaftar Sejak</span>
                <span class="font-medium"><?php echo e(Auth::user()->created_at->isoFormat('D MMMM Y')); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Terakhir Update</span>
                <span class="font-medium"><?php echo e(Auth::user()->updated_at->diffForHumans()); ?></span>
            </div>
        </div>
    </div>

    <!-- Right Column: Forms Grid -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Form: Edit Profil -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 md:p-8 shadow-sm">
            <h4 class="text-md font-bold heading-font mb-6"><i class="fa-solid fa-user-gear text-indigo-500 mr-2"></i> Perbarui Data Profil</h4>
            
            <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required
                            class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-rose-500 font-semibold"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Alamat Email</label>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email', Auth::user()->email)); ?>" required
                            class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-rose-500 font-semibold"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Avatar Upload with Live Preview -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Foto Profil (Avatar)</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border dark:border-slate-800">
                            <img src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff'); ?>" 
                                alt="Avatar Preview" id="avatarPreview" class="w-full h-full object-cover">
                        </div>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-600 dark:file:bg-indigo-500/20 dark:file:text-indigo-400 hover:file:bg-indigo-500 hover:file:text-white file:cursor-pointer transition-colors duration-200">
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">Format: JPG, PNG, GIF. Maksimal 2MB.</span>
                    <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-xs text-rose-500 font-semibold"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Form: Ganti Password -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 md:p-8 shadow-sm">
            <h4 class="text-md font-bold heading-font mb-6"><i class="fa-solid fa-key text-indigo-500 mr-2"></i> Ubah Password Keamanan</h4>
            
            <form action="<?php echo e(route('profile.password')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Current Password -->
                    <div class="space-y-2">
                        <label for="current_password" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-rose-500 font-semibold"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <!-- New Password -->
                    <div class="space-y-2">
                        <label for="password" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Password Baru</label>
                        <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                            class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-rose-500 font-semibold"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password baru"
                            class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Live Image Preview Logic
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const profileSummaryAvatar = document.getElementById('profileSummaryAvatar');
    
    if(avatarInput) {
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    avatarPreview.setAttribute('src', this.result);
                    profileSummaryAvatar.setAttribute('src', this.result);
                });
                reader.readAsDataURL(file);
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\syarat kp\project kp\resources\views/profile/index.blade.php ENDPATH**/ ?>