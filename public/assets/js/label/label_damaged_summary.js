var DamageSummary = function (config) {
    var $html = $("html");
    var dm = this;

    dm.config = config;
    dm.page = $("#main_content");
    dm.createForm = dm.page.find("#create_form");
    dm.dynamicSummary = dm.page.find("#dynamic_summary");
    dm.damageTable = dm.page.find("#summary_damage_table");
    dm.totalLabels = dm.page.find("#total_labels");
    dm.confirmModal = dm.page.find("#confirm-modal");
    dm.confirmBtn = dm.page.find("#confirm");
    dm.productID = dm.createForm.find("#product_id");
    dm.paginateWrapper = $html.find("#paginations");
    dm.page = 1;
    dm.perPage = 10;

    // Functions
    dm.enbaleButton = function () {
        let tds = $html.find("#summary_damage_table").find("td.serial_no");
        let total = 0;
        $(tds).each(function (fine, el) {
            total++;
        });
        if (total > 0) {
            dm.confirmBtn.attr("disabled", false);
        }
        else {
            dm.confirmBtn.attr("disabled", true);
        }
    };
    dm.scriptInit = function () {
        $(".select2Box").each(function () {
            var placeholder = $(this).attr('data-placeholder');
            var search = $(this).attr('data-search');
            if(search==="yes"){
                $(this).select2({
                    placeholder: placeholder,                    
                });
            }
            else{
                $(this).select2({
                    placeholder: placeholder,
                    minimumResultsForSearch: Infinity,
                });
            }        
        });

        $.proxy(dm.enbaleButton());
    };
    dm.fetchSummary = function () {
        var http = $.ajax({
            url: dm.config.links.fetchSummary + '/' + dm.config.productID,
            type: "GET",
            processData: false,
            contentType: false
        });
        http.done(function (data) {
            dm.dynamicSummary.html(data?.table);
            let tds = dm.dynamicSummary.find("td.serial_no");
            dm.totalLabels.html(tds.length + 1);

            dm.paginateWrapper.html(data?.pagination);
            $.proxy(dm.scriptInit());
        });
        http.fail(function () {

        });
    };
    dm.paginateHandler = function (el) {
        var move = $(el).attr("data-move");
        if (move === 'prev') {
            if (dm.page > 1)
                dm.page--;
        } else {
            dm.page++;
        }

        var http = $.ajax({
            url: dm.config.links.fetchSummary + '/' + dm.config.productID,
            type: "GET",
            data: {
                page: dm.page,
                per_page: dm.perPage,
            }
        });
        http.done(function (data) {
            dm.tableWrapper.html(data?.table);
            dm.paginateWrapper.html(data?.pagination);
        });
        http.fail(function () {

        });
    };
    dm.submitHandler = function (formData) {
        var http = $.ajax({
            url: dm.config.links.saveSummary,
            type: "POST",
            processData: false,
            contentType: false,
            data: formData
        });
        http.done(function (data) {
            $.proxy(dm.fetchSummary());

            if (data.status == "validation_error") {
                toastr.error(data?.message, 'Validation Error!');
            }
            else {
                dm.createForm.find("#envelope_id").val('').trigger('change');
                dm.createForm.find("#label_id").val("");
                dm.createForm.find("#damage_type").val('').trigger('change');
                $("#envelope_id option[value=" + formData.label_id + "]").prop('disabled', true);
            }
        });
        http.fail(function () {

        });
    };
    dm.isEmpty = function (value) {
        return value === null || value === undefined || value === '';
    };
    dm.isFilled = function (formID) {
        let allValues = $(formID).serializeArray();
        var isAnyValueEmpty = allValues.some(function (obj) {
            for (var key in obj) {
                if (obj.hasOwnProperty(key) && dm.isEmpty(obj[key])) {
                    return true;
                }
            }

            return false;
        });

        if (!isAnyValueEmpty) {
            $html.find("#create_form").trigger("submit");
        }
    };
    dm.removeHandler = function (el) {
        let id = $(el).attr("data-rowid");
        var http = $.ajax({
            url: dm.config.links.removeSummary + '/' + id,
            type: "GET",
            processData: false,
            contentType: false
        });
        http.done(function (data) {
            if (data.status == "deleted") {
                $.proxy(dm.fetchSummary());
                toastr.error(data?.message, 'Delete!');
            }
        });
        http.fail(function () {

        });
    };
    dm.removeAllHandler = function (el) {
        var http = $.ajax({
            url: dm.config.links.removeAllSummary,
            type: "GET",
            processData: false,
            contentType: false
        });
        http.done(function (data) {
            if (data.status == "deleted") {
                $.proxy(dm.fetchSummary());
                toastr.error(data?.message, 'Delete!');
            }
            else {
                $.proxy(dm.fetchSummary());
                toastr.warning(data?.message, 'Warning!');
            }
            dm.confirmModal.modal("hide");
        });
        http.fail(function () {

        });
    };

    // Events
    $html.on("change", "#create_form", function (e) {
        e.preventDefault();
        $.proxy(dm.isFilled("#create_form"));
    });
    $html.on("submit", "#create_form", function (e) {
        e.preventDefault();
        var fData = $(this).serializeArray();
        var frmData = new FormData;
        $.each(fData, function (ind, field) {
            frmData.append(field.name, field.value);
        });

        $.proxy(dm.submitHandler(frmData));
    });
    $html.on("click", ".removeLabel", function (e) {
        e.preventDefault();
        $.proxy(dm.removeHandler(e.currentTarget));
    });
    $html.on("click", "#confirm", function (e) {
        e.preventDefault();
        dm.confirmModal.modal("show");
    });
    $html.on("click", '#removeLabel', function (e) {
        e.preventDefault();
        $.proxy(dm.removeAllHandler());
    });
    $html.on("change", "#per_page", function (e) {
        e.preventDefault();
        $.proxy(dm.paginateHandler(e.currentTarget));
    });
    $html.on("click", ".paginate-btn", function (e) {
        e.preventDefault();
        $.proxy(dm.paginateHandler(e.currentTarget));
    });

    // Page Ready
    $.proxy(dm.scriptInit());
}
