<?php

function callAPI($url) {
    $response = @file_get_contents($url);
    if ($response === false) {
        return null;
    }

    return json_decode($response, true);
}

// CALL APIs
$kompa = callAPI("http://localhost:8000/api/attendance.php");
$korem = callAPI("http://localhost:8001/api/attendance.php");

$allData = [];

// KOMPA DATA
if (isset($kompa["data"]) && is_array($kompa["data"])) {
    foreach ($kompa["data"] as $row) {
        $allData[] = [
            "source"           => "kompa",
            "id_day"           => $row["id_day"],
            "record_timestamp" => $row["record_timestamp"],
            "visitor_name"     => $row["visitor_name"] ?? "*Unknown Kompa Visitor*",
            "name_type"        => $row["name_type"] ?? "N/A",
            "seat_name"        => $row["seat_name"],
            "isDone"           => $row["isDone"]
        ];
    }
}

// KOREM DATA
if (isset($korem["data"]) && is_array($korem["data"])) {
    foreach ($korem["data"] as $row) {
        $allData[] = [
            "source"           => "korem",
            "id_day"           => $row["id_day"],   
            "record_timestamp" => $row["record_timestamp"],
            "visitor_name"     => $row["visitor_name"] ?? "*Unknown Korem Visitor*",
            "name_type"        => $row["name_type"] ?? "N/A",
            "seat_name"        => $row["seat_name"],
            "isDone"           => $row["isDone"]
        ];
    }
}

