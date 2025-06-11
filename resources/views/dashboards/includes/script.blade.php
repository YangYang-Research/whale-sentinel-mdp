<!-- Bootstrap core JavaScript-->
<script src="{{ asset ('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset ('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset ('assets/js/sb-admin-2.min.js') }}"></script>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<!-- <script>
    function setCookie(name, value, days = 7) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + "=" + encodeURIComponent(value) + "; expires=" + expires + "; path=/";
    }

    function getCookie(name) {
        return document.cookie.split('; ').reduce((r, v) => {
            const parts = v.split('=');
            return parts[0] === name ? decodeURIComponent(parts[1]) : r;
        }, '');
    }

    document.addEventListener("DOMContentLoaded", function () {
        const body = document.body;
        const sidebar = document.getElementById("accordionSidebar");
        const toggleBtn = document.getElementById("sidebarToggle");

        if (!sidebar || !toggleBtn) return;

        // Áp dụng trạng thái ban đầu từ cookie
        const isCollapsed = getCookie("sidebar-collapsed") === "true";
        if (isCollapsed) {
            body.classList.add("sidebar-toggled");
            sidebar.classList.add("toggled");
        }

        toggleBtn.addEventListener("click", function () {
            console.log("Before toggle, toggled =", sidebar.classList.contains("toggled"));
            const willCollapse = !sidebar.classList.contains("toggled");
            console.log("willCollapse =", willCollapse);

            body.classList.toggle("sidebar-toggled");
            sidebar.classList.toggle("toggled");

            console.log("After toggle, toggled =", sidebar.classList.contains("toggled"));

            setCookie("sidebar-collapsed", willCollapse);
        });

    });
</script> -->

