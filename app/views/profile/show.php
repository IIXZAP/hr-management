<?php
// views/profile/show.php
// หน้าที่: การ์ดข้อมูลตัวเอง (ดูอย่างเดียว ไม่มีปุ่มแก้ไข/ลบ)
// รับ $employee, $contract มาจาก ProfileController::show() ($contract อาจเป็น null)
?>

<h1 class="text-2xl font-bold text-gray-900 mb-6">ข้อมูลของฉัน</h1>

<div class="max-w-2xl space-y-4">

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">ข้อมูลส่วนตัว</h2>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">รหัสพนักงาน</dt>
                <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_no']); ?></dd>
            </div>
            <div>
                <dt class="text-gray-500">ชื่อ-สกุล</dt>
                <dd class="text-gray-900 font-medium">
                    <?php echo htmlspecialchars($employee['emp_prefix_th']); ?>
                    <?php echo htmlspecialchars($employee['emp_name_th']); ?>
                    <?php echo htmlspecialchars($employee['emp_sname_th']); ?>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">เบอร์โทร</dt>
                <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_tel']); ?></dd>
            </div>
            <div>
                <dt class="text-gray-500">อีเมล</dt>
                <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_email']); ?></dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-gray-500">ที่อยู่</dt>
                <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_address']); ?></dd>
            </div>
        </dl>
    </div>

    <?php if ($contract !== null): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">ข้อมูลสัญญาจ้าง</h2>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">ตำแหน่ง</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contract['cont_position']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">วันที่เริ่มงาน</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contract['cont_start_date']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">สถานะสัญญา</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contract['cont_status']); ?></dd>
                </div>
            </dl>
        </div>
    <?php endif; ?>

    <p class="text-sm text-gray-400">
        ถ้าข้อมูลไม่ถูกต้อง กรุณาติดต่อฝ่ายบุคคลเพื่อแก้ไข (หน้านี้ดูได้อย่างเดียว)
    </p>

</div>