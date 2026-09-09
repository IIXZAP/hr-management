<?php

class DocumentController
{
    // แสดงเอกสารของพนักงานคนหนึ่ง (GET /documents?emp_id=5)
    public function index()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $empId = $_GET['emp_id'] ?? null;
        $employees = Employee::all();

        $selectedEmployee = null;
        $documents = [];

        // check not null กัน req เปล่า
        if ($empId !== null) {
            $selectedEmployee = Employee::find($empId);
            if ($selectedEmployee !== null) {
                $documents = Document::allByEmployee($empId);
            }
        }

        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/documents/list.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    // อัปโหลดเอกสารใหม่ (POST /documents/store)
    public function store()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        // basic version — ยังไม่ทำ upload ไฟล์จริง แค่รับ url ตรงๆ ก่อน
        $data = [
            'emp_id'     => $_POST['emp_id'] ?? null,
            'doc_url'    => sanitize($_POST['doc_url'] ?? ''),
            'doc_status' => 0,
        ];

        Document::create($data);

        redirect('/documents?emp_id=' . $data['emp_id']);
    }

    // ลบเอกสาร (POST /documents/delete)
    public function delete()
    {
        if (Auth::check() === false) {
            redirect('/login');
        }

        $docId = $_POST['doc_id'] ?? null;
        Document::delete($docId);

        redirect('/documents');
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

        $docId = $_POST['doc_id'] ?? null;

        if(empty($docId)) {
            redirect('/documents');
        }

        Document::updateStatus($docId, 'approved');

        redirect('/documents');
    }
}
