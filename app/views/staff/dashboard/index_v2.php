<?php
// views/dashboard/employee.php
// หน้าที่: หน้าแรก (dashboard) ฝั่งพนักงาน — เรียกจาก DashboardController::index()
// เมื่อ Auth::isAdmin() === false
//
// ตัวแปรที่ต้องได้จาก Controller:
//   $employee       — array ข้อมูลพนักงานที่ login อยู่
//   $todayScan      — array|null เวลาสแกนบัตรวันนี้ (check_in, check_out)
//   $leaveBalance   — int จำนวนวันลาพักร้อนคงเหลือ
//   $recentLeaves   — array คำขอลาล่าสุดของพนักงานคนนี้
//   $recentDocs     — array เอกสารล่าสุดจากแอดมิน (สูงสุด 2 รายการ)
//   $announcement   — array|null ประกาศบริษัทล่าสุด
//
// ⚠️ ยังไม่มีตารางสำหรับ "รายการที่ต้องทำ" (to-do) และ "ประกาศบริษัท" ในระบบเลย
// ตอนนี้ to-do เป็น client-side JS เท่านั้น (รีเฟรชหน้าแล้วข้อมูลหาย ไม่ persist)
// ถ้าต้องการเก็บจริง ต้องสร้างตาราง todos (emp_id, task, due_label, is_done) เพิ่ม
//
// Hallmark redesign · macrostructure: Workbench · tone: utilitarian
// (รอบก่อน: Bento Grid → รอบนี้เปลี่ยนโครง: แถบสถานะ + พื้นที่งานหลัก + rail ข้าง)
// pre-emit critique: P4 H5 E4 S4 R5 V4

// ---- Mock data ----
if (!isset($employee)) {
    $employee = ['emp_name_th' => 'Pannika', 'emp_sname_th' => ''];
}
if (!isset($todayScan)) {
    $todayScan = ['check_in' => '08:30:00', 'check_out' => null];
}
if (!isset($leaveBalance)) {
    $leaveBalance = 6;
}
if (!isset($recentLeaves)) {
    $recentLeaves = [
        ['type' => 'ลาพักร้อน', 'date_range' => '18 ก.ย. 2569', 'days' => '1 วัน', 'status' => 'pending'],
        ['type' => 'ลากิจ', 'date_range' => '10 ก.ย. 2569', 'days' => '0.5 วัน', 'status' => 'approved'],
        ['type' => 'ลาพักร้อน', 'date_range' => '4 ก.ย. 2569', 'days' => '1 วัน', 'status' => 'rejected'],
    ];
}
if (!isset($recentDocs)) {
    $recentDocs = [
        ['label' => 'สลิปเงินเดือน • สิงหาคม 2569', 'meta' => 'ฝ่ายบุคคล • PDF, 245 KB', 'time' => 'วันนี้ 09:15 น.', 'doc_url' => '/files/payslip.pdf'],
        ['label' => 'หนังสือรับรองการทำงาน', 'meta' => 'ฝ่ายบุคคล • PDF, 180 KB', 'time' => 'วันนี้ 08:45 น.', 'doc_url' => '/files/cert.pdf'],
    ];
}
if (!isset($announcement)) {
    $announcement = [
        'title' => 'อัปเดตข้อมูลพนักงานประจำปี',
        'body' => 'กรุณาตรวจสอบที่อยู่และเบอร์โทรศัพท์ให้เป็นข้อมูลล่าสุด ภายในวันที่ 30 ก.ย. 2569',
    ];
}

$statusBadge = [
    'approved' => ['label' => 'อนุมัติแล้ว', 'class' => 'bg-green-100 text-green-800'],
    'pending'  => ['label' => 'รออนุมัติ', 'class' => 'bg-amber-100 text-amber-800'],
    'rejected' => ['label' => 'ไม่อนุมัติ', 'class' => 'bg-red-100 text-red-800'],
];

$fullName = trim(($employee['emp_name_th'] ?? '') . ' ' . ($employee['emp_sname_th'] ?? ''));
$hasCheckedIn = !empty($todayScan['check_in']);
$hasCheckedOut = !empty($todayScan['check_out']);
$newDocsCount = count($recentDocs);

