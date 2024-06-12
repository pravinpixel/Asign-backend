$(document).ready(function() {

    $(".read-more").click(function() {
        $(".hidden-content").toggle();
        $(".read-more").toggle();
        $(".collapse-content").toggle();
    });
    $(".collapse-content").click(function() {
        $(".hidden-content").toggle();
        $(".read-more").toggle();
        $(".collapse-content").toggle();
    });

    function myfunction(per_page, page, search, search_key, location, type, status) {
        $('#filter_count').empty();
        let filter_count = 0;
        if (location.length > 0) {
            filter_count = filter_count + 1;
        }
        if (type.length > 0) {
            filter_count = filter_count + 1;
        }
        if (status.length > 0) {
            filter_count = filter_count + 1;
        }
        if (filter_count != 0) {
            $('#filter_count').append(filter_count);
            $('#filter_count').addClass('span-conn');
            $('#toggle_search').addClass('filter_color');
        }
        if (filter_count == '') {
            $('#filter_count').removeClass('span-conn');
            $('#toggle_search').removeClass('filter_color');
        }
        var api_url = url + '?page=' + page + '&per_page=' + per_page + '&type=' + type + '&location=' + location + '&status=' + status + '&search_key=' + search_key + '&search=' + search;
        $('#from').empty();
        $('#to').empty();
        $('#up').removeAttr('disabled', 'disabled');
        $('#down').removeAttr('disabled', 'disabled');
        var $collection = $('#collection');
        $collection.html('');
        $.ajax({
            url: api_url,
            type: "GET",
            success: function(response) {
                $('#collection').empty();
                $('#from').empty();
                $('#to').empty();
                $('#from').append(response.data.current_page);
                $('#to').append(Math.ceil(response.data.total / per_page));
                if (response.data.total == response.data.to) {
                    $('#up').attr('disabled', 'disabled');
                }
                if (response.data.from == 1) {
                    $('#down').attr('disabled', 'disabled');
                }
                response.data.data.forEach(function(row) {
                    if (row.location_details != null) {
                        if (row.location_details.city != null) {
                            var city = row.location_details.city;
                        } else {
                            var city = '';
                        }
                    } else {
                        var city = '';
                    }
                    $collection.append(
                        '<tr><td data-label="Code">' + row.title +
                        '</td> </td><td data-label="Name">' + row.artist_name +
                        '</td><td data-label="Type">' + row.type_name +
                        '</td><td data-label="City">' + row.creation_year_from +
                        '</td><td data-label="Code"> ' + city + '</td><td data-label="Name"><button class="button-all inspection">' +
                        row.status +
                        '</button></td><td data-label="Type" class="collection-italic"> N/A </td><td data-label="City" class="collection-italic"> N/A</td></tr>'
                    );

                });
            },
            error: function(xhr) {},
            complete: function(response) {
                $('#pageLoader').fadeOut();
            }
        });

    }
    var per_page = 10;
    var page = 1;
    var search = '';
    var search_key = 'title';
    var type = [];
    var location = [];
    var status = [];
    $('#up').on('click', function() {
        if (page != 0) {
            page += 1;
            myfunction(per_page, page, search, search_key, location, type, status);
        }
    });
    $('#down').on('click', function() {
        if (page != 0) {
            page -= 1;
            myfunction(per_page, page, search, search_key, location, type, status);
        }
    });
    $('#per_page').on('change', function() {
        per_page = this.value;
        page = 1;
        $(this).blur();
        myfunction(per_page, page, search, search_key, location, type, status);
    });
    $('#art_type').on("change", function() {
        type = getSelectedTypeValues();
        if (type.length == 0) {
            $("#verifytitle .SumoSelect").removeClass('filter_color');
        } else {
            $("#verifytitle .SumoSelect").addClass('filter_color');
        }
         page = 1;
        myfunction(per_page, page, search, search_key, location, type, status);
    });
    $('#art_location').on("change", function() {
        location = getLocationSelectedValues();
        if (location.length == 0) {
            $("#verifylocation .SumoSelect").removeClass('filter_color');
        } else {
            $("#verifylocation .SumoSelect").addClass('filter_color');
        }
         page = 1;
        myfunction(per_page, page, search, search_key, location, type, status);
    });
    $('#art_status').on("change", function() {
        status = getStatusSelectedValues();
        if (status.length == 0) {
            $("#verifystatus .SumoSelect").removeClass('filter_color');
        } else {
            $("#verifystatus .SumoSelect").addClass('filter_color');
        }
         page = 1;
        myfunction(per_page, page, search, search_key, location, type, status);
    });
    $('#selcted_radio').append('Title');
    $("input[name=search]").attr("placeholder", "Search Title");
    $('input[name=flexRadioDefault1]').on("click", function() {
        $('#selcted_radio').empty();
        search_key = this.value;
        var name = '';
        if (this.value == 'all') {
            name = 'ALL';
            $("input[name=search]").attr("placeholder", "Search All");
        } else if (this.value == 'title') {
            name = 'Title';
            $("input[name=search]").attr("placeholder", "Search Title");
        } else if (this.value == 'type') {
            name = 'Type';
            $("input[name=search]").attr("placeholder", "Search Type");
        } else if (this.value == 'location') {
            name = 'Location';
            $("input[name=search]").attr("placeholder", "Search Location");
        }
        $('#selcted_radio').append(name);
        myfunction(per_page, page, search, search_key, location, type, status);
    });
    $('input[name=search]').keyup(function() {
        search = this.value;
        if (this.value == '') {
            $('.filter-with').removeClass('search_shadow');
        } else {
            $('.filter-with').addClass('search_shadow');
        }
        page = 1;
        myfunction(per_page, page, search, search_key, location, type, status);
    });

    function getSelectedTypeValues() {
        var selectedValues = [];
        $("#art_type option:selected").each(function() {
            selectedValues.push($(this).val());
        });
        return selectedValues;
    }

    function getLocationSelectedValues() {
        var selectedLocationValues = [];
        $("#art_location option:selected").each(function() {
            selectedLocationValues.push($(this).val());
        });
        return selectedLocationValues;
    }

    function getStatusSelectedValues() {
        var selectedStatusValues = [];
        $("#art_status option:selected").each(function() {
            selectedStatusValues.push($(this).val());
        });
        return selectedStatusValues;
    }

    myfunction(per_page, page, search, search_key, location, type, status);
    $('#myTab').on('click', function() {
        if ($('.tab-content .active').attr('id') == 'contact') {
            $('#paginations').removeClass('pagination');
        } else {
            $('#paginations').addClass('pagination');
        }

    });
    $(document).on('shown.bs.tab', function(e, ui) {
        if ($('.tab-content .active').attr('id') == 'home') {
            $('.show-profile-button1').addClass('d-none');
            $('.filter-data').removeClass('d-none');
        } else if ($('.tab-content .active').attr('id') == 'profile') {
            $('.show-profile-button1').removeClass('d-none');
            $('.filter-data').addClass('d-none');
        } else {
            $('.show-profile-button1').addClass('d-none');
            $('.filter-data').addClass('d-none');
        }
    });
    $('.read-more-content').addClass('d-none')
    $('.read-more-show, .read-more-hide').removeClass('d-none')
    $('.read-more-show').on('click', function(e) {
        $(this).next('.read-more-content').removeClass('d-none');
        $(this).addClass('d-none');
        e.preventDefault();
    });
    $('.read-more-hide').on('click', function(e) {
        var p = $(this).parent('.read-more-content');
        p.addClass('d-none');
        p.prev('.read-more-show').removeClass('d-none'); // Hide only the preceding "Read More"
        e.preventDefault();
    });
});