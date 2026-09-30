<?php
// views/roles/permissions.php
$role = $role ?? [];
$modules = $modules ?? [];
$permissions = $permissions ?? [];
?>
<div class="max-w-6xl mx-auto">

    <div class="mb-6">
        <a href="/roles" class="text-sm text-primary-700 hover:underline">← ย้อนกลับ</a>
        <h1 class="text-2xl font-bold text-heading mt-2">
            สิทธิ์ของ Role: <?= htmlspecialchars($role['role_name']) ?>
        </h1>
    </div>

    <form method="POST" action="/roles/permissions/update">
        <input type="hidden" name="role_id" value="<?= (int) $role['role_id'] ?>">

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

                    <?php foreach ($modules as $m): ?>
                        <?php
                        $key = $m['module_key'];
                        $p = $permissions[$key] ?? ['can_create' => 0, 'can_read' => 0, 'can_update' => 0, 'can_delete' => 0];
                        ?>
                        <tr class="hover:bg-neutral-secondary-medium">
                            <td class="px-4 py-3 font-medium text-heading"><?= htmlspecialchars($m['module_name']) ?></td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($key) ?>][create]"
                                    <?= $p['can_create'] ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($key) ?>][read]"
                                    <?= $p['can_read'] ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($key) ?>][update]"
                                    <?= $p['can_update'] ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="perm[<?= htmlspecialchars($key) ?>][delete]"
                                    <?= $p['can_delete'] ? 'checked' : '' ?>
                                    class="w-4 h-4 text-primary-700 bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <a href="/roles" class="text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium rounded-base text-sm px-4 py-2.5">ยกเลิก</a>
            <button type="submit" class="text-white bg-brand hover:bg-blue-800 font-medium rounded-base text-sm px-5 py-2.5">บันทึก</button>
        </div>
    </form>

</div>