document.addEventListener("DOMContentLoaded", function () {
    // Menangkap formulir saat di-submit
    var form = document.getElementById("myForm"); // Ganti 'formSidang' dengan ID formulir Anda

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Mencegah formulir untuk langsung di-submit

        // Menampilkan konfirmasi SweetAlert
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: 'Klik "Ya" untuk konfirmasi.',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengklik "Ya", formulir akan di-submit
                form.submit();
            }
        });
    });
});
