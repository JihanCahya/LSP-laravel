get_data();

function delete_form() {
    $("[name='nama']").val("");
    $("[name='keterangan']").val("");
}

function delete_error() {
    $("#error-nama").hide();
    $("#error-keterangan").hide();
}

function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr("content");
}

function get_data() {
    delete_error();
    $.ajax({
        url: "kategori/get_data",
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#tabelKategori").DataTable({
                destroy: true,
                data: data,
                columns: [
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                    },
                    { data: "name" },
                    { data: "keterangan" },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return (
                                '<button class="btn btn-info" data-toggle="modal" data-target="#kategori" title="lihat" onclick="submit(' +
                                row.id +
                                ')"><i class="ion-edit"></i> Edit</button> ' +
                                '<button class="btn btn-warning waves-effect waves-light" title="hapus" onclick="delete_data(' +
                                row.id +
                                ')"><i class="ion-trash-b"></i> Hapus</button> '
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

function submit(x) {
    if (x == "tambah") {
        $("#btn-insert").show();
        $("#btn-update").hide();
        $("[name='title']").text("Kategori Surat >> Tambah");
    } else {
        $("#btn-insert").hide();
        $("#btn-update").show();
        $("[name='title']").text("Kategori Surat >> Edit");

        $.ajax({
            type: "POST",
            url: "kategori/get_data_id",
            data: {
                id: x,
                _token: getCsrfToken(),
            },
            dataType: "json",
            success: function (hasil) {
                $("[name='id']").val(hasil[0].id);
                $("[name='nama']").val(hasil[0].name);
                $("[name='keterangan']").val(hasil[0].keterangan);
            },
        });
    }
    delete_form();
    delete_error();
}

function insert_data() {
    var formData = new FormData();
    formData.append("nama", $("[name='nama']").val());
    formData.append("keterangan", $("[name='keterangan']").val());
    formData.append("_token", getCsrfToken());

    $.ajax({
        type: "POST",
        url: "kategori/insert_data",
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
                $("#kategori").modal("hide");
                alertify.success(response.success);
                get_data();
            } else if (response.error) {
                $("#kategori").modal("hide");
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
    formData.append("nama", $("[name='nama']").val());
    formData.append("keterangan", $("[name='keterangan']").val());
    formData.append("_token", getCsrfToken());

    $.ajax({
        type: "POST",
        url: "kategori/edit_data",
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
                $("#kategori").modal("hide");
                alertify.success(response.success);
                get_data();
            } else if (response.nothing) {
                $("#kategori").modal("hide");
                alertify.log(response.nothing);
                get_data();
            } else if (response.error) {
                $("#kategori").modal("hide");
                alertify.error(response.error);
                get_data();
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + error);
        },
    });
}
function delete_data(x) {
    alertify.confirm(
        "Apakah anda yakin ingin menghapus data ini?",
        function (ev) {
            $.ajax({
                type: "POST",
                data: {
                    id: x,
                    _token: getCsrfToken(),
                },
                dataType: "json",
                url: "kategori/delete_data",
                success: function (response) {
                    if (response.success) {
                        $("#kategori").modal("hide");
                        alertify.success(response.success);
                        get_data();
                    } else if (response.error) {
                        $("#kategori").modal("hide");
                        alertify.error(response.error);
                        get_data();
                    }
                },
            });
        }
    );
}
