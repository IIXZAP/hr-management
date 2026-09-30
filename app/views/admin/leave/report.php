<?php
// views/leave/report.php
// หน้าที่: รายงานสรุปวันลา แยกแท็บตามพนักงาน
// รับ $employees, $reportData, $year, $yearOptions มาจาก LeaveReportController::index()
// ประเภทลาที่จะโชว์เป็นคอลัมน์ในตาราง — ดึงจาก DB จริง (ตาราง leave_types)
// ไม่ hardcode แล้ว เพราะ admin เพิ่ม/ลบประเภทลาเองผ่านหน้า /leave-types ได้
// ถ้า hardcode ไว้ พอ admin เพิ่มประเภทใหม่ ตารางรายงานนี้จะไม่โชว์คอลัมน์ใหม่จนกว่าจะแก้โค้ดเอง
$leaveTypeColumns = array_column($leaveTypes, 'leave_type_name');
?>

<h1 class="text-2xl font-bold text-gray-900 mb-4">สรุปวันลา</h1>

<!-- <select onchange="location.href = '/leave/report?year=' + this.value" class="mb-4 border border-gray-300 rounded-lg p-2 text-sm">
    <?php foreach ($yearOptions as $yearOption): ?>
        <option value="<?php echo $yearOption; ?>" <?php echo $yearOption === $year ? 'selected' : ''; ?>>
            <?php echo $yearOption; ?>
        </option>
    <?php endforeach; ?>
</select> -->

<div class="flex items-center gap-2 flex-wrap">
    <select id="filterYear"
        onchange="location.href = '/leave/report?year=' + this.value"
        class="text-sm font-medium border  rounded-full pl-4 pr-8 py-1.5 bg-neutral-secondary-medium text-heading focus:ring-2  cursor-pointer">
        <?php
        $currentYear = (int) date('Y');
        $selectedYear = (int) ($_GET['year'] ?? $currentYear);
        for ($y = $currentYear; $y >= $currentYear - 5; $y--):
        ?>
            <option value="<?= $y ?>" <?= $y === $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
    </select>
</div>

<!-- แถบแท็บรายชื่อพนักงาน -->
<?php if ($isAdmin === true): ?>
    <div class="flex gap-1 border-b border-gray-200 mb-4 overflow-x-auto" id="empTabBar">
        <?php foreach ($employees as $index => $employee): ?>
            <button type="button" onclick="switchEmpTab(<?php echo $employee['emp_id']; ?>)"
                data-emp-tab="<?php echo $employee['emp_id']; ?>"
                class="emp-tab-btn shrink-0 px-4 py-2 text-sm font-medium border-b-2 <?php echo $index === 0 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'; ?>">
                <?php echo htmlspecialchars($employee['emp_nickname_th']); ?>
            </button>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<?php foreach ($employees as $index => $employee): ?>
    <?php
    $empId = $employee['emp_id'];
    $data = $reportData[$empId];
    $vacation = $data['vacation'];
    ?>

    <div id="emp-panel-<?php echo $empId; ?>" class="emp-tab-panel <?php echo $index === 0 ? '' : 'hidden'; ?>">

        <!-- ตารางประวัติการลา -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto mb-4">
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
        <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
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
        </div>

    </div>
<?php endforeach; ?>

<script>
    function switchEmpTab(empId) {
        document.querySelectorAll('.emp-tab-panel').forEach(function(el) {
            el.classList.add('hidden');
        });
        document.getElementById('emp-panel-' + empId).classList.remove('hidden');

        document.querySelectorAll('.emp-tab-btn').forEach(function(btn) {
            if (parseInt(btn.dataset.empTab, 10) === empId) {
                btn.classList.add('border-blue-600', 'text-blue-600');
                btn.classList.remove('border-transparent', 'text-gray-500');
            } else {
                btn.classList.remove('border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            }
        });
    }
</script>