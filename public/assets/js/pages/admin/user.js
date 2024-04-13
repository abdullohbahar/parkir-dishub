$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

var currentUrl = "/admin/user";

// Class definition
var KTDatatablesServerSide = (function () {
    // Shared variables
    var dt;

    // Private functions
    var initDatatable = function () {
        dt = $("#kt_datatable_dom_positioning").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: currentUrl,
            },
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
            columns: [
                {
                    orderable: false,
                    data: null,
                    name: null,
                },
                {
                    orderable: true,
                    data: "nama",
                    name: "nama",
                },
                {
                    orderable: true,
                    data: "email",
                    name: "email",
                },
                {
                    orderable: true,
                    data: "no_telepon",
                    name: "no_telepon",
                },
                {
                    orderable: true,
                    data: "role",
                    name: "role",
                },
                // {
                //     orderable: true,
                //     data: "aksi",
                //     name: "aksi",
                // },
            ],
            columnDefs: [
                {
                    targets: 0,
                    data: null,
                    searchable: false,
                    orderable: false,
                    className: "text-left",
                    render: (data, type, row, meta) => {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
            ],
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on("draw", function () {
            KTMenu.createInstances();
        });
    };

    // Public methods
    return {
        init: function () {
            initDatatable();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});

//  Delete Data
$("body").on("click", "#delete", function () {
    var id = $(this).data("id");
    var nama = $(this).data("nama");

    Swal.fire({
        title: `Apakah Anda Ingin Menghapus ${nama}?`,
        text: "Data yang berhubungan akan ikut terhapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/admin/user/destroy/" + id,
                dataType: "json",
                type: "DELETE",
                success: function (response) {
                    if (response.status == 200) {
                        success(response.message);
                        setTimeout(function () {
                            window.location = "";
                        }, 1450);
                    } else {
                        failed(response.message);
                    }
                },
            });
        }
    });
});
