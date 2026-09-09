<?php
// app/models/LeaveType.php
// หน้าที่: จัดการข้อมูลการลา (ตาราง leave_types)

class LeaveType 
{
    public static function all()
    {
        $conn = Database::connect();

        $sql = "SELECT * 
                FROM leave_types 
                ORDER BY leave_type_id ASC
                ";
        $stmt = $conn->prepare($sql);
         $stmt->execute();
        $result = $stmt->fetchAll();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    
}