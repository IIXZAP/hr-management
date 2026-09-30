<?php
// views/documents/upload.php
$avatarPalette = [
    ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
    ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
    ['bg' => 'bg-pink-100', 'text' => 'text-pink-700'],
    ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
];

$employees = $employees ?? [];
$selectedEmployee = $selectedEmployee ?? null;
$documents = $documents ?? [];
?>
<div class="max-w-6xl mx-auto">

    <nav class="text-sm mb-2">
        <span class="text-primary-700 font-medium">งานบุคคล</span>
        <span class="text-body mx-1">/</span>
        <span class="text-body">เอกสารรายบุคคล</span>
    </nav>

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">อัปโหลดเอกสารพนักงาน</h1>
            <p class="text-sm text-body mt-1">จัดการเอกสารสำคัญของพนักงานแต่ละคนในที่เดียว</p>
        </div>
        <button type="button"
            <?php if ($selectedEmployee !== null): ?>
            data-modal-target="uploadDocModal" data-modal-toggle="uploadDocModal"
            <?php else: ?>
            disabled
            <?php endif; ?>
            class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 text-white text-sm font-medium rounded-base
        <?php echo $selectedEmployee === null ? 'bg-gray-300 cursor-not-allowed' : 'bg-primary-700 hover:bg-primary-800'; ?>">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            อัปโหลดเอกสาร
        </button>
    </div>

    <!-- การ์ด 1: เลือกพนักงาน -->
    <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5 mb-4">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-base font-semibold text-heading">เลือกพนักงาน</h2>
                <p class="text-sm text-body mt-0.5">ค้นหาจากชื่อ รหัส หรือแผนก</p>
            </div>
            <span class="shrink-0 px-2.5 py-1 text-xs font-medium text-primary-700 bg-primary-50 rounded-full"><?php echo count($employees); ?> คน</span>
        </div>

        <div class="mb-4">
            <label for="employeeSearch" class="sr-only">เลือกพนักงาน</label>
            <select id="employeeSearch" onchange="selectEmployee(this.value)"
                class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand">
                <option value="">-- เลือกพนักงาน --</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?php echo (int) $employee['emp_id']; ?>"
                        <?php echo ($selectedEmployee !== null && (int) $employee['emp_id'] === (int) $selectedEmployee['emp_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($employee['emp_name_th'] . ' ' . $employee['emp_sname_th'] . ' — ' . $employee['emp_no']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- รวมการ์ด 2 + 3 เป็น section เดียว -->
    <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs">

        <!-- ส่วนหัว -->
        <div class="p-5 flex items-start justify-between">
            <div>
                <h2 class="text-base font-semibold text-heading">เอกสารของพนักงาน</h2>
                <p class="text-sm text-body mt-0.5">ไฟล์ทั้งหมดที่ HR จัดเก็บและแชร์ให้พนักงาน</p>
            </div>
            <button type="button"
                <?php if ($selectedEmployee !== null): ?>
                data-modal-target="uploadDocModal" data-modal-toggle="uploadDocModal"
                <?php else: ?>
                disabled
                <?php endif; ?>
                class="shrink-0 inline-flex items-center gap-1.5 px-3.5 py-2 border border-default text-heading text-sm font-medium rounded-base
            <?php echo $selectedEmployee === null ? 'opacity-40 cursor-not-allowed' : 'hover:bg-neutral-secondary-medium'; ?>">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                เพิ่มเอกสาร
            </button>
        </div>

        <!-- แถบข้อมูลพนักงานที่เลือก (แทนที่การ์ด 2 เดิม) — สไตล์เหมือนแถบ filter ในภาพ -->
        <!-- แถบข้อมูลพนักงานที่เลือก -->
        <div class="border-t border-default bg-primary-50 px-5 py-4 flex items-center justify-between">
            <?php if ($selectedEmployee === null): ?>
                <p class="text-sm text-body">กรุณาเลือกพนักงานจากรายการด้านบนก่อน</p>
            <?php else: ?>
                <div class="flex items-center gap-4">
                    <span class="shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-base font-bold bg-primary-700 text-white shadow-sm">
                        <?php echo htmlspecialchars(mb_substr($selectedEmployee['emp_name_th'], 0, 1) . mb_substr($selectedEmployee['emp_sname_th'], 0, 1)); ?>
                    </span>
                    <div>
                        <p class="text-base font-bold text-heading">
                            <?php echo htmlspecialchars($selectedEmployee['emp_name_th'] . ' ' . $selectedEmployee['emp_sname_th']); ?>
                        </p>
                        <p class="text-sm text-primary-700 font-medium mt-0.5">
                            <?php echo htmlspecialchars(($selectedEmployee['position_name'] ?? '-') . ' · '  . $selectedEmployee['emp_no']); ?>
                        </p>
                    </div>
                </div>
                <span class="shrink-0 px-3 py-1.5 text-sm font-semibold text-primary-700 bg-white rounded-full shadow-sm">
                    <?php echo count($documents); ?> เอกสาร
                </span>
            <?php endif; ?>
        </div>

        <!-- ตาราง/รายการเอกสาร -->
        <div class="p-5">
            <?php if ($selectedEmployee === null): ?>
                <p class="py-8 text-center text-sm text-body">เลือกพนักงานก่อนเพื่อดูเอกสาร</p>
            <?php else: ?>
                <div class="divide-y divide-gray-100">
                    <?php if (count($documents) === 0): ?>
                        <p class="py-6 text-center text-sm text-body">พนักงานคนนี้ยังไม่มีเอกสารอัปโหลด</p>
                    <?php endif; ?>

                    <?php foreach ($documents as $doc): ?>
                        <?php $isReady = ($doc['doc_status'] === 'approved'); ?>
                        <div class="flex items-center justify-between py-3.5 gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="shrink-0 w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-[10px] font-bold">PDF</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-heading truncate"><?php echo htmlspecialchars(basename($doc['doc_url'])); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 shrink-0">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium <?php echo $isReady ? 'text-green-700' : 'text-amber-700'; ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo $isReady ? 'bg-green-500' : 'bg-amber-500'; ?>"></span>
                                    <?php echo $isReady ? 'Approved' : 'Pending'; ?>
                                </span>
                                <div class="flex items-center gap-1">
                                    <a href="<?php echo htmlspecialchars($doc['doc_url']); ?>" target="_blank" title="ดูเอกสาร" class="p-1.5 rounded-lg text-body hover:bg-neutral-secondary-medium hover:text-heading">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" stroke="currentColor" stroke-width="1.6" />
                                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                                        </svg>
                                    </a>
                                    <a href="<?php echo htmlspecialchars($doc['doc_url']); ?>" download title="ดาวน์โหลด" class="p-1.5 rounded-lg text-body hover:bg-neutral-secondary-medium hover:text-heading">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 4v11m0 0 4-4m-4 4-4-4M5 19h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>

                                    <!-- ปุ่ม approve — โชว์เฉพาะ admin + เอกสารที่ยัง pending -->
                                    <?php if (!$isReady && Auth::isAdmin()): ?>
                                        <form action="/documents/approve" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="doc_id" value="<?php echo (int) $doc['doc_id']; ?>">
                                            <button type="submit" title="อนุมัติเอกสาร"
                                                class="p-1.5 rounded-lg text-green-600 hover:bg-green-50">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                    <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 flex items-start gap-2 text-xs text-body bg-neutral-secondary-medium rounded-base px-3 py-2.5">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                        <path d="M12 8h.01M11 12h1v4h1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                    </svg>
                    <span>เอกสารที่อัปโหลดจะถูกจัดเก็บไว้ในโปรไฟล์ของพนักงานที่เลือกเท่านั้น</span>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<!-- Modal อัปโหลด -->
<div id="uploadDocModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-heading">อัปโหลดเอกสาร</h3>
                <button type="button" class="text-body bg-transparent hover:bg-gray-200 hover:text-heading rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="uploadDocModal">
                    <svg class="w-3 h-3" viewBox="0 0 14 14" fill="none">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">ปิด</span>
                </button>
            </div>
            <form action="/documents/store" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="p-4 md:p-5 space-y-4">
                    <input type="hidden" name="emp_id" value="<?php echo $selectedEmployee !== null ? (int) $selectedEmployee['emp_id'] : ''; ?>">
                    <div>
                        <label for="doc_category" class="block mb-2 text-sm font-medium text-heading">ประเภทเอกสาร</label>
                        <select id="doc_category" name="category" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5">
                            <option value="สัญญาจ้าง">สัญญาจ้าง</option>
                            <option value="เอกสารส่วนตัว">เอกสารส่วนตัว</option>
                            <option value="วุฒิการศึกษา">วุฒิการศึกษา</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label for="doc_file" class="block mb-2 text-sm font-medium text-heading">ไฟล์เอกสาร (PDF)</label>
                        <input type="file" id="doc_file" name="document" accept=".pdf" required
                            class="block w-full text-sm text-body border border-default-medium rounded-base cursor-pointer bg-neutral-secondary-medium focus:outline-none">
                    </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b gap-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-base text-sm px-5 py-2.5">อัปโหลด</button>
                    <button type="button" data-modal-hide="uploadDocModal" class="text-body bg-white border border-default font-medium rounded-base text-sm px-5 py-2.5 hover:bg-neutral-secondary-medium">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function selectEmployee(empId) {
        window.location.href = '?emp_id=' + empId;
    }
</script>