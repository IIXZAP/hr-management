<?php

class Document
{
    public static function allByEmployee($empId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM documents WHERE emp_id = :emp_id ORDER BY doc_id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':emp_id' => $empId]);
        $result = $stmt->fetchAll();

        return $result;
    }



    public static function find($docId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM documents WHERE doc_id = :doc_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':doc_id' => $docId]);

        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }

    public static function create($data)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO documents
                (emp_id, doc_url, type_id, doc_name, doc_status, created_at, updated_at)
                VALUES
                (:emp_id, :doc_url, :type_id, :doc_name, :doc_status, NOW(), NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emp_id'     => $data['emp_id'],
            ':doc_url'    => $data['doc_url'],
            ':type_id'    => $data['type_id'],
            ':doc_name'   => $data['doc_name'],
            ':doc_status' => $data['doc_status'],
        ]);

        return $conn->lastInsertId();
    }

    public static function updateStatus($docId, $status)
    {
        $conn = Database::connect();

        $sql = "UPDATE documents
                SET doc_status = :doc_status, updated_at = NOW()
                WHERE doc_id = :doc_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':doc_status' => $status,
            ':doc_id'     => $docId,
        ]);

        $atffectRow = $stmt->rowCount();

        if ($atffectRow > 0) {
            return true;
        }

        return false;
    }

    public static function delete($docId)
    {
        $conn = Database::connect();

        // ดึงไฟล์ก่อนลบ
        $doc = self::find($docId);
        if ($doc === null) {
            return false;
        }

        $sql = "DELETE FROM documents WHERE doc_id = :doc_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':doc_id' => $docId]);

        $atffectRow = $stmt->rowCount();

        if ($atffectRow > 0) {
            $filePath = __DIR__ . '/../../' .  ltrim($doc['doc_url'], '/');
            return true;
        }

        return false;
    }

    // ใช้เฉพาะฝั่ง staff — เห็นแค่เอกสารที่ผ่านการอนุมัติแล้วเท่านั้น
    public static function approvedByEmployee($empId)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM documents 
            WHERE emp_id = :emp_id AND doc_status = 'approved'
            ORDER BY created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':emp_id' => $empId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
