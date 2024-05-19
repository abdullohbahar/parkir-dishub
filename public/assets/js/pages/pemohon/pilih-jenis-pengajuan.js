$("#jenis_pengajuan").on("change", function () {
    var jenisRencanaID = $(this).val();

    fetch(
        "/pemohon/permohonan/pilih-jenis-pengajuan/get-tipe-pengajuan/" +
            jenisRencanaID
    )
        .then((response) => {
            if (!response.ok) {
                if (response.status === 404) {
                    $("#tipe_pengajuan").attr("required", false);
                    $("#tipe_section").attr("hidden", true);
                    $("#tipe_pengajuan").empty();
                    $("#button_section").attr("hidden", true);

                    throw new Error("Data tidak ditemukan.");
                }

                throw new Error("Gagal mengambil data.");
            }
            return response.json();
        })
        .then((data) => {
            // Handle data jika berhasil diambil
            console.log(data);

            // Clear existing options
            $("#tipe_pengajuan").empty();

            $("#tipe_pengajuan").append(
                '<option value="">-- Pilih Tipe Pengajuan --</option>'
            );
            // Append new options based on the response data
            $.each(data.data, function (index, option) {
                $("#tipe_pengajuan").append(
                    '<option value="' +
                        option.id +
                        '">' +
                        option.tipe +
                        "</option>"
                );
            });

            $("#tipe_section").attr("hidden", false);
            $("#tipe_pengajuan").attr("required", true);

            $("#button_section").attr("hidden", false);
        })
        .catch((error) => {
            // Handle jika terjadi error
            console.error("Error:", error);
        });
});
