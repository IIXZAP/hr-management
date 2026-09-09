<?php
// views/employees/list.php
// หน้าที่: แสดงรายชื่อพนักงานทั้งหมด
// รับตัวแปร $employees มาจาก EmployeeController::index()
// ไม่มี <html>/<body>/<script> ของตัวเอง — header.php + footer.php ครอบให้แล้ว
// (ถ้าเติม <html> ซ้ำที่นี่ จะกลายเป็น <html> ซ้อนกัน 2 ชั้นในหน้าเดียว)
// Ensure the view can render safely when no employee data is supplied.
$employees = $employees ?? [];
$totalPages = $totalPages ?? 1;
$page = $page ?? 1;
$i = 1;
?>

<h1 class="text-2xl font-bold text-gray-900 mb-4">รายชื่อพนักงาน</h1>
<div class="flex justify-end">
    <a href="/employees/create" class="inline-block mb-4 px-4 py-2 bg-default-medium text-white rounded-lg text-sm hover:bg-blue-700">
        + เพิ่มพนักงานใหม่
    </a>
</div>
<!-- <form method="GET" action="/employees" class="bg-white rounded-lg border border-gray-200 p-3 mb-3 flex justify-between">
    <label for="search" class="sr-only">Search</label>
    <div class="relative flex-1">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
        </div>
        <input type="text"
            id="search"
            name="search"
            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
            class="block w-full max-w-lg ps-9 pe-3 py-2 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="ค้นหาชื่อ/รหัสพนักงาน">
    </div>

    <?php
    // สถานะปัจจุบันเพื่อโชว์ label บนปุ่ม
    $currentStatus = $_GET['status'] ?? '';
    $statusLabel = match ($currentStatus) {
        '0' => 'Inactive',
        '1' => 'Active',
        default => 'ทั้งหมด',
    };
    ?>

    <input type="hidden" name="status" id="statusInput" value="<?php echo htmlspecialchars($currentStatus); ?>">

    <div class="ms-2">
        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="shrink-0 inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2 focus:outline-none" type="button">
            <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
            </svg>
            <span id="statusLabel"><?php echo $statusLabel; ?></span>
            <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <div id="dropdown" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-40">
            <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownDefaultButton">
                <li>
                    <button type="button" onclick="selectStatus('', 'ทั้งหมด')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded text-left">
                        ทั้งหมด
                    </button>
                </li>
                <li>
                    <button type="button" onclick="selectStatus('1', 'Active')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded text-left">
                        Active
                    </button>
                </li>
                <li>
                    <button type="button" onclick="selectStatus('0', 'Inactive')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded text-left">
                        Inactive
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <button type="submit" class="ms-2 shrink-0 bg-brand text-white rounded-base text-sm px-4 py-2 hover:bg-brand-strong">
        ค้นหา
    </button>

    <?php if (!empty($_GET['search']) || $currentStatus !== ''): ?>
        <a href="/employees" class="ms-2 shrink-0 text-body text-sm underline self-center hover:text-heading">
            ล้างตัวกรอง
        </a>
    <?php endif; ?>
</form> -->


<!-- <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
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
                <?php if (empty($employee['emp_id'])) continue; // กัน row ที่ไม่มี emp_id ไม่ให้สร้าง modal id ว่าง 
                ?>

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
                            <a href="/employees/view?id=<?php echo $employee['emp_id']; ?>" title="ดูรายละเอียด" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-primary-700 dark:hover:bg-slate-800">
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
                // เก็บ modal HTML ไว้ก่อน แล้วค่อย echo หลังปิด table
                // (กัน invalid HTML: div ไม่ควรอยู่ใน tbody โดยตรง)
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
</div>

<?php //echo $modalsHtml; 
?>

<div id="empPagination" class="flex justify-end gap-2 mt-4"></div> -->

