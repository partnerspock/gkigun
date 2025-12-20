<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Now Showing - Advent GKI</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "orange",
                        "background-light": "#f8f6f6",
                        "background-dark": "#211111",
                        "surface-light": "#ffffff",
                        "surface-dark": "#2a1e1e",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        /* Blinking animation for current event */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(249, 115, 22, 0.3); }
            50% { box-shadow: 0 0 40px rgba(249, 115, 22, 0.6); }
        }
        .now-playing {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        /* Gradient border effect */
        .gradient-border {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 50%, #fdba74 100%);
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#1b0e0e] dark:text-[#f3e7e8] min-h-screen">

<!-- Header -->
<header class="sticky top-0 z-50 w-full bg-surface-light/80 dark:bg-surface-dark/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
    <!--<div class="max-w-6xl mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="text-sm font-medium hidden sm:inline">Back to Seats</span>
            </a>
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center size-10 rounded-xl bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-2xl">movie</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">GKI Advent</h1>
            </div>
            <div class="w-24"></div>
        </div>
    </div>-->
</header>

<!-- Main Content -->
<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-8">
    
    <!-- Page Title -->
   <!-- <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 rounded-full text-primary text-sm font-semibold">
            <span class="material-symbols-outlined text-lg">theaters</span>
            Advent Series 2025
        </div>
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight">
            Now <span class="text-primary">Showing</span>
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-lg max-w-xl mx-auto">
            Join us for this year's Advent journey. Select an event to book your seat.
        </p>
    </div>-->

    <!-- Logos -->
    <div class="flex items-center justify-center gap-8 mb-8">
        <img src="img/gunsa.png" alt="Gunsa Logo" class="h-20 w-auto object-contain opacity-80 hover:opacity-100 transition-opacity">
        <div class="h-16 w-px bg-gray-200 dark:bg-gray-700"></div>
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight">
            Now <span class="text-primary">Showing</span>
        </h2>
        <div class="h-16 w-px bg-gray-200 dark:bg-gray-700"></div>
        <img src="img/kompa.jpg" alt="Kompa Logo" class="h-20 w-20 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg">
    </div>

    <!-- Event Cards -->
    <div class="space-y-6">
        
        <!-- Event 1 - Past -->
        <div class="group relative bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden opacity-60 hover:opacity-80 transition-opacity">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gray-300 dark:bg-gray-600"></div>
            <div class="flex flex-col md:flex-row items-center gap-6 p-6 pl-8">
                <!-- Poster -->
                <div class="shrink-0">
                    <img src="img/ad1.png" alt="Event Poster" class="w-32 h-32 md:w-40 md:h-40 rounded-2xl object-cover shadow-md">
                </div>
                <!-- Info -->
                <div class="flex-1 text-center md:text-left space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-semibold text-gray-500">
                        <span class="material-symbols-outlined text-sm">history</span>
                        Completed
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">00.00.00 - Stop In Silence</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base">
                        <span class="font-semibold">30 November 2025</span> • Pdt. Ima F. Simamora - GKI Melur
                    </p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">location_on</span>
                        GSP 3 Lt. 4 • 10.30 WIB
                    </p>
                </div>
                <!-- Badge -->
                <div class="shrink-0">
                    <img src="img/21.png" alt="XXI" class="w-16 h-16 object-contain opacity-50">
                </div>
            </div>
        </div>

        <!-- Event 2 - Past -->
        <div class="group relative bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden opacity-60 hover:opacity-80 transition-opacity">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gray-300 dark:bg-gray-600"></div>
            <div class="flex flex-col md:flex-row items-center gap-6 p-6 pl-8">
                <div class="shrink-0">
                    <img src="img/ad2.png" alt="Event Poster" class="w-32 h-32 md:w-40 md:h-40 rounded-2xl object-cover shadow-md">
                </div>
                <div class="flex-1 text-center md:text-left space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-semibold text-gray-500">
                        <span class="material-symbols-outlined text-sm">history</span>
                        Completed
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">06.00.00 - Watch the Promise Unfold</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base">
                        <span class="font-semibold">07 December 2025</span> • Pnt. Gilbert C. Kristamulyana
                    </p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">location_on</span>
                        GSP 3 Lt. 4 • 10.30 WIB
                    </p>
                </div>
                <div class="shrink-0">
                    <img src="img/21.png" alt="XXI" class="w-16 h-16 object-contain opacity-50">
                </div>
            </div>
        </div>

        <!-- Event 3 - Past -->
        <div class="group relative bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden opacity-60 hover:opacity-80 transition-opacity">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gray-300 dark:bg-gray-600"></div>
            <div class="flex flex-col md:flex-row items-center gap-6 p-6 pl-8">
                <div class="shrink-0">
                    <img src="img/ad3.png" alt="Event Poster" class="w-32 h-32 md:w-40 md:h-40 rounded-2xl object-cover shadow-md">
                </div>
                <div class="flex-1 text-center md:text-left space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-semibold text-gray-500">
                        <span class="material-symbols-outlined text-sm">history</span>
                        Completed
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">23.59.59 - Watch & Hold Your Breath</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base">
                        <span class="font-semibold">14 December 2025</span> • Pdt. Febe O. Hermanto
                    </p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">location_on</span>
                        GSP 3 Lt. 4 • 10.30 WIB
                    </p>
                </div>
                <div class="shrink-0">
                    <img src="img/21.png" alt="XXI" class="w-16 h-16 object-contain opacity-50">
                </div>
            </div>
        </div>

        <!-- Event 4 - NOW SHOWING (Current) -->
        <a href="index.php" class="block group">
            <div class="now-playing relative bg-gradient-to-r from-primary/5 to-orange-50 dark:from-primary/10 dark:to-orange-900/10 rounded-2xl border-2 border-primary overflow-hidden hover:scale-[1.02] transition-transform">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 gradient-border"></div>
                <div class="flex flex-col md:flex-row items-center gap-6 p-6 pl-8">
                    <!-- Poster with glow -->
                    <div class="shrink-0 relative">
                        <div class="absolute inset-0 bg-primary/20 rounded-2xl blur-xl"></div>
                        <img src="img/ad4.png" alt="Event Poster" class="relative w-32 h-32 md:w-40 md:h-40 rounded-2xl object-cover shadow-xl border-2 border-primary/30">
                    </div>
                    <!-- Info -->
                    <div class="flex-1 text-center md:text-left space-y-3">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary text-white rounded-full text-xs font-bold animate-pulse">
                            <span class="material-symbols-outlined text-sm">play_circle</span>
                            NOW SHOWING
                        </div>
                        <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                            00.00.00 - See the Light
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm md:text-base font-medium">
                            <span class="font-bold text-primary">21 December 2025</span> • Pdt. Andi Christianto - GKI Cawang
                        </p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm flex items-center justify-center md:justify-start gap-2">
                            <span class="material-symbols-outlined text-lg text-primary">location_on</span>
                            GSP 3 Lt. 4 • 10.30 WIB
                        </p>
                        <!-- CTA -->
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-full shadow-lg shadow-primary/30 group-hover:shadow-xl group-hover:shadow-primary/40 transition-all">
                                <span class="material-symbols-outlined">event_seat</span>
                                Book Your Seat
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                    <!-- Badge -->
                    <div class="shrink-0 hidden md:block">
                        <img src="img/21.png" alt="XXI" class="w-20 h-20 object-contain">
                    </div>
                </div>
            </div>
        </a>

    </div>

    <!-- Footer Info -->
    <div class="text-center pt-8 border-t border-gray-200 dark:border-gray-800">
        <p class="text-gray-400 dark:text-gray-500 text-sm">
            <span class="material-symbols-outlined text-lg align-middle mr-1">info</span>
            Tap on "Now Showing" event to book your seat
        </p>
    </div>

</main>

<!-- Floating Home Button -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="index.php" class="flex items-center justify-center size-14 rounded-full bg-primary text-white shadow-xl shadow-primary/30 hover:scale-110 hover:shadow-2xl transition-all">
        <span class="material-symbols-outlined text-2xl">event_seat</span>
    </a>
</div>

</body>
</html>
