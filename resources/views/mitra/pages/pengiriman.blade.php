<?php
echo 'Halaman daftar semua pengiriman';

$routeUrl = route('produk'); // generate URL via Laravel di PHP dulu

echo '<script>
    function checkTokoStatus(status) {
        if (status !== "accepted") {
            const alertBox = document.getElementById("statusAlert");
            alertBox.style.display = "block";

            // Auto close after 4s
            setTimeout(() => {
                alertBox.style.display = "none";
            }, 4000);

            return false;
        } else {
            // Redirect ke halaman tambah produk
            window.location.href = "' . $routeUrl . '";
            return true;
        }
    }
</script>';
?>