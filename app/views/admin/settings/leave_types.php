<?php
// views/admin/settings/leave_types.php
// รับ $leaveTypes จาก SettingsController::leaveTypes()
$leaveTypes = $leaveTypes ?? [];
?>
<div class="max-w-6xl mx-auto">

    <a href="/settings" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 hover:underline rounded-base">
        ← ย้อนกลับ
    </a>

    <h1 class="text-2xl font-bold text-heading mb-6">ประเภทการลา</h1>

    <form method="POST" action="/settings/leave_types/store" class="flex flex-col sm:flex-row gap-2 mb-6">
        <input type="text" name="leave_type_name" placeholder="ชื่อประเภทการลาใหม่" required
            class="flex-1 border border-default-medium rounded-base px-3 py-2 text-sm">
        <input type="number" name="max_days_per_year" min="0" placeholder="สิทธิ์ (วัน/ปี) เว้นว่าง = ไม่จำกัด"
            class="sm:w-64 border border-default-medium rounded-base px-3 py-2 text-sm">
        <button type="submit"
            class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-base px-4 py-2">
            เพิ่ม
        </button>
    </form>

    <div class="relative overflow-x-auto bg-neutral-primary-soft border border-default rounded-base">
        <table class="w-full text-sm text-left text-body">
            <thead class="bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th class="px-4 py-3">ประเภทการลา</th>
                    <th class="px-4 py-3">สิทธิ์ (วัน/ปี)</th>
                    <th class="px-4 py-3 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($leaveTypes) === 0): ?>
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center">ยังไม่มีประเภทการลา</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($leaveTypes as $type): ?>
                    <tr class="hover:bg-neutral-secondary-medium">
                        <td class="px-4 py-3 font-medium text-heading">
                            <?= htmlspecialchars($type['leave_type_name']) ?>
                        </td>
                        <td class="px-4 py-3">
                            <?= $type['max_days_per_year'] === null ? 'ไม่จำกัด' : (int) $type['max_days_per_year'] ?>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="/settings/leave_types/delete"
                                onsubmit="return confirm('ลบประเภทการลานี้?')">
                                <input type="hidden" name="leave_type_id" value="<?= (int) $type['leave_type_id'] ?>">
                                <button type="submit" class="text-red-600 hover:underline text-sm">ลบ</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>