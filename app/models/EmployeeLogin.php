<?php

// จัดการข้อมูลบัญชีเข้าระบบของพนักงาน (username, password_hash, สิทธิ์ admin/role)

class EmployeeLogin
{
    public static function findByEmpId($empId)
    {
        $conn = Database::connect();

        $sql = "SELECT e.*, r.role_name 
                FROM employee_login e
                LEFT JOIN roles r
                    ON e.role_id = r.role_id
                WHERE emp_id = :emp_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':emp_id' => $empId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // create
    // หมายเหตุ: hash รหัสผ่านด้วย password_hash() ก่อนบันทึกเสมอ ห้ามเก็บ plain text เด็ดขาด
    // คอลัมน์ในฐานข้อมูลชื่อ password_hash (ไม่ใช่ password เฉยๆ)
    // is_admin มาจาก user_level select ("staff"/"admin") ไม่ใช่ checkbox แล้ว
    public static function create($empId, $data, $conn = null)
    {
        if ($conn === null) {
            $conn = Database::connect();
        }

        $isAdmin = (($data['user_level'] ?? 'staff') === 'admin') ? 1 : 0;
        $roleId  = $isAdmin ? ($data['role_id'] ?? null) : null;

        $sql = "INSERT INTO employee_login (emp_id, username, password_hash, is_admin, role_id)
                VALUES (:emp_id, :username, :password_hash, :is_admin, :role_id)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_id'        => $empId,
            ':username'      => $data['username'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':is_admin'      => $isAdmin,
            ':role_id'       => $roleId,
        ]);

        return $conn->lastInsertId();
    }

    // update
    // หมายเหตุ: ไม่อัปเดตรหัสผ่านที่นี่ (ควรแยกหน้า "เปลี่ยนรหัสผ่าน" ต่างหาก
    // กันการเขียนทับรหัสผ่านเดิมโดยไม่ตั้งใจตอนแก้ไขข้อมูลอื่น)
    public static function update($empId, $data, $conn = null)
    {
        if ($conn === null) {
            $conn = Database::connect();
        }

        $isAdmin = (($data['user_level'] ?? 'staff') === 'admin') ? 1 : 0;
        $roleId  = $isAdmin ? ($data['role_id'] ?? null) : null;

        $sql = "UPDATE employee_login
                SET username = :username,
                    is_admin = :is_admin,
                    role_id = :role_id
                WHERE emp_id = :emp_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'],
            ':is_admin' => $isAdmin,
            ':role_id'  => $roleId,
            ':emp_id'   => $empId,
        ]);

        return $stmt->rowCount() > 0;
    }
}