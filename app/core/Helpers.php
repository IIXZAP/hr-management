<?php
// เก็บ fn() เล็กๆที่ใช้บ่อย

// Clean str ก่อนแสดงผล (กัน XSS)

function sanitize($input)
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header('Location: ' . $path);
    exit;
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