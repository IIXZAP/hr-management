<?php
// views/leave/create.php
// หน้าที่: ฟอร์มยื่นขอลาใหม่
// รับ $leaveTypes มาจาก LeaveController::create()
// ไม่มีช่อง emp_id ในฟอร์ม — Controller ใช้ Auth::empId() ของคนที่ login เองเสมอ (กัน security bug)
// แก้ตาม schema จริง: วันลาเป็นวันเดียว (leave_date) ไม่ใช่ช่วง + leave_day (เต็มวัน/เช้า/บ่าย)
?>

<h1 class="text-2xl font-bold text-gray-900 mb-4">ยื่นขอลาใหม่</h1>

<form action="/leave/store" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4 max-w-md">
    <?php if (!empty($isAdmin)): ?>
        <div>
            <label for="emp_id" class="block text-sm text-gray-600 mb-1">พนักงาน</label>
            <select id="emp_id" name="emp_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                <option value="">-- เลือกพนักงาน --</option>
                <?php foreach ($employees as $emp): ?>
                    <option value="<?php echo (int) $emp['emp_id']; ?>">
                        <?php echo htmlspecialchars($emp['emp_no'] . ' - ' . $emp['emp_prefix_th'] . $emp['emp_name_th'] . ' ' . $emp['emp_sname_th']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
    <div>
        <label for="leave_type_id" class="block text-sm text-gray-600 mb-1">ประเภทการลา</label>
        <select id="leave_type_id" name="leave_type_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
            <option value="">-- เลือกประเภท --</option>
            <?php foreach ($leaveTypes as $leaveType): ?>
                <option value="<?php echo $leaveType['leave_type_id']; ?>">
                    <?php echo htmlspecialchars($leaveType['leave_type_name']); ?>
                    <?php if ($leaveType['max_days_per_year'] !== null): ?>
                        (สิทธิ์ <?php echo $leaveType['max_days_per_year']; ?> วัน/ปี)
                    <?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="leave_date" class="block text-sm text-gray-600 mb-1">วันที่ลา</label>
        <input type="date" id="leave_date" name="leave_date" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="leave_day" class="block text-sm text-gray-600 mb-1">ช่วงเวลา</label>
            <select id="leave_day" name="leave_day" onchange="updateLeaveDays()" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                <option value="เต็มวัน" data-days="1">เต็มวัน</option>
                <option value="ครึ่งเช้า" data-days="0.5">ครึ่งวันเช้า</option>
                <option value="ครึ่งบ่าย" data-days="0.5">ครึ่งวันบ่าย</option>
            </select>
        </div>
        <div>
            <label for="leave_days" class="block text-sm text-gray-600 mb-1">จำนวนวันลา</label>
            <input type="number" step="0.5" min="0.5" id="leave_days" name="leave_days" value="1" readonly
                class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-gray-50">
        </div>
    </div>

    <div>
        <label for="leave_comment" class="block text-sm text-gray-600 mb-1">หมายเหตุ (ไม่บังคับ)</label>
        <textarea id="leave_comment" name="leave_comment" rows="3" class="w-full border border-gray-300 rounded-lg p-2 text-sm"></textarea>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">ส่งคำขอ</button>
        <a href="/leave" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">ยกเลิก</a>
    </div>

</form>

<script>
    // เลือก "เต็มวัน" -> leave_days = 1 อัตโนมัติ, เลือก "เช้า/บ่าย" -> leave_days = 0.5 อัตโนมัติ
    // readonly ไว้กันพิมพ์ผิด (เช่น เลือกครึ่งวันแต่ใส่ 1 วันเอง ทำให้ข้อมูลขัดแย้งกัน)
    function updateLeaveDays() {
        var select = document.getElementById('leave_day');
        var selectedOption = select.options[select.selectedIndex];
        var days = selectedOption.getAttribute('data-days');

        document.getElementById('leave_days').value = days;
    }
</script>