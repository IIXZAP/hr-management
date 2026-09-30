<?php
// views/attendance/approve.php
// หน้าที่: แสดงรายการเวลาที่รออนุมัติ (admin กดอนุมัติทีละแถว)
// รับ $pendingList มาจาก AttendanceController::approve()
?>

<h1 class="text-2xl font-bold text-gray-900 mb-4">อนุมัติเวลาเข้า-ออกงาน</h1>

<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3">รหัสพนักงาน</th>
                <th class="px-4 py-3">ชื่อ-สกุล</th>
                <th class="px-4 py-3">วันที่</th>
                <th class="px-4 py-3">เข้างาน</th>
                <th class="px-4 py-3">ออกงาน</th>
                <th class="px-4 py-3">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">

            <?php if (count($pendingList) === 0): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">ไม่มีรายการรออนุมัติ</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($pendingList as $item): ?>
                <tr>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($item['emp_no']); ?></td>
                    <td class="px-4 py-3">
                        <?php echo htmlspecialchars($item['emp_name_th']); ?>
                        <?php echo htmlspecialchars($item['emp_sname_th']); ?>
                    </td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($item['work_date']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($item['check_in']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($item['check_out']); ?></td>
                    <td class="px-4 py-3">
                        <form action="/attendance/approve" method="POST" class="inline">
                            <input type="hidden" name="att_id" value="<?php echo $item['att_id']; ?>">
                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                                อนุมัติ
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>