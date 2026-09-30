</main>
<footer class="border-t border-default-medium bg-white">
    <div class="max-w-[1600px] mx-auto px-6 md:px-10 py-5 flex justify-center md:justify-end">
        <div class="flex items-center gap-4">
            <div class="text-xs ink-2">© 2026 Tinnalice Co., Ltd. All rights reserved.</div>
            <div class="w-px h-4" style="background:var(--rule);"></div>
            <div class="mono text-[11px] tracking-[0.14em] uppercase ink-3">v1.0.0</div>
        </div>
    </div>
</footer>
<script src="/dist/flowbite.min.js"></script>
<script>
    (function setDate() {
        const THAI_DAYS = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        const THAI_MONTHS = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        const d = new Date();
        const str = 'วัน' + THAI_DAYS[d.getDay()] + 'ที่ ' + d.getDate() + ' ' + THAI_MONTHS[d.getMonth()] + ' ' + (d.getFullYear() + 543);
        document.getElementById('thai-date').textContent = str;
        const d2 = document.getElementById('thai-date-2');
        if (d2) d2.textContent = str;
    })();
</script>

</body>

</html>