// วันที่ภาษาไทย (พ.ศ.) — แทน date('l j F Y') ที่ออกเป็นอังกฤษ
$thaiDays = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
$thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$todayLabel = 'วัน' . $thaiDays[(int) date('w')] . 'ที่ ' . date('j') . ' ' . $thaiMonths[(int) date('n') - 1] . ' ' . (date('Y') + 543);
?>
<div class="max-w-6xl mx-auto">

    <!-- หัวหน้า : ชื่อ + วันที่ ซ้าย / ปุ่มหลักของหน้า ขวา (จุดเน้นเดียว) -->
    <header class="flex flex-wrap items-end justify-between gap-x-6 gap-y-4 mb-4">
        <div class="min-w-0">
            <h1 class="text-2xl font-bold tracking-tight text-heading [overflow-wrap:anywhere]">สวัสดี, <?= htmlspecialchars($fullName) ?></h1>
            <p class="text-sm text-body mt-1">
                <?= htmlspecialchars($todayLabel) ?> · เวลาทำงาน 08:30 - 17:30 น.
            </p>
        </div>
        <a href="/leave" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-base whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            เพิ่มวันลา
        </a>
    </header>

    <!-- แถบสถานะวันนี้ : เข้างาน | ออกงาน | พักร้อนคงเหลือ — แถวเดียว คั่นด้วยเส้น -->
    <section class="bg-neutral-primary-soft border border-default rounded-base" aria-labelledby="t-today">
        <h2 id="t-today" class="sr-only">สถานะวันนี้</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 sm:divide-x sm:divide-default-medium">

            <div class="min-w-0 p-4 border-r border-default-medium sm:border-r-0">
                <p class="text-sm text-body">เข้างาน</p>
                <p class="mt-1 text-2xl font-semibold tracking-tight tabular-nums <?= $hasCheckedIn ? 'text-heading' : 'text-body' ?>">
                    <?= $hasCheckedIn ? htmlspecialchars(substr($todayScan['check_in'], 0, 5)) : '--:--' ?>
                </p>
                <p class="mt-2 flex items-center gap-2 text-sm text-body">
                    <span class="shrink-0 w-1.5 h-1.5 rounded-full <?= $hasCheckedIn ? 'bg-green-600' : 'bg-gray-300' ?>" aria-hidden="true"></span>
                    <?= $hasCheckedIn ? 'บันทึกแล้ว' : 'ยังไม่เข้า' ?>
                </p>
            </div>

            <div class="min-w-0 p-4">
                <p class="text-sm text-body">ออกงาน</p>
                <p class="mt-1 text-2xl font-semibold tracking-tight tabular-nums <?= $hasCheckedOut ? 'text-heading' : 'text-body' ?>">
                    <?= $hasCheckedOut ? htmlspecialchars(substr($todayScan['check_out'], 0, 5)) : '--:--' ?>
                </p>
                <p class="mt-2 flex items-center gap-2 text-sm text-body">
                    <span class="shrink-0 w-1.5 h-1.5 rounded-full <?= $hasCheckedOut ? 'bg-green-600' : 'bg-gray-300' ?>" aria-hidden="true"></span>
                    <?= $hasCheckedOut ? 'บันทึกแล้ว' : 'ยังไม่ลงเวลา' ?>
                </p>
            </div>

            <div class="min-w-0 col-span-2 sm:col-span-1 p-4 border-t border-default-medium sm:border-t-0">
                <p class="text-sm text-body">วันพักร้อนคงเหลือ</p>
                <p class="mt-1 text-heading">
                    <span class="text-2xl font-semibold tracking-tight tabular-nums"><?= (int) $leaveBalance ?></span>
                    <span class="ml-1 text-base text-body">วัน</span>
                </p>
            </div>

        </div>
    </section>

    <!-- พื้นที่งาน : 8 คอลัมน์ (หลัก) + 4 คอลัมน์ (rail) -->
    <div class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

        <!-- หลัก : สถานะการขอวันลา -->
        <div class="lg:col-span-8 space-y-4">

            <!-- รายการที่ต้องทำ (to-do — client-side เท่านั้น ยังไม่ persist) -->
            <!-- <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                <div class="flex items-center justify-between px-5 py-4 border-b border-default-medium">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-semibold text-heading">รายการที่ต้องทำ</h2>
                        <span id="todoRemainingBadge" class="px-1.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">3</span>
                    </div>
                </div>

                <div class="px-5 pt-4 flex items-center gap-2">
                    <input type="text" id="todoInput" placeholder="+ เพิ่มงานที่ต้องทำ..."
                        class="flex-1 text-sm border border-default-medium rounded-base px-3 py-2 bg-neutral-secondary-medium focus:ring-brand focus:border-brand">
                    <button type="button" onclick="addTodo()" class="px-4 py-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-base">เพิ่ม</button>
                </div>

                <ul id="todoList" class="px-5 py-4 space-y-3">
                    <li class="flex items-center justify-between gap-3" data-done="false">
                        <label class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer">
                            <input type="checkbox" onchange="toggleTodo(this)" class="w-4 h-4 text-primary-700 rounded focus:ring-brand">
                            <span class="todo-text text-sm text-heading truncate">ส่งสรุปงานประจำสัปดาห์</span>
                        </label>
                        <span class="todo-badge shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">วันนี้</span>
                    </li>
                    <li class="flex items-center justify-between gap-3" data-done="false">
                        <label class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer">
                            <input type="checkbox" onchange="toggleTodo(this)" class="w-4 h-4 text-primary-700 rounded focus:ring-brand">
                            <span class="todo-text text-sm text-heading truncate">ตรวจสอบเอกสารส่วนตัวจาก HR</span>
                        </label>
                        <span class="todo-badge shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">วันนี้</span>
                    </li>
                    <li class="flex items-center justify-between gap-3" data-done="false">
                        <label class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer">
                            <input type="checkbox" onchange="toggleTodo(this)" class="w-4 h-4 text-primary-700 rounded focus:ring-brand">
                            <span class="todo-text text-sm text-heading truncate">เตรียมข้อมูลประชุมทีม</span>
                        </label>
                        <span class="todo-badge shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-secondary-medium text-body">15 ก.ย.</span>
                    </li>
                    <li class="flex items-center justify-between gap-3" data-done="true">
                        <label class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer">
                            <input type="checkbox" checked onchange="toggleTodo(this)" class="w-4 h-4 text-primary-700 rounded focus:ring-brand">
                            <span class="todo-text text-sm text-heading truncate line-through text-body">อัปเดตข้อมูลติดต่อส่วนตัว</span>
                        </label>
                        <span class="todo-badge shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">เสร็จแล้ว</span>
                    </li>
                </ul>
            </div> -->

            <!-- สถานะการขอวันลา -->
            <section class="bg-neutral-primary-soft border border-default rounded-base" aria-labelledby="t-leaves">
                <div class="flex items-center justify-between gap-4 px-4 pt-4 pb-3">
                    <h2 id="t-leaves" class="text-sm font-semibold text-heading">สถานะการขอวันลา</h2>
                    <a href="/leave/report" class="text-sm font-medium text-primary-700 whitespace-nowrap hover:underline focus:outline-none focus:ring-2 focus:ring-brand rounded-base">ดูทั้งหมด</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[26rem] text-sm text-left">
                        <thead class="text-body">
                            <tr class="border-y border-default-medium">
                                <th scope="col" class="px-4 py-2 font-medium">ประเภท / วันที่ลา</th>
                                <th scope="col" class="px-4 py-2 font-medium">จำนวนวัน</th>
                                <th scope="col" class="px-4 py-2 font-medium">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-medium">
                            <?php if (count($recentLeaves) === 0): ?>
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-body">ยังไม่มีคำขอลา</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($recentLeaves as $leave): ?>
                                <?php $badge = $statusBadge[$leave['status']] ?? $statusBadge['pending']; ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-heading"><?= htmlspecialchars($leave['type']) ?></p>
                                        <p class="text-sm text-body mt-0.5"><?= htmlspecialchars($leave['date_range']) ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-body whitespace-nowrap"><?= htmlspecialchars($leave['days']) ?></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap <?= $badge['class'] ?>"><?= htmlspecialchars($badge['label']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        <!-- rail ข้าง : ประกาศ (ปิดอยู่) + เอกสารจากแอดมิน -->
        <div class="lg:col-span-4 space-y-4">

            <!-- ประกาศบริษัท : ไม่มีกรอบ ใช้พื้นหลังอ่อนแยกน้ำหนักจากการ์ดข้อมูล -->
            <!-- <?php if ($announcement !== null): ?>
                <section class="bg-neutral-secondary-medium rounded-base p-4" aria-labelledby="t-announce">
                    <h2 id="t-announce" class="text-sm font-semibold text-heading">ประกาศบริษัท</h2>
                    <p class="mt-2 text-sm font-medium text-heading"><?= htmlspecialchars($announcement['title']) ?></p>
                    <p class="mt-1 text-sm text-body leading-relaxed"><?= htmlspecialchars($announcement['body']) ?></p>
                    <a href="/profile" class="inline-block mt-3 text-sm font-medium text-primary-700 hover:underline focus:outline-none focus:ring-2 focus:ring-brand rounded-base">ตรวจสอบข้อมูลของฉัน</a>
                </section>
            <?php endif; ?> -->

            <!-- อัปเดตจากแอดมิน -->
            <section class="bg-neutral-primary-soft border border-default rounded-base p-4" aria-labelledby="t-updates">
                <div class="flex items-center justify-between gap-4">
                    <h2 id="t-updates" class="text-sm font-semibold text-heading">อัปเดต</h2>
                    <?php if ($newDocsCount > 0): ?>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-700 whitespace-nowrap">ใหม่ <?= (int) $newDocsCount ?></span>
                    <?php endif; ?>
                </div>
                <p class="mt-1 text-sm text-body">เอกสารและข่าวสารที่สำคัญถึงคุณ</p>

                <?php if ($newDocsCount === 0): ?>
                    <p class="mt-3 py-3 text-sm text-center text-body">ยังไม่มีเอกสารใหม่</p>
                <?php else: ?>
                    <ul class="mt-2 divide-y divide-default-medium">
                        <?php foreach ($recentDocs as $doc): ?>
                            <li class="flex items-start gap-3 py-3">
                                <span class="shrink-0 w-8 h-8 rounded-base bg-neutral-secondary-medium text-body flex items-center justify-center" aria-hidden="true">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-heading truncate"><?= htmlspecialchars($doc['label']) ?></p>
                                    <p class="text-sm text-body mt-0.5 truncate"><?= htmlspecialchars($doc['meta']) ?></p>
                                    <div class="mt-1 flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
                                        <p class="text-sm text-body"><?= htmlspecialchars($doc['time']) ?></p>
                                        <a href="<?= htmlspecialchars($doc['doc_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-primary-700 whitespace-nowrap hover:underline focus:outline-none focus:ring-2 focus:ring-brand rounded-base">เปิดเอกสาร</a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <a href="/documents/my" class="block mt-1 pt-3 border-t border-default-medium text-center text-sm font-medium text-primary-700 hover:underline focus:outline-none focus:ring-2 focus:ring-brand rounded-base">ดูเอกสารย้อนหลังทั้งหมด</a>
            </section>

        </div>
    </div>

</div>

<script>
    // ---- to-do list: client-side เท่านั้น ยังไม่ persist ข้ามหน้า/รีเฟรช ----
    // ถ้าต้องการเก็บจริง ต้องสร้างตาราง todos แล้วเปลี่ยนมาเรียก fetch() ไป backend แทน

    function toggleTodo(checkbox) {
        var li = checkbox.closest('li');
        var text = li.querySelector('.todo-text');
        var badge = li.querySelector('.todo-badge');

        if (checkbox.checked) {
            li.dataset.done = 'true';
            text.classList.add('line-through', 'text-body');
            badge.textContent = 'เสร็จแล้ว';
            badge.className = 'todo-badge shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
        } else {
            li.dataset.done = 'false';
            text.classList.remove('line-through', 'text-body');
            badge.textContent = 'วันนี้';
            badge.className = 'todo-badge shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700';
        }
        updateTodoRemainingBadge();
    }

    function addTodo() {
        var input = document.getElementById('todoInput');
        var text = input.value.trim();
        if (text === '') return;

        var li = document.createElement('li');
        li.className = 'flex items-center justify-between gap-3';
        li.dataset.done = 'false';
        li.innerHTML =
            '<label class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer">' +
            '<input type="checkbox" onchange="toggleTodo(this)" class="w-4 h-4 text-primary-700 rounded focus:ring-brand">' +
            '<span class="todo-text text-sm text-heading truncate"></span>' +
            '</label>' +
            '<span class="todo-badge shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">วันนี้</span>';

        // ตั้งค่าข้อความผ่าน textContent (ไม่ใช่ innerHTML) กัน XSS จากข้อความที่ผู้ใช้พิมพ์เอง
        li.querySelector('.todo-text').textContent = text;

        document.getElementById('todoList').appendChild(li);
        input.value = '';
        updateTodoRemainingBadge();
    }

    function updateTodoRemainingBadge() {
        var remaining = document.querySelectorAll('#todoList li[data-done="false"]').length;
        document.getElementById('todoRemainingBadge').textContent = remaining;
    }
</script>