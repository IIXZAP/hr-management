<?php
// public/index.php
// entry point เดียว รับทุก request แล้วส่งเข้า Router

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

session_start();
define('BASE_PATH', __DIR__ . '/../app');

// server ที่ไม่มี URL rewrite ใช้ /index.php?r=login แทน /login (localhost: php -S ใช้ path ปกติ)
// บังคับได้ด้วย env QUERY_ROUTES=1 / 0
define('USE_QUERY_ROUTES', getenv('QUERY_ROUTES') !== false ? getenv('QUERY_ROUTES') === '1' : PHP_SAPI !== 'cli-server');

// ---- โหลด core ----
require_once BASE_PATH . '/core/Helpers.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Router.php';

// ---- โหลด models ----
require_once BASE_PATH . '/models/Employee.php';
require_once BASE_PATH . '/models/EmployeeContact.php';
require_once BASE_PATH . '/models/EmployeeLogin.php';
// require_once BASE_PATH . '/models/Department.php';
require_once BASE_PATH . '/models/Attendance.php';
// require_once BASE_PATH . '/models/Payroll.php';
require_once BASE_PATH . '/models/Position.php';
require_once BASE_PATH . '/models/Documents.php';
require_once BASE_PATH . '/models/DocumentType.php';
require_once BASE_PATH . '/models/Contract.php';
require_once BASE_PATH . '/models/RfidTag.php';
require_once BASE_PATH . '/models/ScanLog.php';
require_once BASE_PATH . '/models/Leave.php';
require_once BASE_PATH . '/models/LeaveType.php';
require_once BASE_PATH . '/models/LeaveReport.php';
require_once BASE_PATH . '/models/Role.php';

// ---- โหลด controllers ----
require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/EmployeeController.php';
require_once BASE_PATH . '/controllers/DashboardController.php';
require_once BASE_PATH . '/controllers/LeaveController.php';
require_once BASE_PATH . '/controllers/AttendanceController.php';
// require_once BASE_PATH . '/controllers/PayrollController.php';
require_once BASE_PATH . '/controllers/DocumentController.php';
require_once BASE_PATH . '/controllers/ScanController.php';
require_once BASE_PATH . '/controllers/LeaveReportController.php';
require_once BASE_PATH . '/controllers/RoleController.php';
require_once BASE_PATH . '/controllers/SettingsController.php';

// ---- ประกาศ route ----
$router = new Router();

$router->add('/login', 'AuthController', 'showLogin');
$router->add('/login/submit', 'AuthController', 'login');
$router->add('/logout', 'AuthController', 'logout');

$router->add('/dashboard', 'DashboardController', 'index');

$router->add('/profile', 'ProfileController', 'show');

$router->add('/employees', 'EmployeeController', 'index');
$router->add('/employees/create', 'EmployeeController', 'create');
$router->add('/employees/view', 'EmployeeController', 'view');
$router->add('/employees/store', 'EmployeeController', 'store');
$router->add('/employees/edit', 'EmployeeController', 'edit');
$router->add('/employees/update', 'EmployeeController', 'update');
$router->add('/employees/cancel', 'EmployeeController', 'cancel');
$router->add('/employees/reactivate', 'EmployeeController', 'reactivate');
$router->add('/employees/table', 'EmployeeController', 'table');

$router->add('/leave', 'LeaveController', 'index');
$router->add('/leave/create', 'LeaveController', 'create');
$router->add('/leave/store', 'LeaveController', 'store');
$router->add('/leave/approve', 'LeaveController', 'approve');
$router->add('/leave/report', 'LeaveReportController', 'index');


// $router->add('/departments', 'DepartmentController', 'index');
// $router->add('/departments/store', 'DepartmentController', 'store');
// $router->add('/departments/update', 'DepartmentController', 'update');
// $router->add('/departments/delete', 'DepartmentController', 'delete');

$router->add('/attendance', 'AttendanceController', 'index');
$router->add('/attendance/create', 'AttendanceController', 'create');
$router->add('/attendance/store', 'AttendanceController', 'store');
$router->add('/attendance/update', 'AttendanceController', 'update');
$router->add('/attendance/approve', 'AttendanceController', 'approve');
$router->add('/attendance/list', 'AttendanceController', 'index');
// $router->add('/attendance/report', 'AttendanceController', 'approve');


// $router->add('/payroll', 'PayrollController', 'index');
// $router->add('/payroll/store', 'PayrollController', 'store');
// $router->add('/payroll/update', 'PayrollController', 'update');
// $router->add('/payroll/delete', 'PayrollController', 'delete');

$router->add('/documents', 'DocumentController', 'index');
$router->add('/documents/store', 'DocumentController', 'store');
$router->add('/documents/view', 'DocumentController', 'view');
$router->add('/documents/approve', 'DocumentController', 'approve');
$router->add('/documents/delete', 'DocumentController', 'delete');

$router->add('/scan', 'ScanController', 'scan');

$router->add('/roles', 'RoleController', 'index');
$router->add('/roles/permissions', 'RoleController', 'permissions');
$router->add('/roles/permissions/update', 'RoleController', 'updatePermissions');

$router->add('/settings', 'SettingsController', 'index');
$router->add('/settings/positions', 'SettingsController', 'positions');
$router->add('/settings/positions/store', 'SettingsController', 'positionsStore');
$router->add('/settings/positions/delete', 'SettingsController', 'positionsDelete');
$router->add('/settings/leave-types', 'SettingsController', 'leaveTypes');
$router->add('/settings/leave-types/store', 'SettingsController', 'leaveTypesStore');
$router->add('/settings/leave-types/delete', 'SettingsController', 'leaveTypesDelete');
$router->add('/settings/document-types', 'SettingsController', 'documentTypes');
$router->add('/settings/document-types/store', 'SettingsController', 'documentTypesStore');
$router->add('/settings/document-types/delete', 'SettingsController', 'documentTypesDelete');

// ---- ทำงาน ----
if (USE_QUERY_ROUTES) {
    route_paths($router->paths());
    ob_start('rewrite_routes_in_html');
}

$router->dispatch();