<?php

class RfidTag 
{   
    // find uid 
    public static function findByUid($uid)
    {
        $conn = Database::connect();

        $sql = "SELECT tag_id, uid, assign, start_time FROM rfid_tag WHERE uid = :uid LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':uid' => $uid]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    public static function assignToEmployee($tagId, $assign)
    {
        $conn = Database::connect();
 
        $sql = "UPDATE rfid_tag SET assign = :assign WHERE tag_id = :tag_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':assign' => $assign,
            ':tag_id' => $tagId,
        ]);
 
        $atffectRow = $stmt->rowCount();

        if($atffectRow > 0)
        {
            return true;
        }

        return false;
    }
}