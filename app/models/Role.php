<?php

class Role
{
    public static function all()
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM roles ORDER BY role_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    // Fetch 
    public static function find($roleId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM roles WHERE role_id = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $roleId]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }
}
