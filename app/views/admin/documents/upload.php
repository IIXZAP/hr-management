<?php
// views/documents/upload.php
// หน้าที่: อัปโหลด/ดูเอกสารรายบุคคลของพนักงาน (HR)
//
// ตัวแปรที่ต้องได้จาก Controller:
//   $employees         — array รายชื่อพนักงาน (emp_id, emp_name_th, emp_sname_th, emp_no)
//   $selectedEmployee  — array|null พนักงานที่เลือก (+ position_name)
//   $documents         — array เอกสารของพนักงานที่เลือก (doc_id, doc_url, doc_status)
//
// Hallmark redesign · macrostructure: Catalogue · tone: utilitarian
// (รอบก่อน: Long Document → รอบนี้เปลี่ยนโครง: แถบเลือก + ป้ายพนักงาน + ตารางรายการ)
// pre-emit critique: P4 H4 E4 S4 R5 V4

$employees = $employees ?? [];
$selectedEmployee = $selectedEmployee ?? null;
$documents = $documents ?? [];
$hasEmployee = ($selectedEmployee !== null);
$documentTypes = $documentTypes ?? [];

$docStatusBadge = [
    'approved' => ['label' => 'อนุมัติแล้ว', 'class' => 'bg-green-100 text-green-800'],
    'pending'  => ['label' => 'รออนุมัติ', 'class' => 'bg-amber-100 text-amber-800'],
];
?>
<div class="max-w-6xl mx-auto">

    <!-- <nav class="text-sm mb-2" aria-label="breadcrumb">
        <span class="text-blue-700 font-medium">งานบุคคล</span>
        <span class="text-body mx-1">/</span>
        <span class="text-body">เอกสารรายบุคคล</span>
    </nav> -->

    <!-- หัวหน้า : ชื่อหน้า ซ้าย / ปุ่มอัปโหลด ขวา (จุดเน้นเดียว) -->
    <header class="flex flex-wrap items-end justify-between gap-x-6 gap-y-4 mb-6">
        <div class="min-w-0">
            <h1 class="text-2xl font-bold tracking-tight text-heading [overflow-wrap:anywhere]">อัปโหลดเอกสารพนักงาน</h1>
            <p class="text-sm text-body mt-1">จัดการเอกสารสำคัญของพนักงานแต่ละคนในที่เดียว</p>
        </div>
        <button type="button"
            <?php if ($hasEmployee): ?>
            data-modal-target="uploadDocModal" data-modal-toggle="uploadDocModal"
            <?php else: ?>
            disabled
            <?php endif; ?>
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-base whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-blue-700">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            อัปโหลดเอกสาร
        </button>
    </header>

    <!-- แถบเลือกพนักงาน : แถวเดียว -->
    <section class="bg-neutral-primary-soft border border-default rounded-base p-4" aria-labelledby="t-pick">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-3">
            <h2 id="t-pick" class="text-sm font-semibold text-heading whitespace-nowrap">เลือกพนักงาน</h2>
            <div class="flex-1 min-w-[14rem]">
                <label for="employeeSearch" class="sr-only">เลือกพนักงาน</label>
                <select id="employeeSearch" onchange="selectEmployee(this.value)"
                    class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand">
                    <option value="">-- เลือกพนักงาน --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= (int) $employee['emp_id'] ?>"
                            <?= ($hasEmployee && (int) $employee['emp_id'] === (int) $selectedEmployee['emp_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['emp_name_th'] . ' ' . $employee['emp_sname_th'] . ' — ' . $employee['emp_no']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <p class="text-sm text-body whitespace-nowrap tabular-nums"><?= count($employees) ?> คน</p>
        </div>
    </section>

    <!-- รายการเอกสาร -->
    <section class="mt-4 bg-neutral-primary-soft border border-default rounded-base" aria-labelledby="t-docs">

        <!-- ป้ายพนักงานที่เลือก -->
        <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-3 p-4 border-b border-default-medium">
            <?php if (!$hasEmployee): ?>
                <div class="min-w-0">
                    <h2 id="t-docs" class="text-base font-semibold text-heading">เอกสารของพนักงาน</h2>
                    <p class="text-sm text-body mt-0.5">กรุณาเลือกพนักงานจากรายการด้านบนก่อน</p>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-3 min-w-0">
                    <span class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-blue-700 text-white" aria-hidden="true">
                        <?= htmlspecialchars(mb_substr($selectedEmployee['emp_name_th'], 0, 1) . mb_substr($selectedEmployee['emp_sname_th'], 0, 1)) ?>
                    </span>
                    <div class="min-w-0">
                        <h2 id="t-docs" class="text-base font-semibold text-heading truncate">
                            <?= htmlspecialchars($selectedEmployee['emp_name_th'] . ' ' . $selectedEmployee['emp_sname_th']) ?>
                        </h2>
                        <p class="text-sm text-body mt-0.5 truncate">
                            <?= htmlspecialchars(($selectedEmployee['position_name'] ?? '-') . ' · ' . $selectedEmployee['emp_no']) ?>
                        </p>
                    </div>
                </div>
                <p class="text-sm font-medium text-heading whitespace-nowrap tabular-nums"><?= count($documents) ?> เอกสาร</p>
            <?php endif; ?>
        </div>

        <?php if (!$hasEmployee): ?>
            <p class="px-4 py-10 text-center text-sm text-body">เลือกพนักงานก่อนเพื่อดูเอกสาร</p>
        <?php elseif (count($documents) === 0): ?>
            <p class="px-4 py-10 text-center text-sm text-body">พนักงานคนนี้ยังไม่มีเอกสารอัปโหลด</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[26rem] text-sm text-left">
                    <thead class="text-body">
                        <tr class="border-b border-default-medium">
                            <th scope="col" class="px-4 py-2 font-medium">ไฟล์</th>
                            <th scope="col" class="px-4 py-2 font-medium">สถานะ</th>
                            <th scope="col" class="px-4 py-2 font-medium text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-default-medium">
                        <?php foreach ($documents as $doc): ?>
                            <?php
                            $isReady = ($doc['doc_status'] === 'approved');
                            $badge = $isReady ? $docStatusBadge['approved'] : $docStatusBadge['pending'];
                            $docUrl = htmlspecialchars($doc['doc_url']);
                            $docName = htmlspecialchars($doc['doc_name'] ?? '');
                            ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="shrink-0 w-9 h-9 rounded-base bg-red-50 text-red-600 flex items-center justify-center text-[10px] font-bold" aria-hidden="true">PDF</span>
                                        <div class="min-w-0">
                                            <p class="font-medium text-heading  max-w-[16rem]"><?= htmlspecialchars($doc['doc_name'] ?? '') ?></p>
                                            <p class="text-xs  max-w-[16rem]"><?= htmlspecialchars(basename($doc['doc_url'] ?? '')) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap <?= $badge['class'] ?>"><?= htmlspecialchars($badge['label']) ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="/documents/view?doc_id=<?= (int) $doc['doc_id'] ?>" target="_blank" rel="noopener noreferrer" title="ดูเอกสาร" aria-label="ดูเอกสาร"
                                            class="p-2 rounded-base text-body hover:bg-neutral-secondary-medium hover:text-heading focus:outline-none focus:ring-2 focus:ring-brand">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" stroke="currentColor" stroke-width="1.6" />
                                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
                                            </svg>
                                        </a>
                                        <a href="<?= $docUrl ?>" download title="ดาวน์โหลด" aria-label="ดาวน์โหลด"
                                            class="p-2 rounded-base text-body hover:bg-neutral-secondary-medium hover:text-heading focus:outline-none focus:ring-2 focus:ring-brand">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M12 4v11m0 0 4-4m-4 4-4-4M5 19h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>

                                        <!-- ปุ่ม approve — โชว์เฉพาะ admin + เอกสารที่ยัง pending -->
                                        <?php if (!$isReady && Auth::isAdmin()): ?>
                                            <form action="/documents/approve" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="doc_id" value="<?= (int) $doc['doc_id'] ?>">
                                                <button type="submit" title="อนุมัติเอกสาร" aria-label="อนุมัติเอกสาร"
                                                    class="p-2 rounded-base text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-brand">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </form>

                                        <?php endif; ?>
                                        <?php if (Auth::isAdmin()): ?>
                                            <button type="button"
                                                data-modal-target="deleteDocModal" data-modal-toggle="deleteDocModal"
                                                data-doc-id="<?= (int) $doc['doc_id'] ?>"
                                                onclick="setDeleteDoc(this)"
                                                title="ลบเอกสาร" aria-label="ลบเอกสาร"
                                                class="p-2 rounded-base text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-brand">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M9 7V4h6v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="px-4 py-3 border-t border-default-medium text-sm text-body">
                เอกสารที่อัปโหลดจะถูกจัดเก็บไว้ในโปรไฟล์ของพนักงานที่เลือกเท่านั้น
            </p>
        <?php endif; ?>

    </section>

</div>

<!-- Modal อัปโหลด -->
<div id="uploadDocModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm">
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-default-medium">
                <h3 class="text-lg font-semibold text-heading">อัปโหลดเอกสาร</h3>
                <button type="button" class="text-body bg-transparent hover:bg-neutral-secondary-medium hover:text-heading rounded-base text-sm w-8 h-8 ms-auto inline-flex justify-center items-center focus:outline-none focus:ring-2 focus:ring-brand" data-modal-hide="uploadDocModal">
                    <svg class="w-3 h-3" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">ปิด</span>
                </button>
            </div>
            <form action="/documents/store" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="p-4 md:p-5 space-y-4">
                    <input type="hidden" name="emp_id" value="<?= $hasEmployee ? (int) $selectedEmployee['emp_id'] : '' ?>">
                    <?php if ($hasEmployee): ?>
                        <p class="text-sm text-body">
                            พนักงาน:
                            <span class="font-medium text-heading"><?= htmlspecialchars($selectedEmployee['emp_name_th'] . ' ' . $selectedEmployee['emp_sname_th']) ?></span>
                        </p>
                    <?php endif; ?>
                    <div>
                        <label for="doc_name" class="block mb-2 text-sm font-medium text-heading">ชื่อเอกสาร</label>
                        <input type="text" id="doc_name" name="doc_name" required maxlength="255"
                            class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base"
                            placeholder="เช่น สัญญาจ้างงาน">
                    </div>
                    <div>
                        <label for="doc_category" class="block mb-2 text-sm font-medium text-heading">ประเภทเอกสาร</label>
                        <select id="doc_category" name="type_id" required class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base ">
                            <option value="">-- เลือกประเภท --</option>
                            <?php foreach ($documentTypes as $type): ?>
                                <option value="<?= (int) $type['type_id'] ?>"><?= htmlspecialchars($type['type_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="doc_file" class="block mb-2 text-sm font-medium text-heading">ไฟล์เอกสาร (PDF, DOC, DOCX)</label>
                        <input type="file" id="doc_file" name="document" accept=".pdf,.doc,.docx" required
                            class="block w-full text-sm text-body border border-default-medium rounded-base cursor-pointer bg-neutral-secondary-medium focus:outline-none focus:ring-2 focus:ring-brand">
                    </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-default-medium gap-2">
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-base whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">อัปโหลด</button>
                    <button type="button" data-modal-hide="uploadDocModal" class="px-5 py-2.5 text-sm font-medium text-heading bg-neutral-primary-soft border border-default-medium rounded-base whitespace-nowrap hover:bg-neutral-secondary-medium focus:outline-none focus:ring-2 focus:ring-brand">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal ยืนยันลบ -->
<div id="deleteDocModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm">
            <form action="/documents/delete" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="doc_id" id="deleteDocId" value="">
                <input type="hidden" name="emp_id" value="<?= $hasEmployee ? (int) $selectedEmployee['emp_id'] : '' ?>">
                <div class="p-4 md:p-5">
                    <h3 class="text-lg font-semibold text-heading">ลบเอกสาร</h3>
                    <p class="text-sm text-body mt-2">ยืนยันลบเอกสารนี้? ลบแล้วกู้คืนไม่ได้</p>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-default-medium gap-2">
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-base whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">ลบ</button>
                    <button type="button" data-modal-hide="deleteDocModal" class="px-5 py-2.5 text-sm font-medium text-heading bg-neutral-primary-soft border border-default-medium rounded-base whitespace-nowrap hover:bg-neutral-secondary-medium focus:outline-none focus:ring-2 focus:ring-brand">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function selectEmployee(empId) {
        // ไม่เลือกพนักงาน -> กลับหน้าเปล่า (เดิมไปที่ ?emp_id= ว่าง)
        window.location.href = empId ? '?emp_id=' + encodeURIComponent(empId) : window.location.pathname;
    }

    function setDeleteDoc(btn) {
        document.getElementById('deleteDocId').value = btn.dataset.docId;
    }
</script>