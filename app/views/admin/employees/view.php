<?php
// views/profile/show.php
// หน้าที่: การ์ดข้อมูลตัวเอง (ดูอย่างเดียว ไม่มีปุ่มแก้ไข/ลบ)
// รับ $employee, $contract มาจาก ProfileController::show() ($contract อาจเป็น null)
//
// Hallmark redesign · macrostructure: Bento Grid · tone: utilitarian
// pre-emit critique: P4 H4 E4 S4 R5 V4
$employee = $employee ?? [];
$contract = $contract ?? [];
$contact = $contact ?? [];
$position = $position ?? [];
$role = $role ?? [];
$login = $login ?? [];

// helper สำหรับ View: escape output + ตรวจค่าว่าง (กัน null / undefined index)
$h = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
$blank = fn($v) => trim((string) ($v ?? '')) === '';

$fullName_th = trim(preg_replace(
    '/\s+/u',
    ' ',
    ($employee['emp_prefix_th'] ?? '') . ' ' .
        ($employee['emp_name_th'] ?? '') . ' ' .
        ($employee['emp_sname_th'] ?? '')
));

$fullName_en = trim(preg_replace(
    '/\s+/u',
    ' ',
    ($employee['emp_prefix_en'] ?? '') . ' ' .
        ($employee['emp_name_en'] ?? '') . ' ' .
        ($employee['emp_sname_en'] ?? '')
));

$tenureText = '-';
if (!empty($contract['cont_start_date'])) {
    $startDate = new DateTime($contract['cont_start_date']);
    $now = new DateTime();
    $diff = $startDate->diff($now);
    $tenureText = $diff->y . ' ปี ' . $diff->m . ' เดือน';
}

// ตัวอักษรย่อบนรูปโปรไฟล์ (กรณีไม่มีรูป)
$initials = '';
$nameParts = preg_split('/\s+/u', $fullName_th, -1, PREG_SPLIT_NO_EMPTY);
foreach (array_slice($nameParts, 0, 2) as $part) {
    // ตัดสระนำ เ แ โ ใ ไ ออก → ได้พยัญชนะตัวแรก
    $part = preg_replace('/^[เแโใไ]+/u', '', $part);
    $initials .= mb_substr($part, 0, 1, 'UTF-8');
}
$initials = mb_strtoupper($initials, 'UTF-8');

// เลขบัตรประชาชน: ซ่อนกลางเป็นค่าเริ่มต้น (ตัวแรก + ตัวท้าย ยังเห็น)
$idcard = trim((string) ($employee['emp_idcard'] ?? ''));
$idcardMasked = $idcard === '' ? '' : (mb_strlen($idcard, 'UTF-8') > 2
    ? mb_substr($idcard, 0, 1, 'UTF-8') . str_repeat('•', mb_strlen($idcard, 'UTF-8') - 2) . mb_substr($idcard, -1, 1, 'UTF-8')
    : $idcard);

// ---- จัดกลุ่มฟิลด์: [label, value] ----
// ค่าว่างแสดงเป็น "-" ในตำแหน่งเดิม (ไม่ซ่อน ไม่ย้าย)
$rows = function (array $defs) use ($blank): array {
    $shown = [];
    foreach ($defs as [$label, $value]) {
        $shown[] = [$label, $blank($value) ? '' : (string) $value];
    }
    return $shown;
};

$personalRows = $rows([
    ['ชื่อ-สกุล (TH)', $fullName_th],
    ['ชื่อเล่น', $employee['emp_nickname_th'] ?? ''],
    ['ชื่อ-สกุล (EN)', $fullName_en],
    ['Nickname', $employee['emp_nickname_en'] ?? ''],
    ['วันเกิด', $employee['emp_birthday'] ?? ''],
    ['เลขประกันสังคม', $employee['emp_idss'] ?? ''],
]);

$contactRows = $rows([
    ['เบอร์โทร', $employee['emp_tel'] ?? ''],
    ['อีเมล', $employee['emp_email'] ?? ''],
    ['LINE ID', $employee['emp_line'] ?? ''],
    ['ที่อยู่', $employee['emp_address'] ?? ''],
]);

