<?php
// views/documents/my.php
// หน้าที่: พนักงานดู + อัปโหลดเอกสารของตัวเอง (ไม่มีสิทธิ์ดูของคนอื่น)

$documents = $documents ?? [];
$employee = $employee ?? [];


foreach ($documents as &$doc) {
    $doc['category']   = $doc['category'] ?? 'เอกสารทั่วไป';
    $doc['label']       = $doc['label'] ?? basename($doc['doc_url'] ?? '');
    $doc['is_new']      = $doc['is_new'] ?? false;
    $doc['updated_at']  = $doc['updated_at'] ?? '-';
    $doc['size_label']  = $doc['size_label'] ?? '-';
}
unset($doc);

if (count($documents) === 0) {
    $documents = [
        ['doc_url' => '/files/payslip-aug2569.pdf', 'category' => 'สลิปเงินเดือน', 'label' => 'สลิปเงินเดือน • สิงหาคม 2569', 'is_new' => true, 'updated_at' => '14 ก.ย. 2569', 'size_label' => '245 KB', 'doc_status' => 'approved'],
        ['doc_url' => '/files/cert-work.pdf', 'category' => 'หนังสือรับรอง', 'label' => 'หนังสือรับรองการทำงาน', 'is_new' => true, 'updated_at' => '14 ก.ย. 2569', 'size_label' => '180 KB', 'doc_status' => 'approved'],
        ['doc_url' => '/files/payslip-jul2569.pdf', 'category' => 'สลิปเงินเดือน', 'label' => 'สลิปเงินเดือน • กรกฎาคม 2569', 'is_new' => false, 'updated_at' => '31 ส.ค. 2569', 'size_label' => '240 KB', 'doc_status' => 'approved'],
        ['doc_url' => '/files/tax-cert.pdf', 'category' => 'เอกสารภาษี', 'label' => 'หนังสือรับรองภาษีหัก ณ ที่จ่าย', 'is_new' => false, 'updated_at' => '15 ก.พ. 2569', 'size_label' => '310 KB', 'doc_status' => 'approved'],
    ];
}

$totalCount = count($documents);
$newCount = count(array_filter($documents, fn($d) => !empty($d['is_new'])));
$lastUpdated = $documents[0]['updated_at'] ?? '-';
$newestDoc = $documents[0] ?? null;

