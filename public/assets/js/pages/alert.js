function success(message) {
    Swal.fire({
        position: "center",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 1500,
    });

    setTimeout(function () {
        window.location = "";
    }, 1450);
}

function failed(message) {
    Swal.fire({
        position: "center",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 1500,
    });

    setTimeout(function () {
        window.location = "";
    }, 1450);
}
