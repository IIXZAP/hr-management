<?php

class EmployeeContact
{
    public static function findByEmpId($empId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM employee_contact WHERE emp_id = :emp_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':emp_id' => $empId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // Create
    public static function create($empId, $data, $conn = null)
    {
        if ($conn === null) {
            $conn = Database::connect();
        }

        $sql = "INSERT INTO employee_contact (emp_id, contact_type, name, relationship, tel, is_primary) VALUES (:emp_id, :contact_type, :name, :relationship, :tel, :is_primary)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_id' => $empId,
            ':contact_type' => $data['contact_type'] ?? '',
            ':name' => $data['name'] ?? '',
            ':relationship' => $data['relationship'] ?? '',
            ':tel' => $data['tel'] ?? '',
            ':is_primary' => $data['is_primary'] ?? '',
        ]);

        return $conn->lastInsertId();
    }

    // update
    public static function update($empId, $data, $conn = null)
    {
        if ($conn === null) {
            $conn = Database::connect();
        }

        $sql = "UPDATE employee_contact 
                SET contact_type = :contact_type,
                    name = :name,
                    relationship = :relationship,
                    tel = :tel,
                    is_primary = :is_primary
                WHERE emp_id = :emp_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':contact_type' => $data['contact_type'],
            ':name' => $data['name'],
            ':relationship' => $data['relationship'],
            ':tel' => $data['tel'],
            ':is_primary' => $data['is_primary'],
            ':emp_id' => $empId,
        ]);

        $atffectRow = $stmt->rowCount();

        if($atffectRow > 0)
        {
            return true;
        }

        return false;
    }
}
