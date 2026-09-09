<?php
// views/layouts/header.php
// หน้าที่: เปิด <html>, top nav (มีปุ่ม hamburger), เปิดพื้นที่ content
// เวอร์ชัน basic — require ก่อนทุกหน้า (ยกเว้น login ที่ยังไม่ login)
// sidebar เป็น fixed → เผื่อ margin-left ให้ content ด้วย md:ml-64
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Center</title>
    <link rel="stylesheet" href="/dist/flowbite.min.css">
    <link rel="stylesheet" href="/dist/output.css">
    
</head>

<body class="font-sans bg-gray-50 min-h-screen">

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

        <a href="/logout" class="text-red-600 hover:text-red-800 text-sm">ออกจากระบบ</a>

    </nav>

    <!-- overlay — คลิกแล้วปิด sidebar (มือถือเท่านั้น) -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden" onclick="closeSidebar()"></div>

    <?php require BASE_PATH . '/views/layouts/sidebar.php'; ?>

    <!-- md:ml-64 = เผื่อที่ว่างให้ sidebar (w-64) บนจอใหญ่ที่ sidebar โชว์ค้างตลอด -->
    <main id="mainContent" class="md:ml-64 px-6 py-8 transition-all duration-200">