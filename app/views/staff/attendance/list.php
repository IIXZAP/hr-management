<?php
// views/attendance/my.php
// หน้าที่: พนักงานดูเวลาเข้า-ออกงานของตัวเอง (ไม่มีสิทธิ์ดูของคนอื่น)
// emp_id ต้องมาจาก Auth::empId() เท่านั้น (ตามหลักการเดียวกับหน้า dashboard/documents ที่ทำไปก่อนหน้า)
//
// อัปเดตรอบนี้: เปลี่ยนหัวตารางประวัติให้ตรงกับตารางฝั่ง admin
// (#, รหัส, ชื่อ, วันที่, เข้า, ออก, ลา, จำนวนชม., รายละเอียด) แต่ไม่มี dropdown เลือกพนักงาน
// และตัดปุ่มแก้ไข/ลบออก เปลี่ยนเป็นลิงก์ดูรายละเอียดอย่างเดียว เพราะพนักงานไม่ควรแก้ไข/ลบ
// เวลาสแกนบัตรของตัวเองได้ (สิทธิ์นั้นควรเป็นของ admin เท่านั้น เพื่อความน่าเชื่อถือของข้อมูล)

// ---- Mock data (ลบทิ้งเมื่อ Controller ส่งค่าจริงมาให้แล้ว) ----
if (!isset($employee)) {
    $employee = ['emp_no' => 'EMP-004', 'emp_name_th' => 'มาลี', 'emp_sname_th' => 'ดอกไม้'];
}
if (!isset($stats)) {
    $stats = ['recorded_days' => 10, 'on_time_days' => 9, 'late_count' => 1, 'late_minutes' => 15, 'absent_days' => 0];
}
if (!isset($periodLabel)) {
    $periodLabel = '1-14 ก.ย. 2569';
}
if (!isset($todayStatus)) {
    $todayStatus = ['date_label' => '14 ก.ย. 2569', 'shift_label' => '08:30 - 17:30 น.', 'check_in' => '08:30', 'check_out' => null, 'status' => 'working'];
}
if (!isset($history)) {
    $history = [
        ['date' => '14 ก.ย. 2569', 'check_in' => '08:30', 'check_out' => null, 'leave_type_name' => null, 'status' => 'working'],
        ['date' => '11 ก.ย. 2569', 'check_in' => '08:28', 'check_out' => '17:35', 'leave_type_name' => null, 'status' => 'on_time'],
        ['date' => '10 ก.ย. 2569', 'check_in' => '13:00', 'check_out' => '17:30', 'leave_type_name' => 'ลากิจครึ่งวัน', 'status' => 'half_day_leave'],
        ['date' => '9 ก.ย. 2569', 'check_in' => '08:45', 'check_out' => '17:30', 'leave_type_name' => null, 'status' => 'late'],
    ];
}
if (!isset($totalRecords)) {
    $totalRecords = 10;
}

