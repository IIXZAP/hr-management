<?php
// views/employees/edit.php

$idcardRaw = preg_replace('/\D/', '', $employee['emp_idcard'] ?? '');
$idcardFmt = preg_replace('/^(\d)(\d{4})(\d{5})(\d{2})(\d)$/', '$1-$2-$3-$4-$5', $idcardRaw);

?>
<div class="max-w-6xl mx-auto">
    <div class="mb-4">
        <a href="/employees" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 hover:underline rounded-base">
            ← ย้อนกลับ
        </a>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl ring-1 ring-gray-200 dark:ring-gray-700 shadow-sm">
        <div id="tabRoot">
            <div class="flex overflow-x-auto border-b border-gray-200 dark:border-gray-700 px-2">
                <button type="button" data-tab="tabPersonal" aria-selected="true" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">ข้อมูลส่วนตัว</button>
                <button type="button" data-tab="tabContact" aria-selected="false" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">ข้อมูลติดต่อ</button>
                <button type="button" data-tab="tabWork" aria-selected="false" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">ข้อมูลการทำงาน</button>
                <button type="button" data-tab="tabBank" aria-selected="false" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">บัญชีธนาคาร</button>
                <button type="button" data-tab="tabLeave" aria-selected="false" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">สิทธิ์วันลา</button>
                <!-- <button type="button" data-tab="tabEmergency" aria-selected="false" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">ผู้ติดต่อฉุกเฉิน</button> -->
                <!-- <button type="button" data-tab="tabDocs" aria-selected="false" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">เอกสารแนบ</button> -->
                <button type="button" data-tab="tabAccount" aria-selected="false" class="tab-btn shrink-0 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500">บัญชีผู้ใช้</button>
            </div>

            <form id="employeeForm" class="p-5" action="/employees/update?id=<?php echo (int) $employee['emp_id']; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo (int) $employee['emp_id']; ?>">

                <div id="tabPersonal" class="tab-panel grid sm:grid-cols-2 gap-4">

                    <div class="sm:col-span-2 flex items-center gap-4">
                        <img src="<?php echo htmlspecialchars($employee['photo_url'] ?? 'https://i.pravatar.cc/150?img=68'); ?>" class="w-16 h-16 rounded-full object-cover ring-1 ring-gray-200">
                        <div>
                            <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                            <button type="button" onclick="document.getElementById('photoInput').click()" class="text-body bg-neutral-primary-soft border border-default hover:bg-neutral-secondary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary-soft shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">อัปโหลดรูปภาพ</button>
                        </div>
                    </div>

                    <div>
                        <label for="emp_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รหัสพนักงาน</label>
                        <input type="text" id="emp_no" name="emp_no" value="<?php echo htmlspecialchars($employee['emp_no']); ?>" readonly class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed dark:bg-gray-600 dark:border-gray-600 dark:text-gray-400">
                    </div>
                    <div>
                        <label for="emp_idcard" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เลขบัตรประชาชน</label>
                        <input type="text" id="emp_idcard" name="emp_idcard" value="<?php echo htmlspecialchars($idcardFmt); ?>" placeholder="X-XXXX-XXXXX-XX-X" maxlength="17" inputmode="numeric" data-idcard class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-4 gap-4 sm:col-span-2">
                        <div>
                            <label for="emp_prefix_th" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">คำนำหน้า</label>
                            <select id="emp_prefix_th" name="emp_prefix_th" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <?php foreach (['นาย', 'นาง', 'นางสาว'] as $prefix): ?>
                                    <option value="<?php echo htmlspecialchars($prefix); ?>" <?php echo ($employee['emp_prefix_th'] === $prefix) ? 'selected' : ''; ?>><?php echo htmlspecialchars($prefix); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="emp_name_th" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อ</label>
                            <input type="text" id="emp_name_th" name="emp_name_th" value="<?php echo htmlspecialchars($employee['emp_name_th'] ?? ''); ?>" placeholder="ชื่อจริง" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div>
                            <label for="emp_sname_th" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">นามสกุล</label>
                            <input type="text" id="emp_sname_th" name="emp_sname_th" value="<?php echo htmlspecialchars($employee['emp_sname_th'] ?? ''); ?>" placeholder="นามสกุล" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div>
                            <label for="emp_nickname_th" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อเล่น</label>
                            <input type="text" id="emp_nickname_th" name="emp_nickname_th" value="<?php echo htmlspecialchars($employee['emp_nickname_th'] ?? ''); ?>" placeholder="ชื่อเล่น" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div>
                            <label for="emp_prefix_en" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">คำนำหน้า (EN)</label>
                            <select id="emp_prefix_en" name="emp_prefix_en" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <?php foreach (['Mr.', 'Ms.', 'Miss.'] as $prefix): ?>
                                    <option value="<?php echo htmlspecialchars($prefix); ?>" <?php echo ($employee['emp_prefix_en'] === $prefix) ? 'selected' : ''; ?>><?php echo htmlspecialchars($prefix); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="emp_name_en" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อ (EN)</label>
                            <input type="text" id="emp_name_en" name="emp_name_en" value="<?php echo htmlspecialchars($employee['emp_name_en'] ?? ''); ?>" placeholder="ชื่อจริง" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div>
                            <label for="emp_sname_en" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">นามสกุล (EN)</label>
                            <input type="text" id="emp_sname_en" name="emp_sname_en" value="<?php echo htmlspecialchars($employee['emp_sname_en'] ?? ''); ?>" placeholder="นามสกุล" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div>
                            <label for="emp_nickname_en" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อเล่น (EN)</label>
                            <input type="text" id="emp_nickname_en" name="emp_nickname_en" value="<?php echo htmlspecialchars($employee['emp_nickname_en'] ?? ''); ?>" placeholder="ชื่อเล่น" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="emp_birthday" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">วันเกิด</label>
                        <input type="date" id="emp_birthday" name="emp_birthday" value="<?php echo htmlspecialchars($employee['emp_birthday'] ?? ''); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:[color-scheme:dark] dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="emp_idss" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เลขประกันสังคม</label>
                        <input type="text" id="emp_idss" name="emp_idss" value="<?php echo htmlspecialchars($employee['emp_idss'] ?? ''); ?>" placeholder="X-XXXX-XXXXX-XX-X" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                <div id="tabContact" class="tab-panel hidden grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="emp_tel" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เบอร์โทรศัพท์</label>
                        <input type="text" id="emp_tel" name="emp_tel" value="<?php echo htmlspecialchars($employee['emp_tel'] ?? ''); ?>" placeholder="08X-XXX-XXXX" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="emp_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">อีเมล</label>
                        <input type="email" id="emp_email" name="emp_email" value="<?php echo htmlspecialchars($employee['emp_email'] ?? ''); ?>" placeholder="name@company.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="emp_line" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">LINE</label>
                        <input type="text" id="emp_line" name="emp_line" value="<?php echo htmlspecialchars($employee['emp_line'] ?? ''); ?>" placeholder="@lineID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="emp_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ที่อยู่ปัจจุบัน</label>
                        <textarea id="emp_address" name="emp_address" rows="3" placeholder="บ้านเลขที่ ถนน ตำบล อำเภอ จังหวัด รหัสไปรษณีย์" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"><?php echo htmlspecialchars($employee['emp_address'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label for="emergency_contact_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อผู้ติดต่อฉุกเฉิน</label>
                        <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?php echo htmlspecialchars($employee['emergency_contact_name'] ?? ''); ?>" placeholder="ชื่อ-นามสกุล" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="emergency_contact_relationship" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ความสัมพันธ์</label>
                        <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="<?php echo htmlspecialchars($employee['emergency_contact_relationship'] ?? ''); ?>" placeholder="เช่น บิดา มารดา คู่สมรส" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="emergency_contact_tel" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เบอร์โทรศัพท์ฉุกเฉิน</label>
                        <input type="text" id="emergency_contact_tel" name="emergency_contact_tel" value="<?php echo htmlspecialchars($employee['emergency_contact_tel'] ?? ''); ?>" placeholder="08X-XXX-XXXX" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                <!-- แท็บ "ข้อมูลการทำงาน" — ตัด "แผนก" กับ "หัวหน้างานตรง" ออกแล้ว
                 (ไม่มีตาราง departments แยก / ไม่มี manager_id ในตาราง employees) -->
                <div id="tabWork" class="tab-panel hidden grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="cont_position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ตำแหน่ง</label>
                        <select id="cont_position" name="cont_position" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">-- เลือกตำแหน่ง --</option>
                            <?php foreach ($positions as $position): ?>
                                <option value="<?php echo (int) $position['position_id']; ?>" <?php echo ((int) ($employee['cont_position'] ?? 0) === (int) $position['position_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($position['position_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="cont_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ประเภทการจ้าง</label>
                        <select id="cont_status" name="cont_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php foreach (['พนักงานประจำ', 'ทดลองงาน', 'สัญญาจ้าง'] as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($employee['cont_status'] === $type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="cont_start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">วันที่เริ่มงาน</label>
                        <input type="date" id="cont_start_date" name="cont_start_date" value="<?php echo htmlspecialchars($employee['cont_start_date'] ?? ''); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:[color-scheme:dark] dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="cont_salary" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เงินเดือนพื้นฐาน (บาท)</label>
                        <input type="number" step="0.01" id="cont_salary" name="cont_salary" value="<?php echo htmlspecialchars($employee['cont_salary'] ?? ''); ?>" placeholder="0.00" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                <div id="tabBank" class="tab-panel hidden grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="cont_bank" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ธนาคาร</label>
                        <select id="cont_bank" name="cont_bank" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php foreach (['ธนาคารกสิกรไทย', 'ธนาคารไทยพาณิชย์', 'ธนาคารกรุงเทพ', 'ธนาคารกรุงไทย'] as $bank): ?>
                                <option value="<?php echo htmlspecialchars($bank); ?>" <?php echo ($employee['cont_bank'] === $bank) ? 'selected' : ''; ?>><?php echo htmlspecialchars($bank); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="cont_bank_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เลขที่บัญชี</label>
                        <input type="text" id="cont_bank_no" name="cont_bank_no" value="<?php echo htmlspecialchars($employee['cont_bank_no'] ?? ''); ?>" placeholder="XXX-X-XXXXX-X" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="bank_account_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อบัญชี</label>
                        <input type="text" id="bank_account_name" name="bank_account_name" value="<?php echo htmlspecialchars($employee['bank_account_name'] ?? ''); ?>" placeholder="ชื่อ-นามสกุล ตามบัญชี" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="bank_branch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">สาขา</label>
                        <input type="text" id="bank_branch" name="bank_branch" value="<?php echo htmlspecialchars($employee['bank_branch'] ?? ''); ?>" placeholder="ชื่อสาขา" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                <div id="tabLeave" class="tab-panel hidden grid sm:grid-cols-3 gap-4">
                    <!-- <div>
                    <label for="sick_leave_days" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ลาป่วย (วัน/ปี)</label>
                    <input type="number" id="sick_leave_days" name="sick_leave_days" value="<?php echo htmlspecialchars($employee['sick_leave_days'] ?? '30'); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div>
                    <label for="personal_leave_days" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ลากิจ (วัน/ปี)</label>
                    <input type="number" id="personal_leave_days" name="personal_leave_days" value="<?php echo htmlspecialchars($employee['personal_leave_days'] ?? '6'); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div> -->
                    <div>
                        <label for="vacation_leave_days" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ลาพักร้อน (วัน/ปี)</label>
                        <input type="number" id="vacation_leave_days" name="vacation_leave_days" value="<?php echo htmlspecialchars($employee['vacation_leave_days'] ?? '5'); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 " disabled>
                    </div>
                </div>

                <!-- ⚠️ ยังไม่ยืนยันว่ามี column พวกนี้ตรงบน employees จริงหรือเปล่า
                 ในทางทฤษฎีควรมาจากตาราง employee_contact (EmployeeContact.php) แทน -->
                <!-- <div id="tabEmergency" class="tab-panel hidden grid sm:grid-cols-2 gap-4">
                
            </div> -->

                <!-- <div id="tabDocs" class="tab-panel hidden space-y-3">
                    <?php if (!empty($documents)): ?>
                        <div class="space-y-2">
                            <?php foreach ($documents as $doc): ?>
                                <?php
                                $ext = strtolower(pathinfo($doc['doc_url'], PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                ?>
                                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-sm">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 2h8l4 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300 truncate">
                                            <?php echo htmlspecialchars(basename($doc['doc_url'])); ?>
                                        </span>
                                        <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0
                            <?php
                                switch ($doc['doc_status']) {
                                    case 'approved':
                                        echo 'bg-green-100 text-green-700';
                                        break;
                                    case 'pending':
                                        echo 'bg-yellow-100 text-yellow-700';
                                        break;
                                    case 'rejected':
                                        echo 'bg-red-100 text-red-700';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-600';
                                }
                            ?>">
                                            <?php echo htmlspecialchars($doc['doc_status']); ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <button type="button"
                                            onclick="openDocPreview('<?php echo htmlspecialchars($doc['doc_url'], ENT_QUOTES); ?>', '<?php echo $isImage ? 'image' : 'pdf'; ?>')"
                                            class="text-primary-700 dark:text-blue-400 hover:underline text-xs font-medium">
                                            ดู
                                        </button>
                                        <a href="<?php echo htmlspecialchars($doc['doc_url']); ?>" download class="text-gray-500 dark:text-gray-400 hover:underline text-xs">
                                            ดาวน์โหลด
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 text-center py-4">ยังไม่มีเอกสาร</p>
                    <?php endif; ?>

                    <label for="docsInput" class="flex flex-col items-center justify-center w-full p-8 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                        <svg class="w-8 h-8 mb-2 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold text-primary-700 dark:text-blue-400">ลากไฟล์มาวาง</span> หรือเลือกไฟล์</p>
                        <p class="text-xs text-gray-400 mt-1">รองรับ PDF, JPG, PNG ขนาดไม่เกิน 10MB</p>
                        <input type="file" id="docsInput" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                    </label>
                </div> -->

                <!-- ⚠️ ยังไม่ยืนยันว่ามี column account_type/account_status ตรงบน employees หรือ employee_login
                 EmployeeLogin.php ที่มีอยู่ตอนนี้ใช้ is_admin (0/1) + role_id ไม่ใช่ string แบบนี้ -->
                <div id="tabAccount" class="tab-panel hidden grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="role_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ประเภทบัญชี (account_type)</label>
                        <select id="role_name" name="role_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="staff" <?php echo ($employee['role_name'] === 'staff') ? 'selected' : ''; ?>>Staff — พนักงานทั่วไป</option>
                            <option value="admin" <?php echo ($employee['role_name'] === 'admin') ? 'selected' : ''; ?>>Admin — ผู้ดูแลระบบ</option>
                        </select>
                    </div>
                    <div>
                        <label for="emp_cancel" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">สถานะบัญชี</label>
                        <select id="emp_cancel" name="emp_cancel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="active" <?php echo ($employee['emp_cancel'] === 'active') ? 'selected' : ''; ?>>ใช้งาน</option>
                            <option value="inactive" <?php echo ($employee['emp_cancel'] === 'inactive') ? 'selected' : ''; ?>>พ้นสภาพ</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 flex items-start gap-2 text-xs text-amber-800 bg-amber-50 dark:bg-gray-700 dark:text-amber-300 rounded-lg px-3 py-2.5 border border-amber-200 dark:border-amber-800">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none">
                            <path d="M12 9v4M12 17h.01M10.3 3.9L2.7 18a1.5 1.5 0 001.3 2.2h16a1.5 1.5 0 001.3-2.2L13.7 3.9a1.5 1.5 0 00-2.6 0z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                        </svg>
                        <span>การกำหนดสิทธิ์ที่นี่ควบคุมเฉพาะการแสดงผลหน้าเว็บ ระบบจริงต้องตรวจสอบสิทธิ์ (account_type) ซ้ำในทุก API ฝั่ง Backend เสมอ</span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-5 mt-5 border-t border-gray-200 dark:border-gray-700">
                    <a href="/employees" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">ยกเลิก</a>
                    <button type="submit" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<!-- <div id="docPreviewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">ดูเอกสาร</span>
            <button type="button" onclick="closeDocPreview()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto p-2 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
            <img id="docPreviewImage" class="max-w-full max-h-[75vh] hidden" alt="preview">
            <iframe id="docPreviewFrame" class="w-full h-[75vh] hidden" frameborder="0"></iframe>
        </div>
    </div>
</div> -->
<script src="../assets/js/auto-dash.js"></script>
<script>
    // สลับแท็บ
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(function(b) {
                b.setAttribute('aria-selected', 'false');
                b.classList.remove('text-primary-700', 'dark:text-blue-400', 'border-primary-700', 'dark:border-blue-400');
                b.classList.add('text-gray-500', 'border-transparent');
            });
            document.querySelectorAll('.tab-panel').forEach(function(p) {
                p.classList.add('hidden');
            });
            btn.setAttribute('aria-selected', 'true');
            btn.classList.remove('text-gray-500', 'border-transparent');
            btn.classList.add('text-primary-700', 'dark:text-blue-400', 'border-primary-700', 'dark:border-blue-400');
            document.getElementById(btn.dataset.tab).classList.remove('hidden');
        });
    });

    // function openDocPreview(url, type) {
    //     const modal = document.getElementById('docPreviewModal');
    //     const img = document.getElementById('docPreviewImage');
    //     const frame = document.getElementById('docPreviewFrame');

    //     img.classList.add('hidden');
    //     frame.classList.add('hidden');
    //     img.src = '';
    //     frame.src = '';

    //     if (type === 'image') {
    //         img.src = url;
    //         img.classList.remove('hidden');
    //     } else {
    //         frame.src = url;
    //         frame.classList.remove('hidden');
    //     }

    //     modal.classList.remove('hidden');
    // }

    // function closeDocPreview() {
    //     const modal = document.getElementById('docPreviewModal');
    //     document.getElementById('docPreviewImage').src = '';
    //     document.getElementById('docPreviewFrame').src = '';
    //     modal.classList.add('hidden');
    // }

    // ปิด modal เมื่อคลิกพื้นหลัง
    document.getElementById('docPreviewModal').addEventListener('click', function(e) {
        if (e.target === this) closeDocPreview();
    });

    // ปิด modal ด้วย ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDocPreview();
    });
</script>