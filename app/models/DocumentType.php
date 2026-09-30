<?php

class DocumentType
{
    public static function all()
    {
        $conn = Database::connect();

        $stmt = $conn->prepare("SELECT type_id, type_name FROM document_type ORDER BY type_id");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}