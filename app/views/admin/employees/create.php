<?php
// views/employees/create-full.php
// หน้าที่: ฟอร์มเพิ่มพนักงานใหม่ ครบทุกตาราง (employees, employee_contact, employee_login, contract)
// แบ่งเป็น 4 แท็บ เพราะข้อมูลมาจากคนละตารางกัน อ่านง่ายกว่ายัดหน้าเดียวยาวๆ
//
// ตัวแปรที่ต้องได้จาก Controller:
//   $emp_no     — string|null รหัสพนักงานที่แนะนำ (ไม่บังคับ)
//   $positions  — array รายการตำแหน่ง แต่ละแถวมี position_id, position_name
//
// Hallmark redesign · macrostructure: Long Document · tone: utilitarian
// (รอบก่อน: Workbench → รอบนี้เปลี่ยนโครง: คอลัมน์เดียว + stepper แนวนอน)
// pre-emit critique: P4 H4 E4 S4 R5 V4

$empno = $emp_no ?? '';
$positions = $positions ?? [];

// class กลางของฟอร์ม — ใช้ token ชุด Flowbite เดียวกับหน้า dashboard
$fieldBase   = 'block w-full py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body';
$field       = $fieldBase . ' px-3';
$fieldIcon   = $fieldBase . ' pl-9 pr-3';
$label       = 'block mb-2 text-sm font-medium text-heading';
$btnGhost    = 'px-4 py-2.5 text-sm font-medium text-heading bg-neutral-primary-soft border border-default-medium rounded-base whitespace-nowrap hover:bg-neutral-secondary-medium focus:outline-none focus:ring-2 focus:ring-brand';
$btnPrimary  = 'inline-flex items-center gap-1.5 px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-base whitespace-nowrap hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2';
?>
<div class="max-w-5xl mx-auto">

    <!-- หัวหน้า : ลิงก์กลับ + ชื่อหน้า + ปุ่มทดสอบ (ปุ่มทดสอบ = เครื่องมือ dev ลดน้ำหนักเป็นลิงก์) -->
    <div class="mb-6">
        <a href="/employees" class="inline-flex items-center gap-1.5 text-sm font-medium text-body whitespace-nowrap hover:text-heading focus:outline-none focus:ring-2 focus:ring-brand rounded-base">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M19 12H5M11 5l-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            รายชื่อพนักงาน
        </a>
        <div class="mt-3 flex flex-wrap items-end justify-between gap-x-6 gap-y-2">
            <h1 class="text-2xl font-bold tracking-tight text-heading [overflow-wrap:anywhere]">เพิ่มพนักงานใหม่</h1>
            <button type="button" onclick="fillTestData()" class="text-sm font-medium text-body whitespace-nowrap underline underline-offset-4 hover:text-heading focus:outline-none focus:ring-2 focus:ring-brand rounded-base">
                กรอกข้อมูลทดสอบอัตโนมัติ
            </button>
        </div>
    </div>

    <div id="formCard" class="bg-neutral-primary-soft border border-default rounded-base">

        <!-- STEPPER แนวนอน (JS: updateStepper() ใช้ #tabBar > li[data-tab], .step-icon, .step-label) -->
        <div class="px-4 py-4 border-b border-default-medium">
            <ol id="tabBar" class="flex items-center gap-3" aria-label="ขั้นตอนการเพิ่มพนักงาน">
                <li class="flex flex-1 items-center gap-3 after:content-[''] after:flex-1 after:border-t after:border-default-medium" data-tab="tab-personal">
                    <span class="step-icon shrink-0 flex items-center justify-center w-8 h-8 rounded-full">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                        </svg>
                    </span>
                    <span class="step-label hidden sm:inline text-sm font-medium whitespace-nowrap">ข้อมูลส่วนตัว</span>
                </li>
                <li class="flex flex-1 items-center gap-3 after:content-[''] after:flex-1 after:border-t after:border-default-medium" data-tab="tab-contact">
                    <span class="step-icon shrink-0 flex items-center justify-center w-8 h-8 rounded-full">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                        </svg>
                    </span>
                    <span class="step-label hidden sm:inline text-sm font-medium whitespace-nowrap">ติดต่อฉุกเฉิน</span>
                </li>
                <li class="flex flex-1 items-center gap-3 after:content-[''] after:flex-1 after:border-t after:border-default-medium" data-tab="tab-login">
                    <span class="step-icon shrink-0 flex items-center justify-center w-8 h-8 rounded-full">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                        </svg>
                    </span>
                    <span class="step-label hidden sm:inline text-sm font-medium whitespace-nowrap">บัญชีเข้าระบบ</span>
                </li>
                <li class="flex items-center gap-3" data-tab="tab-contract">
                    <span class="step-icon shrink-0 flex items-center justify-center w-8 h-8 rounded-full">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z" />
                        </svg>
                    </span>
                    <span class="step-label hidden sm:inline text-sm font-medium whitespace-nowrap">สัญญาจ้าง</span>
                </li>
            </ol>
            <!-- มือถือ: label ใน stepper ซ่อน → บอกขั้นด้วยข้อความแทน -->
            <p id="stepCounter" class="sm:hidden mt-3 text-sm text-body" aria-live="polite"></p>
        </div>

        <form action="/employees/store" method="POST" class="p-4 sm:p-6 min-w-0">

            <!-- ============ แท็บ 1: ข้อมูลส่วนตัว (employees) ============ -->
            <div id="tab-personal" class="tab-panel">

                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-heading">ข้อมูลส่วนตัว</h2>
                        <p class="text-sm text-body mt-0.5">ข้อมูลพื้นฐานของพนักงาน</p>
                    </div>
                    <p class="text-xs text-body whitespace-nowrap shrink-0 pt-1">
                        <span class="text-red-600">*</span> จำเป็นต้องกรอก
                    </p>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="emp_no" class="<?= $label ?>">รหัสพนักงาน <span class="text-red-600">*</span></label>
                            <input type="text" id="emp_no" name="emp_no" required placeholder="KTN001" value="<?= htmlspecialchars($empno) ?>" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="emp_idcard" class="<?= $label ?>">เลขบัตรประชาชน</label>
                            <input type="text" name="emp_idcard" id="emp_idcard" maxlength="17" placeholder="กรอกเลข 13 หลัก" class="<?= $field ?>">
                        </div>
                    </div>

                    <fieldset class="pt-5 border-t border-default-medium">
                        <legend class="mb-3 text-sm font-semibold text-heading">ชื่อ-สกุล (ภาษาไทย)</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="emp_prefix_th" class="<?= $label ?>">คำนำหน้า</label>
                                <select id="emp_prefix_th" name="emp_prefix_th" class="<?= $field ?>">
                                    <?php foreach (['นาย', 'นาง', 'นางสาว'] as $prefix): ?>
                                        <option value="<?= htmlspecialchars($prefix) ?>" <?= ($prefix === 'นางสาว') ? 'selected' : '' ?>><?= htmlspecialchars($prefix) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="emp_name_th" class="<?= $label ?>">ชื่อ <span class="text-red-600">*</span></label>
                                <input type="text" id="emp_name_th" name="emp_name_th" required placeholder="ชื่อจริง" class="<?= $field ?>">
                            </div>
                            <div>
                                <label for="emp_sname_th" class="<?= $label ?>">นามสกุล <span class="text-red-600">*</span></label>
                                <input type="text" id="emp_sname_th" name="emp_sname_th" required placeholder="นามสกุล" class="<?= $field ?>">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="mb-3 text-sm font-semibold text-heading">ชื่อ-สกุล (ภาษาอังกฤษ)</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="emp_prefix_en" class="<?= $label ?>">Prefix</label>
                                <select id="emp_prefix_en" name="emp_prefix_en" class="<?= $field ?>">
                                    <?php foreach (['Mr.', 'Ms.', 'Miss'] as $prefix): ?>
                                        <option value="<?= htmlspecialchars($prefix) ?>" <?= ($prefix === 'Miss') ? 'selected' : '' ?>><?= htmlspecialchars($prefix) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="emp_name_en" class="<?= $label ?>">First name</label>
                                <input type="text" id="emp_name_en" name="emp_name_en" placeholder="First name" class="<?= $field ?>">
                            </div>
                            <div>
                                <label for="emp_sname_en" class="<?= $label ?>">Last name</label>
                                <input type="text" id="emp_sname_en" name="emp_sname_en" placeholder="Last name" class="<?= $field ?>">
                            </div>
                        </div>
                    </fieldset>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="emp_nickname_th" class="<?= $label ?>">ชื่อเล่น (ไทย)</label>
                            <input type="text" id="emp_nickname_th" name="emp_nickname_th" placeholder="ชื่อเล่น" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="emp_nickname_en" class="<?= $label ?>">Nickname (EN)</label>
                            <input type="text" id="emp_nickname_en" name="emp_nickname_en" placeholder="Nickname" class="<?= $field ?>">
                        </div>
                    </div>

                    <div class="pt-5 border-t border-default-medium grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="emp_birthday" class="<?= $label ?>">วันเกิด</label>
                            <input type="date" id="emp_birthday" name="emp_birthday" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="phone-input" class="<?= $label ?>">เบอร์โทร</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-4 h-4 text-body shrink-0 block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                                    </svg>
                                </div>
                                <input type="text" id="phone-input" name="emp_tel" class="<?= $fieldIcon ?>" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="08X-XXX-XXXX">
                            </div>
                        </div>
                        <div>
                            <label for="emp_email" class="<?= $label ?>">อีเมล</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                                    </svg>
                                </div>
                                <input type="email" id="emp_email" name="emp_email" placeholder="name@company.com" class="<?= $fieldIcon ?>">
                            </div>
                        </div>
                        <div>
                            <label for="emp_line" class="<?= $label ?>">Line ID</label>
                            <input type="text" id="emp_line" name="emp_line" placeholder="Line ID" class="<?= $field ?>">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="emp_address" class="<?= $label ?>">ที่อยู่</label>
                            <textarea id="emp_address" name="emp_address" rows="2" placeholder="บ้านเลขที่ ถนน แขวง/ตำบล เขต/อำเภอ จังหวัด รหัสไปรษณีย์" class="<?= $field ?>"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-default-medium flex flex-wrap items-center justify-between gap-3">
                    <a href="/employees" class="<?= $btnGhost ?>">ยกเลิก</a>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="saveDraft()" class="<?= $btnGhost ?>">บันทึกฉบับร่าง</button>
                        <button type="button" onclick="switchTab('tab-contact')" class="<?= $btnPrimary ?>">
                            ดำเนินการต่อ
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============ แท็บ 2: ผู้ติดต่อฉุกเฉิน (employee_contact — เพิ่มได้หลายแถว) ============ -->
            <div id="tab-contact" class="tab-panel hidden">

                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-heading">ผู้ติดต่อฉุกเฉิน</h2>
                    <p class="text-sm text-body mt-0.5">เพิ่มได้มากกว่า 1 คน ติ๊ก "ผู้ติดต่อหลัก" ได้แค่คนเดียว</p>
                </div>

                <div id="contactRows" class="space-y-4"></div>

                <button type="button" onclick="addContactRow()" class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-blue-700 whitespace-nowrap hover:underline focus:outline-none focus:ring-2 focus:ring-brand rounded-base">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    เพิ่มผู้ติดต่อ
                </button>

                <div class="mt-6 pt-5 border-t border-default-medium flex flex-wrap items-center justify-between gap-3">
                    <button type="button" onclick="switchTab('tab-personal')" class="<?= $btnGhost ?>">← ย้อนกลับ</button>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="saveDraft()" class="<?= $btnGhost ?>">บันทึกฉบับร่าง</button>
                        <button type="button" onclick="switchTab('tab-login')" class="<?= $btnPrimary ?>">
                            ดำเนินการต่อ
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============ แท็บ 3: บัญชีเข้าระบบ (employee_login) ============ -->
            <div id="tab-login" class="tab-panel hidden">

                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-heading">บัญชีเข้าระบบ</h2>
                        <p class="text-sm text-body mt-0.5">ข้อมูลสำหรับเข้าใช้งานระบบของพนักงาน</p>
                    </div>
                    <p class="text-xs text-body whitespace-nowrap shrink-0 pt-1">
                        <span class="text-red-600">*</span> จำเป็นต้องกรอก
                    </p>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="<?= $label ?>">Username <span class="text-red-600">*</span></label>
                            <input type="text" id="username" name="username" required placeholder="เช่น somchai.j" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="password" class="<?= $label ?>">รหัสผ่านเริ่มต้น <span class="text-red-600">*</span></label>
                            <input type="password" id="password" name="password" required placeholder="อย่างน้อย 8 ตัวอักษร" class="<?= $field ?>">
                        </div>
                    </div>

                    <div>
                        <label for="user_level" class="<?= $label ?>">ระดับผู้ใช้งาน</label>
                        <select id="user_level" name="user_level" onchange="toggleRoleField()" class="<?= $field ?>">
                            <option value="staff">Staff — พนักงานทั่วไป</option>
                            <option value="admin">Admin — ผู้ดูแลระบบ</option>
                        </select>
                    </div>

                    <!-- role_id ตาม comment ใน schema: "set only when is_admin = 1" -->
                    <!-- เพราะงั้นซ่อนช่องนี้ไว้ก่อน โชว์เฉพาะตอนเลือก admin เท่านั้น -->
                    <div id="roleField" class="hidden">
                        <label for="role_id" class="<?= $label ?>">ตำแหน่งผู้ดูแลระบบ (role)</label>
                        <select id="role_id" name="role_id" class="<?= $field ?>">
                            <option value="">-- เลือก role --</option>
                            <option value="1">ผู้ดูแลระบบสูงสุด</option>
                            <option value="2">ฝ่ายบุคคล</option>
                            <option value="3">หัวหน้าฝ่าย</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-default-medium flex flex-wrap items-center justify-between gap-3">
                    <button type="button" onclick="switchTab('tab-contact')" class="<?= $btnGhost ?>">← ย้อนกลับ</button>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="saveDraft()" class="<?= $btnGhost ?>">บันทึกฉบับร่าง</button>
                        <button type="button" onclick="switchTab('tab-contract')" class="<?= $btnPrimary ?>">
                            ดำเนินการต่อ
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============ แท็บ 4: สัญญาจ้าง (contract) ============ -->
            <div id="tab-contract" class="tab-panel hidden">

                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-heading">สัญญาจ้าง</h2>
                        <p class="text-sm text-body mt-0.5">รายละเอียดตำแหน่งและค่าตอบแทน</p>
                    </div>
                    <p class="text-xs text-body whitespace-nowrap shrink-0 pt-1">
                        <span class="text-red-600">*</span> จำเป็นต้องกรอก
                    </p>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="cont_position" class="<?= $label ?>">ตำแหน่ง</label>
                            <select id="cont_position" name="cont_position" class="<?= $field ?>">
                                <option value="">-- เลือกตำแหน่ง --</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?= htmlspecialchars((string) $position['position_id']) ?>">
                                        <?= htmlspecialchars($position['position_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="cont_start_date" class="<?= $label ?>">วันที่เริ่มงาน</label>
                            <input type="date" id="cont_start_date" name="cont_start_date" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="cont_duration_time" class="<?= $label ?>">ระยะเวลาสัญญา (เดือน)</label>
                            <input type="number" id="cont_duration_time" name="cont_duration_time" placeholder="เช่น 12" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="cont_status" class="<?= $label ?>">สถานะสัญญา</label>
                            <select id="cont_status" name="cont_status" class="<?= $field ?>">
                                <option value="พนักงานประจำ">พนักงานประจำ</option>
                                <option value="ทดลองงาน">ทดลองงาน</option>
                                <option value="สัญญาจ้าง">สัญญาจ้าง (contract)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-default-medium grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="cont_bank" class="<?= $label ?>">ธนาคาร</label>
                            <input type="text" id="cont_bank" name="cont_bank" placeholder="เช่น ธนาคารกสิกรไทย" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="cont_bank_no" class="<?= $label ?>">เลขบัญชี</label>
                            <input type="text" id="cont_bank_no" name="cont_bank_no" placeholder="XXX-X-XXXXX-X" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="cont_salary" class="<?= $label ?>">เงินเดือน</label>
                            <input type="number" step="0.01" id="cont_salary" name="cont_salary" placeholder="0.00" class="<?= $field ?>">
                        </div>
                        <div>
                            <label for="emp_idss" class="<?= $label ?>">เลขประกันสังคม</label>
                            <input type="text" name="emp_idss" id="emp_idss" maxlength="17" placeholder="เลขประกันสังคม" class="<?= $field ?>">
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-default-medium flex flex-wrap items-center justify-between gap-3">
                    <button type="button" onclick="switchTab('tab-login')" class="<?= $btnGhost ?>">← ย้อนกลับ</button>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="saveDraft()" class="<?= $btnGhost ?>">บันทึกฉบับร่าง</button>
                        <button type="submit" class="<?= $btnPrimary ?>">บันทึกพนักงานใหม่</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<template id="contactRowTemplate">
    <div class="contact-row relative p-4 bg-neutral-secondary-medium border border-default-medium rounded-base">
        <button type="button" onclick="this.closest('.contact-row').remove()"
            class="absolute top-2 right-2 px-1 text-sm text-body whitespace-nowrap hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-brand rounded-base">
            ลบ
        </button>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pr-8">
            <div>
                <label class="<?= $label ?>">ชื่อผู้ติดต่อ <span class="text-red-600">*</span></label>
                <input type="text" name="contacts[__INDEX__][name]" placeholder="ชื่อ-นามสกุล" class="<?= $field ?>">
            </div>
            <div>
                <label class="<?= $label ?>">ความสัมพันธ์</label>
                <input type="text" name="contacts[__INDEX__][relationship]" placeholder="เช่น บิดา, มารดา, คู่สมรส" class="<?= $field ?>">
            </div>
            <div>
                <label class="<?= $label ?>">เบอร์โทร <span class="text-red-600">*</span></label>
                <input type="text" name="contacts[__INDEX__][tel]" placeholder="08X-XXX-XXXX" class="<?= $field ?>">
            </div>
            <div>
                <label class="<?= $label ?>">ประเภท</label>
                <select name="contacts[__INDEX__][contact_type]" class="<?= $field ?>">
                    <option value="emergency">ติดต่อฉุกเฉิน</option>
                    <option value="family">ครอบครัว</option>
                    <option value="reference">บุคคลอ้างอิง</option>
                </select>
            </div>
        </div>

        <label class="mt-3 flex items-center gap-2 text-sm text-heading">
            <input type="radio" name="primary_contact_index" value="__INDEX__" class="w-4 h-4 text-blue-700 focus:ring-brand">
            ผู้ติดต่อหลัก
        </label>
    </div>
</template>

<script src="../assets/js/auto-dash.js"></script>
<script src="../assets/js/fill-data.js"></script>

<script>
    // ---- สลับ step ----
    // ลำดับ step ต้องตรงกับ data-tab ของ <li> ใน stepper
    var stepOrder = ['tab-personal', 'tab-contact', 'tab-login', 'tab-contract'];

    function switchTab(tabId) {
        document.querySelectorAll('.tab-panel').forEach(function(el) {
            el.classList.add('hidden');
        });
        document.getElementById(tabId).classList.remove('hidden');

        updateStepper(tabId);

        // เลื่อนกลับหัวการ์ด กันเปิดแท็บใหม่แล้วค้างอยู่ท้ายหน้า
        document.getElementById('formCard').scrollIntoView({ block: 'start' });
    }

    // อัปเดตสี stepper ตามตำแหน่งของแท็บที่เลือก
    // - ผ่านมาแล้ว -> เขียว (completed)
    // - ปัจจุบัน   -> primary (active)
    // - ยังไม่ถึง   -> เทา (upcoming)
    function updateStepper(tabId) {
        var currentIndex = stepOrder.indexOf(tabId);

        document.querySelectorAll('#tabBar > li[data-tab]').forEach(function(li) {
            var icon = li.querySelector('.step-icon');
            var label = li.querySelector('.step-label');
            var stepIndex = stepOrder.indexOf(li.dataset.tab);

            // เคลียร์สีเก่าทั้งหมดก่อนทุกครั้ง กันสีเก่าค้าง
            icon.classList.remove(
                'bg-success-soft', 'text-fg-success-strong',
                'bg-blue-700', 'text-white',
                'bg-neutral-tertiary', 'text-body'
            );
            label.classList.remove('text-blue-700', 'text-heading', 'text-body');
            li.removeAttribute('aria-current');

            if (stepIndex < currentIndex) {
                icon.classList.add('bg-success-soft', 'text-fg-success-strong');
                label.classList.add('text-heading');
            } else if (stepIndex === currentIndex) {
                icon.classList.add('bg-blue-700', 'text-white');
                label.classList.add('text-blue-700');
                li.setAttribute('aria-current', 'step');
            } else {
                icon.classList.add('bg-neutral-tertiary', 'text-body');
                label.classList.add('text-body');
            }
        });

        // ข้อความบอกขั้นบนมือถือ (label ใน stepper ถูกซ่อน)
        var activeLabel = document.querySelector('#tabBar > li[data-tab="' + tabId + '"] .step-label');
        document.getElementById('stepCounter').textContent =
            'ขั้นที่ ' + (currentIndex + 1) + ' จาก ' + stepOrder.length + ' · ' + activeLabel.textContent;
    }

    // ตั้งค่า stepper เริ่มต้นตอนโหลดหน้า (แท็บแรกเป็น active)
    updateStepper('tab-personal');

    // ---- เพิ่มแถวผู้ติดต่อฉุกเฉิน (clone จาก template, แทน __INDEX__ ด้วยเลขจริง) ----
    var contactIndex = 0;

    function addContactRow() {
        var template = document.getElementById('contactRowTemplate');
        var clone = template.content.cloneNode(true);

        var html = clone.querySelector('.contact-row').outerHTML.split('__INDEX__').join(contactIndex);

        var wrapper = document.createElement('div');
        wrapper.innerHTML = html;

        document.getElementById('contactRows').appendChild(wrapper.firstElementChild);

        contactIndex = contactIndex + 1;
    }

    // เพิ่มแถวแรกให้อัตโนมัติตอนโหลดหน้า (กันหน้าว่างเปล่าตอนเปิดแท็บนี้ครั้งแรก)
    addContactRow();

    // ---- โชว์/ซ่อนช่อง role_id ตาม user_level ที่เลือก ----
    function toggleRoleField() {
        var userLevel = document.getElementById('user_level').value;
        var roleField = document.getElementById('roleField');

        if (userLevel === 'admin') {
            roleField.classList.remove('hidden');
        } else {
            roleField.classList.add('hidden');
        }
    }

    // ---- บันทึกฉบับร่าง ----
    // TODO: ต่อกับ endpoint จริงฝั่ง PHP เช่น POST ไปที่ /employees/draft ด้วย fetch()
    // ตอนนี้ทำเป็น placeholder ไว้ก่อน กันปุ่มไม่มีฟังก์ชันอะไรเลย
    function saveDraft() {
        alert('บันทึกฉบับร่างเรียบร้อย (ยังไม่ได้ต่อ backend จริง)');
    }

    // helper: ตั้งค่าให้ input/select/textarea ที่มี name ตรงกับที่ระบุ
    // ใช้ได้ทั้ง name ธรรมดา (เช่น "emp_no") และ name แบบ array (เช่น "contacts[0][name]")
    // (ถูกเรียกจาก fill-data.js)
    function setFieldValue(name, value) {
        var el = document.querySelector('[name="' + name + '"]');
        if (!el) return;
        el.value = value;
    }
</script>