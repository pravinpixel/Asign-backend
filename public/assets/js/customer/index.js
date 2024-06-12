$(document).ready(function () {
    $('#paginations').removeClass('pagination');

    function myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage) {
        $('#filter_count').empty();
        let filter_count = 0;
        if (status.length > 0) {
            filter_count = filter_count + 1;
        }
        if (city.length > 0) {
            filter_count = filter_count + 1;
        }
        if (login.length > 0) {
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

        var api_url = url + '?page=' + page + '&per_page=' + per_page + '&sort=' + sort + '&type=' + type + '&city=' + city + '&status=' + status + '&login=' + login + '&search_key=' + search_key + '&search=' + search + '&field_sort=' + field_sort + '&percentage=' + percentage;
        $('#up').removeAttr('disabled', 'disabled');
        $('#down').removeAttr('disabled', 'disabled');
        $('#from').empty();
        $('#to').empty();
        // $('#total').empty();
        // $('#customer').empty();

        var selectedValue = $("#dropdown").val();
        var $customer = $('#customer');
        //$customer.html('');
        $.ajax({
            url: api_url,
            type: "GET",
            success: function (response) {
                $('#customer').empty();
                $('#total').empty();
                $('#from').empty();
                $('#to').empty();
                $('#from').append(response.data.current_page);
                $('#to').append(Math.ceil(response.data.total / per_page));
                $('#total').append('(' + response.data.total + ' customers)');
                if (response.data.total == response.data.to) {
                    $('#up').attr('disabled', 'disabled');
                }
                if (response.data.from == 1) {
                    $('#down').attr('disabled', 'disabled');
                }

                response.data.data.forEach(function (row) {
                    if (row.is_online == 1) {
                        var online_status = 'online';
                    } else {
                        var online_status = 'offline';
                    }

                    var listContent = '<ul>';
                    $.each(row.verify_value, function (index, value) {
                        listContent += "<li>" + value + "</li>";
                    });

                    listContent += '</ul>';

                    let table_html = '<tr class="unverified-hover" id=' + row.id + '>';
                    table_html += '<td data-label="Code" class="progress-wrap"><div data-toggle="tooltip" data-placement="left" title="Profile Completetion: ' + row.profile_completion + '%">' + row.aa_no + '</div><div class="progress" style="height: 3px; width: 47px"><div class="progress-bar bg-custom" role="progressbar" style="width: ' + row.profile_completion + '%" aria-valuenow="' + row.profile_completion + '" aria-valuemin="0" aria-valuemax="100"></div></div></td>';
                    table_html += '<td data-label="Name" > <p class="overflow-tooltip"><span class="status ' + online_status + '"></span>' + row.full_name + '</p></td>';
                    table_html += '<td data-label="City"><p class="overflow-tooltip">' + row.city + '</p></td> ';
                    table_html += '<td data-label="Mobile"> <p class="overflow-tooltip">(+91) ' + row.mobile + '</p></td> ';
                    table_html += '<td data-label="Email"><p class="overflow-tooltip">' + row.email + '</p></td>';
                    table_html += '<td data-label="Verification"><button style="text-transform: capitalize;" class="button-all align ' + row.status.toLowerCase() + '" data-toggle="tooltip" data-bs-html="true"  data-placement="top" title="<ul>' + listContent + '</ul>" >' + row.status + '</button></td>';
                    table_html += '</tr>';
                    $customer.append(table_html);

                    $('[data-toggle="tooltip"]').tooltip({
                        placement: 'bottom'
                    });
                });
            },
            error: function (xhr) { },
            complete: function (response) {
                $('#pageLoader').fadeOut();
            }
        });


    }

    var per_page = 10;
    var page = 1;
    var sort = 'recent';
    var search = '';
    var search_key = 'full_name';
    var type = [];
    var city = [];
    var login = [];
    var status = [];
    var field_sort = [];
    var percentage = [];

    $('#up').on('click', function () {
        if (page != 0) {
            page += 1;
            myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
        }
    });
    $('#down').on('click', function () {
        if (page != 0) {
            page -= 1;
            myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
        }
    });

    $('#per_page').on('change', function () {
        per_page = this.value;
        page = 1;
        $(this).blur();
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('input[name=flexRadioDefault]').on("click", function () {
        sort = this.value;
        field_sort = [];
        $(".dropdown-menu").removeClass("show");
        $("#customer_table #code").addClass('filter-dropy');
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('#type').on("change", function () {
        type = getSelectedValues();
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('#city_data').on("change", function () {
        city = getCitySelectedValues();
        if (city.length == 0) {
            $("#citystatus .SumoSelect").removeClass('filter_color');
        } else {
            $("#citystatus .SumoSelect").addClass('filter_color');

        }
        page = 1;
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('#login_status').on("change", function () {
        login = getLoginSelectedValues();
        if (login.length == 0) {
            $("#loginstatus .SumoSelect").removeClass('filter_color');
        } else {
            $("#loginstatus .SumoSelect").addClass('filter_color');
        }
        page = 1;
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('#status').on("change", function () {
        status = getStatusSelectedValues();
        if (status.length === 0) {
            $("#verifystatus .SumoSelect").removeClass('filter_color');
        } else {
            $("#verifystatus .SumoSelect").addClass('filter_color');
        }
        page = 1;
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('#selcted_radio').append('Name');
    $("input[name=search]").attr("placeholder", "Search Name");
    $('input[name=flexRadioDefault1]').on("click", function () {
        $('#selcted_radio').empty();
        search_key = this.value;
        var name = '';
        if (this.value == 'all') {
            name = 'All';
            $("input[name=search]").attr("placeholder", "Search All");
        } else if (this.value == 'email') {
            name = 'Email Id';
            $("input[name=search]").attr("placeholder", "Search Email");
        } else if (this.value == 'mobile') {
            name = 'Mobile Number';
            $("input[name=search]").attr("placeholder", "Search Mobile Number");
        } else if (this.value == 'aa_no') {
            name = 'Code';
            $("input[name=search]").attr("placeholder", "Search Code");
        } else if (this.value == 'city') {
            name = 'City';
            $("input[name=search]").attr("placeholder", "Search City");
        } else if (this.value == 'full_name') {
            name = 'Name';
            $("input[name=search]").attr("placeholder", "Search Name");
        }
        $('#selcted_radio').append(name);
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('input[name=search]').keyup(function () {
        search = this.value;
        if (this.value == '') {
            $('.filter-with').removeClass('search_shadow');
        } else {
            $('.filter-with').addClass('search_shadow');
        }
        page = 1;
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('#customer_table thead tr th').click(function () {
        $(".content-start").removeClass('dropdown-toggle');
        $("#customer_table thead tr th").removeClass();
        let value = $(this).attr('value');
        var id = this.id;
        var name = $(this).attr('name');

        if (name == "mobile" || name == "email") return false;

        if (value == 'asc') {
            $(this).removeClass("has_sort asc");
            $(this).addClass("has_sort desc");
            $(this).attr("value", "desc");
        } else if (value == 'desc') {
            $(this).removeClass("has_sort desc");
            $(this).addClass("has_sort asc");
            $(this).attr("value", "asc");
        }
        if (id == 'code') {
            $(".content-start").addClass('dropdown-toggle');
            $("#customer_table #code").addClass('asc');

        }
        if (id != 'code') {
            field_sort = [name, value];
        }

        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    $('#clear-all').click(function () {
        $('#search').empty();
        $('#type').empty();
        $("#verifylogin p span").text('Select Login Status');
        $("#verifytype p span").text('Select Type');
        $("#verifycity p span").text('Select City');
        $("#verifystatus p span").text('Select Verification Status');
        $(".SumoSelect").removeClass('filter_color');
        $(".options li").removeClass('selected');
        $(".SumoSelect  p span").addClass('placeholder');
        $('#selcted_radio').empty();
        $("#customer_table thead tr th").removeClass();
        $('#selcted_radio').append('Name');
        $('#search').val('');
        $('input[name=flexRadioDefault1]').prop('checked', false);
        $('#name').prop('checked', true);
        $('.filter-with').removeClass('search_shadow');
        $("#filter_panel").removeClass('open');
        $("#filter_panel").addClass('close');
        $("input[name=search]").attr("placeholder", "Search Name");
        $("#verifystatus .SumoSelect").removeClass('filter_color');
        per_page = 10;
        page = 1;
        sort = 'recent';
        type = [];
        city = [];
        login = [];
        status = [];
        search = '';
        search_key = 'full_name';
        field_sort = [];
        percentage = [];
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });
    function getSelectedValues() {
        var selectedValues = [];
        $("#type option:selected").each(function () {
            selectedValues.push($(this).val());
        });
        return selectedValues;
    }

    function getCitySelectedValues() {
        var selectedCityValues = [];
        $("#city_data option:selected").each(function () {
            selectedCityValues.push($(this).val());
        });
        return selectedCityValues;
    }

    function getLoginSelectedValues() {
        var selectedLoginValues = [];
        $("#login_status option:selected").each(function () {
            selectedLoginValues.push($(this).val());
        });
        return selectedLoginValues;
    }

    function getStatusSelectedValues() {
        var selectedStatusValues = [];
        $("#status option:selected").each(function () {
            selectedStatusValues.push($(this).val());
        });
        return selectedStatusValues;
    }

    function getPercentageSelectedValues() {
        var selectedStatusValues = [];
        $("#completion_percentage option:selected").each(function () {
            selectedStatusValues.push($(this).val());
        });
        return selectedStatusValues;
    }

    $('#customer').on('click', 'tr', function () {
        window.location.href = view_url + this.id;
    });

    $('#completion_percentage').on("change", function () {
        percentage = getPercentageSelectedValues();
        if (percentage.length == 0) {
            $("#sortByPercentage .SumoSelect").removeClass('filter_color');
        } else {
            $("#sortByPercentage .SumoSelect").addClass('filter_color');
        }
        page = 1;
        myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);
    });

    myfunction(per_page, page, sort, type, city, login, status, search, search_key, field_sort, percentage);

});
