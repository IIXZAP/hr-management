<?php
// views/employees/_table.php
// ไฟล์นี้คืนแค่ <table> + modal — ไม่มี header, form filter, script
// ถูกเรียกจาก 2 ที่: list.php (โหลดครั้งแรก) และ EmployeeController::table() (AJAX)
$employees = $employees ?? [];
$i = 1;
$modalsHtml = '';
?>

<table id="employeeTable" class="w-full text-sm text-left">
    <thead class="bg-gray-50 text-gray-600">
        <tr>
            <th class="px-4 py-3">#</th>
            <th class="px-4 py-3">รหัส</th>
            <th class="px-4 py-3 text-center">รูป</th>
            <th class="px-4 py-3">ชื่อ-สกุล</th>
            <th class="px-4 py-3">ชื่อเล่น</th>
            <th class="px-4 py-3">ตำแหน่ง</th>
            <th class="px-4 py-3">เบอร์โทร</th>
            <th class="px-4 py-3">E-mail</th>
            <th class="px-4 py-3">ระดับผู้ใช้งาน</th>
            <th class="px-4 py-3 text-center">จัดการ</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">

        <?php if (count($employees) === 0): ?>
            <tr>
                <td colspan="10" class="px-4 py-6 text-center text-gray-400">ยังไม่มีข้อมูลพนักงาน</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($employees as $employee): ?>
            <?php if (empty($employee['emp_id'])) continue; ?>

            <tr class="emp-row <?php echo Employee::isCancelled($employee) ? 'filter grayscale bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500' : ''; ?>">
                <td class="px-4 py-3"><?php echo $i; ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($employee['emp_no']); ?></td>
                <td class="px-4 py-3">
                    <?php if (!empty($employee['url'])) : ?>
                        <img src="/uploads/profile/<?php echo htmlspecialchars($employee['url']); ?>"
                            alt="<?php echo htmlspecialchars($employee['emp_name_th'] ?? ''); ?>"
                            class="w-16 object-cover">
                    <?php else : ?>
                        <img src="/assets/images/profile.png"
                            alt="<?php echo htmlspecialchars($employee['emp_name_th'] ?? ''); ?>"
                            class="w-16 object-cover">
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <?php echo htmlspecialchars($employee['emp_name_th']); ?>
                    <?php echo htmlspecialchars($employee['emp_sname_th']); ?>
                </td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($employee['emp_nickname_th']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($employee['position_name'] ?? ''); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($employee['emp_tel']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($employee['emp_email']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($employee['role_name'] ?? 'staff'); ?></td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-1">
                        <a href="/employees/view?id=<?php echo $employee['emp_id']; ?>" title="ดูรายละเอียด" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-primary-700">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" stroke="currentColor" stroke-width="1.6" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                            </svg>
                        </a>

                        <?php if (!Employee::isCancelled($employee)): ?>
                            <a href="/employees/edit?id=<?php echo $employee['emp_id']; ?>" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-primary-700" title="แก้ไข">
                                <svg class="w-4 h-4 text-gray-400 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <button type="button"
                                data-modal-target="cancelModal-<?php echo $employee['emp_id']; ?>"
                                data-modal-toggle="cancelModal-<?php echo $employee['emp_id']; ?>"
                                title="ยกเลิก" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-primary-700">
                                <svg class="w-4 h-4 text-gray-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.36 6.64a9 9 0 11-12.73 0M12 3v9" />
                                </svg>
                            </button>
                        <?php else: ?>
                            <button type="button" data-modal-target="reactivateModal-<?php echo $employee['emp_id']; ?>" data-modal-toggle="reactivateModal-<?php echo $employee['emp_id']; ?>"
                                title="กู้คืนสถานะ" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-primary-700">
                                <svg class="w-4 h-4 text-gray-400 hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php $i++; ?>

            <?php
            ob_start();
            if (!Employee::isCancelled($employee)):
            ?>
                <div id="cancelModal-<?php echo $employee['emp_id']; ?>" tabindex="-1"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="modal-panel relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 transition-all duration-200 ease-out opacity-0 scale-95">

                            <div class="p-4 md:p-5 text-center">
                                <svg class="mx-auto mb-4 bg-red-50 p-2 rounded-full text-red-500 w-12 h-12" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20">
                                    <path d="M0 0h20v20H0z" fill="none" />
                                    <path fill="currentColor" d="M10 2a8 8 0 1 1 0 16a8 8 0 0 1 0-16m0 1a7 7 0 1 0 0 14a7 7 0 0 0 0-14m0 9.5a.75.75 0 1 1 0 1.5a.75.75 0 0 1 0-1.5M10 6a.5.5 0 0 1 .492.41l.008.09V11a.5.5 0 0 1-.992.09L9.5 11V6.5A.5.5 0 0 1 10 6" />
                                </svg>

                                </svg>
                                <h3 class="mb-6 text-body">ต้องการยกเลิกพนักงานคนนี้ใช่หรือไม่?</h3>
                                <div class="flex items-center gap-4 justify-center">
                                    <form action="/employees/cancel" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="emp_id" value="<?php echo (int) $employee['emp_id']; ?>">
                                        <button type="submit"
                                            class="text-white bg-fg-danger hover:bg-danger-strong box-border border border-transparent focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                            ยืนยัน
                                        </button>
                                    </form>
                                    <button data-modal-hide="cancelModal-<?php echo $employee['emp_id']; ?>" type="button"
                                        class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                        ยกเลิก
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div id="reactivateModal-<?php echo $employee['emp_id']; ?>" tabindex="-1"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="modal-panel relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 transition-all duration-200 ease-in opacity-0 scale-95">

                            <div class="p-4 md:p-5 text-center">
                                <svg class="mx-auto bg mb-4 bg-red-50 p-2 rounded-full text-red-500 w-12 h-12" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20">
                                    <path d="M0 0h20v20H0z" fill="none" />
                                    <path fill="currentColor" d="M10 2a8 8 0 1 1 0 16a8 8 0 0 1 0-16m0 1a7 7 0 1 0 0 14a7 7 0 0 0 0-14m0 9.5a.75.75 0 1 1 0 1.5a.75.75 0 0 1 0-1.5M10 6a.5.5 0 0 1 .492.41l.008.09V11a.5.5 0 0 1-.992.09L9.5 11V6.5A.5.5 0 0 1 10 6" />
                                </svg>
                                <h3 class="mb-6 text-body">ต้องการกู้คืนสถานะพนักงานคนนี้ใช่หรือไม่?</h3>
                                <div class="flex items-center gap-4 justify-center">
                                    <form action="/employees/reactivate" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="emp_id" value="<?php echo (int) $employee['emp_id']; ?>">
                                        <button type="submit"
                                            class="text-white bg-green-600 hover:bg-green-700 box-border border border-transparent focus:ring-4 focus:ring-green-200 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                            ใช่ กู้คืนสถานะ
                                        </button>
                                    </form>
                                    <button data-modal-hide="reactivateModal-<?php echo $employee['emp_id']; ?>" type="button"
                                        class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                        ยกเลิก
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            $modalsHtml .= ob_get_clean();
            ?>
        <?php endforeach; ?>

    </tbody>
</table>

<?php echo $modalsHtml; ?>