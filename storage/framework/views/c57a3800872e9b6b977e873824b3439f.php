<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Request History - SAUCEtv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { brand: '#ff8c32', brandDark: '#e07320' } }
            }
        }
    </script>
</head>
<body class="bg-neutral-950 text-white font-sans min-h-screen p-8">

    <div class="max-w-4xl mx-auto">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8 pb-4 border-b border-neutral-800">
            <h1 class="text-2xl font-black text-brand flex items-center gap-2">
                📁 My Request History
            </h1>
            <a href="<?php echo e(route('home')); ?>" class="text-xs font-bold bg-neutral-800 hover:bg-neutral-700 text-white px-4 py-2 rounded-full border border-neutral-700 transition">
                ← Back to Home
            </a>
        </div>

        <?php
            $allRequests = session()->get('user_movie_requests', []);
            $currentUser = session('user')['username'] ?? '';
            $myRequests = array_filter($allRequests, function($r) use ($currentUser) {
                return isset($r['requested_by']) && $r['requested_by'] === $currentUser;
            });
        ?>

        <!-- TABLE -->
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden shadow-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-neutral-800/60 text-[10px] font-black uppercase text-gray-400 tracking-wider border-b border-neutral-800">
                        <th class="p-4">Title</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Requested Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800 text-xs">
                    <?php $__empty_1 = true; $__currentLoopData = $myRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-neutral-800/40 transition">
                            <td class="p-4 font-bold text-white"><?php echo e($req['title']); ?></td>
                            <td class="p-4 uppercase text-brand font-bold text-[10px]"><?php echo e($req['type']); ?></td>
                            <td class="p-4 text-gray-400 font-mono"><?php echo e($req['created_at'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="p-8 text-center text-gray-500 font-semibold">
                                Wala ka pang naisusumiteng movie request.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\movie-website\resources\views/user_requests_history.blade.php ENDPATH**/ ?>