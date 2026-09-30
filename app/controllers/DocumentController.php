<?php

class DocumentController
{
    // แสดงเอกสารของพนักงานคนหนึ่ง (GET /documents?emp_id=5)
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::can('documents', 'read') === false) {
            redirect('/profile');
        }

        if (Auth::isAdmin()) {
            // ฝั่ง admin: เลือกดูของใครก็ได้
            $employees = Employee::all();
            $empId = $_GET['emp_id'] ?? null;
            $selectedEmployee = $empId !== null ? Employee::find($empId) : null;
            $documents = $selectedEmployee !== null ? Document::allByEmployee($empId) : [];
            $documentTypes = DocumentType::all();

            require BASE_PATH . '/views/shared/layouts/header.php';
            require BASE_PATH . '/views/admin/documents/upload.php';
            require BASE_PATH . '/views/shared/layouts/footer.php';
        } else {
            // ฝั่ง staff: เห็นแค่ของตัวเอง ไม่ต้องเลือกใคร
            $employee = Employee::find(Auth::empId());
            $documents = Document::approvedByEmployee(Auth::empId());
            $documentTypes = DocumentType::all();

            require BASE_PATH . '/views/shared/layouts/header.php';
            require BASE_PATH . '/views/staff/documents/my.php';
            require BASE_PATH . '/views/shared/layouts/footer.php';
        }
    }

    // อัปโหลดเอกสารใหม่ (POST /documents/store)
    public function store()
    {
        if (Auth::check() === false) {
            redirect('/login');
            return;
        }

        $empId  = (int) ($_POST['emp_id'] ?? 0);
        $typeId = (int) ($_POST['type_id'] ?? 0);
        $docName = trim($_POST['doc_name'] ?? '');
        $file   = $_FILES['document'] ?? null;

        if ($file === null || $file['error'] !== UPLOAD_ERR_OK) {
            redirect('/documents?emp_id=' . $empId);
            return;
        }

        $allowedTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!isset($allowedTypes[$ext]) || $mime !== $allowedTypes[$ext]) {
            redirect('/documents?emp_id=' . $empId);
            return;
        }

        $year = date('Y');
        $uploadDir = BASE_PATH . '/storage/docs/' . $year . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
            redirect('/documents?emp_id=' . $empId);
            return;
        }

        $data = [
            'emp_id'     => $empId,
            'type_id'    => $typeId,
            'doc_name'   => $docName,
            'doc_url'    => $year . '/' . $fileName,
            'doc_status' => 'pending',
        ];

        Document::create($data);

        redirect('/documents?emp_id=' . $data['emp_id']);
    }

    public function view()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $docId = (int) ($_GET['doc_id'] ?? 0);
        if ($docId > 0) {
            $doc = Document::find($docId);
        } else {
            $doc = false;
        }

        if (!$doc) {
            http_response_code(404);
            exit('Document not found');
        }

        if (!Auth::isAdmin()) {
            if ((int) $doc['emp_id'] !== Auth::empId() || $doc['doc_status'] !== 'approved') {
                http_response_code(403);
                exit('Forbidden');
            }
        }

        if (!preg_match('#^[0-9]{4}/[a-f0-9]+\.(pdf|doc|docx)$#', $doc['doc_url'])) {
            http_response_code(400);
            exit('Invalid document path');
        }

        $filePath = BASE_PATH . '/storage/docs/' . $doc['doc_url'];

        if (!is_file($filePath)) {
            http_response_code(404);
            exit('File not found');
        }

        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeMap = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        header('Content-Type: ' . ($mimeMap[$fileExt] ?? 'application/octet-stream'));
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('X-Content-Type-Options: nosniff');
        readfile($filePath);
        exit;
    }

    // ลบเอกสาร (POST /documents/delete)
    public function delete()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $empId = (int) ($_POST['emp_id'] ?? 0);

        if (!Auth::isAdmin()) {
            redirect('/documents?emp_id=' . $empId);
            return;
        }

        $docId = (int) ($_POST['doc_id'] ?? 0);
        $doc   = $docId > 0 ? Document::find($docId) : false;

        if (!$doc) {
            redirect('/documents?emp_id=' . $empId);
            return;
        }

        Document::delete($docId);

        redirect('/documents?emp_id=' . (int) $doc['emp_id']);
    }

    public function approve()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        if (Auth::isAdmin() === false) {
            redirect('/documents');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/documents');
        }
        $empId = (int) ($_POST['emp_id'] ?? 0);
        $docId = $_POST['doc_id'] ?? null;
        $doc   = $docId > 0 ? Document::find($docId) : false;

        if (!$doc) {
            redirect('/documents?emp_id=' . $empId);
            return;
        }

        if (empty($docId)) {
            redirect('/documents');
        }

        Document::updateStatus($docId, 'approved');

        redirect('/documents?emp_id=' . (int) $doc['emp_id']);
    }
}

