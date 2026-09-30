<?php
// views/settings/index.php

$sections = [
    // 'การเข้าถึงระบบ' => [
    //     [
    //         'title' => 'Roles & สิทธิ์การเข้าถึง',
    //         'desc'  => 'กำหนดว่าแต่ละบทบาททำอะไรกับข้อมูลส่วนไหนได้บ้าง',
    //         'url'   => '/roles',
    //     ],
    // ],
    'ข้อมูลตั้งต้น (Master Data)' => [
        [
            'title' => 'ตำแหน่งงาน',
            'desc'  => 'รายการตำแหน่งที่ใช้ตอนทำสัญญาจ้าง',
            'url'   => '/settings/positions',
        ],
        [
            'title' => 'ประเภทการลา',
            'desc'  => 'ประเภทการลาและโควต้าต่อปี',
            'url'   => '/settings/leave-types',
        ],
        // [
        //     'title' => 'ประเภทเอกสาร',
        //     'desc'  => 'ประเภทเอกสารที่พนักงานอัปโหลดได้',
        //     'url'   => '/settings/document-types',
        // ],
    ],
    // 'ค่าระบบทั่วไป' => [
    //     [
    //         'title' => 'ค่าตั้งค่าระบบ',
    //         'desc'  => 'เวลาเข้างาน, เกณฑ์นับสาย, ค่าเริ่มต้นอื่นๆ',
    //         'url'   => '/settings/system',
    //     ],
    // ],
];
?>
<div class="max-w-6xl mx-auto">

    <h1 class="text-2xl font-bold text-heading mb-8">ตั้งค่าระบบ</h1>

    <?php foreach ($sections as $groupName => $items): ?>
    <div class="mb-8">
        <h2 class="text-sm font-semibold text-body uppercase tracking-wide mb-3">
            <?= htmlspecialchars($groupName) ?>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($items as $item): ?>
            <a href="<?= $item['url'] ?>"
               class="block bg-neutral-primary-soft border border-default rounded-base p-4 hover:border-primary-700 hover:shadow-xs transition">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-medium text-heading"><?= htmlspecialchars($item['title']) ?></span>
                    <svg class="w-4 h-4 text-body" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="text-sm text-body"><?= htmlspecialchars($item['desc']) ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>