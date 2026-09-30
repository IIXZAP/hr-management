<?php

class Auth
{
    public static function login($username, $password)
    {
        // Connect DB
        $conn = Database::connect();

        $sql = "SELECT
                 em.emp_id AS user_id,
                 em_login.username AS username,
                 em_login.password_hash AS password,
                 em_login.role_id AS role_id,
                 roles.role_name AS role_name
                 FROM employees em
                 INNER JOIN employee_login em_login
                     ON em.emp_id = em_login.emp_id
                 INNER JOIN roles
                     ON em_login.role_id = roles.role_id
                 WHERE em_login.username = :username AND em.emp_cancel = 1
                 ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        // $params = [':username' => $username];
        // $stmt = Database::query($sql, $params);
        $user = $stmt->fetch();

        // Check user
        if (!$user) {
            return false;
        }

        $isPasswordCorrect = password_verify($password, $user['password']);

        // Check password hash
        if (!$isPasswordCorrect) {
            return false;
        }

        // สร้างเลข id กันการโจมตี
        session_regenerate_id(true);

        //   Collect session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role_name'] = $user['role_name'];

        $upd = $conn->prepare("UPDATE employee_login SET last_login = NOW() WHERE emp_id = :emp_id");
        $upd->execute([':emp_id' => $user['user_id']]);



        return true;
    }

    public static function check()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    public static function isAdmin()
    {
        // if (isset($_SESSION['is_admin'])) {
        //     $isAdmin_check = $_SESSION['is_admin'];
        // } else {
        //     $isAdmin_check = 0;
        // }

        // if ($isAdmin_check == 1) {
        //     return true;
        // } else {
        //     return false;
        // }
        // return ($_SESSION['is_admin'] ?? 0) === 1;
        return self::roleName() === 'admin';
    }

    public static function roleName()
    {
        return $_SESSION['role_name'] ?? null;
    }

    public static function empId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function logout()
    {
        // Clear session
        $_SESSION = [];
        session_destroy();
    }

    public static function can($module, $action)
    {
        if (!self::check()) {
            return false;
        }

        if (self::isAdmin()) {
            return true;
        }

        $roleId = $_SESSION['role_id'] ?? null;

        if (!$roleId) {
            return false;
        }

        $conn = Database::connect();

        $sql = "SELECT p.can_create, p.can_read, p.can_update, p.can_delete
                FROM permissions p
                JOIN modules m ON m.module_id = p.module_id
                WHERE p.role_id = :role_id AND m.module_key = :module";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':role_id' => $roleId,
            ':module'  => $module
        ]);
        $perm = $stmt->fetch();

        // ไม่มีแถวนี้เลย = ไม่มีสิทธิ์
        if (!$perm) {
            return false;
        }

        $column = 'can_' . $action; // create / read / update / delete

        return isset($perm[$column]) && $perm[$column] == 1;
    }

    public static function user()
    {
        static $user = null;

        if($user === null && self::check()) {
            $user = Employee::find(self::empId());
        }
        return $user;
    } 
}
