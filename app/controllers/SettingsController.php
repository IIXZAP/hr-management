<?php
// หน้าที่: จัดการ master data (positions, leave_types, document_type)

class SettingsController
{
    public function index()
    {
        $this->guard();

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/admin/settings/index.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }
    private function guard()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/profile');
        }
    }

    // ---------------- POSITIONS ----------------

    public function positions()
    {
        $this->guard();

        $conn = Database::connect();
        $stmt = $conn->query("SELECT * FROM positions ORDER BY position_id");
        $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/admin/settings/positions.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }

    public function positionsStore()
    {
        $this->guard();

        $name = trim($_POST['position_name'] ?? '');

        if ($name === '') {
            redirect('/settings/positions');
        }

        $conn = Database::connect();
        $stmt = $conn->prepare("INSERT INTO positions (position_name) VALUES (:name)");
        $stmt->execute([':name' => $name]);

        redirect('/settings/positions');
    }

    public function positionsDelete()
    {
        $this->guard();

        $id = (int) ($_POST['position_id'] ?? 0);

        if ($id <= 0) {
            redirect('/settings/positions');
        }

        $conn = Database::connect();

        // เช็กก่อนว่ามี contract ใช้ position นี้อยู่ไหม
        $check = $conn->prepare("SELECT COUNT(*) FROM contract WHERE cont_position = :id");
        $check->execute([':id' => $id]);

        if ($check->fetchColumn() > 0) {
            echo 'ไม่สามารถลบได้ เนื่องจากมีพนักงานใช้ตำแหน่งนี้อยู่';
            return;
        }

        $stmt = $conn->prepare("DELETE FROM positions WHERE position_id = :id");
        $stmt->execute([':id' => $id]);

        redirect('/settings/positions');
    }

    // ---------------- LEAVE TYPES ----------------

    public function leaveTypes()
    {
        $this->guard();

        $conn = Database::connect();
        $stmt = $conn->query("SELECT * FROM leave_types ORDER BY leave_type_id");
        $leaveTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/admin/settings/leave_types.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }

    public function leaveTypesStore()
    {
        $this->guard();

        $name = trim($_POST['leave_type_name'] ?? '');
        $maxDays = $_POST['max_days_per_year'] ?? null;
        $maxDays = ($maxDays === '' || $maxDays === null) ? null : (int) $maxDays;

        if ($name === '') {
            redirect('/settings/leave_types');
        }

        $conn = Database::connect();
        $stmt = $conn->prepare(
            "INSERT INTO leave_types (leave_type_name, max_days_per_year) VALUES (:name, :max)"
        );
        $stmt->execute([':name' => $name, ':max' => $maxDays]);

        redirect('/settings/leave_types');
    }

    public function leaveTypesDelete()
    {
        $this->guard();

        $id = (int) ($_POST['leave_type_id'] ?? 0);

        if ($id <= 0) {
            redirect('/settings/leave_types');
        }

        $conn = Database::connect();

        $check = $conn->prepare("SELECT COUNT(*) FROM time_leave WHERE leave_type_id = :id");
        $check->execute([':id' => $id]);

        if ($check->fetchColumn() > 0) {
            echo 'ไม่สามารถลบได้ เนื่องจากมีประวัติการลาผูกกับประเภทนี้อยู่';
            return;
        }

        $stmt = $conn->prepare("DELETE FROM leave_types WHERE leave_type_id = :id");
        $stmt->execute([':id' => $id]);

        redirect('/settings/leave_types');
    }

    // ---------------- DOCUMENT TYPES ----------------

    public function documentTypes()
    {
        $this->guard();

        $conn = Database::connect();
        $stmt = $conn->query("SELECT * FROM document_type ORDER BY type_id");
        $documentTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/views/shared/layouts/header.php';
        require BASE_PATH . '/views/admin/settings/document_types.php';
        require BASE_PATH . '/views/shared/layouts/footer.php';
    }

    public function documentTypesStore()
    {
        $this->guard();

        $name = trim($_POST['type_name'] ?? '');

        if ($name === '') {
            redirect('/settings/document-types');
        }

        $conn = Database::connect();
        $stmt = $conn->prepare("INSERT INTO document_type (type_name) VALUES (:name)");
        $stmt->execute([':name' => $name]);

        redirect('/settings/document-types');
    }

    public function documentTypesDelete()
    {
        $this->guard();

        $id = (int) ($_POST['type_id'] ?? 0);

        if ($id <= 0) {
            redirect('/settings/document-types');
        }

        $conn = Database::connect();

        $check = $conn->prepare("SELECT COUNT(*) FROM documents WHERE type_id = :id");
        $check->execute([':id' => $id]);

        if ($check->fetchColumn() > 0) {
            echo 'ไม่สามารถลบได้ เนื่องจากมีเอกสารผูกกับประเภทนี้อยู่';
            return;
        }

        $stmt = $conn->prepare("DELETE FROM document_type WHERE type_id = :id");
        $stmt->execute([':id' => $id]);

        redirect('/settings/document-types');
    }
}
