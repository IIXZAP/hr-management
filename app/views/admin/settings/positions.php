<?php
// views/settings/positions.php
$positions = $positions ?? [];
?>
<div class="max-w-6xl mx-auto">

    <a href="/settings" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 hover:underline rounded-base">
        ← ย้อนกลับ
    </a>

    <h1 class="text-2xl font-bold text-heading mb-6">ตำแหน่งงาน</h1>

    <form method="POST" action="/settings/positions/store" class="flex gap-2 mb-6">
        <input type="text" name="position_name" placeholder="ชื่อตำแหน่งใหม่" required
            class="flex-1 border border-default-medium rounded-base px-3 py-2 text-sm">
        <button type="submit"
            class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-base px-4 py-2">
            เพิ่ม
        </button>
    </form>

    <div class="relative overflow-x-auto bg-neutral-primary-soft border border-default rounded-base">
        <table class="w-full text-sm text-left text-body">
            <thead class="bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th class="px-4 py-3">ตำแหน่ง</th>
                    <th class="px-4 py-3 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($positions) === 0): ?>
                    <tr>
                        <td colspan="2" class="px-4 py-6 text-center">ยังไม่มีตำแหน่งงาน</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($positions as $pos): ?>
                    <tr class="hover:bg-neutral-secondary-medium">
                        <td class="px-4 py-3 font-medium text-heading">
                            <?= htmlspecialchars($pos['position_name']) ?>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="/settings/positions/delete"
                                onsubmit="return confirm('ลบตำแหน่งนี้?')">
                                <input type="hidden" name="position_id" value="<?= $pos['position_id'] ?>">
                                <button type="submit" class="text-red-600 hover:underline text-sm">ลบ</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>