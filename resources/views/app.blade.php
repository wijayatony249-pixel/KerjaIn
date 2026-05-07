<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet" />
    {{-- @routes --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="bg-[#0a0a0a] text-white" style="font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">
    <!-- Tombol Keluar Darurat -->
    <div style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" style="background: rgba(255, 51, 102, 0.1); color: #FF3366; border: 1px solid rgba(255, 51, 102, 0.2); padding: 8px 16px; font-size: 10px; font-weight: 900; cursor: pointer; border-radius: 8px; backdrop-filter: blur(10px); text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.3s ease;">
                KELUAR PAKSA (DEBUG)
            </button>
        </form>
    </div>
    @inertia
</body>
</html>
