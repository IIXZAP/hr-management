<?php

// จัดการข้อมูลตำแหน่งงาน (ใช้ป้อน dropdown "ตำแหน่ง" ในฟอร์มพนักงาน)

class Position
{
    // Fetch all
    public static function all()
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM positions ORDER BY position_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    // Find
    public static function find($positionId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM positions WHERE position_id = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $positionId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    // Create
    public static function create($positionName)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO positions (position_name) VALUES (:position_name)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':position_name' => $positionName]);

        return $conn->lastInsertId();
    }

    // Update
    public static function update($positionId, $positionName)
    {
        $conn = Database::connect();

        $sql = "UPDATE positions SET position_name = :position_name WHERE position_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':position_name' => $positionName,
            ':id' => $positionId,
        ]);

        return $stmt->rowCount() > 0;
    }

    // Delete
    public static function delete($positionId)
    {
        $conn = Database::connect();

        $sql = "DELETE FROM positions WHERE position_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $positionId]);

        return $stmt->rowCount() > 0;
    }
}