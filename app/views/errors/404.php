<?php
// views/errors/404.php
// หน้าที่: โชว์ตอนหา route ไม่เจอ (Router::dispatch() require เข้ามา)
// ไม่มี <html> ครอบเอง — header.php/footer.php ครอบให้แล้ว
// รูปเป็น SVG วาดเอง (ธีมฟันเฟือง + 404) ไม่ใช่ stock image เพราะมีลิขสิทธิ์
?>

<div class="text-center py-16">

    <img src="/assets/images/404.jpg" alt="404 error" class="w-64 mx-auto mb-6">

    <h1 class="text-xl font-semibold text-gray-900 mb-2">ไม่พบหน้านี้</h1>
    <p class="text-gray-500 mb-6">URL ที่เข้ามาไม่มีอยู่ในระบบ ตรวจสอบลิงก์อีกครั้ง</p>

    <a href="/dashboard" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        กลับหน้าแรก
    </a>

</div>