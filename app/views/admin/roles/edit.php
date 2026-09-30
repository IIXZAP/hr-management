<?php
// views/roles/edit.php
$role = $role ?? [];
$modules = $modules ?? [];
$permissions = $permissions ?? [];
?>
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="/roles" class="text-sm text-primary-700 hover:underline">← กลับไปหน้า Role</a>
        <h1 class="text-2xl font-bold text-heading mt-2">
            จัดการสิทธิ์: <?= htmlspecialchars($role['role_name'] ?? '') ?>
        </h1>
    </div>

    <form method="POST" action="/roles/<?= (int) ($role['role_id'] ?? 0) ?>/permissions">

        <div class="relative overflow-x-auto bg-neutral-primary-soft border border-default rounded-base shadow-xs">
            <table class="w-full text-sm text-left text-body">
                <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                    <tr>
                        <th class="px-4 py-3">Module</th>
                        <th class="px-4 py-3 text-center">เพิ่ม</th>
                        <th class="px-4 py-3 text-center">ดู</th>
                        <th class="px-4 py-3 text-center">แก้ไข</th>
                        <th class="px-4 py-3 text-center">ลบ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (count($modules) === 0): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-body">ยังไม่มี module ให้ตั้งค่าสิทธิ์</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($modules as $module): ?>
                        <?php $p = $permissions[$module] ?? []; ?>
                        <tr class="hover:bg-neutral-secondary-medium">
                            <td class="px-4 py-3 font-medium text-heading"><?= htmlspecialchars($module) ?></td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($module) ?>][create]"
                                    <?= !empty($p['can_create']) ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($module) ?>][read]"
                                    <?= !empty($p['can_read']) ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($module) ?>][update]"
                                    <?= !empty($p['can_update']) ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($module) ?>][delete]"
                                    <?= !empty($p['can_delete']) ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <a href="/roles" class="text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium rounded-base text-sm px-4 py-2.5">ยกเลิก</a>
            <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-base text-sm px-5 py-2.5">บันทึก</button>
        </div>
    </form>

</div>