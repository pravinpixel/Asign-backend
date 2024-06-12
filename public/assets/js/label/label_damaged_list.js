var DamagedList = function (config) {
    var $html = $("html");
    var dm = this;

    dm.config = config;
    dm.mainContent = $html.find("#main_content");
    dm.tableWrapper = $html.find("#table_content");
    dm.paginateWrapper = $html.find("#paginations");
    dm.page = 1;
    dm.perPage = 10;

    // Functions
    dm.searchHandler = function(search){
        var http = $.ajax({
            url: dm.config.links.searchPaginate,
            type: "GET",
            data:{
                search: search,
            }
        });
        http.done(function (data) {
            dm.tableWrapper.html(data?.table);
            dm.paginateWrapper.html(data?.pagination);
        });
        http.fail(function () {

        });
    };
    dm.paginateHandler = function(el){
        var move = $(el).attr("data-move");
        if (move === 'prev') {
            if (dm.page > 1)
                dm.page--;
        } else {
            dm.page++;
        }

        var http = $.ajax({
            url: dm.config.links.searchPaginate,
            type: "GET",
            data:{
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
    dm.sortHandler = function(el){
        let element = $(el);
        let direction = "desc"
        if(element.hasClass("desc")){
            element.removeClass("desc").addClass("asc");
            direction="asc";
        }
        else{
            element.removeClass("asc").addClass("desc");
            direction="desc";
        }
        
        var http = $.ajax({
            url: dm.config.links.searchPaginate,
            type: "GET",
            data:{
                search: $("#search_damage").val(),
                sort_field: element.attr("data-field"),
                direction: direction,
            }
        });
        http.done(function (data) {
            dm.tableWrapper.html(data?.table);
            dm.paginateWrapper.html(data?.pagination);
        });
        http.fail(function () {

        });
    };

    // Events
    $html.on("input", "#search_damage", function(e) {
        e.preventDefault();
        $.proxy(dm.searchHandler(e.target.value));
    });
    $html.on("change", "#per_page", function(e) {
        e.preventDefault();
        $.proxy(dm.paginateHandler(e.currentTarget));
    });
    $html.on("click", ".paginate-btn", function(e) {
        e.preventDefault();            
        $.proxy(dm.paginateHandler(e.currentTarget));
    });
    $html.on("click", "th.has_sort", function(e) {
        e.preventDefault();            
        $.proxy(dm.sortHandler(e.currentTarget));
    });
    // Page Ready
    //$.proxy(dm.scriptInit());
}