// รายชื่อหมวดหมู่ทั้งหมดที่มีอยู่จริงในเอกสาร (สำหรับปุ่ม filter)
$categories = array_values(array_unique(array_map(fn($d) => $d['category'], $documents)));
?>
<div class="max-w-6xl mx-auto">

    <!-- <p class="text-xs font-semibold text-primary-700 uppercase tracking-wide mb-2">My Workspace</p> -->

    <div class="flex items-start justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-heading">เอกสารของฉัน</h1>
            <p class="text-sm text-body mt-1">เอกสารส่วนตัวที่แอดมินและฝ่ายบุคคลส่งถึงคุณ</p>
        </div>
        <div class="relative shrink-0 w-64">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-body" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" id="docSearch" placeholder="ค้นหาชื่อเอกสาร"
                class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand placeholder:text-body">
        </div>
    </div>

    <!-- Stat cards -->
    <div class="grid sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <p class="text-xs text-body mb-2">เอกสารทั้งหมด</p>
            <p class="text-xl font-bold text-heading"><?= (int) $totalCount ?> ไฟล์</p>
            <p class="text-xs text-primary-700 mt-1">เอกสารของคุณเท่านั้น</p>
        </div>
        <!-- <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <p class="text-xs text-body mb-2">เอกสารใหม่</p>
            <p class="text-xl font-bold text-heading"><?= (int) $newCount ?> ไฟล์</p>
            <p class="text-xs text-primary-700 mt-1">ยังไม่ได้เปิดอ่าน</p>
        </div>
        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5">
            <p class="text-xs text-body mb-2">อัปเดตล่าสุด</p>
            <p class="text-xl font-bold text-heading"><?= htmlspecialchars($lastUpdated) ?></p>
            <p class="text-xs text-green-700 mt-1">วันนี้ เวลา 09:15 น.</p>
        </div> -->
    </div>

    <!-- Banner เอกสารใหม่ล่าสุด -->
    <!-- <?php if ($newestDoc !== null && !empty($newestDoc['is_new'])): ?>
        <div class="flex items-center justify-between gap-4 bg-primary-50 border border-primary-100 rounded-base p-4 mb-4">
            <div class="flex items-center gap-3 min-w-0">
                <span class="shrink-0 w-10 h-10 rounded-lg bg-white text-primary-700 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <path d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-primary-700">เอกสารใหม่จากฝ่ายบุคคล</p>
                    <p class="text-sm font-semibold text-heading truncate"><?= htmlspecialchars($newestDoc['label']) ?></p>
                    <p class="text-xs text-body">อัปโหลดวันนี้ 09:15 น. • PDF, <?= htmlspecialchars($newestDoc['size_label']) ?></p>
                </div>
            </div>
            <a href="<?= htmlspecialchars($newestDoc['doc_url']) ?>" target="_blank"
                class="shrink-0 px-4 py-2.5 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-base">
                เปิดเอกสาร
            </a>
        </div>
    <?php endif; ?> -->

    <!-- ตารางเอกสารทั้งหมด -->
    <div class="text-right">
        <p class="flex items-center gap-1.5 text-xs text-body mb-2 justify-end">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="none" stroke="currentColor" stroke-width="2" d="M1.75 16.002C3.353 20.098 7.338 23 12 23c6.075 0 11-4.925 11-11m-.75-4.002C20.649 3.901 16.663 1 12 1C5.925 1 1 5.925 1 12m8 4H1v8M23 0v8h-8" />
            </svg>
            <span>อัปเดตล่าสุด <?= htmlspecialchars($lastUpdated, ENT_QUOTES, 'UTF-8') ?></span>
        </p>
    </div>
    <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs">
        <div class="flex items-center justify-between px-5 py-4 border-b border-default-medium flex-wrap gap-3">
            <h2 class="text-sm font-semibold text-heading">เอกสารของฉัน</h2>

            <div class="flex items-center gap-4">
                <!-- Filter tabs ตามหมวดหมู่ -->
                <div id="categoryFilter" class="flex items-center gap-2">
                    <button type="button" data-category="all"
                        class="category-btn px-3 py-1.5 rounded-full text-xs font-medium bg-primary-700 text-white">
                        ทั้งหมด <?= (int) $totalCount ?>
                    </button>
                    <?php foreach ($categories as $category): ?>
                        <button type="button" data-category="<?= htmlspecialchars($category) ?>"
                            class="category-btn px-3 py-1.5 rounded-full text-xs font-medium bg-neutral-secondary-medium text-body hover:bg-neutral-tertiary">
                            <?= htmlspecialchars($category) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <input type="month" id="filterMonth"
                        value="<?= htmlspecialchars($_GET['month'] ?? date('Y-m')) ?>"
                        onchange="location.href = '/documents?month=' + this.value"
                        class="text-sm border border-default-medium rounded-base px-3 py-2 bg-neutral-secondary-medium text-heading focus:ring-brand focus:border-brand">

                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-body bg-neutral-secondary-medium">
                    <tr>
                        <th class="px-5 py-3">ชื่อเอกสาร</th>
                        <th class="px-5 py-3">ประเภท</th>
                        <th class="px-5 py-3">วันที่อัปเดต</th>
                        <th class="px-5 py-3">ขนาด</th>
                        <th class="px-5 py-3 text-center">ดาวน์โหลด</th>
                    </tr>
                </thead>
                <tbody id="docTableBody" class="divide-y divide-gray-100">
                    <?php if ($totalCount === 0): ?>
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-body">ยังไม่มีเอกสาร</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($documents as $doc): ?>
                        <tr class="doc-row hover:bg-neutral-secondary-medium"
                            data-category="<?= htmlspecialchars($doc['category']) ?>"
                            data-name="<?= htmlspecialchars(mb_strtolower($doc['label'])) ?>">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary-700 shrink-0" viewBox="0 0 24 24" fill="none">
                                        <path d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span class="font-medium text-heading"><?= htmlspecialchars($doc['label']) ?></span>
                                    <?php if (!empty($doc['is_new'])): ?>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">ใหม่</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-body"><?= htmlspecialchars($doc['category']) ?></td>
                            <td class="px-5 py-3 text-body"><?= htmlspecialchars($doc['updated_at']) ?></td>
                            <td class="px-5 py-3 text-body"><?= htmlspecialchars($doc['size_label']) ?></td>
                            <td class="px-5 py-3 text-center">
                                <a href="<?= htmlspecialchars($doc['doc_url']) ?>" download
                                    class="inline-flex text-primary-700 hover:text-primary-800" title="ดาวน์โหลด">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 4v11m0 0 4-4m-4 4-4-4M5 19h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                                <!-- ไม่มีปุ่ม approve — staff ไม่มีสิทธิ์อนุมัติเอกสารตัวเอง -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between px-5 py-3 border-t border-default-medium text-xs text-body">
            <span>แสดง 1-<?= min(4, $totalCount) ?> จาก <?= (int) $totalCount ?> ไฟล์</span>
            <!-- pagination นี้เป็นแค่การแสดงผล ยังไม่ได้ผูก logic กับหน้าถัดไปจริง
                 ต้องรอ Controller ส่ง limit/offset จริงมาก่อนถึงจะทำให้กดเปลี่ยนหน้าได้ -->
            <div class="flex items-center gap-1">
                <button type="button" class="px-2 py-1 rounded hover:bg-neutral-secondary-medium">‹</button>
                <button type="button" class="px-2 py-1 rounded bg-primary-700 text-white">1</button>
                <button type="button" class="px-2 py-1 rounded hover:bg-neutral-secondary-medium">2</button>
                <button type="button" class="px-2 py-1 rounded hover:bg-neutral-secondary-medium">3</button>
                <button type="button" class="px-2 py-1 rounded hover:bg-neutral-secondary-medium">›</button>
            </div>
        </div>
    </div>



