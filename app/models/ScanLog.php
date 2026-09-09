<?php

class ScanLog
{
    public static function create($tagId, $empId, $action, $status, $note = null, $attendanceId = null)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO scan_logs
                (rfid_id, employee_id, attendance_id, scanned_at, action, status, note, created_at)
                VALUES
                (:rfid_id, :employee_id, :attendance_id, NOW(), :action, :status, :note, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':rfid_id'       => $tagId,
            ':employee_id'   => $empId,
            ':attendance_id' => $attendanceId,
            ':action'        => $action,   // 'in' หรือ 'out'
            ':status'        => $status,   // 'success' หรือ 'error'
            ':note'          => $note,
        ]);

        return $conn->lastInsertId();
    }

    public static function isDuplicate($uid, $windowSeconds)
    {
        $conn = Database::connect();

        $sql = "SELECT id FROM scan_logs
            WHERE rfid_uid = ?
              AND scanned_at >= DATE_SUB(NOW(), INTERVAL ? SECOND)
              AND action NOT IN ('duplicate_scan')
            ORDER BY scanned_at DESC
            LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':uid', $uid);
        $stmt->bindValue(':window_seconds', $windowSeconds, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        
        if ($result === false) {
            return null;
        }

        return $result;
 
    }
}
