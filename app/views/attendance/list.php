<?php

$attendances = $attendances ?? [];
$employees = $employees ?? [];
$countAll = $countAll ?? '-';
$countAttendance = $countAttendance ?? '-';
$countLeave = $countLeave ?? '-';
$i = 1;

?>

<h1 class="text-2xl font-bold text-gray-900 ">บันทึกเวลาทำงาน</h1>
<div class="flex justify-end">
    <button data-modal-target="createAttendanceModal" data-modal-toggle="createAttendanceModal"
        type="button"
        class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        + เพิ่มเวลา
    </button>
</div>

<!-- ============ Modal: เพิ่มเวลาเข้า-ออกงาน ============ -->
<div id="createAttendanceModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">

            <!-- Header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    เพิ่มเวลาเข้า–ออกงาน
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="createAttendanceModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">ปิด</span>
                </button>
            </div>

            <!-- Body: ฟอร์ม -->
            <form action="/attendance/store" method="POST">
                <div class="p-4 md:p-5 space-y-4">
                    <div>
                        <label for="emp_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">พนักงาน</label>
                        <select id="emp_id" name="emp_id" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">-- เลือกพนักงาน --</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?php echo (int) $employee['emp_id']; ?>">
                                    <?php echo htmlspecialchars($employee['emp_name_th'] . ' ' . $employee['emp_sname_th']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="work_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">วันที่</label>
                        <input type="date" id="work_date" name="work_date" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="check_in" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เวลาเข้า</label>
                            <input type="time" id="check_in" name="check_in"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div>
                            <label for="check_out" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เวลาออก</label>
                            <input type="time" id="check_out" name="check_out"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 gap-2">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        บันทึก
                    </button>
                    <button type="button" data-modal-hide="createAttendanceModal"
                        class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        ยกเลิก
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="bg-gray-200 p-1 rounded-xl">
        <div class="bg-white rounded-xl p-4 ring-1 ring-slate-200/70 dark:ring-slate-800 shadow-sm shadow-slate-200/60 dark:shadow-none">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">พนักงานทั้งหมด</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-primary-700 dark:text-blue-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12a4 4 0 100-8 4 4 0 000 8zM3 21v-1a6 6 0 016-6h0a6 6 0 016 6v1M17 11a3 3 0 100-6 3 3 0 000 6zM21 21v-1a5 5 0 00-4-4.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800 dark:text-white" id="statTotal"><?= $countAll ?></p>

        </div>
    </div>
    <div class="bg-gray-200 p-1 rounded-xl">
        <div class="bg-white rounded-xl p-4 ring-1 ring-slate-200/70 dark:ring-slate-800 shadow-sm shadow-slate-200/60 dark:shadow-none">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">เข้างานวันนี้</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-primary-700 dark:text-blue-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12a4 4 0 100-8 4 4 0 000 8zM3 21v-1a6 6 0 016-6h0a6 6 0 016 6v1M17 11a3 3 0 100-6 3 3 0 000 6zM21 21v-1a5 5 0 00-4-4.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800 dark:text-white" id="statTotal"><?= $countAttendance ?></p>

        </div>
    </div>
    <div class="bg-gray-200 p-1 rounded-xl">
        <div class="bg-white rounded-xl p-4 ring-1 ring-slate-200/70 dark:ring-slate-800 shadow-sm shadow-slate-200/60 dark:shadow-none">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">ลาวันนี้</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-primary-700 dark:text-blue-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12a4 4 0 100-8 4 4 0 000 8zM3 21v-1a6 6 0 016-6h0a6 6 0 016 6v1M17 11a3 3 0 100-6 3 3 0 000 6zM21 21v-1a5 5 0 00-4-4.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800 dark:text-white" id="statTotal"><?= $countLeave ?></p>

        </div>
    </div>
</div>
<div id="empPagination" class="flex justify-end gap-2 mt-4"></div>

<div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">

    <div class="p-5">
        <h2 class="text-lg font-medium text-heading">สรุปเวลาเข้า - ออก</h2>
    </div>

    <form method="GET" class="bg-white border-t border-gray-200 p-4 flex flex-wrap items-center gap-3">
        <div>
            <label for="filterDateStart" class="sr-only">วันที่</label>
            <input type="date" id="filterDateStart" name="start"
                value="<?php echo htmlspecialchars($_GET['start'] ?? date('Y-m-d')); ?>"
                class="block bg-neutral-secondary-medium border-t border-b border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs">
        </div>
        <div>
            <label for="filterDateEnd" class="sr-only">ถึง</label>
            <input type="date" id="filterDateEnd" name="end"
                value="<?php echo htmlspecialchars($_GET['end'] ?? date('Y-m-d')); ?>"
                class="block bg-neutral-secondary-medium border-t border-b border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs">
        </div>

        <!-- <div class="relative flex-1 min-w-[200px]">
            <label for="filterSearch" class="sr-only">ค้นหาชื่อหรือรหัสพนักงาน</label>
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" id="filterSearch" name="search"
                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                placeholder="ค้นหาชื่อหรือรหัสพนักงาน">
        </div> -->

        <div class="relative flex-1 min-w-[200px]">
            <label for="filterSearch" class="sr-only">ค้นหาชื่อหรือรหัสพนักงาน</label>
            <select id="emp_id" name="emp_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                <option value="">ทุกคน</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?php echo (int) $employee['emp_id']; ?>">
                        <?php echo htmlspecialchars($employee['emp_name_th'] . ' ' . $employee['emp_sname_th']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="ms-2 shrink-0 bg-brand text-white rounded-base text-sm px-4 py-2 hover:bg-brand-strong">
            ค้นหา
        </button>
        <?php if (!empty($_GET['start']) || !empty($_GET['end']) || !empty($_GET['search'])): ?>
            <a href="?" class="text-sm text-body hover:underline">ล้างตัวกรอง</a>
        <?php endif; ?>
    </form>

    <table class="w-full text-sm text-left rtl:text-right text-body">
        <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium">
            <tr>
                <th class="px-4 py-3 text-center">#</th>
                <th class="px-4 py-3 text-center">รหัส</th>
                <th class="px-4 py-3 text-center">ชื่อ</th>
                <th class="px-4 py-3 text-center">วันที่</th>
                <th class="px-4 py-3 text-center">เข้า</th>
                <th class="px-4 py-3 text-center">ออก</th>
                <th class="px-4 py-3 text-center">ลา</th>
                <th class="px-4 py-3 text-center">จำนวนชม.</th>
                <th class="px-4 py-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">

            <?php if (count($attendances) === 0): ?>
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-400">ยังไม่มีข้อมูลพนักงาน</td>
                </tr>
            <?php endif; ?>
            <?php $i = 1; ?>
            <?php foreach ($attendances as $attendance): ?>
                <?php
                // rowKey ระบุแถวไม่ซ้ำ (emp_id + work_date รวมกัน เพราะพนักงานคนเดียวมีได้หลายวัน)
                // ถ้าตาราง attendance มี primary key ของตัวเอง (เช่น attendance_id) แนะนำใช้ตัวนั้นแทน
                $rowKey = (int) $attendance['emp_id'] . '-' . preg_replace('/[^0-9]/', '', $attendance['work_date'] ?? '');

                $has_full_attendance = !empty($attendance['check_in']) && !empty($attendance['check_out']);
                $display_time = '-';
                if ($has_full_attendance) {
                    $seconds = strtotime($attendance['check_out']) - strtotime($attendance['check_in']);
                    $display_time = $seconds > 0 ? gmdate("H:i", $seconds) : '-';
                }

                // TODO: ถ้ามี column สถานะแยกต่างหาก (เช่น attendance['status'] === 'leave')
                // ให้เปลี่ยนมาเช็คจาก column นั้นแทน จะแม่นยำกว่าการเดาจากค่าว่าง
                $is_on_leave = !empty($attendance['leave_id']);
                ?>
                <tr class="emp-row hover:bg-neutral-secondary-medium" data-row="<?php echo $rowKey; ?>">
                    <td class="px-4 py-3 text-center"><?php echo $i; ?></td>
                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($attendance['emp_no']); ?></td>

                    <td class="px-4 py-3">
                        <?php echo htmlspecialchars($attendance['emp_name_th']); ?>
                        <?php echo htmlspecialchars($attendance['emp_sname_th']); ?>
                    </td>
                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($attendance['work_date'] ?? date('Y-m-d')); ?></td>

                    <!-- เข้า: view-mode = ตัวหนังสือ, edit-mode = input ซ่อนไว้ (คนละ element กับ layout class เสมอ) -->
                    <td class="px-4 py-3 text-center">
                        <span class="view-mode"><?php echo htmlspecialchars($attendance['check_in'] ?: '-'); ?></span>
                        <input type="time" form="editForm-<?php echo $rowKey; ?>" name="check_in"
                            value="<?php echo htmlspecialchars($attendance['check_in'] ?? ''); ?>"
                            class="edit-mode hidden w-28 mx-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5">
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="view-mode"><?php echo htmlspecialchars($attendance['check_out'] ?: '-'); ?></span>
                        <input type="time" form="editForm-<?php echo $rowKey; ?>" name="check_out"
                            value="<?php echo htmlspecialchars($attendance['check_out'] ?? ''); ?>"
                            class="edit-mode hidden w-28 mx-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5">
                    </td>

                    <td class="px-4 py-3 text-center">
                        <?php if ($is_on_leave): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                <?php echo htmlspecialchars($attendance['leave_type_name'] ?? 'ลา'); ?>
                            </span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($display_time); ?></td>

                    <td class="px-4 py-3 text-center">
                        <!-- โหมดดูข้อมูลปกติ: ไอคอนแก้ไข + ฟอร์มลบ
                             หมายเหตุ: hidden (ควบคุมซ่อน/โชว์) กับ inline-flex (จัด layout) ต้องแยกคนละ element
                             เสมอ ห้ามใส่ไว้ที่เดียวกัน ไม่งั้นจะเจอบั๊กที่ hidden ไม่มีผลเหมือนที่เคยเจอมาก่อน -->
                        <span class="view-mode">
                            <span class="inline-flex items-center justify-center gap-1">
                                <button type="button" title="แก้ไข" onclick="toggleEditRow('<?php echo $rowKey; ?>')"
                                    class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-primary-700 dark:hover:bg-slate-800">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                                        <path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <form action="/attendance/delete" method="POST" class="inline">
                                    <input type="hidden" name="emp_id" value="<?php echo (int) $attendance['emp_id']; ?>">
                                    <button type="submit" onclick="return confirm('ยืนยันการลบข้อมูล?');"
                                        class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-primary-700 dark:hover:bg-slate-800">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                                            <path d="M6 6l12 12" stroke="currentColor" stroke-width="1.6" />
                                        </svg>
                                    </button>
                                </form>
                            </span>
                        </span>

                        <!-- โหมดแก้ไข: ปุ่มบันทึก/ยกเลิก ปกติซ่อนไว้ -->
                        <span class="edit-mode hidden">
                            <span class="inline-flex items-center justify-center gap-2">
                                <button type="submit" form="editForm-<?php echo $rowKey; ?>"
                                    class="px-2.5 py-1 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                                    บันทึก
                                </button>
                                <button type="button" onclick="cancelEditRow('<?php echo $rowKey; ?>')"
                                    class="px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                                    ยกเลิก
                                </button>
                            </span>
                        </span>
                    </td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>

        </tbody>
    </table>

    <!-- ฟอร์มบันทึกของแต่ละแถว วางไว้นอกตารางทั้งหมด ผูกกับ input/button ในแถวผ่าน form="..." -->
    <!-- ฟอร์มบันทึกของแต่ละแถว วางไว้นอกตารางทั้งหมด -->
    <?php foreach ($attendances as $attendance): ?>
        <?php $rowKey = (int) $attendance['emp_id'] . '-' . preg_replace('/[^0-9]/', '', $attendance['work_date'] ?? ''); ?>
        <form id="editForm-<?php echo $rowKey; ?>" action="/attendance/update" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="att_id" value="<?php echo (int) $attendance['att_id']; ?>">
        </form>
    <?php endforeach; ?>
</div>

<script>
    function toggleEditRow(rowKey) {
        document.querySelectorAll('[data-row="' + rowKey + '"] .view-mode').forEach(function(el) {
            el.classList.add('hidden');
        });
        document.querySelectorAll('[data-row="' + rowKey + '"] .edit-mode').forEach(function(el) {
            el.classList.remove('hidden');
        });
    }

    function cancelEditRow(rowKey) {
        document.querySelectorAll('[data-row="' + rowKey + '"] .view-mode').forEach(function(el) {
            el.classList.remove('hidden');
        });
        document.querySelectorAll('[data-row="' + rowKey + '"] .edit-mode').forEach(function(el) {
            el.classList.add('hidden');
        });
    }
</script>