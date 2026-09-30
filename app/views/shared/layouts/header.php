<?php
// views/layouts/header.php

$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Center</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/logo_ktn.webp">
    <link rel="stylesheet" href="/dist/flowbite.min.css">
    <link rel="stylesheet" href="/dist/output.css">

</head>

<body class="font-sans bg-gray-50 min-h-screen flex flex-col">

    <!-- top nav -->

    <nav id="topbar" class="h-16 bg-white border-b border-gray-200 px-4 flex items-center justify-between sticky top-0 z-20 md:ml-64 transition-all duration-200">

        <div class="flex items-center gap-3">

            <button onclick="toggleSidebar()" type="button" class="md:hidden text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5  text-sm p-2 focus:outline-none inline-flex">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="16" rx="2" stroke-width="1.6" />
                    <line x1="9" y1="4" x2="9" y2="20" stroke-width="1.6" />
                </svg>
            </button>

            <!-- จอใหญ่ (md ขึ้นไป): เรียก toggleCollapse() ย่อ-ขยาย sidebar เหลือแค่ icon -->
            <button onclick="toggleCollapse()" class="hidden md:flex w-9 h-9 items-center justify-center rounded-base border border-gray-300 text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="16" rx="2" stroke-width="1.6" />
                    <line x1="9" y1="4" x2="9" y2="20" stroke-width="1.6" />
                </svg>
            </button>


        </div>

        <!-- <a href="/logout" class="text-red-600 hover:text-red-800 text-sm">ออกจากระบบ</a> -->
        <div class="flex items-center gap-3">

            <div class="text-sm ink" id="thai-date">—</div>
            <div class="w-px h-4" style="background:var(--rule);"></div>
            <!-- <div class="text-sm ink-2"><?= htmlspecialchars($currentUser['emp_name_th'] . ' ' . $currentUser['emp_sname_th']) ?></div> -->
            <div>
                <button id="dropdownUserAvatarButton" data-dropdown-toggle="dropdownAvatar" class="flex items-center gap-2 text-sm rounded-full md:me-0 " type="button">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8  rounded-full object-cover overflow-hidden" src="/assets/images/logo_ktn.webp" alt="user photo">
                    <span class="text-sm ink-2"><?= htmlspecialchars($currentUser['emp_name_th'] . ' ' . $currentUser['emp_sname_th']) ?></span>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdownAvatar" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-64">
                    <div class="p-2">
                        <div class="flex items-center gap-2 px-2.5 p-2 space-x-1.5 text-sm bg-neutral-secondary-strong rounded">
                            <img class=" h-8  object-cover overflow-hidden" src="/assets/images/logo_ktn.webp" alt="Rounded avatar">
                            <div class="text-sm">
                                <div class="font-medium text-heading"><?= htmlspecialchars($currentUser['emp_name_th'] . ' ' . $currentUser['emp_sname_th']) ?></div>
                                <div class="truncate text-body"><?= htmlspecialchars($currentUser['emp_email']) ?></div>
                            </div>
                        </div>
                    </div>
                    <ul class="px-2 pb-2 text-sm text-body font-medium" aria-labelledby="dropdownAvatarButton">
                        <li>
                            <a href="/profile" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Account
                            </a>
                        </li>
                        
                        <li>
                            <a href="/logout" class="inline-flex items-center w-full p-2 text-fg-danger hover:bg-neutral-tertiary-medium rounded">
                                <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                                </svg>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- overlay — คลิกแล้วปิด sidebar (มือถือเท่านั้น) -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden" onclick="closeSidebar()"></div>

    <?php require BASE_PATH . '/views/shared/layouts/sidebar.php'; ?>

    <!-- md:ml-64 = เผื่อที่ว่างให้ sidebar (w-64) บนจอใหญ่ที่ sidebar โชว์ค้างตลอด -->
    <main id="mainContent" class="md:ml-64 px-6 py-8 transition-all duration-200 flex-1">