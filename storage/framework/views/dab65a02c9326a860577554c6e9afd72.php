<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password — Monitoring</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        slate: { 850: '#1e293b', 900: '#0f172a', 950: '#020617' },
                    },
                    keyframes: {
                        fadeUp: { '0%': { opacity: '0', transform: 'translateY(16px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                    },
                    animation: { 'fade-up': 'fadeUp 0.4s ease-out both' }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }

        .bg-auth {
            background-color: #0B1120;
            background-image:
                radial-gradient(ellipse at 50% 0%, rgba(16, 185, 129, 0.12) 0%, transparent 65%),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 10h80v80h-80z' fill='none' stroke='rgba(30,41,59,0.5)' stroke-width='1'/%3E%3Cpath d='M30 30h40v40h-40z' fill='none' stroke='rgba(30,41,59,0.5)' stroke-width='1'/%3E%3Ccircle cx='10' cy='10' r='2' fill='rgba(16,185,129,0.08)'/%3E%3Ccircle cx='90' cy='10' r='2' fill='rgba(16,185,129,0.08)'/%3E%3Ccircle cx='90' cy='90' r='2' fill='rgba(16,185,129,0.08)'/%3E%3Ccircle cx='10' cy='90' r='2' fill='rgba(16,185,129,0.08)'/%3E%3Cpath d='M50 10v20M50 70v20M10 50h20M70 50h20' stroke='rgba(51,65,85,0.3)' stroke-width='1'/%3E%3C/svg%3E");
            background-size: 100% 100%, 120px 120px;
        }
    </style>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-auth text-slate-200 font-sans antialiased min-h-screen flex flex-col items-center justify-center p-4">

    
    

    
    <div class="w-full max-w-md animate-fade-up" style="animation-delay: 0.08s">
        <?php echo e($slot); ?>

    </div>

    
    <div class="mt-6 animate-fade-up" style="animation-delay: 0.16s">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit"
                    class="flex items-center gap-2 text-sm text-slate-500 hover:text-red-400 transition-colors group">
                <i class="ph-bold ph-sign-out group-hover:text-red-400 transition-colors"></i>
                Sign out of <?php echo e(auth()->user()->email ?? 'your account'); ?>

            </button>
        </form>
    </div>

    <p class="mt-4 text-xs text-slate-700 animate-fade-up" style="animation-delay: 0.24s">
        Authorized personnel only — all password changes are logged.
    </p>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/components/layouts/auth.blade.php ENDPATH**/ ?>