<?php
// views/profile/show.php
// หน้าที่: แสดงรายละเอียดโปรไฟล์ผู้ใช้ 4 หมวด (ข้อมูลส่วนตัว, บัญชี, ความปลอดภัย, การตั้งค่า)
//
// ตัวแปรที่ต้องได้จาก Controller (ตอนนี้ใช้ mock data ไปก่อน แทนที่ด้วยของจริงทีหลัง):
//   $profile — array ข้อมูลทั้งหมดของผู้ใช้ที่กำลังดู

if (!isset($profile)) {
    $profile = [
        'display_name'   => 'Liam Smith',
        'email'          => 'wilson@example.com',
        'avatar_url'     => 'https://i.pravatar.cc/150?img=13',
        'verified'       => true,

        'full_name'      => 'Samuel Wilson',
        'birth_date'     => 'January 1, 1987',
        'gender'         => 'Male',
        'nationality'    => 'American',
        'address'        => 'California - United States',
        'address_flag'   => '🇺🇸',
        'phone'          => '(213) 555-1234',

        'username'          => 's_wilson_168920',
        'account_created'   => 'March 20, 2020',
        'last_login'        => 'August 22, 2024',
        'membership_status' => 'Premium Member',
        'account_verified'  => true,
        'language_pref'     => 'English',
        'timezone'          => 'GMT-5 (Eastern Time)',

        'password_changed'   => 'July 15, 2024',
        'two_factor_enabled' => true,
        'security_questions' => 'Yes',
        'login_notify'       => true,
        'connected_devices'  => '3 Devices',
        'recent_activity'    => 'No Suspicious Activity Detected',

        'email_notify'       => 'Subscribed',
        'sms_alerts'         => true,
        'content_prefs'      => 'Technology, Design, Innovation',
        'dashboard_view'     => 'Compact Mode',
        'dark_mode'          => 'Activated',
        'content_language'   => 'English',
    ];
}

// helper: badge สีตามความหมาย (เขียว=verified, ฟ้า=enabled, ม่วง=subscribed)
function profileBadge($text, $color)
{
    $palette = [
        'green'  => 'text-green-800 bg-green-100',
        'blue'   => 'text-blue-800 bg-blue-100',
        'purple' => 'text-purple-800 bg-purple-100',
    ];
    $classes = $palette[$color] ?? $palette['blue'];
    return '<span class="px-2 py-0.5 text-xs font-medium rounded ' . $classes . '">' . htmlspecialchars($text) . '</span>';
}
?>

<div class="mb-4">
    <a href="/employees" class="flex items-center justify-center w-8 h-8 rounded-full ring-4 ring-white text-sm font-medium bg-white text-gray-500 hover:bg-gray-50">←</a>
</div>

<div class="max-w-6xl mx-auto py-8">

    <!-- Header: avatar + ชื่อ + อีเมล -->
    <div class="flex flex-col items-center text-center mb-8">
        <div class="relative">
            <img src="<?php echo htmlspecialchars($profile['avatar_url']); ?>" alt="<?php echo htmlspecialchars($profile['display_name']); ?>"
                class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-md">
        </div>
        <div class="flex items-center gap-1.5 mt-4">
            <h1 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($profile['display_name']); ?></h1>
            <?php if (!empty($profile['verified'])): ?>
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l2.4 2.4 3.3-.6.9 3.3 3.3.9-.6 3.3L23.5 14l-2.2 2.7.6 3.3-3.3.9-.9 3.3-3.3-.6L12 26l-2.4-2.4-3.3.6-.9-3.3-3.3-.9.6-3.3L.5 14l2.2-2.7-.6-3.3 3.3-.9.9-3.3 3.3.6L12 2z" transform="scale(0.85) translate(1.8,1.8)" />
                    <path d="m9 12 2 2 4-4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                </svg>
            <?php endif; ?>
        </div>
        <p class="text-sm text-gray-500 mt-0.5"><?php echo htmlspecialchars($profile['email']); ?></p>
    </div>

    <!-- Grid การ์ด 2x2 -->
    <div class="grid sm:grid-cols-2 gap-4">

        <!-- Personal details -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Personal details</h2>
            </div>
            <div class="px-4">
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Ful name:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['full_name']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Date of Birth:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['birth_date']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Gender:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['gender']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Nationality:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['nationality']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Address:</span>
                    <span class="text-sm font-medium text-gray-900 flex items-center gap-1.5">
                        <span><?php echo $profile['address_flag']; ?></span>
                        <?php echo htmlspecialchars($profile['address']); ?>
                    </span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Phone Number:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['phone']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-gray-500">Email:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['email']); ?></span>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Account Details</h2>
            </div>
            <div class="px-4">
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Display Name:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['username']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Account Created:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['account_created']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Last Login:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['last_login']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Membership Status:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['membership_status']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Account Verification:</span>
                    <?php echo $profile['account_verified'] ? profileBadge('Verified', 'green') : profileBadge('Not verified', 'blue'); ?>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Language Preference:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['language_pref']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-gray-500">Time Zone:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['timezone']); ?></span>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Security Settings</h2>
            </div>
            <div class="px-4">
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Password Last Changed:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['password_changed']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Two-Factor Authentication:</span>
                    <?php echo $profile['two_factor_enabled'] ? profileBadge('Enabled', 'blue') : profileBadge('Disabled', 'blue'); ?>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Security Questions Set:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['security_questions']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Login Notifications:</span>
                    <?php echo $profile['login_notify'] ? profileBadge('Enabled', 'blue') : profileBadge('Disabled', 'blue'); ?>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Connected Devices:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['connected_devices']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-gray-500">Recent Account Activity:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['recent_activity']); ?></span>
                </div>
            </div>
        </div>

        <!-- Preferences -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Preferences</h2>
            </div>
            <div class="px-4">
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Email Notifications:</span>
                    <?php echo profileBadge($profile['email_notify'], 'purple'); ?>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">SMS Alerts:</span>
                    <?php echo $profile['sms_alerts'] ? profileBadge('Enabled', 'blue') : profileBadge('Disabled', 'blue'); ?>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Content Preferences:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['content_prefs']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Default Dashboard View:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['dashboard_view']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-dashed border-gray-100">
                    <span class="text-sm text-gray-500">Dark Mode:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['dark_mode']); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-gray-500">Language for Content:</span>
                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($profile['content_language']); ?></span>
                </div>
            </div>
        </div>

    </div>
</div>