<!-- <script>
    (function() {
        var rowsPerPage = 10;
        var rows = document.querySelectorAll('#employeeTable .emp-row');
        var totalRows = rows.length;
        var totalPages = Math.ceil(totalRows / rowsPerPage);
        var currentPage = 1;

        function showPage(page) {
            currentPage = page;
            rows.forEach(function(row, index) {
                var rowPage = Math.floor(index / rowsPerPage) + 1;
                row.classList.toggle('hidden', rowPage !== page);
            });
            renderPageButtons();
        }

        function renderPageButtons() {
            var container = document.getElementById('empPagination');
            container.innerHTML = '';
            if (totalPages <= 1) return;
            for (var i = 1; i <= totalPages; i++) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = i;
                btn.className = 'px-3 py-1.5 border rounded-lg text-sm ' +
                    (i === currentPage ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50');
                btn.onclick = (function(pageNum) {
                    return function() {
                        showPage(pageNum);
                    };
                })(i);
                container.appendChild(btn);
            }
        }

        if (totalRows > 0) showPage(1);
    })();

    function selectStatus(value, label) {
        document.getElementById('statusInput').value = value;
        document.getElementById('statusLabel').textContent = label;
        document.getElementById('dropdown').classList.add('hidden');
        document.querySelector('form').submit();
    }

    // เฝ้าดูทุก modal ที่ id ขึ้นต้นด้วย cancelModal- หรือ reactivateModal-
    document.querySelectorAll('[id^="cancelModal-"], [id^="reactivateModal-"]').forEach(function(modal) {
        const panel = modal.querySelector('.modal-panel');

        // เฝ้าดูการเปลี่ยน class ของ modal (Flowbite toggle 'hidden' ตรงนี้)
        const observer = new MutationObserver(function() {
            if (!modal.classList.contains('hidden')) {
                // modal เพิ่งถูกเปิด (hidden ถูกลบออก)
                // รอ 1 frame ก่อนใส่ class ใหม่ ไม่งั้น browser ไม่เห็น state เปลี่ยน (transition จะไม่เล่น)
                requestAnimationFrame(function() {
                    panel.classList.remove('opacity-0', 'scale-95');
                    panel.classList.add('opacity-100', 'scale-100');
                });
            } else {
                // modal ถูกซ่อน — reset กลับเป็น state เริ่มต้น (เผื่อเปิดใหม่รอบหน้า)
                panel.classList.remove('opacity-100', 'scale-100');
                panel.classList.add('opacity-0', 'scale-95');
            }
        });

        observer.observe(modal, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
</script> -->
<div class="bg-white rounded-lg border border-gray-200 p-3 mb-3 flex justify-between">
    <div class="relative flex-1">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-body" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
        </div>
        <input type="text" id="searchInput"
            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
            class="block w-full max-w-lg ps-9 pe-3 py-2 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="ค้นหาชื่อ/รหัสพนักงาน">
    </div>

    <select id="statusInput" class="ms-2 w-32 border border-default-medium rounded-base text-sm px-3 py-2">
        <option value="">ทั้งหมด</option>
        <option value="1" <?php echo ($_GET['status'] ?? '') === '1' ? 'selected' : ''; ?>>Active</option>
        <option value="0" <?php echo ($_GET['status'] ?? '') === '0' ? 'selected' : ''; ?>>Inactive</option>
    </select>

    <div id="loadingIndicator" class="hidden ms-2 self-center text-sm text-gray-400">
        กำลังค้นหา...
    </div>
</div>
<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <div id="tableContainer">
        <?php require __DIR__ . '/_table.php'; ?>
    </div>
</div>
<div id="empPagination" class="flex justify-end gap-2 mt-4"></div> 

<script>
    var rowsPerPage = 10;
    const searchInput = document.getElementById('searchInput');
    const statusInput = document.getElementById('statusInput');
    const tableContainer = document.getElementById('tableContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    let debounceTimer;
    // กำหนด
    function initPagination()
    {
        var rows = document.querySelectorAll('#employeeTable .emp-row');
        var totalRow = rows.length;
        var totalPages = Math.ceil(totalRow / rowsPerPage);

        showPage(1, rows, totalPages);
    }

    function showPage(page, rows, totalPages)
    {
        rows.forEach(function(row, index) {
            var rowPage = Math.floor(index / rowsPerPage) + 1;
            row.classList.toggle('hidden', rowPage !== page);
        });

        var container = document.getElementById('empPagination');
        if(!container) return ;
        container.innerHTML = '';

        for (let i = 1; i <= totalPages; i++ ){
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = i;
            btn.className = 'px-3 py-1.5 border rounded-lg text-sm ' +
                (i === page ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50');
            btn.onclick = function() {
                showPage(i, rows, totalPages);
            };
            container.appendChild(btn);
        }
    }


    function initModalAnimations() {
        document.querySelectorAll('[id^="cancelModal-"], [id^="reactivateModal-"]').forEach(function(modal) {
            if (modal.dataset.observed === 'true') return;
            modal.dataset.observed = 'true';

            const panel = modal.querySelector('.modal-panel');
            if (!panel) return;

            const observer = new MutationObserver(function() {
                if (!modal.classList.contains('hidden')) {
                    requestAnimationFrame(function() {
                        panel.classList.remove('opacity-0', 'scale-95');
                        panel.classList.add('opacity-100', 'scale-100');
                    });
                } else {
                    panel.classList.remove('opacity-100', 'scale-100');
                    panel.classList.add('opacity-0', 'scale-95');
                }
            });

            observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
        });
    }

    async function fetchTable() {
        const params = new URLSearchParams();
        if (searchInput.value) params.set('search', searchInput.value);
        if (statusInput.value !== '') params.set('status', statusInput.value);

        const newUrl = '/employees' + (params.toString() ? '?' + params.toString() : '');
        history.pushState({}, '', newUrl);

        loadingIndicator.classList.remove('hidden');

        try {
            const response = await fetch('/employees/table?' + params.toString());
            if (!response.ok) {
                throw new Error('Server error: ' + response.status);
            }
            const html = await response.text();
            tableContainer.innerHTML = html;

            if (window.initFlowbite) {
                window.initFlowbite();
            }

            initPagination();       // คำนวณ pagination ใหม่ตามข้อมูลที่ filter มา
            initModalAnimations();  // ผูก animation ให้ modal ชุดใหม่

        } catch (error) {
            console.error('โหลดข้อมูลไม่สำเร็จ:', error);
            tableContainer.innerHTML = '<p class="text-center text-red-400 py-6">เกิดข้อผิดพลาด ไม่สามารถโหลดข้อมูลได้</p>';
        } finally {
            loadingIndicator.classList.add('hidden');
        }
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchTable, 400);
    });

    statusInput.addEventListener('change', fetchTable);

    window.addEventListener('popstate', function() {
        const params = new URLSearchParams(window.location.search);
        searchInput.value = params.get('search') || '';
        statusInput.value = params.get('status') || '';
        fetchTable();
    });

    // ==================== เรียกครั้งแรกตอนหน้าโหลด ====================
    initPagination();
    initModalAnimations();
</script>