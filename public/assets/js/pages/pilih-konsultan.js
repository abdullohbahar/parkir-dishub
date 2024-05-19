$("#kt_datatable_dom_positioning").DataTable({
    language: {
        lengthMenu: "Show _MENU_",
    },
    dom:
        "<'row'" +
        "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
        "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
        ">" +
        "<'table-responsive'tr>" +
        "<'row'" +
        "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
        "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
        ">",
});

$("body").on("click", "#pilih", function () {
    var id = $(this).data("id");
    var email = $(this).data("email");
    var notelepon = $(this).data("notelepon");
    var nama = $(this).data("nama");

    $("#konsultan_id").val(id);
    $("#nama_konsultan").val(nama);
    $("#no_telepon_konsultan").val(notelepon);
    $("#email_konsultan").val(email);

    $("#modalPilihKonsultan").modal("hide");
});
