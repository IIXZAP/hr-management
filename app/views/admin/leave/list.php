<?php
// views/leave/list.php
// หน้าที่: แสดงรายการคำขอลา
// รับ $leaves มาจาก LeaveController::index() (admin ได้ทุกคน, พนักงานทั่วไปได้แค่ของตัวเอง)
$isAdmin = Auth::isAdmin();
$leaves = $leaves ?? '';
$i = 1;

$statusLabel = [
    'pending'  => 'รอพิจารณา',
    'approved' => 'อนุมัติ',
    'rejected' => 'ปฏิเสธ',
];

$statusColor = [
    'pending'  => 'bg-yellow-100 text-yellow-700',
    'approved' => 'bg-green-100 text-green-700',
    'rejected' => 'bg-red-100 text-red-700',
];
?>
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-4">
        <?php echo $isAdmin === true ? 'คำขอลาทั้งหมด' : 'ประวัติการลาของฉัน'; ?>
    </h1>
    <div class="flex justify-end">
        <a href="/leave/create" class="inline-block mb-4 px-4 py-2 bg-brand text-white rounded-lg text-sm hover:bg-blue-800">
            + เพิ่มวันลา
        </a>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
        <div class="p-5">
            <h2 class="text-lg font-medium text-heading">รายการคำขอ</h2>
            
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">รหัส</th>
                    <?php if ($isAdmin === true): ?>
                        <th class="px-4 py-3">ชื่อ</th>
                    <?php endif; ?>
                    <th class="px-4 py-3">วันที่</th>
                    <th class="px-4 py-3">ประเภทลา</th>
                    <th class="px-4 py-3">จำนวนวัน</th>
                    <th class="px-4 py-3">หมายเหตุ</th>
                    <th class="px-4 py-3">สถานะ</th>
                    <th class="px-4 py-3">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">

                <?php if (count($leaves) === 0): ?>
                    <tr>
                        <td colspan="<?php echo $isAdmin === true ? 7 : 6; ?>" class="px-4 py-6 text-center text-gray-400">
                            ยังไม่มีคำขอลา
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($leaves as $leave): ?>
                    <tr>
                        <td class="px-4 py-3"><?php echo $i; ?></td>
                        <td><?php echo htmlspecialchars(strtoupper($leave['emp_no'])); ?></td>
                        <?php if ($isAdmin === true): ?>
                            <td class="px-4 py-3">
                                <?php echo htmlspecialchars($leave['emp_name_th']); ?>
                                <?php echo htmlspecialchars($leave['emp_sname_th']); ?>
                                (<?php echo htmlspecialchars($leave['emp_nickname_th']); ?>)
                            </td>
                        <?php endif; ?>
                        <td class="px-4 py-3">
                            <?php echo htmlspecialchars($leave['leave_date']); ?>

                        </td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($leave['leave_type_name']); ?></td>

                        <td class="px-4 py-3"><?php echo htmlspecialchars($leave['leave_day']); ?></td>
                        <td class="px-4 py-3"><?php echo $leave['leave_comment'] ? htmlspecialchars($leave['leave_comment']) : '-'; ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs <?php echo $statusColor[$leave['leave_status']]; ?>">
                                <?php echo $statusLabel[$leave['leave_status']]; ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">

                            <?php if ($isAdmin === true && $leave['leave_status'] === 'pending'): ?>
                                <form action="/leave/approve" method="POST" class="inline">
                                    <input type="hidden" name="leave_id" value="<?php echo $leave['leave_id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="text-green-600 hover:underline text-xs">อนุมัติ</button>
                                </form>
                                <form action="/leave/approve" method="POST" class="inline">
                                    <input type="hidden" name="leave_id" value="<?php echo $leave['leave_id']; ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="text-red-600 hover:underline text-xs ml-2">ปฏิเสธ</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($isAdmin === false && $leave['leave_status'] === 'pending'): ?>
                                <form action="/leave/delete" method="POST" class="inline">
                                    <input type="hidden" name="leave_id" value="<?php echo $leave['leave_id']; ?>">
                                    <button type="submit" onclick="return confirm('ยกเลิกคำขอลานี้?');" class="text-red-600 hover:underline text-xs">
                                        ยกเลิก
                                    </button>
                                </form>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>