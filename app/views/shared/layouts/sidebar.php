<?php
// views/layouts/sidebar.php
// หน้าที่: เมนูด้านซ้าย
// 2 โหมดแยกกัน:
//   - มือถือ (จอเล็ก): เลื่อนเข้า-ออกทั้งแถบ (toggleSidebar / closeSidebar)
//   - จอใหญ่: ย่อ-ขยาย เหลือแค่ icon (toggleCollapse) — ไม่ซ่อนทั้งแถบ แค่ซ่อน label ข้อความ
//
// เมนูไหนโชว์ไม่โชว์ ขึ้นกับ is_admin ของคนที่ login อยู่ — ดึงจาก Auth::isAdmin() (มาจาก session)
// ไม่ query DB ตรงๆ ใน view เด็ดขาด ให้ Auth เป็นตัวกลางเสมอ
//
// แก้บั๊กรอบนี้:
// หัวข้อกลุ่มเมนู ("ภาพรวม", "งานบุคคล", "ระบบ") และ label ของปุ่ม dropdown "บันทึกเวลา"
// ไม่มี class sidebar-label ติดอยู่เลย ทำให้ toggleCollapse() (ที่ query แค่ .sidebar-label
// มาซ่อน) มองไม่เห็น element พวกนี้ ตอนพับ sidebar เหลือแค่ icon เลยยังมีตัวหนังสือ
// หัวข้อกลุ่มค้างโผล่มาให้เห็นอยู่ (ตามภาพที่เจอปัญหา) เพิ่ม class sidebar-label ให้ครบทุกจุด
$isAdmin = Auth::isAdmin();

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-[width,transform] duration-300 ease-in-out -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-default">
        <a href="/employees" class="flex w-full items-center justify-center py-3 mb-3">
            <img src="/assets/images/logo_ktn.webp" alt="HR Center" class="h-12 w-auto object-contain">
        </a>

        <ul class="space-y-2 font-medium border-t border-default pt-4 mt-4">
            <?php if ($isAdmin === true): ?>

                <li>
                    <span class="sidebar-label text-xs text-body">ภาพรวม</span>
                </li>
                <li>
                    <a href="/dashboard" class="sidebar-link rounded-base flex items-center px-2 py-1.5  hover:bg-neutral-tertiary hover:text-fg-brand group <?= $currentPage === 'dashboard' ? 'bg-neutral-tertiary text-body font-semibold' : 'hover:bg-neutral-tertiary' ?>">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                        </svg>

                        <span class="sidebar-label ms-3">หน้าหลัก</span>
                    </a>
                </li>

                <li>
                    <span class="sidebar-label text-xs text-body">งานบุคคล</span>
                </li>
                <li>
                    <a href="/employees" class="sidebar-link rounded-base flex items-center px-2 py-1.5 text-body  hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                        <span class="sidebar-label ms-3">พนักงาน</span>
                    </a>
                </li>
                <li>
                    <button type="button" class="flex items-center w-full justify-between px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                        <span class="sidebar-label flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">บันทึกเวลา</span>
                        <svg class="sidebar-label w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                        </svg>
                    </button>
                    <ul id="dropdown-example" class="hidden py-2 space-y-2">
                        <li>
                            <a href="/attendance" class="pl-10 flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">เวลาเข้า - ออก</a>
                        </li>
                        <li>
                            <a href="/leave" class="pl-10 flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">เพิ่มวันลา</a>
                        </li>
                        <li>
                            <a href="/leave/report" class="pl-10 flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">สรุปวันลา</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/payroll" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 6c0 1.657-3.134 3-7 3S5 7.657 5 6m14 0c0-1.657-3.134-3-7-3S5 4.343 5 6m14 0v6M5 6v6m0 0c0 1.657 3.134 3 7 3s7-1.343 7-3M5 12v6c0 1.657 3.134 3 7 3s7-1.343 7-3v-6" />
                        </svg>

                        <span class="sidebar-label ms-3">เงินเดือน</span>
                    </a>
                </li>
                <li>
                    <a href="/documents" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                        </svg>

                        <span class="sidebar-label ms-3">เอกสาร</span>
                    </a>
                </li>

                <li>
                    <span class="sidebar-label text-xs text-body">ระบบ</span>
                </li>

                <li>
                    <a href="/roles" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 11.5 11 13l4-3.5M12 20a16.405 16.405 0 0 1-5.092-5.804A16.694 16.694 0 0 1 5 6.666L12 4l7 2.667a16.695 16.695 0 0 1-1.908 7.529A16.406 16.406 0 0 1 12 20Z" />
                        </svg>

                        <span class="sidebar-label ms-3">สิทธิ์การเข้าถึง</span>
                    </a>
                </li>
                <li>
                    <a href="/settings" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        </svg>

                        <span class="sidebar-label ms-3">ตั้งค่าระบบ</span>
                    </a>
                </li>
            <?php else: ?>

                <li>
                    <span class="sidebar-label text-xs text-body">ภาพรวม</span>
                </li>
                <li>
                    <a href="/dashboard" class="sidebar-link rounded-base flex items-center px-2 py-1.5  hover:bg-neutral-tertiary hover:text-fg-brand group <?= $currentPage === 'dashboard' ? 'bg-neutral-tertiary text-body font-semibold' : 'hover:bg-neutral-tertiary' ?>">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                        </svg>
                        <span class="sidebar-label ms-3">หน้าหลัก</span>
                    </a>
                </li>
                <li>
                    <span class="sidebar-label text-xs text-body">งานบุคคล</span>
                </li>
                <li>
                    <a href="/profile" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="sidebar-label ms-3">ข้อมูลส่วนตัว</span>
                    </a>
                </li>

                <li>
                    <a href="/leave/report" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="sidebar-label ms-3">เพิ่มวันลา</span>
                    </a>
                </li>
                <li>
                    <a href="/attendance" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="sidebar-label ms-3">เวลาเข้า-ออก</span>
                    </a>
                </li>
                <li>
                    <a href="/documents" class="sidebar-link flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                        </svg>
                        <span class="sidebar-label ms-3">เอกสาร</span>
                    </a>
                </li>

            <?php endif; ?>
        </ul>
    </div>
