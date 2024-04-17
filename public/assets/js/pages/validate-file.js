function validatePdf(input) {
    const allowedExtensions = /(\.pdf)$/i;

    if (!allowedExtensions.exec(input.value)) {
        alert("Hanya file PDF yang diperbolehkan.");
        input.value = "";
        return false;
    }
}

function validateFile(input) {
    const allowedExtensions = /(\.pdf|\.jpg|\.png)$/i;

    if (!allowedExtensions.exec(input.value)) {
        alert("Hanya file PDF, JPG, atau PNG yang diperbolehkan.");
        input.value = "";
        return false;
    }
}
