<?php
// views/profile/show.php
// หน้าที่: การ์ดข้อมูลตัวเอง (ดูอย่างเดียว ไม่มีปุ่มแก้ไข/ลบ)
// รับ $employee, $contract มาจาก ProfileController::show() ($contract อาจเป็น null)
$employee = $employee ?? [];
$contract = $contract ?? [];
$contact = $contact ?? [];

$fullName_th = trim(
    ($employee['emp_prefix_th'] ?? '') . ' ' .
        ($employee['emp_name_th'] ?? '') . ' ' .
        ($employee['emp_sname_th'] ?? '')
);

$fullName_en = trim(
    ($employee['emp_prefix_en'] ?? '') . ' ' .
        ($employee['emp_name_en'] ?? '') . ' ' .
        ($employee['emp_sname_en'] ?? '')
);

$tenureText = '-';
if (!empty($contract['cont_start_date'])) {
    $startDate = new DateTime($contract['cont_start_date']);
    $now = new DateTime();
    $diff = $startDate->diff($now);
    $tenureText = $diff->y . ' ปี ' . $diff->m . ' เดือน';
}

?>

<h1 class="text-2xl font-bold text-gray-900 mb-6">ข้อมูลของฉัน</h1>

<div class="max-w-6xl grid sm:grid-cols-[360px_1fr] gap-4 items-start">

    <!-- รูปโปรไฟล์ (ซ้าย) -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 text-center">
        <?php if (!empty($employee['url'])) : ?>
            <img src="/uploads/profile/<?php echo htmlspecialchars($employee['url']); ?>"
                alt="<?php echo htmlspecialchars($fullName_th); ?>"
                class=" h-28  object-cover mx-auto ">
        <?php else : ?>
            <img src="/assets/images/logo_ktn.webp"
                alt="<?php echo htmlspecialchars($fullName_th); ?>"
                class=" h-28  object-cover mx-auto ">
        <?php endif; ?>
        <p class="mt-4 font-semibold text-gray-900"><?php echo htmlspecialchars($fullName_th); ?></p>
        <!-- <p class="text-sm text-gray-500"><?php echo htmlspecialchars($position['position_name'] ?? '-'); ?></p> -->
        <p class="text-sm text-gray-500">ระดับผู้ใช้งาน : <?php echo htmlspecialchars($role['role_name'] ?? '-'); ?></p>
        <div class="flex items-center gap-2 mt-2 ">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white whitespace-nowrap">
                อายุงาน <?php echo htmlspecialchars($tenureText); ?>
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white whitespace-nowrap">
                ลาพักร้อน <?php echo htmlspecialchars($contract['vacation_leave_days'] ?? '-'); ?> วัน
            </span>
        </div>

    </div>

    <!-- การ์ดข้อมูล (ขวา) -->
    <div class="space-y-4">

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">ข้อมูลส่วนตัว</h2>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">รหัสพนักงาน</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_no']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">วันเกิด</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_birthday']); ?></dd>
                </div>

                <div>
                    <dt class="text-gray-500">ชื่อ-สกุล (TH)</dt>
                    <dd class="text-gray-900 font-medium">
                        <?php echo htmlspecialchars($employee['emp_prefix_th']); ?>
                        <?php echo htmlspecialchars($employee['emp_name_th']); ?>
                        <?php echo htmlspecialchars($employee['emp_sname_th']); ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">ชื่อเล่น</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_nickname_th']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">ชื่อ-สกุล (EN)</dt>
                    <dd class="text-gray-900 font-medium">
                        <?php echo htmlspecialchars($employee['emp_prefix_en']); ?>
                        <?php echo htmlspecialchars($employee['emp_name_en']); ?>
                        <?php echo htmlspecialchars($employee['emp_sname_en']); ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Nickname</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_nickname_en']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">เบอร์โทร</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_tel']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">อีเมล</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_email']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">LINE ID</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_line']); ?></dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">ที่อยู่</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_address']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">เลขบัตรประชาชน</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_idcard']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">เลขประกันสังคม</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_idss'] ?? '-'); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">USERNAME</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($login['username'] ?? '-'); ?></dd>
                </div>
            </dl>
        </div>

        <?php if ($contract !== null): ?>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-gray-900 mb-4">ข้อมูลสัญญาจ้าง</h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">ตำแหน่ง</dt>
                        <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($position['position_name'] ?? '-'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">วันที่เริ่มงาน</dt>
                        <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contract['cont_start_date']); ?></dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">สถานะสัญญา</dt>
                        <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contract['cont_status']); ?></dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">เงินเดือน</dt>
                        <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contract['cont_salary']) . ' '; ?>บาท</dd>
                    </div>
                </dl>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">ข้อมูลติดต่อ</h2>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">เบอร์โทรติดต่อฉุกเฉิน</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contact['tel'] ?? '-'); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">ผู้ติดต่อฉุกเฉิน</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contact['name']); ?></dd>
                </div>
                <div>
                    <dt class="text-gray-500">ความสัมพันธ์</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contact['relationship']); ?></dd>
                </div>
            </dl>
        </div>

        <!-- <p class="text-sm text-gray-400">
            ถ้าข้อมูลไม่ถูกต้อง กรุณาติดต่อฝ่ายบุคคลเพื่อแก้ไข 
        </p> -->

    </div>
</div>