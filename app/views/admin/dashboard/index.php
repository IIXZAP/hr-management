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
?>
<div class="max-w-6xl mx-auto">

    <!-- <p class="text-xs font-semibold text-primary-700 uppercase tracking-wide mb-2">ภาพรวมของฉัน</p> -->

    <div class="flex items-start justify-between mb-6 flex-wrap gap-2">
        <div>
            <h1 class="text-2xl font-bold text-heading">สวัสดี, <?= htmlspecialchars($fullName) ?></h1>
            <p class="text-sm text-body mt-1">เริ่มวันทำงานอย่างเป็นเรียบ ทุกเรื่องสำคัญอยู่ที่นี่</p>
        </div>
        <div class="text-right shrink-0">
            <!-- <p class="text-sm font-medium text-heading"><?= htmlspecialchars(date('l j F Y')) ?></p> -->
            <!-- <p class="text-xs text-body">เวลาทำงาน 08:30 - 17:30 น.</p> -->
        </div>
    </div>

    <!-- 4 การ์ดบนสุด -->
    <div class="grid grid-cols-4 gap-4 mb-6">

        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-body">จำนวนพนักงานทั้งหมด</p>
                <svg class="w-4 h-4 text-body" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" /><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" /></svg>
            </div>
            <p class="text-xl font-bold text-heading">20 คน</p>
            <p class="text-xs text-body mt-2">บันทึกจากเครื่องสแกน</p>
        </div>

        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-body">คำขอลาอนุมัติ</p>
                <svg class="w-4 h-4 text-body" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" /><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" /></svg>
            </div>
            <p class="text-xl font-bold text-heading">5 รายการ</p>
            
            
        </div>

        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-body">วันพักร้อนคงเหลือ</p>
                <svg class="w-4 h-4 text-body" viewBox="0 0 24 24" fill="none"><path d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </div>
            <p class="text-xl font-bold text-heading"><?= (int) $leaveBalance ?> วัน</p>
            <p class="text-xs text-body mt-2">ปี <?= htmlspecialchars(date('Y') + 543) ?></p>
            <p class="text-xs text-body">ใช้สิทธิ์ก่อน 10 วัน</p>
        </div>

        <!-- Quick action -->
        <div class="bg-gray-700 rounded-base shadow-xs p-5 text-white">
            <p class="text-xs font-semibold uppercase tracking-wide opacity-80 mb-2">Quick Action</p>
            <p class="text-sm font-semibold">วางแผนวันลาของคุณ</p>
            <p class="text-xs opacity-80 mt-1 mb-3">ส่งคำขอลาทันทีตามความจำเป็นให้อยู่ที่นี่</p>
            <a href="/leave" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white text-gray-700 text-xs font-medium rounded-base">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
                เพิ่มวันลา
            </a>
        </div>

    </div>

    <div class="grid sm:grid-cols-2 gap-4">

        <!-- คอลัมน์ซ้าย -->
        <div class="space-y-4">

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
            <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                <div class="flex items-center justify-between px-5 py-4 border-b border-default-medium">
                    <h2 class="text-sm font-semibold text-heading">สถานะการขอวันลา</h2>
                    <a href="/leave/report" class="text-xs font-medium text-primary-700 hover:underline">ดูทั้งหมด</a>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-body">
                        <tr>
                            <th class="px-5 py-2">ประเภท / วันที่ลา</th>
                            <th class="px-5 py-2">จำนวนวัน</th>
                            <th class="px-5 py-2">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (count($recentLeaves) === 0): ?>
                            <tr><td colspan="3" class="px-5 py-6 text-center text-body">ยังไม่มีคำขอลา</td></tr>
                        <?php endif; ?>
                        <?php foreach ($recentLeaves as $leave): ?>
                            <?php $badge = $statusBadge[$leave['status']] ?? $statusBadge['pending']; ?>
                            <tr>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-heading"><?= htmlspecialchars($leave['type']) ?></p>
                                    <p class="text-xs text-body"><?= htmlspecialchars($leave['date_range']) ?></p>
                                </td>
                                <td class="px-5 py-3 text-body"><?= htmlspecialchars($leave['days']) ?></td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $badge['class'] ?>"><?= htmlspecialchars($badge['label']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="px-5 py-3 text-xs text-body border-t border-default-medium">ตรวจสอบรายละเอียดและเหตุผลได้ในหน้ารายการขอลา</p>
            </div>

        </div>

        <!-- คอลัมน์ขวา -->
        <div class="space-y-4">

            <!-- อัปเดตจากแอดมิน -->
            <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-sm font-semibold text-heading">อัปเดตจากแอดมิน</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">ใหม่ <?= (int) $newDocsCount ?></span>
                </div>
                <p class="text-xs text-body mb-4">เอกสารและข่าวสารที่สำคัญถึงคุณ</p>

                <div class="space-y-3">
                    <?php foreach ($recentDocs as $doc): ?>
                        <div class="flex items-start gap-3 p-3 bg-neutral-secondary-medium rounded-base">
                            <span class="shrink-0 w-9 h-9 rounded-lg bg-white text-primary-700 flex items-center justify-center">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-heading truncate"><?= htmlspecialchars($doc['label']) ?></p>
                                    <span class="shrink-0 px-1.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">ใหม่</span>
                                </div>
                                <p class="text-xs text-body"><?= htmlspecialchars($doc['meta']) ?></p>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-xs text-body"><?= htmlspecialchars($doc['time']) ?></p>
                                    <a href="<?= htmlspecialchars($doc['doc_url']) ?>" target="_blank" class="text-xs font-medium text-primary-700 hover:underline">เปิดเอกสาร →</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="/documents/my" class="block text-center text-xs font-medium text-primary-700 hover:underline mt-4">ดูเอกสารย้อนหลังทั้งหมด →</a>
            </div>

            <!-- ประกาศบริษัท -->
            <?php if ($announcement !== null): ?>
                <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-primary-700" viewBox="0 0 24 24" fill="none"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /><path d="M13.73 21a2 2 0 01-3.46 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        <h2 class="text-sm font-semibold text-heading">ประกาศบริษัท</h2>
                    </div>
                    <p class="text-sm font-medium text-heading"><?= htmlspecialchars($announcement['title']) ?></p>
                    <p class="text-xs text-body mt-1"><?= htmlspecialchars($announcement['body']) ?></p>
                    <a href="/profile" class="inline-block text-xs font-medium text-primary-700 hover:underline mt-3">ตรวจสอบข้อมูลของฉัน →</a>
                </div>
            <?php endif; ?>

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