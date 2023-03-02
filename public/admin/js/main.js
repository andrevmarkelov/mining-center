$(function () {
    // ajaxSetup
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name = "csrf-token"]').attr("content"),
        },
    });

    // js-select2
    const $select2 = $(".js-select2"),
        $select2bs4 = $(".js-select2bs4");

    if ($select2.length) {
        $select2.select2();
    }

    if ($select2bs4.length) {
        $select2bs4.select2({
            theme: "bootstrap4",
        });
    }

    // popover
    $('[data-toggle="popover"]').popover({
        trigger: "focus",
        html: true,
    });

    // nav-sidebar
    $(".nav-sidebar .nav-treeview a.active")
        .parents(".nav-treeview")
        .prev()
        .addClass("active")
        .parent()
        .addClass("menu-open");

    // data mask
    const $dataMask = $("[data-mask]");

    if ($dataMask.length) {
        $dataMask.inputmask();
    }

    // phone mask
    const $phoneMask = $('[type="tel"]');

    if ($phoneMask.length) {
        $phoneMask.inputmask("+74 999 999 9999");
    }

    // bootstrap-switch
    const $bootstrapSwitch = $("input[data-bootstrap-switch]");

    if ($bootstrapSwitch.length) {
        $bootstrapSwitch.each(function () {
            $(this).bootstrapSwitch("state", $(this).prop("checked"));
        });
    }

    // js-summernote
    const $summernote = $(".js-summernote");

    if ($summernote.length) {
        let userRole = $("body").data("role") === "user" ? 1 : 0,
            settingByRole = [
                ["color", ["color"]],
                ["insert", ["link"]],
                ["view", ["fullscreen", "codeview", "help"]],
            ];

        if (userRole) settingByRole = [["view", ["fullscreen"]]];

        $summernote.each(function () {
            if ($(this).data("attach-image") === true) {
                settingByRole[1] = ["insert", ["link", "picture"]];
            }

            $(this).summernote({
                callbacks: {
                    onPaste: function (e) {
                        e.preventDefault();
                        let bufferText = (
                            (e.originalEvent || e).clipboardData ||
                            window.clipboardData
                        ).getData("Text");
                        document.execCommand("insertText", false, bufferText);
                    },
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).summernote("code");
                        $(this).val(codeviewHtml);
                    },
                    onImageUpload: function (files) {
                        sendFile(files[0], $(this));
                    },
                    onMediaDelete: function (target) {
                        deleteFile(target[0].src);
                    },
                },
                height: $(this).data("height") ? $(this).data("height") : 300,
                lang: "ru-RU",
                useProtocol: false,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                ].concat(settingByRole),
            });
        });
    }

    let useProtocol = document.querySelector(
        ".note-editor .sn-checkbox-use-protocol"
    );

    if (useProtocol !== null) {
        document.querySelector(
            ".note-editor .sn-checkbox-use-protocol"
        ).hidden = true;
    }

    function sendFile(file, $editor) {
        let data = new FormData();
        data.append("image", file);

        $.ajax({
            url: "/cabinet/upload-editor/",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.success) {
                    $editor.summernote("insertImage", data.url);
                    toastr.success(data.success);
                }
                if (data.error) {
                    toastr.error(data.error);
                }
            },
        });
    }

    function deleteFile(src) {
        $.ajax({
            data: { src: src },
            method: "POST",
            url: "/cabinet/destroy-editor/",
            cache: false,
            success: function (data) {
                if (data.success) {
                    toastr.success(data.success);
                }
                if (data.error) {
                    toastr.error(data.error);
                }
            },
        });
    }

    // js-data-table
    const $dataTable = $(".js-data-table");

    if ($dataTable.length) {
        $dataTable.each(function () {
            if ($(this)[0].hasAttribute("data-without-search")) {
                dTSettings = Object.assign(dTSettings, {
                    lengthChange: false,
                    searching: false,
                });
            }

            $(this).DataTable(dTSettings);
        });
    }

    // START WIDGET js-image-picker

    // js-image-picker-action
    $(".js-image-picker-action").on("change", function () {
        let $thisElement = $(this).parents(".js-image-picker"),
            $img = $thisElement.find("img").fadeIn();

        $thisElement.find(".js-image-picker-remove").fadeIn();
        $thisElement.find('[type="hidden"]').val(0);

        loadImage(this.files.item(0), function (data) {
            $img.attr("src", data);
        });
    });

    // js-image-picker-remove
    $(".js-image-picker-remove").on("click", function () {
        let $thisElement = $(this).parents(".js-image-picker");

        $thisElement.find("img").fadeOut(function () {
            $(this).removeAttr("src");
        });

        $thisElement.find('[type="hidden"]').val(1);
        $thisElement.find('[type="file"]').val("");

        $(this).fadeOut();
    });
    // END WIDGET js-image-picker

    // START WIDGET js-gallery-picker-action
    let currentIndex = 0;
    let showError = false;

    // js-gallery-picker-action
    $(".js-gallery-picker-action").on("change", function () {
        let files = $(this).prop("files");

        for (let i = 0; i < files.length; i++) {
            loadImage(files.item(i), function (data) {
                if ($(".js-gallery-picker-item").length > 8) {
                    if (showError === false) {
                        toastr.error(
                            "Максимальное количество картинок в галереи 9 штук"
                        );
                    }

                    showError = true;
                    return;
                }

                let html = renderTemplate("gallery-picker", {
                    image: data,
                    index: currentIndex,
                });

                let list = new DataTransfer();
                let file = files.item(i);

                list.items.add(file);
                let fileList = list.files;

                $(".js-gallery-picker-empty").hide();
                $(".js-gallery-picker-list").prepend(html).children().show();
                $(
                    '.js-gallery-picker-item[data-index="' + currentIndex + '"]'
                ).find("input")[0].files = fileList;

                currentIndex++;
            });
        }
    });

    // js-gallery-picker-remove
    $(document).on("click", ".js-gallery-picker-remove", function () {
        $(this)
            .parents(".js-gallery-picker-item")
            .fadeOut(function () {
                $(this).remove();

                if ($(".js-gallery-picker-item").length === 0) {
                    $(".js-gallery-picker-empty").show();
                }
            });
    });

    // js-ajax-gallery-remove
    $(".js-ajax-gallery-remove").click(function () {
        let url = $(this).data("url"),
            $thisButton = $(this);

        if (!confirm("Подтвердите удаление")) {
            return false;
        }

        $.ajax({
            url: url,
            method: "DELETE",
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    toastr.success(data.success);

                    $thisButton
                        .parents(".js-gallery-picker-item")
                        .fadeOut(function () {
                            $(this).remove();

                            if ($(".js-gallery-picker-item").length === 0) {
                                $(".js-gallery-picker-empty").show();
                            }
                        });
                }
            },
            error: function (data) {
                toastr.error("Send error");
            },
        });
    });
    // END WIDGET js-gallery-picker-action
});

