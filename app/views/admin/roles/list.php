<?php
// views/roles/permissions.php
// หน้าที่: จัดการสิทธิ์การเข้าถึงแบบ toggle switch จัดกลุ่มตามหมวดหมู่
//
// ตัวแปรที่ต้องได้จาก Controller:
//   $roles           — array บทบาททั้งหมด (สำหรับ sidebar ซ้าย)
//   $selectedRole    — array บทบาทที่กำลังแก้ไขอยู่
//   $permissionGroups — array กลุ่มสิทธิ์ พร้อมรายการสิทธิ์ย่อยและสถานะเปิด/ปิดของ $selectedRole

// ---- Mock data (ลบทิ้งเมื่อ Controller ส่งค่าจริงมาให้แล้ว) ----
if (!isset($roles)) {
    $roles = [
        ['role_id' => 1, 'role_name' => 'ผู้ดูแลระบบ', 'description' => 'เข้าถึงทุกเมนูในระบบ', 'icon' => 'crown'],
        ['role_id' => 2, 'role_name' => 'ผู้จัดการ', 'description' => 'จัดการทีมและอนุมัติคำขอ', 'icon' => 'briefcase'],
        ['role_id' => 3, 'role_name' => 'ฝ่ายบุคคล', 'description' => 'ดูแลข้อมูลบุคลากร', 'icon' => 'people'],
        ['role_id' => 4, 'role_name' => 'พนักงาน', 'description' => 'ดูข้อมูลของตนเอง', 'icon' => 'user'],
    ];
}
if (!isset($selectedRole)) {
    $selectedRole = $roles[0];
}
if (!isset($permissionGroups)) {
    $permissionGroups = [
        [
            'group_name' => 'ข้อมูลพนักงาน',
            'group_desc' => 'ข้อมูลส่วนตัวและโครงสร้างองค์กร',
            'items' => [
                ['key' => 'view_employee', 'label' => 'ดูข้อมูลพนักงาน', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถดูข้อมูลพนักงาน', 'enabled' => true],
                ['key' => 'edit_employee', 'label' => 'เพิ่มและแก้ไขข้อมูล', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถเพิ่มและแก้ไขข้อมูล', 'enabled' => true],
                ['key' => 'delete_employee', 'label' => 'ลบข้อมูลพนักงาน', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถลบข้อมูลพนักงาน', 'enabled' => false],
            ],
        ],
        [
            'group_name' => 'เวลาและการลา',
            'group_desc' => 'จัดการเวลาเข้าออกและคำขอลา',
            'items' => [
                ['key' => 'view_attendance', 'label' => 'ดูบันทึกเวลา', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถดูและบันทึกเวลา', 'enabled' => true],
                ['key' => 'approve_leave', 'label' => 'อนุมัติคำขอลา', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถอนุมัติคำขอลา', 'enabled' => true],
                ['key' => 'edit_attendance', 'label' => 'แก้ไขบันทึกเวลา', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถแก้ไขบันทึกเวลา', 'enabled' => false],
            ],
        ],
        [
            'group_name' => 'เอกสารและรายงาน',
            'group_desc' => 'เอกสารสำคัญและข้อมูลสรุป',
            'items' => [
                ['key' => 'view_documents', 'label' => 'ดูเอกสารพนักงาน', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถดูเอกสารพนักงาน', 'enabled' => true],
                ['key' => 'upload_documents', 'label' => 'อัปโหลดเอกสาร', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถอัปโหลดเอกสาร', 'enabled' => true],
                ['key' => 'export_reports', 'label' => 'ส่งออกข้อมูลรายงาน', 'desc' => 'อนุญาตให้' . $selectedRole['role_name'] . ' สามารถส่งออกข้อมูลรายงาน', 'enabled' => false],
            ],
        ],
    ];
}

// นับจำนวนสิทธิ์ที่เปิดอยู่ทั้งหมด (สำหรับ badge หัวข้อ)
$enabledCount = 0;
foreach ($permissionGroups as $group) {
    foreach ($group['items'] as $item) {
        if ($item['enabled']) {
            $enabledCount++;
        }
    }
}

// ไอคอนต่อบทบาท (เก็บเป็น path แยกไว้อ่านง่าย)
$roleIcons = [
    'crown'     => 'M5 8l3 3 4-6 4 6 3-3-2 10H7L5 8z',
    'briefcase' => 'M4 7h16v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7Zm4 0V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2',
    'people'    => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 100-8 4 4 0 000 8zm6 3a4 4 0 00-3-3.87M5 10a4 4 0 013-3.87',
    'user'      => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0',
];
?>
<div class="max-w-6xl mx-auto">

    <!-- Breadcrumb + Header -->
    <nav class="text-sm mb-2">
        <span class="text-primary-700 font-medium">ระบบ</span>
        <span class="text-body mx-1">/</span>
        <span class="text-body">จัดการสิทธิ์การเข้าถึง</span>
    </nav>

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">จัดการสิทธิ์การเข้าถึง</h1>
            <p class="text-sm text-body mt-1">กำหนดสิทธิ์การใช้งานระบบให้เหมาะกับแต่ละบทบาท</p>
        </div>
        <button type="button" id="saveChangesBtn"
            class="shrink-0 px-4 py-2.5 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-base">
            บันทึกการเปลี่ยนแปลง
        </button>
    </div>

    <div class="grid md:grid-cols-[280px_1fr] gap-4 items-start">

        <!-- Sidebar: เลือกบทบาท -->
        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-4">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-9 h-9 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><path d="M12 2l2.4 4.8 5.3.8-3.8 3.7.9 5.3L12 14l-4.8 2.6.9-5.3-3.8-3.7 5.3-.8L12 2z" fill="currentColor" /></svg>
                </span>
                <div>
                    <p class="text-sm font-semibold text-heading">บทบาทผู้ใช้งาน</p>
                    <p class="text-xs text-body">เลือกบทบาทเพื่อจัดการสิทธิ์</p>
                </div>
            </div>

            <div class="space-y-2">
                <?php foreach ($roles as $role): ?>
                    <?php $isActive = ((int) $role['role_id'] === (int) $selectedRole['role_id']); ?>
                    <a href="/roles/permissions?id=<?= (int) $role['role_id'] ?>"
                        class="flex items-center gap-3 p-3 rounded-base border <?= $isActive ? 'border-primary-700 bg-primary-50' : 'border-transparent hover:bg-neutral-secondary-medium' ?>">
                        <span class="w-9 h-9 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="<?= $roleIcons[$role['icon'] ?? 'user'] ?? $roleIcons['user'] ?>" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold text-heading"><?= htmlspecialchars($role['role_name']) ?></span>
                            <span class="block text-xs text-body truncate"><?= htmlspecialchars($role['description'] ?? '') ?></span>
                        </span>
                        <svg class="w-4 h-4 text-body shrink-0" viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Panel: สิทธิ์ของบทบาทที่เลือก -->
        <!-- <form id="permissionsForm" method="POST" action="/roles/permissions/update">
            <input type="hidden" name="role_id" value="<?= (int) $selectedRole['role_id'] ?>">

            <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs p-5 mb-4">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-primary-700">กำลังแก้ไขสิทธิ์ของ</p>
                        <h2 class="text-xl font-bold text-heading"><?= htmlspecialchars($selectedRole['role_name']) ?></h2>
                        <p class="text-sm text-body mt-1">เปิดหรือปิดสิทธิ์การเข้าถึงของบทบาทนี้</p>
                    </div>
                    <span id="enabledCountBadge" class="shrink-0 px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                        เปิดใช้งาน <span id="enabledCountNumber"><?= $enabledCount ?></span> รายการ
                    </span>
                </div>
            </div>

            <?php foreach ($permissionGroups as $groupIndex => $group): ?>
                <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs mb-4">
                    <div class="flex items-start justify-between px-5 py-4 border-b border-default-medium">
                        <div>
                            <h3 class="text-sm font-semibold text-heading"><?= htmlspecialchars($group['group_name']) ?></h3>
                            <p class="text-xs text-body mt-0.5"><?= htmlspecialchars($group['group_desc']) ?></p>
                        </div>
                        <button type="button" onclick="toggleAllInGroup(<?= $groupIndex ?>)"
                            class="shrink-0 text-xs font-medium text-primary-700 hover:underline">
                            เปิดทั้งหมด
                        </button>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <?php foreach ($group['items'] as $itemIndex => $item): ?>
                            <div class="flex items-center justify-between px-5 py-4">
                                <div class="min-w-0 pe-4">
                                    <p class="text-sm font-medium text-heading"><?= htmlspecialchars($item['label']) ?></p>
                                    <p class="text-xs text-body mt-0.5"><?= htmlspecialchars($item['desc']) ?></p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <label class="permission-toggle inline-flex items-center cursor-pointer"
                                        data-group="<?= $groupIndex ?>">
                                        <input type="checkbox"
                                            name="perm[<?= htmlspecialchars($item['key']) ?>]"
                                            value="1"
                                            class="sr-only peer"
                                            onchange="onToggleChange(this)"
                                            <?= $item['enabled'] ? 'checked' : '' ?>>
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                    <span class="toggle-label text-xs font-medium <?= $item['enabled'] ? 'text-green-700' : 'text-body' ?> w-6">
                                        <?= $item['enabled'] ? 'เปิด' : 'ปิด' ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </form> -->
    </div>
</div>

<script>
    // อัปเดตข้อความ "เปิด"/"ปิด" ข้างๆ toggle switch แต่ละอัน ทันทีที่กด
    function onToggleChange(checkbox) {
        var label = checkbox.closest('.permission-toggle').nextElementSibling;
        if (checkbox.checked) {
            label.textContent = 'เปิด';
            label.classList.remove('text-body');
            label.classList.add('text-green-700');
        } else {
            label.textContent = 'ปิด';
            label.classList.remove('text-green-700');
            label.classList.add('text-body');
        }
        updateEnabledCount();
    }

    // ปุ่ม "เปิดทั้งหมด" ของแต่ละกลุ่ม — เปิด toggle ทุกอันในกลุ่มนั้น
    function toggleAllInGroup(groupIndex) {
        document.querySelectorAll('.permission-toggle[data-group="' + groupIndex + '"] input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.checked = true;
            onToggleChange(checkbox);
        });
    }

    // นับจำนวนสิทธิ์ที่เปิดอยู่ทั้งหมด แล้วอัปเดต badge หัวข้อ
    function updateEnabledCount() {
        var count = document.querySelectorAll('#permissionsForm input[type="checkbox"]:checked').length;
        document.getElementById('enabledCountNumber').textContent = count;
    }
</script>