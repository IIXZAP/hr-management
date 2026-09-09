<?php

// จัดการข้อมูลของ Employee

class Employee
{
    // Fetch all
    public static function all($search = '', $status = '')
    {
        $conn = Database::connect();

        $sql = "SELECT e.* , c.* , r.role_name, p.position_name
                FROM employees e
                LEFT JOIN contract c 
                    ON e.emp_id = c.emp_id
                LEFT JOIN employee_login el
                    ON e.emp_id = el.emp_id
                LEFT JOIN roles r
                    ON el.role_id = r.role_id
                LEFT JOIN positions p
                    ON c.cont_position = p.position_id
                WHERE 1=1
                ";

        $params = [];

        if ($search !== '') {
            $sql .= " AND (p.position_name LIKE :search
                        OR e.emp_name_th LIKE :search
                        OR e.emp_sname_th LIKE :search)";
            $params[':search'] = '%' .  $search . '%';
        }

        if ($status !== '') {
            $sql .= " AND e.emp_cancel LIKE :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY e.emp_id ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll();

        return $result;
    }

    // Find
    public static function find($empId)
    {
        $conn = Database::connect();

        $sql = "SELECT e.* , c.* , r.role_name, p.position_name
                FROM employees e
                LEFT JOIN contract c 
                    ON e.emp_id = c.emp_id
                LEFT JOIN employee_login el
                    ON e.emp_id = el.emp_id
                LEFT JOIN roles r
                    ON el.role_id = r.role_id
                LEFT JOIN positions p
                    ON c.cont_position = p.position_id
                WHERE e.emp_id = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $empId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }
    // Add new employee
    public static function create($data)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO employees (emp_no, emp_prefix_th, emp_name_th, emp_sname_th, emp_nickname_th, emp_prefix_en, emp_name_en, emp_sname_en, emp_nickname_en, emp_idcard, emp_idss, emp_birthday, emp_tel, emp_email, emp_address, emp_line, emp_cancel) VALUES (:emp_no, :emp_prefix_th, :emp_name_th, :emp_sname_th, :emp_nickname_th,
         :emp_prefix_en, :emp_name_en, :emp_sname_en, :emp_nickname_en,
         :emp_idcard, :emp_birthday, :emp_tel, :emp_email, :emp_address, :emp_line, :emp_cancel)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_no' => $data['emp_no'],
            ':emp_prefix_th' => $data['emp_prefix_th'],
            ':emp_name_th' => $data['emp_name_th'],
            ':emp_sname_th' => $data['emp_sname_th'],
            ':emp_nickname_th' => $data['emp_nickname_th'],
            ':emp_prefix_en' => $data['emp_prefix_en'],
            ':emp_name_en' => $data['emp_name_en'],
            ':emp_sname_en' => $data['emp_sname_en'],
            ':emp_nickname_en' => $data['emp_nickname_en'],
            ':emp_idcard' => $data['emp_idcard'],
            ':emp_idss' => $data['emp_idss'],
            ':emp_birthday' => $data['emp_birthday'],
            ':emp_tel' => $data['emp_tel'],
            ':emp_email' => $data['emp_email'],
            ':emp_address' => $data['emp_address'],
            ':emp_line' => $data['emp_line'],
            ':emp_cancel' => 1,
        ]);

        $newId = $conn->lastInsertId();

        return $newId;
    }
    // Update
    public static function update($empId, $data)
    {
        $conn = Database::connect();

        $sql = "UPDATE employees 
                SET emp_no = :emp_no,
                    emp_prefix_th = :emp_prefix_th,
                    emp_name_th = :emp_name_th,
                    emp_sname_th = :emp_sname_th,
                    emp_nickname_th = :emp_nickname_th,
                    emp_prefix_en = :emp_prefix_en,
                    emp_name_en = :emp_name_en,
                    emp_sname_en = :emp_sname_en,
                    emp_nickname_en = :emp_nickname_en,
                    emp_idcard = :emp_idcard,
                    emp_idss = :emp_idss,
                    emp_birthday = :emp_birthday,
                    emp_tel = :emp_tel,
                    emp_email = :emp_email,
                    emp_address = :emp_address,
                    emp_line = :emp_line,
                    emp_cancel = :emp_cancel 
                WHERE emp_id = :emp_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_no' => $data['emp_no'],
            ':emp_prefix_th' => $data['emp_prefix_th'],
            ':emp_name_th' => $data['emp_name_th'],
            ':emp_sname_th' => $data['emp_sname_th'],
            ':emp_nickname_th' => $data['emp_nickname_th'],
            ':emp_prefix_en' => $data['emp_prefix_en'],
            ':emp_name_en' => $data['emp_name_en'],
            ':emp_sname_en' => $data['emp_sname_en'],
            ':emp_nickname_en' => $data['emp_nickname_en'],
            ':emp_idcard' => $data['emp_idcard'],
            ':emp_idss' => $data['emp_idss'],
            ':emp_birthday' => $data['emp_birthday'],
            ':emp_tel' => $data['emp_tel'],
            ':emp_email' => $data['emp_email'],
            ':emp_address' => $data['emp_address'],
            ':emp_line' => $data['emp_line'],
            ':emp_cancel' => $data['emp_cancel'],
            ':emp_id' => $empId,
        ]);

        $atffectRow = $stmt->rowCount();

        if ($atffectRow > 0) {
            return true;
        }

        return false;
    }

    // Delete - (Soft delete set status)
    public static function cancel($empId)
    {
        $conn = Database::connect();

        $sql = "UPDATE employees SET emp_cancel = 0 WHERE emp_id = :emp_id ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_id' => $empId
        ]);

        $atffectRow = $stmt->rowCount();

        if ($atffectRow > 0) {
            return true;
        }

        return false;
    }

    public static function reactivate($empId)
    {
        $conn = Database::connect();

        $sql = "UPDATE employees SET emp_cancel = 1 WHERE emp_id = :emp_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':emp_id' => $empId]);

        $atffectRow = $stmt->rowCount();

        if ($atffectRow > 0) {
            return true;
        }

        return false;
    }

    public static function findByAssign($assign)
    {
        $conn = Database::connect();

        $sql = "SELECT emp_name_th, emp_sname_th, emp_nickname_th ,emp_no FROM employees WHERE emp_no = :assign AND emp_cancel = 1 LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':assign' => $assign]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    public static function paginate($page, $perPage)
    {
        $conn = Database::connect();

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM employees WHERE emp_cancel = 1 ORDER BY emp_id DESC LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    public static function countAll()
    {
        $conn = Database::connect();

        $sql = "SELECT COUNT(*) AS total FROM employees WHERE emp_cancel = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        return (int) $result['total'];
    }

    public static function empno()
    {
        $conn = Database::connect();

        $sql = "SELECT MAX(emp_no) AS emp_no FROM employees";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();


        $count_no = $result['emp_no'] ?? '';
        $clean_no = str_replace("ktn", "", $count_no);
        $next_no = (int)$clean_no + 1;


        $emp_no = sprintf("ktn%03d", $next_no);

        return $emp_no;
    }

    // Check cancel
    public static function isCancelled($employee)
    {
        return isset($employee['emp_cancel']) && (int)$employee['emp_cancel'] === 0;
    }

}