$statusMap = [
    'working'         => ['label' => 'กำลังทำงาน', 'class' => 'bg-blue-100 text-blue-800'],
    'on_time'         => ['label' => 'ตรงเวลา', 'class' => 'bg-green-100 text-green-800'],
    'half_day_leave'  => ['label' => 'ลากิจครึ่งวัน', 'class' => 'bg-gray-100 text-gray-700'],
    'late'            => ['label' => 'สาย 15 นาที', 'class' => 'bg-amber-100 text-amber-800'],
];
$currentPage = $currentPage ?? '';
$totalPages = $totalPages ?? '';
?>
<div class="max-w-6xl mx-auto">

    <!-- <p class="text-xs font-semibold text-primary-700 uppercase tracking-wide mb-2">My Workspace</p> -->

    <div class="flex items-start justify-between mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-heading">เวลาเข้า-ออกงาน</h1>
            <p class="text-sm text-body mt-1">ตรวจสอบเวลาเข้า-ออกงานและสรุปการทำงานของคุณในแต่ละเดือน</p>
        </div>

    </div>

    <!-- 4 stat cards -->
    <!-- <div class="grid sm:grid-cols-4 gap-4 mb-4">
        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <p class="text-xs text-body mb-2">วันทำงานที่บันทึก</p>
            <p class="text-xl font-bold text-heading"><?= (int) $stats['recorded_days'] ?> วัน</p>
            <p class="text-xs text-primary-700 mt-1"><?= htmlspecialchars($periodLabel) ?></p>
        </div>
        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <p class="text-xs text-body mb-2">เข้างานตรงเวลา</p>
            <p class="text-xl font-bold text-heading"><?= (int) $stats['on_time_days'] ?> วัน</p>
            <p class="text-xs text-primary-700 mt-1">จาก <?= (int) $stats['recorded_days'] ?> วันที่บันทึก</p>
        </div>
        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <p class="text-xs text-body mb-2">มาสาย</p>
            <p class="text-xl font-bold text-heading"><?= (int) $stats['late_count'] ?> ครั้ง</p>
            <p class="text-xs text-primary-700 mt-1">รวม <?= (int) $stats['late_minutes'] ?> นาที</p>
        </div>
        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <p class="text-xs text-body mb-2">ขาดงาน</p>
            <p class="text-xl font-bold text-heading"><?= (int) $stats['absent_days'] ?> วัน</p>
            <p class="text-xs text-primary-700 mt-1">ในเดือนที่ขาดงาน</p>
        </div>
    </div> -->

    <!-- Banner สถานะวันนี้ -->
    <div class="flex items-center justify-between gap-4 bg-neutral-primary-soft border border-default rounded-base shadow-xs px-5 py-4 mb-4 flex-wrap">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-primary-700 shrink-0" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
            </svg>
            <div>
                <p class="text-sm font-medium text-heading">วันนี้</p>
            </div>
        </div>
        <div class="text-center">
            <p class="text-xs text-body">เข้างาน</p>
            <p class="text-lg font-bold text-heading"><?= htmlspecialchars($todayStatus['check_in'] ?? '--:--') ?></p>
        </div>
        <div class="text-center">
            <p class="text-xs text-body">ออกงาน</p>
            <p class="text-lg font-bold text-heading"><?= htmlspecialchars($todayStatus['check_out'] ?? '--:--') ?></p>
        </div>
        <?php $todayBadge = $statusMap[$todayStatus['status']] ?? $statusMap['working']; ?>
        <span class="px-3 py-1.5 rounded-full text-xs font-medium <?= $todayBadge['class'] ?>"><?= htmlspecialchars($todayBadge['label']) ?></span>
    </div>

    <!-- ประวัติการลงเวลา -->
    <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs">
        <div class="flex items-center justify-between px-5 py-4 border-b border-default-medium flex-wrap gap-3">
            <h2 class="text-sm font-semibold text-heading">ประวัติการลงเวลา</h2>
            <div class="flex items-center gap-2 flex-wrap">
                <input type="month" id="filterMonth"
                    value="<?= htmlspecialchars($_GET['month'] ?? date('Y-m')) ?>"
                    onchange="location.href = '/attendance/list?month=' + this.value"
                    class="text-sm border border-default-medium rounded-base px-3 py-2 bg-neutral-secondary-medium text-heading focus:ring-brand focus:border-brand">
                <!-- <select id="filterStatus" onchange="filterHistoryByStatus(this.value)"
                    class="text-sm border border-default-medium rounded-base px-3 py-2 bg-neutral-secondary-medium text-heading">
                    <option value="all">ทุกสถานะ</option>
                    <?php foreach ($statusMap as $key => $info): ?>
                        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($info['label']) ?></option>
                    <?php endforeach; ?>
                </select> -->
                <!-- <span class="text-xs text-body shrink-0"><?= (int) $totalRecords ?> รายการ</span> -->
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium">
                    <tr>
                        <th class="px-4 py-3 text-center">#</th>
                        <th class="px-4 py-3 text-center">รหัส</th>
                        <th class="px-4 py-3 text-center">ชื่อ</th>
                        <th class="px-4 py-3 text-center">วันที่</th>
                        <th class="px-4 py-3 text-center">เข้า</th>
                        <th class="px-4 py-3 text-center">ออก</th>
                        <th class="px-4 py-3 text-center">ลา</th>
                        <th class="px-4 py-3 text-center">จำนวนชม.</th>

                    </tr>
                </thead>
                <tbody id="historyTableBody" class="divide-y divide-gray-100">
                    <?php if (count($history) === 0): ?>
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-400">ยังไม่มีข้อมูลการลงเวลา</td>
                        </tr>
                    <?php endif; ?>

                    <?php $i = 1; ?>
                    <?php foreach ($history as $row): ?>
                        <?php
                        // $hasFullAttendance = !empty($row['check_in']) && !empty($row['check_out']);
                        // $displayTime = '-';
                        // if ($hasFullAttendance) {
                        //     $seconds = strtotime($row['check_out']) - strtotime($row['check_in']);
                        //     if ($seconds > 0) {
                        //         $hours   = intdiv($seconds, 3600);
                        //         $minutes = intdiv($seconds % 3600, 60);

                        //         $parts = [];
                        //         if ($hours > 0) {
                        //             $parts[] = $hours . ' ชม.';
                        //         }
                        //         if ($minutes > 0 || $hours === 0) {
                        //             $parts[] = $minutes . ' นาที';
                        //         }
                        //         $displayTime = implode(' ', $parts);
                        //     }
                        // }
                        $displayTime = formatWorkDuration($row['check_in'], $row['check_out']);
                        $isOnLeave = !empty($row['leave_type_name']);
                        $badge = $statusMap[$row['status']] ?? $statusMap['working'];
                        ?>
                        <?php
                        $checkInBadgeClass = '';
                        $checkInBadgeText  = $displayTime;

                        if ($isOnLeave) {
                            // ลา → แสดง badge สีเหลือง แทนจำนวนชม.
                            $checkInBadgeClass = 'bg-yellow-100 text-yellow-800';
                            $checkInBadgeText  = '';
                        } elseif (!empty($row['check_in']) && empty($row['check_out'])) {
                            // มีเวลาเข้า แต่ยังไม่มีเวลาออก → กำลังทำงาน
                            $checkInBadgeClass = 'bg-gray-100 text-gray-800';
                            $checkInBadgeText  = 'กำลังทำงาน';
                        } elseif (!empty($row['check_in']) && !empty($row['check_out'])) {
                            // มีครบทั้งเข้า-ออก
                            $workedSeconds = strtotime($row['check_out']) - strtotime($row['check_in']);
                            $workedHours   = $workedSeconds / 3600;

                            if ($workedHours < 9) {
                                // ทำงานไม่ถึง 9 ชม.
                                $checkInBadgeClass = 'bg-red-100 text-red-800';
                            } elseif (!empty($row['start_time'])) {
                                // ครบ 9 ชม. ขึ้นไป และมีเวลามาตรฐานเปรียบเทียบได้
                                $checkInTime = strtotime(date('H:i:s', strtotime($row['check_in'])));
                                $startWork   = strtotime(date('H:i:s', strtotime($row['start_time'])));
                                if ($checkInTime <= $startWork) {
                                    $checkInBadgeClass = 'bg-green-100 text-green-800';
                                } else {
                                    $checkInBadgeClass = 'bg-blue-100 text-blue-800';
                                }
                            }
                        }
                        ?>
                        <tr class="history-row  " data-status="<?= htmlspecialchars($row['status']) ?>">
                            <td class="px-4 py-3 text-center"><?= $i ?></td>
                            <td class="px-4 py-3 text-center"><?= htmlspecialchars($employee['emp_no']) ?></td>
                            <td class="px-4 py-3 text-center">
                                <?= htmlspecialchars($employee['emp_name_th'] . ' ' . $employee['emp_sname_th']) ?>
                            </td>
                            <td class="px-4 py-3 text-center"><?= htmlspecialchars($row['date']) ?></td>
                            <td class="px-4 py-3 text-center "><?= $row['check_in'] ? htmlspecialchars($row['check_in']) : '-' ?></td>
                            <td class="px-4 py-3 text-center"><?= $row['check_out'] ? htmlspecialchars($row['check_out']) : '-' ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($isOnLeave): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <?= htmlspecialchars($row['leave_type_name']) ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($checkInBadgeText !== ''): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $checkInBadgeClass ?>">
                                        <?= htmlspecialchars($checkInBadgeText) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between px-5 py-3 border-t border-default-medium text-xs text-body flex-wrap gap-2">
            <span class="text-xs text-body shrink-0"><?= (int) $totalRecords ?> รายการ</span>

            <?php
            // สร้าง query string เดิมไว้ใช้ต่อท้ายทุกลิงก์เปลี่ยนหน้า (คง filter เดือน/สถานะที่เลือกอยู่ไว้)
            $pageQuery = $_GET;
            ?>

            <div class="flex items-center gap-1">
                <?php $pageQuery['page'] = max(1, (int)$currentPage - 1); ?>
                <a href="?<?= http_build_query($pageQuery) ?>"
                    class="px-2 py-1 rounded hover:bg-neutral-secondary-medium <?= $currentPage <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">‹</a>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <?php $pageQuery['page'] = $p; ?>
                    <a href="?<?= http_build_query($pageQuery) ?>"
                        class="px-2 py-1 rounded <?= $p === $currentPage ? 'bg-primary-700 text-white' : 'hover:bg-neutral-secondary-medium' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <?php $pageQuery['page'] = min($totalPages, (int)$currentPage + 1); ?>
                <a href="?<?= http_build_query($pageQuery) ?>"
                    class="px-2 py-1 rounded hover:bg-neutral-secondary-medium <?= $currentPage >= $totalPages ? 'opacity-40 pointer-events-none' : '' ?>">›</a>
            </div>
        </div>
    </div>

</div>

<script>
    // กรองตารางตามสถานะ (client-side)
    function filterHistoryByStatus(status) {
        document.querySelectorAll('#historyTableBody .history-row').forEach(function(row) {
            var match = (status === 'all') || (row.dataset.status === status);
            row.style.display = match ? '' : 'none';
        });
    }
</script>