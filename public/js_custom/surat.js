get_data();

$("#kategori").select2({
    width: "100%",
});

function previewFilename(fileId) {
    const input = document.getElementById(fileId);
    const fileName = input.files[0].name;

    const label = document.querySelector(`label[for=${fileId}]`);
    label.textContent = fileName;
}

function delete_error() {
    $("#error-nomor").hide();
    $("#error-kategori").hide();
    $("#error-keterangan").hide();
    $("#error-file").hide();
    $("#error-file2").hide();
}

function delete_form() {
    $("[name='nomor']").val("");
    $("#kategori").val("").trigger("change");
    $("[name='keterangan']").val("");
    resetFileInputLabel("file2");
    resetFileInputLabel("file");
}

function resetFileInputLabel(inputId) {
    var inputFile = document.getElementById(inputId);
    var label = inputFile.nextElementSibling;
    label.innerHTML = "Pilih file";
}

function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr("content");
}

function get_data() {
    delete_error();
    $.ajax({
        url: "surat/get_data",
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#tabelSurat").DataTable({
                destroy: true,
                data: data,
                columns: [
                    { data: "nomor" },
                    { data: "name" },
                    { data: "judul" },
                    { data: "created_at" },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return (
                                '<button class="btn btn-warning waves-effect waves-light" title="hapus" onclick="delete_data(' +
                                row.id +
                                ", '" +
                                row.file_name +
                                '\')"><i class="ion-trash-b"></i></button> ' +
                                '<button class="btn btn-success" title="unduh" onclick="downloadFile(\'' +
                                row.file_name +
                                '\')"><i class="ion-ios7-cloud-download"></i></button> ' +
                                '<button class="btn btn-info" data-toggle="modal" data-target="#lihatSurat" title="lihat" onclick="submit(' +
                                row.id +
                                ')"><i class="ion-eye"></i></button> '
                            );
                        },
                    },
                ],
                initComplete: function () {
                    $("th").css("text-align", "center");
                    $("td").css("text-align", "center");
                },
            });
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.statusText);
        },
    });
}

function downloadFile(file_name) {
    url = "surat/download/" + file_name;
    window.location.href = url;
}

function submit(x) {
    $.ajax({
        type: "POST",
        url: "surat/get_data_id",
        data: {
            id: x,
            _token: getCsrfToken(),
        },
        dataType: "json",
        success: function (hasil) {
            $("[name='id']").val(hasil[0].id);
            $("[name='nomor']").val(hasil[0].nomor);
            $("[name='kategori']").val(hasil[0].name);
            $("[name='keterangan']").val(hasil[0].judul);
            $("[name='waktu']").val(hasil[0].created_at);
            var url = hasil[0].file_name;
            $("embed").attr("src", "files/" + url);
        },
    });
    delete_form();
    delete_error();
}

function insert_data() {
    var formData = new FormData();
    formData.append("nomor", $("[name='nomor']").val());
    formData.append("kategori", $("#kategori").val());
    formData.append("keterangan", $("[name='keterangan']").val());
    var fileInput = $("[name='file']")[0];
    if (fileInput.files.length > 0) {
        formData.append("file", fileInput.files[0]);
    }
    formData.append("_token", getCsrfToken());

    $.ajax({
        type: "POST",
        url: "surat/insert_data",
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
                $("#modalSurat").modal("hide");
                alertify.success(response.success);
                get_data();
            } else if (response.error) {
                $("#modalSurat").modal("hide");
                alertify.error(response.error);
                get_data();
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + error);
        },
    });
}

function edit_data() {
    var formData = new FormData();
    formData.append("id", $("[name='id']").val());
    var fileInput = $("[name='file2']")[0];
    if (fileInput.files.length > 0) {
        formData.append("file2", fileInput.files[0]);
    }
    formData.append("_token", getCsrfToken());

    $.ajax({
        type: "POST",
        url: "surat/edit_data",
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
                $("#lihatSurat").modal("hide");
                alertify.success(response.success);
                get_data();
            } else if (response.nothing) {
                $("#lihatSurat").modal("hide");
                alertify.log(response.nothing);
                get_data();
            } else if (response.error) {
                $("#lihatSurat").modal("hide");
                alertify.error(response.error);
                get_data();
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + error);
        },
    });
}

function delete_data(x, y) {
    alertify.confirm(
        "Apakah anda yakin ingin menghapus data ini?",
        function (ev) {
            $.ajax({
                type: "POST",
                data: {
                    id: x,
                    file: y,
                    _token: getCsrfToken(),
                },
                dataType: "json",
                url: "surat/delete_data",
                success: function (response) {
                    if (response.success) {
                        $("#modalSurat").modal("hide");
                        alertify.success(response.success);
                        get_data();
                    } else if (response.error) {
                        $("#modalSurat").modal("hide");
                        alertify.error(response.error);
                        get_data();
                    }
                },
            });
        }
    );
}
