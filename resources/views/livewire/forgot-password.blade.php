<div>

    {{-- ── Step indicator ────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-center gap-2 mb-6">
        @foreach([1 => 'Email', 2 => 'Verify OTP', 3 => 'New Password'] as $n => $label)
        <div class="flex items-center gap-2">
            <div class="flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-all
                {{ $step >= $n ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-slate-500' }}">
                @if($step > $n)
                    <i class="ph-bold ph-check text-xs"></i>
                @else
                    {{ $n }}
                @endif
            </div>
            <span class="text-xs font-medium {{ $step >= $n ? 'text-emerald-400' : 'text-slate-600' }} hidden sm:inline">{{ $label }}</span>
        </div>
        @if($n < 3)
        <div class="w-8 h-px {{ $step > $n ? 'bg-emerald-600' : 'bg-slate-700' }} transition-all"></div>
        @endif
        @endforeach
    </div>

    {{-- ── Card ──────────────────────────────────────────────────────────── --}}
    <div class="bg-slate-900/80 backdrop-blur-md border border-slate-700 rounded-2xl shadow-2xl overflow-hidden">

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- STEP 1 — Enter email                                             --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($step === 1)
        <div class="p-6 border-b border-slate-700 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600/20 border border-blue-500/30">
                <i class="ph-fill ph-envelope text-blue-400 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Forgot Password</h2>
                <p class="text-xs text-slate-400">Enter your email to receive a One-Time Password</p>
            </div>
        </div>

        <form wire:submit="sendOtp" class="p-6 space-y-5">
            <div>
                <label for="fp_email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-envelope text-slate-500"></i>
                    </div>
                    <input wire:model="email" type="email" id="fp_email"
                           autocomplete="email"
                           class="block w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                           placeholder="your@email.com">
                </div>
                @error('email')
                    <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                        <i class="ph-bold ph-x-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-500 transition-all">
                <span wire:loading.remove wire:target="sendOtp" class="flex items-center gap-2">
                    <i class="ph-bold ph-paper-plane-right"></i> Send OTP
                </span>
                <span wire:loading wire:target="sendOtp" class="flex items-center gap-2">
                    <i class="ph-bold ph-spinner animate-spin"></i> Sending...
                </span>
            </button>
        </form>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- STEP 2 — Enter OTP                                               --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($step === 2)
        <div class="p-6 border-b border-slate-700 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-600/20 border border-amber-500/30">
                <i class="ph-fill ph-shield-check text-amber-400 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Verify OTP</h2>
                <p class="text-xs text-slate-400">Enter the 6-digit code sent to <span class="text-white font-medium">{{ $maskedEmail }}</span></p>
            </div>
        </div>

        <div class="px-6 pt-5">
            <div class="flex items-center gap-2 bg-emerald-900/20 border border-emerald-500/20 rounded-lg p-3">
                <i class="ph-fill ph-check-circle text-emerald-400 shrink-0"></i>
                <p class="text-xs text-emerald-300">OTP sent! Check your inbox (and spam folder). Valid for <strong>10 minutes</strong>.</p>
            </div>
        </div>

        <form wire:submit="verifyOtp" class="p-6 space-y-5">
            <div>
                <label for="otp_input" class="block text-sm font-medium text-slate-300 mb-1.5">One-Time Password</label>
                <input wire:model="otp"
                       type="text"
                       id="otp_input"
                       inputmode="numeric"
                       maxlength="6"
                       autocomplete="one-time-code"
                       class="block w-full py-3 px-4 bg-slate-800 border border-slate-700 rounded-lg text-white text-center text-2xl font-bold tracking-[1rem] placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                       placeholder="——————">
                @error('otp')
                    <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                        <i class="ph-bold ph-x-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-lg text-sm font-bold text-white bg-amber-600 hover:bg-amber-500 transition-all">
                <span wire:loading.remove wire:target="verifyOtp" class="flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i> Verify OTP
                </span>
                <span wire:loading wire:target="verifyOtp" class="flex items-center gap-2">
                    <i class="ph-bold ph-spinner animate-spin"></i> Verifying...
                </span>
            </button>

            <div class="flex items-center justify-between pt-1">
                <button type="button" wire:click="goBack"
                        class="text-xs text-slate-500 hover:text-slate-300 flex items-center gap-1 transition-colors">
                    <i class="ph-bold ph-arrow-left"></i> Change email
                </button>
                <button type="button" wire:click="resendOtp"
                        class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 transition-colors">
                    <i class="ph-bold ph-arrows-clockwise"></i>
                    <span wire:loading.remove wire:target="resendOtp">Resend OTP</span>
                    <span wire:loading wire:target="resendOtp">Sending...</span>
                </button>
            </div>
        </form>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- STEP 3 — New password                                            --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($step === 3)
        <div class="p-6 border-b border-slate-700 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-emerald-600/20 border border-emerald-500/30">
                <i class="ph-fill ph-key text-emerald-400 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Set New Password</h2>
                <p class="text-xs text-slate-400">OTP verified ✓ — Choose a strong new password</p>
            </div>
        </div>

        <form wire:submit="resetPassword" class="p-6 space-y-5">
            {{-- New Password --}}
            <div>
                <label for="rp_password" class="block text-sm font-medium text-slate-300 mb-1.5">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-lock-key text-slate-500"></i>
                    </div>
                    <input wire:model="password" type="password" id="rp_password"
                           autocomplete="new-password"
                           class="block w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm"
                           placeholder="Minimum 8 characters">
                </div>
                @error('password')
                    <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                        <i class="ph-bold ph-x-circle"></i> {{ $message }}
                    </p>
                @enderror
                <p class="text-slate-500 text-xs mt-1.5">Must include uppercase, lowercase &amp; numbers.</p>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="rp_confirm" class="block text-sm font-medium text-slate-300 mb-1.5">Confirm New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-lock-key text-slate-500"></i>
                    </div>
                    <input wire:model="password_confirmation" type="password" id="rp_confirm"
                           autocomplete="new-password"
                           class="block w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm"
                           placeholder="Re-enter new password">
                </div>
                @error('password_confirmation')
                    <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                        <i class="ph-bold ph-x-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition-all uppercase tracking-wide">
                <span wire:loading.remove wire:target="resetPassword" class="flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i> Reset Password
                </span>
                <span wire:loading wire:target="resetPassword" class="flex items-center gap-2">
                    <i class="ph-bold ph-spinner animate-spin"></i> Saving...
                </span>
            </button>
        </form>
        @endif

    </div>{{-- /card --}}
</div>
