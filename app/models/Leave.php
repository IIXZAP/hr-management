<?php
// app/models/Leave.php
// หน้าที่: จัดการข้อมูลการลา (ตาราง time_leave)

class Leave
{
    // fetch history for each employee (employee side)
    public static function allByEmployee($empId)
    {
        $conn = Database::connect();

        $sql = "SELECT tl.* , lts.leave_types_name 
                FROM time_leave tl
                LEFT JOIN leave_types lts  
                    ON lts.leave_type_id = tl.leave_type_id
                WHERE tl.emp_id = :emp_id
                ORDER BY tl.leave_date DESC
                ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':emp_id' => $empId]);
        $result = $stmt->fetchAll();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // fetch all (admin side)
    public static function all()
    {
        $conn = Database::connect();

        $sql = "SELECT tl.* , lts.leave_type_name,  e.*
                FROM time_leave tl
                LEFT JOIN leave_types lts  
                    ON lts.leave_type_id = tl.leave_type_id
                LEFT JOIN employees e
                    ON e.emp_id = tl.emp_id
                ORDER BY tl.leave_date DESC
                ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // select for delete
    public static function find($leaveId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM time_leave WHERE leave_id = :leave_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':leave_id' => $leaveId]);

        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // create()
    public static function create($data)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO time_leave
                (emp_id, leave_type_id, leave_date, leave_day, leave_days, leave_comment, leave_status, created_at, updated_at) 
                VALUES 
                (:emp_id, :leave_type_id, :leave_date, :leave_day, :leave_days, :leave_comment, 'pending', NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_id' => $data['emp_id'],
            ':leave_type_id' => $data['leave_type_id'],
            ':leave_date' => $data['leave_date'],
            ':leave_day' => $data['leave_day'],
            ':leave_days' => $data['leave_days'],
            ':leave_comment' => $data['leave_comment'],
        ]);

        return $conn->lastInsertId();
    }

    // update
    public static function updateStatus($leaveId, $status)
    {
        $conn = Database::connect();

        $sql = "UPDATE time_leave 
                SET leave_status = :leave_status,
                    updated_at = NOW()
                WHERE leave_id = :leave_id
                ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':leave_status' => $status,
            ':leave_id' => $leaveId
        ]);

        return $stmt->rowCount() > 0;
    }

    //  delete (ลบได้เฉพาะตอนยัง pending เท่านั้น — เช็คใน Controller ก่อนเรียก)
    public static function delete($leaveId)
    {
        $conn = Database::connect();

        $sql = "DELETE FROM time_leave WHERE leave_id = :leave_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':leave_id' => $leaveId]);

        return $stmt->rowCount() > 0;
    }

    public static function countLeave()
    {
        $conn = Database::connect();

        $sql = "SELECT COUNT(*) AS totalLeave FROM time_leave WHERE leave_date = CURDATE()";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        return (int) $result['totalLeave'];
    }
}
