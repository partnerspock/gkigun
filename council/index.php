<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Class Manager Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
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
                    borderRadius: {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        /* Custom scrollbar hiding for clean horizontal scroll */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#1b0e0e] dark:text-[#f3e7e8] min-h-screen flex flex-col relative overflow-x-hidden">
<!-- Top Navigation Bar -->
<header class="sticky top-0 z-50 w-full bg-surface-light/80 dark:bg-surface-dark/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 px-6 py-4">
<div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
<!-- Logo Area -->
<div class="flex items-center gap-3">
<div class="flex items-center justify-center size-10 rounded-xl bg-primary/10 text-primary">
<span class="material-symbols-outlined text-3xl">church</span>
</div>
<h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white hidden sm:block">GKI</h1>
</div>
<!-- Search Bar -->
<div class="flex-1 max-w-lg hidden md:block">
<div class="relative group">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors">search</span>
</div>
<input class="block w-full pl-10 pr-3 py-2.5 border-none rounded-full bg-gray-100 dark:bg-black/20 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Search classes, students, or events..." type="text"/>
</div>
</div>
<!-- Right Actions -->
<div class="flex items-center gap-3">
<button class="flex items-center justify-center size-10 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 transition-colors">
<span class="material-symbols-outlined">calendar_month</span>
</button>
<button class="flex items-center justify-center size-10 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 size-2 bg-primary rounded-full border-2 border-surface-light dark:border-surface-dark"></span>
</button>
<div class="h-8 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>
<button class="flex items-center gap-2 pl-1 pr-1 sm:pr-4 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
<div class="size-9 rounded-full bg-gray-200 overflow-hidden border-2 border-white dark:border-gray-700 shadow-sm" data-alt="User profile picture showing a smiling professional">
<img alt="Profile" class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBwNiyFjTQcTwxIXaAJu6I1r2VDhIUTkkwej5pfyUSow1ImEmI-f8vACNq70J23_kUuzTJMIAc7YdndcehOarqtR19OKtTPbnI8nIofPa5iArfgWxdTeEKgzuQsj07OlU_5LOjkUskpDNAzThVNFucmKSg0QR6HuG7mqGQX55_xuq-9tm2eI2Or0eEb3YMcRrJF2h7xtCf5ZdW1S6YQ4RYKyXqL6IDjBhX3V4gzCOeqY-8EqiIp7oEtL-WKJtDSGULkOxVjozu4Hb9k"/>
</div>
<span class="text-sm font-semibold text-gray-700 dark:text-gray-200 hidden sm:block">Prof. Smith</span>
<span class="material-symbols-outlined text-gray-400 text-lg hidden sm:block">expand_more</span>
</button>
</div>
</div>
</header>
<!-- Main Layout -->
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
<!-- Page Heading & Controls -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
<div>
<h2 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2">Dashboard</h2>
<!--<p class="text-gray-500 dark:text-gray-400 text-lg">Overview of your active classes for Fall 2023</p>-->
</div>
<div class="flex gap-3">
<!--<button class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-bold shadow-sm hover:shadow-md transition-all">
<span class="material-symbols-outlined text-[20px]">filter_list</span>
Filter</button>
<button class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-white text-sm font-bold shadow-lg shadow-primary/30 hover:bg-primary/90 hover:shadow-xl transition-all hover:-translate-y-0.5">
<span class="material-symbols-outlined text-[20px]">add</span>
New Class
</button>-->
</div>
</div>

<!-- Class List Container -->
<div class="flex flex-col gap-6">

<!-- Class Card 1: Kompa -->
<article class="group relative flex flex-col bg-surface-light dark:bg-surface-dark rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-all hover:shadow-md">
<!-- Status Bar -->
<div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary/80 group-hover:bg-primary transition-colors"></div>
<div class="p-6 md:p-8 flex flex-col gap-6">
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start gap-4">
<div class="space-y-1">
<div class="flex items-center gap-3">
</div>
<!-- <div class="flex items-center gap-3">
<span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">HIST-204</span>
<span class="flex items-center gap-1 text-gray-500 text-xs font-semibold">
<span class="material-symbols-outlined text-[14px]">calendar_clock</span>
Upcoming
</span>
</div>-->
<h3 class="text-2xl font-bold text-gray-900 dark:text-white">Komisi Pemuda</h3>
<p class="text-gray-500 font-medium flex items-center gap-2">
<span class="material-symbols-outlined text-lg">schedule</span>
30 November 2025 - 21 December 2025, 10.30 AM (4th Floor)              
</p>
</div>
<!-- Quick Stats Mini-Widget -->
<!-- <div class="flex gap-4 sm:border-l border-gray-100 dark:border-gray-700 sm:pl-6">
<div class="text-center">
<p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Students</p>
<p class="text-xl font-bold text-gray-900 dark:text-white">42</p>
</div>
<div class="text-center">
<p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Avg Grade</p>
<p class="text-xl font-bold text-gray-900 dark:text-white">88%</p>
</div>
</div> -->
</div>
<!-- Divider -->
<div class="h-px w-full bg-gray-100 dark:bg-gray-800"></div>
<!-- Horizontal Action Menu -->
<div class="relative">
<!-- Left Fade/Arrow hint (optional for UX, keeping clean for now) -->
<div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2 -mx-2 px-2 snap-x">
<a href="http://localhost:8000/cinema_listings.php">
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">local_activity</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Cinema List</span>
</button></a>
<!-- Action Item: Attendance -->
<a href="http://localhost:8000"><button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">checklist</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Attendance</span>
</button></a>
<!-- Action Item: Events -->
<a href="http://localhost:8000/history.php">
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">book_5</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">History Attendance</span>
</button></a>
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">key_visualizer</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Instagram</span>
</button>
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">key_visualizer</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Other?</span>
</button>
<!-- Action Item: More
<button class="snap-start shrink-0 group/btn flex items-center justify-center size-[56px] rounded-full bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-gray-600 dark:text-gray-300 transition-colors">
<span class="material-symbols-outlined">more_horiz</span>
</button>-->
</div>
</div>
</div>
</article>

<!-- Class Card 2: Korem -->
<article class="group relative flex flex-col bg-surface-light dark:bg-surface-dark rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-all hover:shadow-md">
<!-- Status Bar -->
<div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary/80 group-hover:bg-primary transition-colors"></div>
<div class="p-6 md:p-8 flex flex-col gap-6">
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start gap-4">
<div class="space-y-1">
<div class="flex items-center gap-3">
</div>
<!-- <div class="flex items-center gap-3">
<span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">HIST-204</span>
<span class="flex items-center gap-1 text-gray-500 text-xs font-semibold">
<span class="material-symbols-outlined text-[14px]">calendar_clock</span>
Upcoming
</span>
</div>-->
<h3 class="text-2xl font-bold text-gray-900 dark:text-white">Komisi Other?</h3>
<p class="text-gray-500 font-medium flex items-center gap-2">
<span class="material-symbols-outlined text-lg">schedule</span>
30 November 2025 - 21 December 2025, 10.30 AM (4th Floor)              
</p>
</div>
<!-- Quick Stats Mini-Widget -->
<!-- <div class="flex gap-4 sm:border-l border-gray-100 dark:border-gray-700 sm:pl-6">
<div class="text-center">
<p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Students</p>
<p class="text-xl font-bold text-gray-900 dark:text-white">42</p>
</div>
<div class="text-center">
<p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Avg Grade</p>
<p class="text-xl font-bold text-gray-900 dark:text-white">88%</p>
</div>
</div> -->
</div>
<!-- Divider -->
<div class="h-px w-full bg-gray-100 dark:bg-gray-800"></div>
<!-- Horizontal Action Menu -->
<div class="relative">
<!-- Left Fade/Arrow hint (optional for UX, keeping clean for now) -->
<div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2 -mx-2 px-2 snap-x">
<a href="http://localhost:8001/cinema_listings.php">
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">local_activity</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Cinema List</span>
</button></a>
<!-- Action Item: Attendance -->
<a href="http://localhost:8001"><button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">checklist</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Attendance</span>
</button></a>
<!-- Action Item: Events -->
<a href="http://localhost:8001/history.php">
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">book_5</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">History Attendance</span>
</button></a>
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">key_visualizer</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Instagram</span>
</button>
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">key_visualizer</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Other?</span>
</button>
<!-- Action Item: More
<button class="snap-start shrink-0 group/btn flex items-center justify-center size-[56px] rounded-full bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-gray-600 dark:text-gray-300 transition-colors">
<span class="material-symbols-outlined">more_horiz</span>
</button>-->
</div>
</div>
</div>
</article>

<!-- Class Card 2: Korem -->
<article class="group relative flex flex-col bg-surface-light dark:bg-surface-dark rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-all hover:shadow-md">
<!-- Status Bar -->
<div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary/80 group-hover:bg-primary transition-colors"></div>
<div class="p-6 md:p-8 flex flex-col gap-6">
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start gap-4">
<div class="space-y-1">
<div class="flex items-center gap-3">
</div>
<!-- <div class="flex items-center gap-3">
<span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">HIST-204</span>
<span class="flex items-center gap-1 text-gray-500 text-xs font-semibold">
<span class="material-symbols-outlined text-[14px]">calendar_clock</span>
Upcoming
</span>
</div>-->
<h3 class="text-2xl font-bold text-gray-900 dark:text-white">Board</h3>
<p class="text-gray-500 font-medium flex items-center gap-2">
<span class="material-symbols-outlined text-lg">schedule</span>
Home              
</p>
</div>
<!-- Quick Stats Mini-Widget -->
<!-- <div class="flex gap-4 sm:border-l border-gray-100 dark:border-gray-700 sm:pl-6">
<div class="text-center">
<p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Students</p>
<p class="text-xl font-bold text-gray-900 dark:text-white">42</p>
</div>
<div class="text-center">
<p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Avg Grade</p>
<p class="text-xl font-bold text-gray-900 dark:text-white">88%</p>
</div>
</div> -->
</div>
<!-- Divider -->
<div class="h-px w-full bg-gray-100 dark:bg-gray-800"></div>
<!-- Horizontal Action Menu -->
<div class="relative">
<!-- Left Fade/Arrow hint (optional for UX, keeping clean for now) -->
<div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2 -mx-2 px-2 snap-x">

<!-- Action Item: Events -->
<a href="http://localhost:8002/report.php">
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">book_5</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">History Attendance</span>
</button></a>
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">key_visualizer</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Instagram</span>
</button>
<button class="snap-start shrink-0 group/btn flex items-center gap-3 pl-4 pr-6 py-3 rounded-full bg-gray-50 dark:bg-white/5 border border-transparent hover:border-primary/20 hover:bg-white dark:hover:bg-surface-dark hover:shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
<div class="size-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover/btn:bg-primary group-hover/btn:text-white transition-colors">
<span class="material-symbols-outlined text-lg">key_visualizer</span>
</div>
<span class="font-bold text-gray-700 dark:text-gray-200 group-hover/btn:text-primary transition-colors">Other?</span>
</button>
<!-- Action Item: More
<button class="snap-start shrink-0 group/btn flex items-center justify-center size-[56px] rounded-full bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-gray-600 dark:text-gray-300 transition-colors">
<span class="material-symbols-outlined">more_horiz</span>
</button>-->
</div>
</div>
</div>
</article>

</div>
</main>
<!-- Floating Action Button (Mobile/Tablet Friendly) -->
<div class="fixed bottom-8 right-8 z-50">
<button class="flex items-center justify-center size-14 rounded-full bg-primary text-white shadow-xl shadow-primary/40 hover:scale-110 hover:shadow-2xl hover:shadow-primary/50 transition-all duration-300">
<span class="material-symbols-outlined text-3xl">add</span>
</button>
</div>
</body></html>