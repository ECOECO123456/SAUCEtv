<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SAUCEtv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#ff8c32',
                        brandDark: '#e07320',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-neutral-950 text-white font-sans flex items-center justify-center min-h-screen px-4">

    <div class="max-w-md w-full bg-neutral-900 border border-neutral-800 p-8 rounded-3xl shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="text-4xl font-black text-brand tracking-wider">SAUCE<span class="text-white">tv</span></a>
            <p class="text-gray-400 text-xs">Sign in to rate, comment, and save your watchlist.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 text-xs p-3 rounded-xl text-center">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 text-xs p-3 rounded-xl text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1">Username</label>
                <input type="text" name="username" required placeholder="Enter username" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1">Password</label>
                <input type="password" name="password" required placeholder="Enter password" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <button type="submit" class="w-full bg-brand hover:bg-brandDark text-neutral-950 font-bold py-3 rounded-xl transition text-sm">
                Sign In
            </button>
        </form>

        <div class="text-center text-xs text-gray-500 space-y-2">
            <p>Don't have an account? <a href="{{ route('register') }}" class="text-brand font-bold hover:underline">Register here</a></p>
            <p><a href="{{ route('guest.login') }}" class="text-gray-400 hover:underline">&larr; Continue as Guest (Watch Only)</a></p>
        </div>
    </div>

</body>
</html>