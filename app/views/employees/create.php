<?php
// views/employees/create-full.php
// หน้าที่: ฟอร์มเพิ่มพนักงานใหม่ ครบทุกตาราง (employees, employee_contact, employee_login, contract)
// แบ่งเป็น 4 แท็บ เพราะข้อมูลมาจากคนละตารางกัน อ่านง่ายกว่ายัดหน้าเดียวยาวๆ
$empno = $emp_no ?? '';
?>
<div class="mb-4">
    <a href="/employees" class="flex items-center justify-center w-8 h-8 rounded-full ring-4 ring-white text-sm font-medium bg-white text-gray-500 hover:bg-gray-50">←</a>
</div>

<!-- ปุ่มกรอกข้อมูลทดสอบ — วางไว้ข้างๆ หัวข้อ "เพิ่มพนักงานใหม่" -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">เพิ่มพนักงานใหม่</h1>
    <button type="button" onclick="fillTestData()"
        class="px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100">
        🧪 กรอกข้อมูลทดสอบอัตโนมัติ
    </button>
</div>

<div class="border border-gray-200 rounded-2xl shadow-sm">
    <div class="flex flex-col md:flex-row items-stretch gap-8 lg:gap-12">
        <div class="p-6 rounded-l-2xl ">
            <div class="self-stretch py-4 md:border-s md:border-gray-100 ">


                <ol id="tabBar" class="relative border-s-2 border-gray-200  md:w-48 md:shrink-0 pt-1">
                    <li class="mb-10 ms-7" data-tab="tab-personal">
                        <span class="step-icon absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 ring-4 ring-buffer">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                            </svg>
                        </span>
                        <h3 class="step-label font-medium leading-tight">ข้อมูลส่วนตัว</h3>
                    </li>
                    <li class="mb-10 ms-7" data-tab="tab-contact">
                        <span class="step-icon absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 ring-4 ring-buffer">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                            </svg>
                        </span>
                        <h3 class="step-label font-medium leading-tight">ข้อมูลติดต่อฉุกเฉิน</h3>
                    </li>
                    <li class="mb-10 ms-7" data-tab="tab-login">
                        <span class="step-icon absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 ring-4 ring-buffer">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                            </svg>
                        </span>
                        <h3 class="step-label font-medium leading-tight">บัญชีเข้าระบบ</h3>
                    </li>
                    <li class="ms-7" data-tab="tab-contract">
                        <span class="step-icon absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 ring-4 ring-buffer">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z" />
                            </svg>
                        </span>
                        <h3 class="step-label font-medium leading-tight">สัญญาจ้าง</h3>
                    </li>
                </ol>

            </div>
        </div>
        <div class="p-6 bg-white rounded-r-2xl flex-1 min-w-0">
            <form action="/employees/store" method="POST" class="mx-auto min-w-0">

                <!-- ============ แท็บ 1: ข้อมูลส่วนตัว (employees) ============ -->
                <div id="tab-personal" class="tab-panel">

                    <!-- หัวข้อการ์ด: ชื่อแท็บ + คำอธิบาย + หมายเหตุ "จำเป็นต้องกรอก" มุมขวา -->
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">ข้อมูลส่วนตัว</h2>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">ข้อมูลพื้นฐานของพนักงาน</p>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap shrink-0 pt-1">
                            <span class="text-red-500">*</span> จำเป็นต้องกรอก
                        </p>
                    </div>
                    <hr class="border-gray-100 dark:border-gray-700 mb-5">

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รหัสพนักงาน <span class="text-red-500">*</span></label>
                                <input type="text" name="emp_no" required placeholder="KTN001" value="<?php echo $empno; ?>" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เลขบัตรประชาชน</label>
                                <input type="text" name="emp_idcard" id="emp_idcard" maxlength="17" placeholder="กรอกเลข 13 หลัก" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- เส้นคั่นก่อนขึ้นหัวข้อย่อย "ชื่อ-สกุล" ตามภาพ reference -->
                        <hr class="border-gray-100 dark:border-gray-700">

                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">ชื่อ-สกุล (ภาษาไทย)</p>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำนำหน้า</label>
                                    <select name="emp_prefix_th" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <?php foreach (['นาย', 'นาง', 'นางสาว'] as $prefix): ?>
                                            <option value="<?php echo htmlspecialchars($prefix); ?>" <?php echo ($prefix === 'นางสาว') ? 'selected' : ''; ?>><?php echo htmlspecialchars($prefix); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อ <span class="text-red-500">*</span></label>
                                    <input type="text" name="emp_name_th" required placeholder="ชื่อจริง" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">นามสกุล <span class="text-red-500">*</span></label>
                                    <input type="text" name="emp_sname_th" required placeholder="นามสกุล" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">ชื่อ-สกุล (ภาษาอังกฤษ)</p>
                            <div class="grid grid-cols-3 gap-4">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prefix</label>
                                    <select name="emp_prefix_en" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <?php foreach (['Mr.', 'Ms.', 'Miss'] as $prefix): ?>
                                            <option value="<?php echo htmlspecialchars($prefix); ?>" <?php echo ($prefix === 'Miss') ? 'selected' : ''; ?>><?php echo htmlspecialchars($prefix); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First name</label>
                                    <input type="text" name="emp_name_en" placeholder="First name" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last name</label>
                                    <input type="text" name="emp_sname_en" placeholder="Last name" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อเล่น (ไทย)</label>
                                <input type="text" name="emp_nickname_th" placeholder="ชื่อเล่น" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nickname (EN)</label>
                                <input type="text" name="emp_nickname_en" placeholder="Nickname" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">วันเกิด</label>
                                <input type="date" name="emp_birthday" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:[color-scheme:dark] rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เบอร์โทร</label>
                                <div class="relative">
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg class="w-4 h-4 text-body shrink-0 block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="phone-input" name="emp_tel" class="block w-full pl-9 pr-3 py-2.5  border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder-gray-400 " pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="08X-XXX-XXXX" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">อีเมล</label>
                                <div class="relative">
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                                        </svg>
                                    </div>
                                    <input type="email" name="emp_email" placeholder="name@company.com" class="block w-full pl-9 pr-3 py-2.5  border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder-gray-400 "">
                                </div>
                            </div>
                            <div>
                                <label class=" block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Line ID</label>
                                    <input type="text" name="emp_line" placeholder="Line ID" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ที่อยู่</label>
                                <textarea name="emp_address" rows="2" placeholder="บ้านเลขที่ ถนน แขวง/ตำบล เขต/อำเภอ จังหวัด รหัสไปรษณีย์" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700 my-5">
                        <div class="flex items-center justify-between">
                            <a href="/employees" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                ยกเลิก
                            </a>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="saveDraft()" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                    บันทึกฉบับร่าง
                                </button>
                                <button type="button" onclick="switchTab('tab-contact')" class="flex items-center gap-1.5 px-5 py-2 bg-blue-600 text-white rounded-base text-sm hover:bg-blue-700">
                                    ดำเนินการต่อ
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============ แท็บ 2: ผู้ติดต่อฉุกเฉิน (employee_contact — เพิ่มได้หลายแถว) ============ -->
                    <div id="tab-contact" class="tab-panel hidden">

                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">ผู้ติดต่อฉุกเฉิน</h2>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">เพิ่มได้มากกว่า 1 คน ติ๊ก "ผู้ติดต่อหลัก" ได้แค่คนเดียว</p>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap shrink-0 pt-1">
                                <span class="text-red-500">*</span> จำเป็นต้องกรอก
                            </p>
                        </div>
                        <hr class="border-gray-100 dark:border-gray-700 mb-5">

                        <div id="contactRows" class="space-y-4"></div>

                        <button type="button" onclick="addContactRow()" class="mt-4 text-sm text-blue-600 hover:underline">
                            + เพิ่มผู้ติดต่อ
                        </button>

                        <hr class="border-gray-100 dark:border-gray-700 my-5">
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="switchTab('tab-personal')" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                ← ย้อนกลับ
                            </button>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="saveDraft()" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                    บันทึกฉบับร่าง
                                </button>
                                <button type="button" onclick="switchTab('tab-login')" class="flex items-center gap-1.5 px-5 py-2 bg-blue-600 text-white rounded-base text-sm hover:bg-blue-700">
                                    ดำเนินการต่อ
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============ แท็บ 3: บัญชีเข้าระบบ (employee_login) ============ -->
                    <div id="tab-login" class="tab-panel hidden">

                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">บัญชีเข้าระบบ</h2>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">ข้อมูลสำหรับเข้าใช้งานระบบของพนักงาน</p>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap shrink-0 pt-1">
                                <span class="text-red-500">*</span> จำเป็นต้องกรอก
                            </p>
                        </div>
                        <hr class="border-gray-100 dark:border-gray-700 mb-5">

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username <span class="text-red-500">*</span></label>
                                    <input type="text" name="username" required placeholder="เช่น somchai.j" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รหัสผ่านเริ่มต้น <span class="text-red-500">*</span></label>
                                    <input type="password" name="password" required placeholder="อย่างน้อย 8 ตัวอักษร" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label for="user_level" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ระดับผู้ใช้งาน</label>
                                <select id="user_level" name="user_level" onchange="toggleRoleField()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="staff">Staff — พนักงานทั่วไป</option>
                                    <option value="admin">Admin — ผู้ดูแลระบบ</option>
                                </select>
                            </div>

                            <!-- role_id ตาม comment ใน schema: "set only when is_admin = 1" -->
                            <!-- เพราะงั้นซ่อนช่องนี้ไว้ก่อน โชว์เฉพาะตอนติ๊ก admin เท่านั้น -->
                            <div id="roleField" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ตำแหน่งผู้ดูแลระบบ (role)</label>
                                <select name="role_id" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- เลือก role --</option>
                                    <option value="1">ผู้ดูแลระบบสูงสุด</option>
                                    <option value="2">ฝ่ายบุคคล</option>
                                    <option value="3">หัวหน้าฝ่าย</option>
                                </select>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700 my-5">
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="switchTab('tab-contact')" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                ← ย้อนกลับ
                            </button>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="saveDraft()" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                    บันทึกฉบับร่าง
                                </button>
                                <button type="button" onclick="switchTab('tab-contract')" class="flex items-center gap-1.5 px-5 py-2 bg-blue-600 text-white rounded-base text-sm hover:bg-blue-700">
                                    ดำเนินการต่อ
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============ แท็บ 4: สัญญาจ้าง (contract) ============ -->
                    <div id="tab-contract" class="tab-panel hidden">

                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">สัญญาจ้าง</h2>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">รายละเอียดตำแหน่งและค่าตอบแทน</p>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap shrink-0 pt-1">
                                <span class="text-red-500">*</span> จำเป็นต้องกรอก
                            </p>
                        </div>
                        <hr class="border-gray-100 dark:border-gray-700 mb-5">

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ตำแหน่ง</label>
                                    <select name="cont_position" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        <?php foreach ($positions as $position): ?>
                                            <option value="<?php echo $position['position_id']; ?>">
                                                <?php echo htmlspecialchars($position['position_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">วันที่เริ่มงาน</label>
                                    <input type="date" name="cont_start_date" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:[color-scheme:dark] rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ระยะเวลาสัญญา (เดือน)</label>
                                    <input type="number" name="cont_duration_time" placeholder="เช่น 12" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สถานะสัญญา</label>
                                    <select name="cont_status" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="พนักงานประจำ">พนักงานประจำ</option>
                                        <option value="ทดลองงาน">ทดลองงาน</option>
                                        <option value="สัญญาจ้าง">สัญญาจ้าง (contract)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เงินเดือน</label>
                                <input type="number" step="0.01" name="cont_salary" placeholder="0.00" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ธนาคาร</label>
                                    <input type="text" name="cont_bank" placeholder="เช่น ธนาคารกสิกรไทย" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เลขบัญชี</label>
                                    <input type="text" name="cont_bank_no" placeholder="XXX-X-XXXXX-X" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 rounded-base p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700 my-5">
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="switchTab('tab-login')" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                ← ย้อนกลับ
                            </button>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="saveDraft()" class="px-4 py-2 border border-gray-200 text-gray-700 dark:border-gray-600 dark:text-gray-300 rounded-base text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                    บันทึกฉบับร่าง
                                </button>
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-base text-sm hover:bg-blue-700">
                                    บันทึกพนักงานใหม่
                                </button>
                            </div>
                        </div>
                    </div>

            </form>


        </div>
    </div>
</div>

<template id="contactRowTemplate">
    <div class="contact-row bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-base p-4 relative">
        <button type="button" onclick="this.closest('.contact-row').remove()"
            class="absolute top-2 right-2 text-gray-400 hover:text-red-600 text-sm">
            ลบ
        </button>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">ชื่อผู้ติดต่อ <span class="text-red-500">*</span></label>
                <input type="text" name="contacts[__INDEX__][name]" required placeholder="ชื่อ-นามสกุล"
                    class="w-full border border-gray-200 dark:border-gray-500 dark:bg-gray-600 dark:text-white placeholder-gray-400 rounded-base p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">ความสัมพันธ์</label>
                <input type="text" name="contacts[__INDEX__][relationship]" placeholder="เช่น บิดา, มารดา, คู่สมรส"
                    class="w-full border border-gray-200 dark:border-gray-500 dark:bg-gray-600 dark:text-white placeholder-gray-400 rounded-base p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">เบอร์โทร <span class="text-red-500">*</span></label>
                <input type="text" name="contacts[__INDEX__][tel]" required placeholder="08X-XXX-XXXX"
                    class="w-full border border-gray-200 dark:border-gray-500 dark:bg-gray-600 dark:text-white placeholder-gray-400 rounded-base p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">ประเภท</label>
                <select name="contacts[__INDEX__][contact_type]"
                    class="w-full border border-gray-200 dark:border-gray-500 dark:bg-gray-600 dark:text-white rounded-base p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="emergency">ติดต่อฉุกเฉิน</option>
                    <option value="family">ครอบครัว</option>
                    <option value="reference">บุคคลอ้างอิง</option>
                </select>
            </div>
        </div>

        <label class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300 mt-3">
            <input type="radio" name="primary_contact_index" value="__INDEX__">
            ผู้ติดต่อหลัก
        </label>
    </div>
</template>

<script src="../assets/js/auto-dash.js"></script>
<script src="../assets/js/fill-data.js"></script>

<script>
    // ---- สลับ step ----
    // ลำดับ step ต้องตรงกับ data-tab ของปุ่มใน stepper (ซ้ายมือของหน้า)
    // ---- สลับ step ----
    // ลำดับ step ต้องตรงกับ data-tab ของ <li> ใน stepper (ซ้ายมือของหน้า)
    var stepOrder = ['tab-personal', 'tab-contact', 'tab-login', 'tab-contract'];

    function switchTab(tabId) {
        document.querySelectorAll('.tab-panel').forEach(function(el) {
            el.classList.add('hidden');
        });
        document.getElementById(tabId).classList.remove('hidden');

        updateStepper(tabId);
    }

    // อัปเดตสี stepper ตามตำแหน่งของแท็บที่เลือก เทียบกับแท็บปัจจุบัน
    // - ก่อนหน้า (ผ่านมาแล้ว)  -> เขียว (completed)
    // - ตรงกับแท็บปัจจุบัน      -> น้ำเงิน (active)
    // - หลังจากนี้ (ยังไม่ถึง)   -> เทา (upcoming)
    function updateStepper(tabId) {
        var currentIndex = stepOrder.indexOf(tabId);

        document.querySelectorAll('#tabBar > li[data-tab]').forEach(function(li) {
            var icon = li.querySelector('.step-icon');
            var label = li.querySelector('.step-label');
            var stepIndex = stepOrder.indexOf(li.dataset.tab);

            // เคลียร์สีเก่าทั้งหมดก่อนทุกครั้ง กันสีเก่าค้าง
            icon.classList.remove(
                'bg-success-soft', 'text-fg-success-strong',
                'bg-blue-600', 'text-white',
                'bg-neutral-tertiary', 'text-body'
            );
            label.classList.remove('text-blue-600', 'text-gray-900', 'text-body');

            if (stepIndex < currentIndex) {
                // ผ่านมาแล้ว -> เขียว
                icon.classList.add('bg-success-soft', 'text-fg-success-strong');
                label.classList.add('text-gray-900');
            } else if (stepIndex === currentIndex) {
                // กำลังกรอกอยู่ -> น้ำเงิน
                icon.classList.add('bg-blue-600', 'text-white');
                label.classList.add('text-blue-600');
            } else {
                // ยังไม่ถึง -> เทา
                icon.classList.add('bg-neutral-tertiary', 'text-body');
                label.classList.add('text-body');
            }
        });
    }

    // ตั้งค่าสี stepper เริ่มต้นตอนโหลดหน้า (แท็บแรกเป็น active)
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
    function setFieldValue(name, value) {
        var el = document.querySelector('[name="' + name + '"]');
        if (!el) return;
        el.value = value;
    }
</script>


