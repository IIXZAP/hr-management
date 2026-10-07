<?php
// views/leave/report.php (staff)
// หน้าที่: รายงานสรุปวันลาของตัวเอง (staff เห็นได้แค่ข้อมูลตัวเอง ไม่มีแท็บสลับพนักงาน)
// รับ $employees (มีแค่ 1 คน = ตัวเอง), $reportData, $year, $yearOptions มาจาก LeaveReportController::index()
// ประเภทลาที่จะโชว์เป็นคอลัมน์ในตาราง — ดึงจาก DB จริง (ตาราง leave_types)
// ไม่ hardcode แล้ว เพราะ admin เพิ่ม/ลบประเภทลาเองผ่านหน้า /leave-types ได้
// ถ้า hardcode ไว้ พอ admin เพิ่มประเภทใหม่ ตารางรายงานนี้จะไม่โชว์คอลัมน์ใหม่จนกว่าจะแก้โค้ดเอง
$leaveTypeColumns = array_column($leaveTypes ?? [], 'leave_type_name');
$employees = $employees ?? [];
?>

<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-4">สรุปวันลา</h1>



    <?php foreach ($employees as $employee): ?>
        <?php
        $empId = $employee['emp_id'];
        $data = $reportData[$empId];
        $vacation = $data['vacation'];
        ?>

        <div class="grid grid-cols-4 gap-4 mb-6">

            <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-body">วันพักร้อนคงเหลือ</p>
                    <svg class="w-4 h-4 text-body" viewBox="0 0 24 24" fill="none">
                        <path d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <p class="text-xl font-bold text-heading"><?= (int) ($vacation['remaining_this_year'] ?? 0) ?> วัน</p>
                <!-- <p class="text-xs text-body mt-2">ปี <?= htmlspecialchars(date('Y') + 543) ?></p> -->
                <!-- <p class="text-xs text-body">ใช้สิทธิ์ก่อน 10 วัน</p> -->
            </div>



        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
            <div class="flex items-center justify-between px-5 py-4 border-b border-default-medium flex-wrap gap-3">
                <h2 class=" font-semibold text-heading">วันลาของฉัน</h2>
                <div class="flex items-center gap-2 flex-wrap">
                    <input type="month" id="filterMonth"
                        value="<?= htmlspecialchars($_GET['month'] ?? date('Y-m')) ?>"
                        onchange="location.href = '/leave/report?month=' + this.value"
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

                <!-- <select onchange="location.href = '/leave/report?year=' + this.value"
                class="border border-default-medium rounded-base w-24 px-3 py-2 text-sm bg-neutral-secondary-medium text-heading focus:ring-brand focus:border-brand">
                <?php foreach ($yearOptions as $yearOption): ?>
                    <option value="<?php echo (int) $yearOption; ?>" <?php echo ((int) $yearOption === (int) $year) ? 'selected' : ''; ?>>
                        <?php echo (int) $yearOption; ?>
                    </option>
                <?php endforeach; ?>
            </select> -->
            </div>
            <!-- ตารางประวัติการลา -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto ">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">วันที่</th>
                            <th class="px-4 py-3">รายละเอียด</th>
                            <?php foreach ($leaveTypeColumns as $col): ?>
                                <th class="px-4 py-3"><?php echo htmlspecialchars($col); ?></th>
                            <?php endforeach; ?>
                            <th class="px-4 py-3">หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        <?php if (count($data['history']) === 0): ?>
                            <tr>
                                <td colspan="<?php echo 3 + count($leaveTypeColumns); ?>" class="px-4 py-6 text-center text-gray-400">
                                    ไม่มีประวัติการลาปีนี้
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($data['history'] as $record): ?>
                            <?php $isPending = ($record['leave_status'] !== 'approved'); ?>
                            <tr class="<?php echo $isPending ? 'text-gray-400' : ''; ?>">
                                <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($record['leave_date'])); ?></td>
                                <td class="px-4 py-3">
                                    <?php echo htmlspecialchars($record['leave_type_name']); ?>
                                    (<?php echo htmlspecialchars($record['leave_day']); ?>)
                                </td>
                                <?php foreach ($leaveTypeColumns as $col): ?>
                                    <td class="px-4 py-3">
                                        <?php echo ($record['leave_type_name'] === $col) ? htmlspecialchars($record['leave_days']) : ''; ?>
                                    </td>
                                <?php endforeach; ?>
                                <td class="px-4 py-3">
                                    <?php echo $record['leave_comment'] ? htmlspecialchars($record['leave_comment']) : '-'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                    <tfoot class="bg-gray-100 font-medium">
                        <tr>
                            <td class="px-4 py-3">รวม</td>
                            <td class="px-4 py-3"></td>
                            <?php foreach ($leaveTypeColumns as $col): ?>
                                <td class="px-4 py-3"><?php echo $data['totals'][$col] ?? 0; ?></td>
                            <?php endforeach; ?>
                            <td class="px-4 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- สรุปสิทธิ์วันลาพักร้อน -->
            <!-- <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3"></th>
                        <th class="px-4 py-3">สิทธิ์ทั้งหมด</th>
                        <th class="px-4 py-3">ที่ใช้ไป</th>
                        <th class="px-4 py-3">คงเหลือ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-4 py-3 font-medium">วันลาพักร้อนปีนี้</td>
                        <td class="px-4 py-3"><?php echo $vacation['vacation_this_year']; ?></td>
                        <td class="px-4 py-3"><?php echo $vacation['used_this_year']; ?></td>
                        <td class="px-4 py-3"><?php echo $vacation['remaining_this_year']; ?></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-medium">วันลาสะสม</td>
                        <td class="px-4 py-3"><?php echo $vacation['carried_over']; ?></td>
                        <td class="px-4 py-3"><?php echo $vacation['used_carried_over']; ?></td>
                        <td class="px-4 py-3"><?php echo $vacation['remaining_carried_over']; ?></td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-100 font-medium">
                    <tr>
                        <td class="px-4 py-3">สรุป</td>
                        <td class="px-4 py-3"><?php echo $vacation['total_available']; ?></td>
                        <td class="px-4 py-3"><?php echo $vacation['total_used']; ?></td>
                        <td class="px-4 py-3"><?php echo $vacation['total_remaining']; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div> -->

        </div>
    <?php endforeach; ?>
</div>