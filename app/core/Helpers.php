<?php
// เก็บ fn() เล็กๆที่ใช้บ่อย

// Clean str ก่อนแสดงผล (กัน XSS)

function sanitize($input)
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}

// สร้าง URL ของ route: โหมด path -> /login , โหมด query -> /index.php?r=login
// (โหมด query ใช้บน server ที่ไม่มี URL rewrite ดู USE_QUERY_ROUTES ใน public/index.php)
function url($path)
{
    if (!USE_QUERY_ROUTES) {
        return $path;
    }

    $parts = explode('?', (string) $path, 2);
    $url = '/index.php?r=' . trim($parts[0], '/');

    if (isset($parts[1]) && $parts[1] !== '') {
        $url .= '&' . $parts[1];
    }

    return $url;
}

// เก็บรายการ route ไว้ให้ rewrite_routes_in_html ใช้ (callback ของ output buffer
// ทำงานตอนจบ script ซึ่งตอนนั้นตัวแปร global อย่าง $router อาจถูกล้างไปแล้ว)
function route_paths($set = null)
{
    static $paths = [];

    if ($set !== null) {
        $paths = $set;
    }

    return $paths;
}

// ผ่าน output buffer: แปลงลิงก์ /route ที่เขียนไว้ใน view ให้เป็น /index.php?r=route
// เพื่อไม่ต้องแก้ลิงก์ทีละจุด ทำงานเฉพาะโหมด query และเฉพาะ response ที่เป็น HTML
function rewrite_routes_in_html($html)
{
    foreach (headers_list() as $header) {
        if (stripos($header, 'Content-Type:') === 0 && stripos($header, 'text/html') === false) {
            return $html; // JSON / PDF ฯลฯ ห้ามแตะ
        }
    }

    $paths = route_paths();
    if (empty($paths)) {
        return $html;
    }

    usort($paths, function ($a, $b) {
        return strlen($b) - strlen($a);
    });
    $alt = implode('|', array_map(function ($p) {
        return preg_quote(ltrim($p, '/'), '~');
    }, $paths));

    $current = (isset($_GET['r']) && is_string($_GET['r'])) ? trim($_GET['r'], '/') : '';

    // href="/route" , action="/route?x=1" , location.href = '/route' , fetch('/route?...')
    $html = preg_replace_callback(
        '~(\b(?:href|action)\s*=\s*|fetch\(\s*)(["\'`])/(' . $alt . ')(\?)?(?=[?"\'`]|(?<=\?))~',
        function ($m) {
            return $m[1] . $m[2] . '/index.php?r=' . $m[3] . (($m[4] ?? '') === '?' ? '&' : '');
        },
        $html
    );

    // ลิงก์แบบ relative ที่ขึ้นต้นด้วย ? (เช่น pagination, ล้างตัวกรอง) ต้องคง r ไว้
    $html = preg_replace_callback(
        '~(\b(?:href|action)\s*=\s*)(["\'])\?([^"\']*)~',
        function ($m) use ($current) {
            return $m[1] . $m[2] . '?r=' . $current . ($m[3] !== '' ? '&' . $m[3] : '');
        },
        $html
    );

    // ฟอร์ม GET ที่ไม่ระบุ action จะทิ้ง query เดิม ต้องส่ง r ไปด้วย
    $html = preg_replace(
        '~(<form\b(?![^>]*\baction\s*=)[^>]*\bmethod\s*=\s*["\']?get["\']?[^>]*>)~i',
        '$1<input type="hidden" name="r" value="' . htmlspecialchars($current, ENT_QUOTES, 'UTF-8') . '">',
        $html
    );

    return $html;
}

// create/fetch token
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// create tag <input> แปะ form
function csrf_field()
{
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// check response POST
function crsf_verify()
{
    $token = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
        http_response_code(403);
        exit('CSRF token incorrect , Please refresh and try again');
    }
}

function require_method_post()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('/employees');
    }
}

function can($module, $action)
{
    return Auth::can($module, $action);
}

function formatWorkDuration($checkIn, $checkOut)
{
    if (empty($checkIn) || empty($checkOut)) {
        return '-';
    }

    $seconds = strtotime($checkOut) - strtotime($checkIn);

    if ($seconds <= 0) {
        return '-';
    }
    $hours = intdiv($seconds, 3600);
    $minutes = intdiv($seconds % 3600, 60);

    $parts = [];
    if ($hours > 0) {
        $parts[] = $hours . ' ชม.';
    }
    if ($minutes >= 0 || $hours === 0) {
        $parts[] = $minutes . ' นาที';
    }

    return implode(' ', $parts);
}
