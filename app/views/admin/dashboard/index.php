<?php
// views/admin/dashboard/index.php
// หน้าที่: หน้าแรก (dashboard) ฝั่งแอดมิน — เรียกจาก DashboardController::index()
// เมื่อ Auth::isAdmin() === true
//
// ตัวแปรที่ต้องได้จาก Controller:
//   $employee         — array ข้อมูลแอดมินที่ login อยู่
//   $totalEmployees   — int จำนวนพนักงานที่ยังทำงานอยู่
//   $pendingLeaves    — int จำนวนคำขอลาที่รออนุมัติ
//   $todayAttendance  — int จำนวนคนที่สแกนเข้างานวันนี้
//   $recentLeaves     — array คำขอลาล่าสุดของทุกคน (employee, type, date_range, days, status)
//   $recentDocs       — array เอกสารรออนุมัติล่าสุด (label, meta, time, doc_url)
//   $pendingDocsCount — int จำนวนเอกสารที่รออนุมัติทั้งหมด

$employee         = $employee ?? [];
$totalEmployees   = $totalEmployees ?? 0;
$pendingLeaves    = $pendingLeaves ?? 0;
$todayAttendance  = $todayAttendance ?? 0;
$recentLeaves     = $recentLeaves ?? [];
$recentDocs       = $recentDocs ?? [];
$pendingDocsCount = $pendingDocsCount ?? 0;

$statusBadge = [
    'approved' => ['label' => 'อนุมัติแล้ว', 'class' => 'bg-green-100 text-green-800'],
    'pending'  => ['label' => 'รออนุมัติ', 'class' => 'bg-amber-100 text-amber-800'],
    'rejected' => ['label' => 'ไม่อนุมัติ', 'class' => 'bg-red-100 text-red-800'],
];

$fullName = trim(($employee['emp_name_th'] ?? '') . ' ' . ($employee['emp_sname_th'] ?? ''));
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
            <p class="text-xl font-bold text-heading"><?= (int) $totalEmployees ?> คน</p>
            <p class="text-xs text-body mt-2">พนักงานที่ยังทำงานอยู่</p>
        </div>

        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-body">คำขอลารออนุมัติ</p>
                <svg class="w-4 h-4 text-body" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" /><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" /></svg>
            </div>
            <p class="text-xl font-bold text-heading"><?= (int) $pendingLeaves ?> รายการ</p>
            <p class="text-xs text-body mt-2">รอดำเนินการ</p>
        </div>

        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-body">เข้างานวันนี้</p>
                <svg class="w-4 h-4 text-body" viewBox="0 0 24 24" fill="none"><path d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </div>
            <p class="text-xl font-bold text-heading"><?= (int) $todayAttendance ?> คน</p>
            <p class="text-xs text-body mt-2">จากพนักงาน <?= (int) $totalEmployees ?> คน</p>
        </div>

        <!-- Quick action -->
        <div class="bg-gray-700 rounded-base shadow-xs p-5 text-white">
            <p class="text-xs font-semibold uppercase tracking-wide opacity-80 mb-2">Quick Action</p>
            <p class="text-sm font-semibold">จัดการคำขอลา</p>
            <p class="text-xs opacity-80 mt-1 mb-3">ตรวจสอบและอนุมัติคำขอลาของพนักงาน</p>
            <a href="/leave" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white text-gray-700 text-xs font-medium rounded-base">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
                ไปที่รายการลา
            </a>
        </div>

    </div>

    <div class="grid sm:grid-cols-2 gap-4">

        <!-- คอลัมน์ซ้าย -->
        <div class="space-y-4">

            <!-- สถานะการขอวันลา -->
            <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                <div class="flex items-center justify-between px-5 py-4 border-b border-default-medium">
                    <h2 class="text-sm font-semibold text-heading">คำขอลาล่าสุด</h2>
                    <a href="/leave/report" class="text-xs font-medium text-primary-700 hover:underline">ดูทั้งหมด</a>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-body">
                        <tr>
                            <th class="px-5 py-2">พนักงาน / ประเภท / วันที่ลา</th>
                            <th class="px-5 py-2">จำนวนวัน</th>
                            <th class="px-5 py-2">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($recentLeaves)): ?>
                            <tr><td colspan="3" class="px-5 py-6 text-center text-body">ยังไม่มีคำขอลา</td></tr>
                        <?php endif; ?>
                        <?php foreach ($recentLeaves as $leave): ?>
                            <?php $badge = $statusBadge[$leave['status']] ?? $statusBadge['pending']; ?>
                            <tr>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-heading"><?= htmlspecialchars($leave['employee']) ?></p>
                                    <p class="text-xs text-body"><?= htmlspecialchars($leave['type']) ?></p>
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
                    <h2 class="text-sm font-semibold text-heading">เอกสารรออนุมัติ</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">รออนุมัติ <?= (int) $pendingDocsCount ?></span>
                </div>
                <p class="text-xs text-body mb-4">เอกสารที่พนักงานอัปโหลดและรอการตรวจสอบ</p>

                <div class="space-y-3">
                    <?php if (empty($recentDocs)): ?>
                        <p class="text-sm text-body text-center py-4">ไม่มีเอกสารรออนุมัติ</p>
                    <?php endif; ?>
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

                <a href="/documents" class="block text-center text-xs font-medium text-primary-700 hover:underline mt-4">ดูเอกสารทั้งหมด →</a>
            </div>

        </div>
    </div>

</div>