</div>

<!-- Modal อัปโหลด — เหมือนของ admin แต่ตัด dropdown เลือกพนักงานออก ใช้ emp_id ของตัวเองตรงๆ -->
<!-- <div id="uploadDocModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-heading">อัปโหลดเอกสาร</h3>
                <button type="button" data-modal-hide="uploadDocModal"
                    class="text-body bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">✕</button>
            </div>
            <form action="/documents/store" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="p-4 md:p-5 space-y-4">

                    <input type="hidden" name="emp_id" value="<?php echo (int) $employee['emp_id']; ?>">

                    <div>
                        <label class="block mb-2 text-sm font-medium text-heading">ประเภทเอกสาร</label>
                        <select name="category" class="bg-neutral-secondary-medium border border-default-medium text-sm rounded-base block w-full p-2.5">
                            <option value="เอกสารส่วนตัว">เอกสารส่วนตัว</option>
                            <option value="วุฒิการศึกษา">วุฒิการศึกษา</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-heading">ไฟล์เอกสาร (PDF)</label>
                        <input type="file" name="document" accept=".pdf" required
                            class="block w-full text-sm border border-default-medium rounded-base cursor-pointer bg-neutral-secondary-medium">
                    </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b gap-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-base text-sm px-5 py-2.5">อัปโหลด</button>
                    <button type="button" data-modal-hide="uploadDocModal" class="text-body bg-white border border-default font-medium rounded-base text-sm px-5 py-2.5">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<script>
    // ค้นหาชื่อเอกสาร (client-side, กรองแถวในตาราง)
    document.getElementById('docSearch').addEventListener('input', function(e) {
        var keyword = e.target.value.trim().toLowerCase();
        document.querySelectorAll('#docTableBody .doc-row').forEach(function(row) {
            var name = row.dataset.name || '';
            row.style.display = name.includes(keyword) ? '' : 'none';
        });
    });

    // Filter ตามหมวดหมู่ (client-side)
    document.querySelectorAll('#categoryFilter .category-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var selected = btn.dataset.category;

            // สลับสีปุ่มที่กำลังเลือกอยู่
            document.querySelectorAll('#categoryFilter .category-btn').forEach(function(b) {
                b.classList.remove('bg-primary-700', 'text-white');
                b.classList.add('bg-neutral-secondary-medium', 'text-body');
            });
            btn.classList.remove('bg-neutral-secondary-medium', 'text-body');
            btn.classList.add('bg-primary-700', 'text-white');

            // กรองแถวตามหมวดที่เลือก
            document.querySelectorAll('#docTableBody .doc-row').forEach(function(row) {
                var match = (selected === 'all') || (row.dataset.category === selected);
                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>