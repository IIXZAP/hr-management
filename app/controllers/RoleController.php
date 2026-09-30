<?php

class RoleController
{
    // private static $modules = ['dashboard', 'employee', 'payroll', 'documents', 'attendance', 'leave'];

    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $conn = Database::connect();
        $stmt = $conn->query("SELECT * FROM roles ORDER BY role_id");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/admin/roles/list.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }

    public function permissions()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $roleId = (int) ($_GET['id'] ?? 0);

        $conn = Database::connect();

        $stmt = $conn->prepare("SELECT * FROM roles WHERE role_id = :id");
        $stmt->execute([':id' => $roleId]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$role) {
            echo 'ไม่พบ Role นี้';
            return;
        }

        $stmt = $conn->prepare(
            "SELECT p.*, m.module_key
             FROM permissions p
             JOIN modules m ON m.module_id = p.module_id
             WHERE p.role_id = :id"
        );
        $stmt->execute([':id' => $roleId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $permissions = [];
        foreach ($rows as $row) {
            $permissions[$row['module_key']] = $row;
        }

        // $modules = self::$modules;
        $modules = $conn->query("SELECT module_id, module_key, module_name FROM modules ORDER BY module_id")
            ->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/admin/roles/permissions.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }

    public function updatePermissions()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }

        $roleId = (int) ($_POST['role_id'] ?? 0);

        if ($roleId <= 0) {
            redirect('/roles');
        }

        $conn = Database::connect();

        $modules = $conn->query("SELECT module_id, module_key FROM modules")->fetchAll(PDO::FETCH_ASSOC);

        $sql = "INSERT INTO permissions (role_id, module_id, can_create, can_read, can_update, can_delete)
                VALUES (:role_id, :module_id, :create, :read, :update, :delete)
                ON DUPLICATE KEY UPDATE
                    can_create = :create2, can_read = :read2,
                    can_update = :update2, can_delete = :delete2";

        $stmt = $conn->prepare($sql);

        foreach ($modules as $m) {
            $key    = $m['module_key'];
            $create = isset($_POST['perm'][$key]['create']) ? 1 : 0;
            $read   = isset($_POST['perm'][$key]['read'])   ? 1 : 0;
            $update = isset($_POST['perm'][$key]['update']) ? 1 : 0;
            $delete = isset($_POST['perm'][$key]['delete']) ? 1 : 0;

            $stmt->execute([
                ':role_id'   => $roleId,
                ':module_id' => (int) $m['module_id'],
                ':create'    => $create,
                ':read'      => $read,
                ':update'    => $update,
                ':delete'    => $delete,
                ':create2'   => $create,
                ':read2'     => $read,
                ':update2'   => $update,
                ':delete2'   => $delete,
            ]);
        }

        redirect('/roles/permissions?id=' . $roleId);
    }
}