$emergencyRows = $rows([
    ['ผู้ติดต่อฉุกเฉิน', $contact['name'] ?? ''],
    ['ความสัมพันธ์', $contact['relationship'] ?? ''],
    ['เบอร์โทรติดต่อฉุกเฉิน', $contact['tel'] ?? ''],
]);

$hasContract = !empty($contract);
$salaryRaw = $contract['cont_salary'] ?? '';
$salaryText = is_numeric($salaryRaw) ? number_format((float) $salaryRaw, 2) : (string) $salaryRaw;
$vacation = $contract['vacation_leave_days'] ?? '';

// แถว 2 กว้างรวม 8 คอลัมน์ เพราะการ์ดตัวตนกินคอลัมน์ซ้าย 2 แถว
?>

<div class="max-w-6xl mx-auto">
    <a href="/employees" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 hover:underline rounded-base">
        ← ย้อนกลับ
    </a>
    <h1 class="text-2xl font-bold tracking-tight text-heading mb-6">ข้อมูลของฉัน</h1>

    <div class=" grid grid-cols-1 lg:grid-cols-12 gap-4">

        <!-- ตัวตน : 4 คอลัมน์ × 2 แถว (จุดหนักของหน้า) -->
        <section class="lg:col-span-4 lg:row-span-2 bg-neutral-primary-soft border border-default rounded-base p-5" aria-labelledby="t-identity">
            <div class="flex flex-col items-center text-center">
                <?php if (!empty($employee['url'])) : ?>
                    <img src="/uploads/profile/<?= $h($employee['url']) ?>"
                        alt="<?= $h($fullName_th) ?>"
                        class="w-20 h-20 rounded-full object-cover ring-1 ring-gray-200">
                <?php else : ?>
                    <div class="flex items-center justify-center w-20 h-20 rounded-full bg-neutral-secondary-medium ring-1 ring-gray-200" aria-hidden="true">
                        <span class="text-2xl font-semibold text-heading"><?= $h($initials) ?></span>
                    </div>
                <?php endif; ?>

                <h2 id="t-identity" class="mt-4 text-lg font-semibold leading-snug text-heading [overflow-wrap:anywhere]"><?= $h($fullName_th) ?></h2>
                <?php if (!$blank($position['position_name'] ?? '')) : ?>
                    <p class="mt-0.5 text-sm text-body"><?= $h($position['position_name']) ?></p>
                <?php endif; ?>

                <p class="mt-3 inline-flex items-center gap-2 rounded-full border border-default-medium px-3 py-1 text-xs font-medium text-heading whitespace-nowrap">
                    <?php if (!Employee::isCancelled($employee)) : ?>
                        <span class="w-1.5 h-1.5 rounded-full bg-green-600" aria-hidden="true"></span>ทำงานอยู่
                    <?php else : ?>
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600" aria-hidden="true"></span>ยกเลิกแล้ว
                    <?php endif; ?>
                </p>
            </div>

            <dl class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-base bg-neutral-secondary-medium p-3">
                    <dt class="text-xs text-body">อายุงาน</dt>
                    <dd class="mt-1 text-base font-semibold leading-tight text-heading"><?= $h($tenureText) ?></dd>
                </div>
                <div class="rounded-base bg-neutral-secondary-medium p-3">
                    <dt class="text-xs text-body">ลาพักร้อน</dt>
                    <dd class="mt-1 text-base font-semibold leading-tight text-heading">
                        <?php if ($blank($vacation)) : ?>
                            <span class="text-body">-</span><span class="sr-only">ไม่มีข้อมูล</span>
                        <?php else : ?>
                            <?= $h($vacation) ?> <span class="text-sm font-normal text-body">วัน</span>
                        <?php endif; ?>
                    </dd>
                </div>
            </dl>

            <dl class="mt-5 divide-y divide-default-medium border-t border-default-medium text-sm">
                <div class="flex items-center justify-between gap-4 py-2.5">
                    <dt class="text-body">รหัสพนักงาน</dt>
                    <dd class="font-medium tabular-nums text-heading"><?= $h($employee['emp_no'] ?? '-') ?></dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-2.5">
                    <dt class="text-body">Username</dt>
                    <dd class="font-medium text-heading [overflow-wrap:anywhere]"><?= $h($login['username'] ?? '-') ?></dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-2.5">
                    <dt class="text-body">ระดับผู้ใช้งาน</dt>
                    <dd class="font-medium text-heading"><?= $h($role['role_name'] ?? '-') ?></dd>
                </div>
            </dl>
        </section>

        <!-- ข้อมูลส่วนตัว : 8 คอลัมน์ -->
        <section class="lg:col-span-8 bg-neutral-primary-soft border border-default rounded-base p-5" aria-labelledby="t-personal">
            <h2 id="t-personal" class="text-sm font-semibold text-heading">ข้อมูลส่วนตัว</h2>
            <div class="-mx-5 mt-3 h-px bg-default rounded-full" aria-hidden="true"></div>

            <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <?php foreach ($personalRows as [$label, $value]) : ?>
                    <div class="min-w-0">
                        <dt class="text-sm text-body"><?= $h($label) ?></dt>
                        <dd class="mt-0.5 text-sm font-medium text-heading [overflow-wrap:anywhere]">
                            <?= $value === '' ? '<span class="text-body">-</span>' : $h($value) ?>
                        </dd>
                    </div>
                <?php endforeach; ?>

                <div class="min-w-0">
                    <dt class="text-sm text-body">เลขบัตรประชาชน</dt>
                    <dd class="mt-0.5 flex items-center gap-1.5">
                        <?php if ($idcard === '') : ?>
                            <span class="text-sm font-medium text-body">-</span>
                        <?php else : ?>
                            <span id="idcard-value" class="text-sm font-medium tabular-nums tracking-wide text-heading"
                                data-full="<?= $h($idcard) ?>" data-masked="<?= $h($idcardMasked) ?>"><?= $h($idcardMasked) ?></span>
                            <button type="button" data-reveal-target="idcard-value" aria-pressed="false" aria-label="แสดงหรือซ่อนเลขบัตรประชาชน"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-base text-body hover:bg-neutral-secondary-medium focus:outline-none focus:ring-2 focus:ring-brand">
                                <svg class="ic-eye w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                                </svg>
                                <svg class="ic-eye-off w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M3 3l18 18M10.6 5.1A10 10 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 4M6.5 6.6C3.8 8.3 2 12 2 12s3.6 7 10 7c1.6 0 3-.4 4.2-1M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </dd>
                </div>
            </dl>
        </section>

        <!-- ข้อมูลติดต่อ -->
        <section class="lg:col-span-4 bg-neutral-primary-soft border border-default rounded-base p-5" aria-labelledby="t-contact">
            <h2 id="t-contact" class="text-sm font-semibold text-heading">ช่องทางติดต่อ</h2>
            <div class="-mx-5 mt-3 h-px bg-default rounded-full" aria-hidden="true"></div>
            <dl class="mt-4 space-y-4">
                <?php foreach ($contactRows as [$label, $value]) : ?>
                    <div class="min-w-0">
                        <dt class="text-sm text-body"><?= $h($label) ?></dt>
                        <dd class="mt-0.5 text-sm font-medium text-heading [overflow-wrap:anywhere]">
                            <?= $value === '' ? '<span class="text-body">-</span>' : $h($value) ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>

        <!-- ผู้ติดต่อฉุกเฉิน -->
        <section class="lg:col-span-4 bg-neutral-primary-soft border border-default rounded-base p-5" aria-labelledby="t-emergency">
            <h2 id="t-emergency" class="text-sm font-semibold text-heading">ข้อมูลติดต่อฉุกเฉิน</h2>
            <div class="-mx-5 mt-3 h-px bg-default rounded-full" aria-hidden="true"></div>

            <dl class="mt-4 space-y-4">
                <?php foreach ($emergencyRows as [$label, $value]) : ?>
                    <div class="min-w-0">
                        <dt class="text-sm text-body"><?= $h($label) ?></dt>
                        <dd class="mt-0.5 text-sm font-medium text-heading [overflow-wrap:anywhere]">
                            <?= $value === '' ? '<span class="text-body">-</span>' : $h($value) ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>

        <!-- ข้อมูลสัญญาจ้าง (รวมเงินเดือน) : เต็มความกว้าง -->
        <?php if ($hasContract) : ?>
            <section class="lg:col-span-12 bg-neutral-primary-soft border border-default rounded-base p-5" aria-labelledby="t-contract">
                <h2 id="t-contract" class="text-sm font-semibold text-heading">ข้อมูลสัญญาจ้าง</h2>
                <div class="-mx-5 mt-3 h-px bg-default rounded-full" aria-hidden="true"></div>

                <dl class="mt-4 grid grid-cols-1 sm:grid-cols-4 gap-x-8 gap-y-4">
                    <div class="min-w-0">
                        <dt class="text-sm text-body">ตำแหน่ง</dt>
                        <dd class="mt-0.5 text-sm font-medium text-heading"><?= $h($position['position_name'] ?? '-') ?></dd>
                    </div>
                    <div class="min-w-0">
                        <dt class="text-sm text-body">วันที่เริ่มงาน</dt>
                        <dd class="mt-0.5 text-sm font-medium text-heading"><?= $h($contract['cont_start_date'] ?? '-') ?></dd>
                    </div>
                    <div class="min-w-0">
                        <dt class="text-sm text-body">สถานะสัญญา</dt>
                        <dd class="mt-0.5">
                            <?php if ($blank($contract['cont_status'] ?? '')) : ?>
                                <span class="text-sm font-medium text-body">-</span>
                            <?php else : ?>
                                <span class="inline-flex items-center rounded-full bg-neutral-secondary-medium px-2.5 py-0.5 text-xs font-medium text-heading whitespace-nowrap"><?= $h($contract['cont_status']) ?></span>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="min-w-0">
                        <dt class="text-sm text-body">เงินเดือน</dt>
                        <dd class="mt-0.5 flex items-center gap-1.5 text-heading">
                            <?php if ($blank($salaryRaw)) : ?>
                                <span class="text-sm font-medium text-body">-</span>
                            <?php else : ?>
                                <span class="text-base font-semibold tabular-nums [overflow-wrap:anywhere]">
                                    <span id="salary-value" data-full="<?= $h($salaryText) ?>" data-masked="••••••">••••••</span>
                                    <span class="ml-0.5 text-sm font-normal text-body">บาท</span>
                                </span>
                                <button type="button" data-reveal-target="salary-value" aria-pressed="false" aria-label="แสดงหรือซ่อนเงินเดือน"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded-base text-body hover:bg-neutral-secondary-medium focus:outline-none focus:ring-2 focus:ring-brand">
                                    <svg class="ic-eye w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                                    </svg>
                                    <svg class="ic-eye-off w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M3 3l18 18M10.6 5.1A10 10 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 4M6.5 6.6C3.8 8.3 2 12 2 12s3.6 7 10 7c1.6 0 3-.4 4.2-1M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </dd>
                    </div>
                </dl>
            </section>
        <?php endif; ?>

        <!-- <p class="text-sm text-gray-400">
        ถ้าข้อมูลไม่ถูกต้อง กรุณาติดต่อฝ่ายบุคคลเพื่อแก้ไข 
    </p> -->

    </div>
</div>

<script>
    // ปุ่มแสดง/ซ่อนข้อมูลสำคัญ (เลขบัตรประชาชน, เงินเดือน)
    // ปุ่มระบุเป้าหมายด้วย data-reveal-target="<id ของ span>" ; ค่าเต็ม/ค่าซ่อนอยู่ใน data-full / data-masked
    document.querySelectorAll('[data-reveal-target]').forEach(function(btn) {
        var val = document.getElementById(btn.dataset.revealTarget);
        if (!val) return;
        var shown = false;
        btn.addEventListener('click', function() {
            shown = !shown;
            val.textContent = shown ? val.dataset.full : val.dataset.masked;
            btn.querySelector('.ic-eye').classList.toggle('hidden', shown);
            btn.querySelector('.ic-eye-off').classList.toggle('hidden', !shown);
            btn.setAttribute('aria-pressed', String(shown));
        });
    });
</script>