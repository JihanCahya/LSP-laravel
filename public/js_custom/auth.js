delete_error();
function delete_error() {
    $("#error-nama").hide();
    $("#error-email").hide();
    $("#error-password").hide();
    $("#error-password_confirmation").hide();
}

function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr("content");
}

function register() {
    var formData = new FormData();
    formData.append("nama", $("[name='nama']").val());
    formData.append("email", $("[name='email']").val());
    formData.append("password", $("[name='password']").val());
    formData.append(
        "password_confirmation",
        $("[name='password_confirmation']").val()
    );
    formData.append("_token", getCsrfToken());

    $.ajax({
        type: "POST",
        url: "auth/register",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (response) {
            delete_error();
            if (response.errors) {
                Object.keys(response.errors).forEach(function (fieldName) {
                    $("#error-" + fieldName).show();
                    $("#error-" + fieldName).html(
                        response.errors[fieldName][0]
                    );
                });
            } else if (response.success) {
                window.location.href = "/login";
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + error);
        },
    });
}

function login() {
    var formData = new FormData();
    formData.append("email", $("[name='email']").val());
    formData.append("password", $("[name='password']").val());
    formData.append("_token", getCsrfToken());

    $.ajax({
        type: "POST",
        url: "auth/login",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (response) {
            delete_error();
            if (response.errors) {
                Object.keys(response.errors).forEach(function (fieldName) {
                    $("#error-" + fieldName).show();
                    $("#error-" + fieldName).html(
                        response.errors[fieldName][0]
                    );
                });
            } else if (response.success) {
                window.location.href = "/";
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + error);
        },
    });
}