// js-ajax-form
$(document).on("submit", ".js-ajax-form", function (e) {
    e.preventDefault();

    let $thisForm = $(this),
        $formSubmit = $thisForm
            .find('[type="submit"]')
            .add($('[form="' + $thisForm.attr("id") + '"]'));

    $.ajax({
        url: $thisForm.attr("action"),
        method: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        beforeSend: function () {
            $formSubmit.prop("disabled", true);

            setTimeout(function () {
                $formSubmit.prop("disabled", false);
            }, 8000);
        },
        success: function (data) {
            if (data.errors) {
                $.each(data.errors, function (key, value) {
                    toastr.error(data.errors[key]);
                });
            }

            if (data.success) {
                toastr.success(data.success);
            }

            if (data.redirect) {
                setTimeout(function () {
                    window.location = data.redirect;
                }, 1000);
            }

            $formSubmit.prop("disabled", false);
        },
    });
});

// toastr
toastr.options = {
    positionClass: "toast-bottom-right",
    progressBar: true,
    hideDuration: "4000",
};

// js-data-table
let dTSettings = {
    order: [],
    columnDefs: [
        {
            targets: "no-sort",
            orderable: false,
        },
    ],
    responsive: true,
    autoWidth: false,
    language: {
        url: "/admin/libs/datatables/i18n/ru.json",
    },
};

// btnLoading
function btnLoading($button) {
    let loadingText =
        '<i class="fa fa-circle-o-notch fa-spin"></i> Загрузка...';

    if ($button.html() !== loadingText) {
        $button.data("original-text", $button.html());
        $button.html(loadingText);
    }

    setTimeout(function () {
        $button.html($button.data("original-text"));
    }, 2000);
}

// loadImage
function loadImage(file, callback) {
    let reader = new FileReader();

    reader.onload = function () {
        let dataURL = this.result;
        callback(dataURL);
    };

    reader.readAsDataURL(file);
}

// renderTemplate
function renderTemplate(name, data = null) {
    let template = document.getElementById(name).innerHTML;

    if (data !== null) {
        for (let property in data) {
            if (data.hasOwnProperty(property)) {
                let search = new RegExp("{" + property + "}", "g");
                template = template.replace(search, data[property]);
            }
        }
    }

    return template;
}

// goBackWithRefresh
function goBackWithRefresh() {
    if ("referrer" in document) {
        window.location = document.referrer;
    } else {
        window.history.back();
    }
}
