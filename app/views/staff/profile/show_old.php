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
                class="w-28 h-28 rounded-full object-cover mx-auto ring-2 ring-gray-200">
        <?php else : ?>
            <?php
            $initials = '';
            $nameParts = preg_split('/\s+/u', trim($fullName_th), -1, PREG_SPLIT_NO_EMPTY);
            foreach (array_slice($nameParts, 0, 2) as $part) {
                // ตัดสระนำ เ แ โ ใ ไ ออก → ได้พยัญชนะตัวแรก
                $part = preg_replace('/^[เแโใไ]+/u', '', $part);
                $initials .= mb_substr($part, 0, 1, 'UTF-8');
            }
            $initials = mb_strtoupper($initials, 'UTF-8');
            ?>
            <div class="relative flex items-center justify-center w-28 h-28 mx-auto overflow-hidden bg-neutral-tertiary rounded-full ring-2 ring-gray-200">
                <span class="font-medium text-body text-3xl"><?php echo htmlspecialchars($initials); ?></span>
            </div>
        <?php endif; ?>
        <p class="mt-4 font-semibold text-gray-900"><?php echo htmlspecialchars($fullName_th); ?></p>
        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($position['position_name'] ?? '-'); ?></p>

        <div class="flex items-center justify-center gap-2 mt-2">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white whitespace-nowrap">
                อายุงาน <?php echo htmlspecialchars($tenureText); ?>
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white whitespace-nowrap">
                ลาพักร้อน <?php echo htmlspecialchars($contract['vacation_leave_days'] ?? '-'); ?> วัน
            </span>
        </div>

        <div class="border-t mt-3 mb-3 border-gray-200"></div>

        <dl class="text-sm text-left space-y-3">
            <div>
                <dt class="text-gray-500">รหัสพนักงาน</dt>
                <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_no']); ?></dd>
            </div>
            <div>
                <dt class="text-gray-500">ระดับผู้ใช้งาน</dt>
                <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($role['role_name'] ?? '-'); ?></dd>
            </div>
            <div>
                <dt class="text-gray-500">สถานะพนักงาน</dt>
                <dd class="font-medium">
                    <?php if (!Employee::isCancelled($employee)): ?>
                        <span class="inline-flex items-center gap-1.5 text-green-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            ทำงานอยู่
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 text-red-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            ยกเลิกแล้ว
                        </span>
                    <?php endif; ?>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">วันที่เริ่มงาน</dt>
                <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($contract['cont_start_date'] ?? '-'); ?></dd>
            </div>
        </dl>

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
                        <?php echo htmlspecialchars($employee['emp_prefix_en'] ?? '-'); ?>
                        <?php echo htmlspecialchars($employee['emp_name_en'] ?? ' '); ?>
                        <?php echo htmlspecialchars($employee['emp_sname_en'] ?? ' '); ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Nickname</dt>
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_nickname_en'] ?? '-'); ?></dd>
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
                    <dd class="text-gray-900 font-medium"><?php echo htmlspecialchars($employee['emp_line'] ?? '-'); ?></dd>
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