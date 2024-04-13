$(".view-password").on("click", function () {
    let input = $(this).parent().find("#password");
    input.attr("type", input.attr("type") === "password" ? "text" : "password");
    $("#icon").attr(
        "class",
        input.attr("type") === "password" ? "fas fa-eye" : "fas fa-eye-slash"
    );
});

$(".view-password-confirmation").on("click", function () {
    let input = $(this).parent().find("#password_confirmation");
    input.attr("type", input.attr("type") === "password" ? "text" : "password");
    $("#icon-password-confirmation").attr(
        "class",
        input.attr("type") === "password" ? "fas fa-eye" : "fas fa-eye-slash"
    );
});
