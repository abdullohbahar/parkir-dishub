$("#jenisRencana").on("change", function () {
    var jenisRencana = $(this).val();

    console.log("halo");

    $.ajax({
        url: "/pemohon/ajax/show-sub-jenis-rencana/" + jenisRencana,
        method: "GET",
        dataType: "JSON",
        success: function (response) {
            // Clear existing options
            $("#subJenisRencana").empty();

            $("#subJenisRencana").append(
                '<option value="">-- Pilih Sub Jenis Rencana Pembangunan --</option>'
            );
            // Append new options based on the response data
            $.each(response.data, function (index, option) {
                $("#subJenisRencana").append(
                    '<option value="' +
                        option.id +
                        '">' +
                        option.nama +
                        "</option>"
                );
            });

            $("#sectionSubJenisRencana").attr("hidden", false);
            $("#subJenisRencana").attr("required", true);
        },
    });
});

$("#subJenisRencana").on("change", function () {
    var subJenisRencana = $(this).val();

    $.ajax({
        url: "/pemohon/ajax/show-sub-sub-jenis-rencana/" + subJenisRencana,
        method: "GET",
        dataType: "JSON",
        success: function (response) {
            if (response.has_sub_sub) {
                // Clear existing options
                $("#subSubJenisRencana").empty();

                $("#subSubJenisRencana").append(
                    '<option value="">-- Pilih Sub Sub Jenis Rencana Pembangunan --</option>'
                );
                // Append new options based on the response data
                $.each(response.data, function (index, option) {
                    console.log(option);
                    $("#subSubJenisRencana").append(
                        '<option value="' +
                            option.id +
                            '">' +
                            option.nama +
                            "</option>"
                    );
                });

                $("#sectionSubSubJenisRencana").attr("hidden", false);
                $("#subSubJenisRencana").attr("required", true);

                $("#sectionUkuranMinimal").attr("hidden", true);
                $("#ukuranMinimal").attr("required", false);
            } else {
                // Clear existing options
                $("#ukuranMinimal").empty();

                $("#ukuranMinimal").append(
                    '<option value="">-- Pilih Ukuran Minimal --</option>'
                );

                // Append new options based on the response data
                $.each(response.data_ukuran_minimal, function (index, option) {
                    console.log(option);
                    $("#ukuranMinimal").append(
                        `<option value="${option.id}">${option.keterangan} - ${option.kategori}</option>`
                    );
                });

                $("#sectionSubSubJenisRencana").attr("hidden", true);
                $("#subSubJenisRencana").attr("required", false);

                $("#sectionUkuranMinimal").attr("hidden", false);
                $("#ukuranMinimal").attr("required", true);
            }
        },
    });
});

$("#subSubJenisRencana").on("change", function () {
    var idSubSub = $(this).val();

    $.ajax({
        url: "/pemohon/ajax/show-ukuran-minimal/subsub/" + idSubSub,
        method: "GET",
        dataType: "JSON",
        success: function (response) {
            console.log(response);
            // Clear existing options
            $("#ukuranMinimal").empty();

            $("#ukuranMinimal").append(
                '<option value="">-- Pilih Ukuran Minimal --</option>'
            );
            // Append new options based on the response data
            $.each(response.data, function (index, option) {
                console.log(option);
                $("#ukuranMinimal").append(
                    `<option value="${option.id}">${option.keterangan} - ${option.kategori}</option>`
                );
            });

            $("#sectionUkuranMinimal").attr("hidden", false);
            $("#ukuranMinimal").attr("required", true);
        },
    });
});

setInterval(() => {
    if ($("#sectionUkuranMinimal:hidden").length > 0) {
        // The element with ID "yourElementId" is hidden
        $("#next").attr("hidden", true);
    } else {
        // The element with ID "yourElementId" is not hidden
        $("#next").attr("hidden", false);
    }
}, 10);
