<!-- ===== LOGIN MODAL ===== -->
<div id="loginModal" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-sm mx-auto animate-slide-in">
        
        <!-- Close Button -->
        <button id="closeLoginModal"
            class="absolute -top-3 -right-3 z-10 w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center text-gray-500 hover:text-red-500 transition duration-300 hover:scale-110">
            <i class="fas fa-times text-sm"></i>
        </button>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-white">Masuk ke Akun</h2>
                        <p class="text-primary-100 mt-1 text-xs">Masukkan kredensial Anda</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-lock text-white"></i>
                    </div>
                </div>
            </div>

            @if ($errors->has('username'))
                <div class="mx-5 mt-4 p-3 rounded-lg bg-red-100 text-red-700 text-xs">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $errors->first('username') }}
                </div>
            @endif

            <!-- Form -->
            <form id="loginForm" method="POST" action="{{ route('auth.login') }}" class="p-5">
                @csrf
                
                <!-- Username -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-800 mb-2 text-xs">
                        Username
                    </label>
                    <input type="text" name="username" required
                        class="w-full p-2.5 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-sm">
                </div>
                
                <!-- Password -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-800 mb-2 text-xs">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="loginPassword" required
                            class="w-full p-2.5 pr-10 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-sm">
                        
                        <!-- Tombol Mata -->
                        <button type="button" id="toggleLoginPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit -->
                <button type="submit"
                    class="w-full py-2.5 rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium text-sm">
                    <i class="fas fa-sign-in-alt mr-2 text-xs"></i>
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ===== SCRIPT ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const loginModal = document.getElementById('loginModal');
    const openLoginBtn = document.getElementById('openLoginModal');
    const closeLoginBtn = document.getElementById('closeLoginModal');

    // Buka modal
    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', () => {
            loginModal.classList.remove('hidden');
            loginModal.classList.add('flex');
        });
    }

    // Tutup modal
    if (closeLoginBtn) {
        closeLoginBtn.addEventListener('click', () => {
            loginModal.classList.add('hidden');
            loginModal.classList.remove('flex');
        });
    }

    // Toggle lihat password
    const passwordField = document.getElementById('loginPassword');
    const toggleButton  = document.getElementById('toggleLoginPassword');

    if(passwordField && toggleButton){
        toggleButton.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

});
</script>
@if ($errors->has('username'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginModal = document.getElementById('loginModal');
    loginModal.classList.remove('hidden');
    loginModal.classList.add('flex');
});
</script>
@endif