// Get unique types for filter
$types = array_unique(array_column($allData, 'name_type'));
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Attendance Report - GKI Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
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
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        tbody tr.hidden {
            display: none;
        }
        /* Calendar Styles */
        .calendar-popup {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            z-index: 100;
        }
        .calendar-popup.active {
            display: block;
        }
        .calendar-day:hover:not(.today):not(.selected) {
            background-color: rgba(249, 115, 22, 0.1);
        }
        .calendar-day.today {
            background-color: rgba(249, 115, 22, 0.2);
            color: #ea580c;
            font-weight: 700;
        }
        .calendar-day.selected {
            background-color: #f97316;
            color: white;
        }
        .calendar-day.other-month {
            color: #9ca3af;
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
        <!-- Search Bar (integrated with table filter) -->
        <div class="flex-1 max-w-lg hidden md:block">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                </div>
                <input id="searchInput" class="block w-full pl-10 pr-3 py-2.5 border-none rounded-full bg-gray-100 dark:bg-black/20 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Search by name, seat, date..." type="text"/>
            </div>
        </div>
        <!-- Right Actions -->
        <div class="flex items-center gap-3">
            <!-- Calendar Button with Popup -->
            <div class="relative">
                <button id="calendarBtn" class="flex items-center justify-center size-10 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 transition-colors">
                    <span class="material-symbols-outlined">calendar_month</span>
                </button>
                <!-- Calendar Popup -->
                <div id="calendarPopup" class="calendar-popup w-100 bg-surface-light dark:bg-surface-dark rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-4">
                    <!-- Calendar Header -->
                    <div class="flex items-center justify-between mb-4">
                        <button id="prevMonth" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">chevron_left</span>
                        </button>
                        <div class="flex items-center gap-2">
                            <select id="calendarMonth" class="bg-transparent text-gray-900 dark:text-white font-bold text-sm cursor-pointer">
                                <option value="0">January</option>
                                <option value="1">February</option>
                                <option value="2">March</option>
                                <option value="3">April</option>
                                <option value="4">May</option>
                                <option value="5">June</option>
                                <option value="6">July</option>
                                <option value="7">August</option>
                                <option value="8">September</option>
                                <option value="9">October</option>
                                <option value="10">November</option>
                                <option value="11">December</option>
                            </select>
                            <select id="calendarYear" class="bg-transparent text-gray-900 dark:text-white font-bold text-sm cursor-pointer focus:outline-none">
                            </select>
                        </div>
                        <button id="nextMonth" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">chevron_right</span>
                        </button>
                    </div>
                    <!-- Day Labels -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center text-xs font-semibold text-gray-400 py-1">Su</div>
                        <div class="text-center text-xs font-semibold text-gray-400 py-1">Mo</div>
                        <div class="text-center text-xs font-semibold text-gray-400 py-1">Tu</div>
                        <div class="text-center text-xs font-semibold text-gray-400 py-1">We</div>
                        <div class="text-center text-xs font-semibold text-gray-400 py-1">Th</div>
                        <div class="text-center text-xs font-semibold text-gray-400 py-1">Fr</div>
                        <div class="text-center text-xs font-semibold text-gray-400 py-1">Sa</div>
                    </div>
                    <!-- Calendar Days Grid -->
                    <div id="calendarDays" class="grid grid-cols-7 gap-1">
                    </div>
                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button id="todayBtn" class="text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                            Today
                        </button>
                        <button id="clearDateBtn" class="text-sm font-semibold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                            Clear Filter
                        </button>
                    </div>
                </div>
            </div>
            <button class="flex items-center justify-center size-10 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 transition-colors relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 size-2 bg-primary rounded-full border-2 border-surface-light dark:border-surface-dark"></span>
            </button>
            <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>
            <button class="flex items-center gap-2 pl-1 pr-1 sm:pr-4 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                <div class="size-9 rounded-full bg-gray-200 overflow-hidden border-2 border-white dark:border-gray-700 shadow-sm">
                    <img alt="Profile" class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBwNiyFjTQcTwxIXaAJu6I1r2VDhIUTkkwej5pfyUSow1ImEmI-f8vACNq70J23_kUuzTJMIAc7YdndcehOarqtR19OKtTPbnI8nIofPa5iArfgWxdTeEKgzuQsj07OlU_5LOjkUskpDNAzThVNFucmKSg0QR6HuG7mqGQX55_xuq-9tm2eI2Or0eEb3YMcRrJF2h7xtCf5ZdW1S6YQ4RYKyXqL6IDjBhX3V4gzCOeqY-8EqiIp7oEtL-WKJtDSGULkOxVjozu4Hb9k"/>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 hidden sm:block">Admin</span>
                <span class="material-symbols-outlined text-gray-400 text-lg hidden sm:block">expand_more</span>
            </button>
        </div>
    </div>
</header>

<!-- Main Layout -->
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Back Button & Page Heading -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="index.php" class="flex items-center justify-center size-12 rounded-full bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary transition-all shadow-sm hover:shadow-md">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">Attendance Report</h2>
                <p class="text-gray-500 dark:text-gray-400 text-base md:text-lg">Combined attendance data from all sources</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Showing <span id="visibleCount" class="font-bold text-primary"><?php echo count($allData); ?></span> of <?php echo count($allData); ?> records
            </span>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-4 md:p-6">
        <div class="flex flex-wrap items-end gap-4">
            
            <!-- Source Filter -->
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Source</label>
                <select id="filterSource" class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all cursor-pointer">
                    <option value="">All Sources</option>
                    <option value="kompa">KOMPA</option>
                    <option value="korem">KOREM</option>
                </select>
            </div>
            
            <!-- Type Filter -->
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Type</label>
                <select id="filterType" class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all cursor-pointer">
                    <option value="">All Types</option>
                    <?php foreach ($types as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Status Filter -->
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status</label>
                <select id="filterStatus" class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="done">Done</option>
                    <option value="pending">Not Yet</option>
                </select>
            </div>

            <!-- Search Input -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-lg">search</span>
                    </div>
                    <input id="searchInputFilter" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent transition-all" placeholder="Search by name, seat..." type="text"/>
                </div>
            </div>
            
            <!-- Search Button -->
            <button onclick="applyFilters()" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold shadow-md hover:bg-primary/90 hover:shadow-lg transition-all">
                <span class="material-symbols-outlined text-lg">search</span>
                Search
            </button>
            
            <!-- Clear Button -->
            <button onclick="clearFilters()" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-bold hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                <span class="material-symbols-outlined text-lg">refresh</span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-primary/10 to-orange-50 dark:from-primary/20 dark:to-orange-900/10 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Visitor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Seat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php foreach ($allData as $row): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                        data-source="<?php echo $row['source']; ?>" 
                        data-type="<?php echo htmlspecialchars($row['name_type']); ?>" 
                        data-status="<?php echo $row['isDone'] ? 'done' : 'pending'; ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($row['source'] === 'kompa'): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                <span class="size-1.5 rounded-full bg-blue-500"></span>
                                KOMPA
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300">
                                <span class="size-1.5 rounded-full bg-orange-500"></span>
                                KOREM
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400 text-lg">calendar_today</span>
                                <?php echo $row['record_timestamp']; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-full bg-gradient-to-br from-primary/20 to-orange-200 dark:from-primary/30 dark:to-orange-800 flex items-center justify-center text-primary font-bold text-sm">
                                    <?php echo strtoupper(substr($row['visitor_name'], 0, 1)); ?>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($row['visitor_name']); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                <?php echo htmlspecialchars($row['name_type']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400 text-lg">event_seat</span>
                                <?php echo htmlspecialchars($row['seat_name']); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($row['isDone']): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Done
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                <span class="material-symbols-outlined text-sm">pending</span>
                                Not Yet
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <?php if (empty($allData)): ?>
        <div class="flex flex-col items-center justify-center py-16 px-4">
            <div class="size-20 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-4xl text-gray-400">inbox</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No records found</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">No attendance data available from the APIs.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- Floating Export Button -->
<div class="fixed bottom-8 right-8 z-50">
    <button class="flex items-center justify-center size-14 rounded-full bg-primary text-white shadow-xl shadow-primary/40 hover:scale-110 hover:shadow-2xl hover:shadow-primary/50 transition-all duration-300" title="Export Data">
        <span class="material-symbols-outlined text-2xl">download</span>
    </button>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const searchInputFilter = document.getElementById('searchInputFilter');
    const filterSource = document.getElementById('filterSource');
    const filterType = document.getElementById('filterType');
    const filterStatus = document.getElementById('filterStatus');
    const tableBody = document.getElementById('tableBody');
    const visibleCount = document.getElementById('visibleCount');

    // Calendar elements
    const calendarBtn = document.getElementById('calendarBtn');
    const calendarPopup = document.getElementById('calendarPopup');
    const calendarMonth = document.getElementById('calendarMonth');
    const calendarYear = document.getElementById('calendarYear');
    const calendarDays = document.getElementById('calendarDays');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const todayBtn = document.getElementById('todayBtn');
    const clearDateBtn = document.getElementById('clearDateBtn');

    let currentDate = new Date();
    let selectedDate = null;

    // Initialize year dropdown
    function initYearDropdown() {
        const currentYear = new Date().getFullYear();
        calendarYear.innerHTML = '';
        for (let year = currentYear - 10; year <= currentYear + 5; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            calendarYear.appendChild(option);
        }
        calendarYear.value = currentDate.getFullYear();
    }

    // Render calendar
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        calendarMonth.value = month;
        calendarYear.value = year;
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDay = firstDay.getDay();
        const daysInMonth = lastDay.getDate();
        
        // Previous month days
        const prevMonthLastDay = new Date(year, month, 0).getDate();
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        calendarDays.innerHTML = '';
        
        // Previous month days
        for (let i = startDay - 1; i >= 0; i--) {
            const day = prevMonthLastDay - i;
            const btn = createDayButton(day, 'other-month', new Date(year, month - 1, day));
            calendarDays.appendChild(btn);
        }
        
        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            date.setHours(0, 0, 0, 0);
            
            let classes = '';
            if (date.getTime() === today.getTime()) {
                classes = 'today';
            }
            if (selectedDate && date.getTime() === selectedDate.getTime()) {
                classes = 'selected';
            }
            
            const btn = createDayButton(day, classes, date);
            calendarDays.appendChild(btn);
        }
        
        // Next month days
        const totalCells = startDay + daysInMonth;
        const remainingCells = totalCells > 35 ? 42 - totalCells : 35 - totalCells;
        for (let day = 1; day <= remainingCells; day++) {
            const btn = createDayButton(day, 'other-month', new Date(year, month + 1, day));
            calendarDays.appendChild(btn);
        }
    }

    function createDayButton(day, classes, date) {
        const btn = document.createElement('button');
        btn.className = `calendar-day w-9 h-9 rounded-lg text-sm font-medium transition-colors ${classes}`;
        btn.textContent = day;
        btn.addEventListener('click', () => selectDate(date));
        return btn;
    }

    function selectDate(date) {
        selectedDate = date;
        renderCalendar();
        applyFilters();
        calendarPopup.classList.remove('active');
    }

    function applyFilters() {
        const searchTerm = (searchInputFilter?.value || searchInput?.value || '').toLowerCase();
        const sourceFilter = filterSource.value;
        const typeFilter = filterType.value;
        const statusFilter = filterStatus.value;

        const rows = tableBody.querySelectorAll('tr');
        let count = 0;

        rows.forEach(row => {
            const source = row.getAttribute('data-source');
            const type = row.getAttribute('data-type');
            const status = row.getAttribute('data-status');
            const text = row.textContent.toLowerCase();
            const dateText = row.querySelector('td:nth-child(2)')?.textContent || '';

            const matchesSearch = searchTerm === '' || text.includes(searchTerm);
            const matchesSource = sourceFilter === '' || source === sourceFilter;
            const matchesType = typeFilter === '' || type === typeFilter;
            const matchesStatus = statusFilter === '' || status === statusFilter;
            
            // Date filter - matches any time on the selected date (00:00:00 - 23:59:59)
            let matchesDate = true;
            if (selectedDate) {
                // Format selected date as YYYY-MM-DD
                const year = selectedDate.getFullYear();
                const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
                const day = String(selectedDate.getDate()).padStart(2, '0');
                const formattedDate = `${year}-${month}-${day}`;
                
                // Check if the date text contains the formatted date (handles any time format)
                matchesDate = dateText.includes(formattedDate);
            }

            if (matchesSearch && matchesSource && matchesType && matchesStatus && matchesDate) {
                row.classList.remove('hidden');
                count++;
            } else {
                row.classList.add('hidden');
            }
        });

        visibleCount.textContent = count;
    }

    function clearFilters() {
        if (searchInput) searchInput.value = '';
        if (searchInputFilter) searchInputFilter.value = '';
        filterSource.value = '';
        filterType.value = '';
        filterStatus.value = '';
        selectedDate = null;
        renderCalendar();
        applyFilters();
    }

    // Sync search inputs
    function syncSearch(e) {
        const value = e.target.value;
        if (searchInput && e.target !== searchInput) searchInput.value = value;
        if (searchInputFilter && e.target !== searchInputFilter) searchInputFilter.value = value;
        applyFilters();
    }

    // Calendar event listeners
    calendarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        calendarPopup.classList.toggle('active');
    });

    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    calendarMonth.addEventListener('change', () => {
        currentDate.setMonth(parseInt(calendarMonth.value));
        renderCalendar();
    });

    calendarYear.addEventListener('change', () => {
        currentDate.setFullYear(parseInt(calendarYear.value));
        renderCalendar();
    });

    todayBtn.addEventListener('click', () => {
        currentDate = new Date();
        selectedDate = new Date();
        selectedDate.setHours(0, 0, 0, 0);
        renderCalendar();
        applyFilters();
        calendarPopup.classList.remove('active');
    });

    clearDateBtn.addEventListener('click', () => {
        selectedDate = null;
        renderCalendar();
        applyFilters();
        calendarPopup.classList.remove('active');
    });

    // Close calendar when clicking outside
    document.addEventListener('click', (e) => {
        if (!calendarPopup.contains(e.target) && !calendarBtn.contains(e.target)) {
            calendarPopup.classList.remove('active');
        }
    });

    // Event listeners
    if (searchInput) searchInput.addEventListener('input', syncSearch);
    if (searchInputFilter) searchInputFilter.addEventListener('input', syncSearch);
    filterSource.addEventListener('change', applyFilters);
    filterType.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);

    // Initialize calendar
    initYearDropdown();
    renderCalendar();
</script>
</body>
</html>