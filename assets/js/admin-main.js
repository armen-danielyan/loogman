jQuery(document).ready(function($){
    var custom_uploader;
    $("#upload-csv").click(function(e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: "Choose File",
            button: {
                text: "Choose File"
            },
            multiple: false
        });
        custom_uploader.on("select", function() {
            var attachment = custom_uploader.state().get("selection").first().toJSON();
            $("#url-csv").val(attachment.url);
        });
        custom_uploader.open();
    });

    $("#attach-document").click(function(e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: "Choose File",
            button: {
                text: "Choose File"
            },
            multiple: false
        });
        custom_uploader.on("select", function() {
            var attachment = custom_uploader.state().get("selection").first().toJSON();
            $("#_document").val(attachment.url);
        });
        custom_uploader.open();
    });

    var csvData = "";

    $("#select-csv").on("change", function() {
        $("#select-csv").parse({
            config: {
                complete: function(results, file) {
                    csvData = results.data;
                    var csvDom = "";
                    for(var i = 0; i < csvData.length; i++) {
                        csvDom += "<tr>";
                        csvDom += "<td>" + csvData[i][0] + "</td>";
                        csvDom += "<td>" + csvData[i][1] + "</td>";
                        csvDom += "<td>" + csvData[i][2] + " x " + csvData[i][4] + "</td>";
                        csvDom += "<td>" + csvData[i][5] + "</td>";
                        csvDom += "<td>" + csvData[i][6] + "</td>";
                        csvDom += "<td>" + csvData[i][7] + "</td>";
                        csvDom += "<td>" + csvData[i][8] + "</td>";
                        csvDom += "</tr>";
                    }
                    $("#csv-table").append(csvDom);
                }
            },
            error: function(err, file, inputElem, reason) {
                console.log(err);
                csvData = "";
            }
        });
    });

    var importBtnExtra = $("#import-progress"),
        importBtn = $("#import-csv");

    importBtn.on("click", function() {
        if(csvData) {
            importBtn.prop("disabled", true);
            importBtnExtra.html();
            addProduct(0);
        }
    });

    function addProduct(p) {
        var page = p,
            offset = 10;

        var startItem = page * offset,
            endItem = startItem + offset;

        var pages = Math.ceil(csvData.length / offset),
            progress = Math.floor((page * 100) / pages);

        importBtnExtra.html(progress + "%");

        if(page * offset < csvData.length) {
            $.ajax({
                url: wp_vars.ajax_url,
                data: {
                    action: "add-product",
                    data: csvData.slice(startItem, endItem)
                },
                type: "POST",
                success: function (res) {
                    addProduct(++page);
                },
                error: function(err){
                    console.log(err);
                }
            });
        } else {
            console.log("Products Imported");
            importBtnExtra.empty();
            importBtn.prop("disabled", false);
        }
    }
});