</aside>

<script>
    // ---- มือถือ: เลื่อนเข้า-ออกทั้งแถบ ----
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        const topbar = document.getElementById('topbar');
        const labels = document.querySelectorAll('.sidebar-label');

        sidebar.style.width = '';
        main.style.marginLeft = '';
        topbar.style.marginLeft = '';
        labels.forEach(function(el) {
            el.classList.remove('hidden');
        });

        document.querySelectorAll('.sidebar-link').forEach(function(el) {
            el.classList.remove('justify-center', 'px-0');
            el.classList.add('px-2');
        });

        sidebar.dataset.collapsed = 'false';

        sidebar.classList.remove('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.remove('hidden');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
    }

    // ---- จอใหญ่: ย่อ-ขยาย เหลือแค่ icon ----
    function toggleCollapse() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        const topbar = document.getElementById('topbar');
        const labels = document.querySelectorAll('.sidebar-label');
        const isDesktop = window.matchMedia('(min-width: 768px)').matches;

        const isCollapsed = sidebar.dataset.collapsed === 'true';
        const links = document.querySelectorAll('.sidebar-link');

        if (isCollapsed === true) {
            sidebar.style.width = '16rem';
            main.style.marginLeft = isDesktop ? '16rem' : '0';
            topbar.style.marginLeft = isDesktop ? '16rem' : '0';
            labels.forEach(function(el) {
                el.classList.remove('hidden');
            });

            links.forEach(function(el) {
                el.classList.remove('justify-center', 'px-0');
                el.classList.add('px-2');
            });

            sidebar.dataset.collapsed = 'false';
        } else {
            sidebar.style.width = '4rem';
            main.style.marginLeft = isDesktop ? '4rem' : '0';
            topbar.style.marginLeft = isDesktop ? '4rem' : '0';
            labels.forEach(function(el) {
                el.classList.add('hidden');
            });

            links.forEach(function(el) {
                el.classList.remove('px-2');
                el.classList.add('justify-center', 'px-0');
            });

            sidebar.dataset.collapsed = 'true';
        }
    }
</script>