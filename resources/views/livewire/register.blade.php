<div class="fixed inset-0 flex items-center justify-center bg-[#071421] overflow-hidden z-[100]">
    <!-- Large Outer Container -->
    <div class="w-full md:w-[90%] max-w-[1700px] h-[95vh] bg-[#0a1628] rounded-[24px] p-6 md:p-[40px] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.8)] flex items-center justify-start border border-[#16273f] overflow-hidden">
        
        <!-- Register Card -->
        <div class="w-full max-w-[450px] sm:w-[450px] bg-[#0d1d33] rounded-[16px] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.7)] border border-[#1d2f4a] md:ml-[4%] flex flex-col overflow-hidden">
            
            {{-- Fixed Top Tabs --}}
            <div class="flex border-b border-[#1d2f4a] bg-[#0b1a2e]">
                <a href="{{ route('login') }}" class="flex-1 py-4 text-center text-xs font-bold transition-all duration-300 text-slate-500 bg-[#0b1a2e] hover:bg-[#0d1d33] hover:text-white uppercase tracking-widest relative overflow-hidden">
                    Sign In
                </a>
                <div class="flex-1 py-4 text-center text-xs font-bold text-[#1abc9c] bg-[#0d1d33] uppercase tracking-widest relative overflow-hidden border-l border-[#1d2f4a]">
                    Sign Up
                    <div class="absolute bottom-0 left-0 w-full h-0.5 bg-[#1abc9c]"></div>
                </div>
            </div>

            <div class="p-6 md:p-8">

            <form wire:submit="register" class="space-y-4">
                {{-- Full Name --}}
                <div>
                    <label for="name" class="block text-[11px] font-bold text-[#1abc9c] uppercase tracking-wider mb-2 ms-0.5">Full Name</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-user text-[#a0b3c2]/40 group-focus-within:text-[#1abc9c] transition-colors"></i>
                        </div>
                        <input wire:model="name" type="text" id="name"
                               class="block w-full pl-12 pr-4 py-2.5 bg-[#0b1a2e] border border-[#2a4a60] rounded-xl text-white placeholder-[#a0b3c2]/20 focus:outline-none focus:border-[#1abc9c] focus:ring-1 focus:ring-[#1abc9c]/30 transition-all sm:text-sm shadow-inner"
                               placeholder="John Doe">
                    </div>
                    @error('name') <span class="text-red-400 text-xs mt-2 block ps-1 font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-[11px] font-bold text-[#1abc9c] uppercase tracking-wider mb-2 ms-0.5">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-envelope text-[#a0b3c2]/40 group-focus-within:text-[#1abc9c] transition-colors"></i>
                        </div>
                        <input wire:model="email" type="email" id="email"
                               class="block w-full pl-12 pr-4 py-2.5 bg-[#0b1a2e] border border-[#2a4a60] rounded-xl text-white placeholder-[#a0b3c2]/20 focus:outline-none focus:border-[#1abc9c] focus:ring-1 focus:ring-[#1abc9c]/30 transition-all sm:text-sm shadow-inner"
                               placeholder="john@example.com">
                    </div>
                    @error('email') <span class="text-red-400 text-xs mt-2 block ps-1 font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-[11px] font-bold text-[#1abc9c] uppercase tracking-wider mb-2 ms-0.5">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-lock text-[#a0b3c2]/40 group-focus-within:text-[#1abc9c] transition-colors"></i>
                        </div>
                        <input wire:model="password" type="password" id="password"
                               class="block w-full pl-12 pr-4 py-2.5 bg-[#0b1a2e] border border-[#2a4a60] rounded-xl text-white placeholder-[#a0b3c2]/20 focus:outline-none focus:border-[#1abc9c] focus:ring-1 focus:ring-[#1abc9c]/30 transition-all sm:text-sm shadow-inner"
                               placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-red-400 text-xs mt-2 block ps-1 font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-[11px] font-bold text-[#1abc9c] uppercase tracking-wider mb-2 ms-0.5">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-lock-key text-[#a0b3c2]/40 group-focus-within:text-[#1abc9c] transition-colors"></i>
                        </div>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation"
                               class="block w-full pl-12 pr-4 py-2.5 bg-[#0b1a2e] border border-[#2a4a60] rounded-xl text-white placeholder-[#a0b3c2]/20 focus:outline-none focus:border-[#1abc9c] focus:ring-1 focus:ring-[#1abc9c]/30 transition-all sm:text-sm shadow-inner"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit"
                            class="w-full flex justify-center items-center py-3 px-4 rounded-xl text-sm font-bold text-white bg-[#1abc9c] hover:bg-[#16a085] transition-all shadow-[0_0_20px_-5px_rgba(26,188,156,0.3)] hover:shadow-[0_0_25px_-5px_rgba(26,188,156,0.5)] active:scale-[0.98] uppercase tracking-widest">
                        <span wire:loading.remove>Create Account</span>
                        <span wire:loading class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Processing...
                        </span>
                    </button>
                </div>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center px-2">
                        <div class="w-full border-t border-[#2a4a60]/50"></div>
                    </div>
                    <div class="relative flex justify-center text-[10px] uppercase font-bold tracking-tighter">
                        <span class="bg-[#0d1d33] px-3 text-[#a0b3c2]/30">Secured Enrollment</span>
                    </div>
                </div>

                <div>
                    <a href="{{ route('auth.google') }}"
                       class="w-full flex justify-center items-center gap-3 py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-transparent hover:bg-[#1c3448] border border-[#2a4a60] transition-all hover:border-[#1abc9c]/50 group">
                        <i class="ph-bold ph-google-logo text-lg text-red-500 group-hover:scale-110 transition-transform"></i>
                        <span class="uppercase tracking-widest text-slate-300">Register with Google</span>
                    </a>
                </div>
            </form>

            <div class="mt-4 text-center border-t border-[#1d2f4a] pt-3">
                <p class="text-[11px] text-slate-400">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-[#1abc9c] font-medium hover:underline ml-1">Sign In</a>
                </p>
            </div>
        </div>
        
    </div>
</div>
