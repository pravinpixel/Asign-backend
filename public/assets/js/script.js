$(document).ready(function () {
    var win = $(this);
    let $html = $("html");
    let $body = $("body");
    let $logo = $(".side-bar-logo");
    let $searchPannel = $("#filter_panel");
    let $searchPannelnew = $("#filter_panelnew");

    let AsignArt = (() => {
        let global = {
            tooltipOptions: {
                placement: "right"
            },
            menuClass: ".c-menu"
        }

        let subMenuCollapse = (el) => {
            let currentMenu = $(el);
            let allMenu = currentMenu.siblings(".has-submenu");
            let currentMenuIndicator = currentMenu.find("img.indicator");
            allMenu.removeClass("up").addClass("down");
            allMenu.next("div.article-submenu").removeClass("down").addClass("up");
            allMenu.find("img.indicator").attr("src", "https://uat-api.asign.art/admin/public/assets/icons/arrow-down.svg");
            currentMenu.toggleClass("up down");

            if (currentMenu.hasClass("up")) {
                currentMenuIndicator.attr("src", "https://uat-api.asign.art/admin/public/assets/icons/arrow-up.svg");
                currentMenu.next("div.article-submenu").removeClass("up").addClass("down");
            } else {
                currentMenuIndicator.attr("src", "https://uat-api.asign.art/admin/public/assets/icons/arrow-down.svg");
                currentMenu.next("div.article-submenu").removeClass("down").addClass("up");
            }
        }

        let sideBarCollapse = () => {
            $body.toggleClass("sidebar_reduced sidebar_expanded");
            if ($body.hasClass("sidebar_reduced")) {
                $logo.fadeOut(300, function () {
                });
                $logo.fadeIn(300);
            } else {
                $logo.fadeOut(300, function () {
                });
                $logo.fadeIn(300);
            }
        }

        let sideBarResize = () => {
            if (win.width() <= 768) {
                if ($body.hasClass("sidebar_expanded")) {
                    $body.removeClass("sidebar_expanded").addClass("sidebar_reduced");
                }
            }
        }

        let searchBarCollapse = () => {
            $searchPannel.toggleClass("close open");
        }

        let searchBarCollapseNew = () => {
            $searchPannelnew.toggleClass("close open");
        }

        let mainMenuRedirect = (el) => {
            let has_redirect = $(el).attr("data-redirect");
            if (has_redirect) {
                window.location.replace(has_redirect);
            }
        }

        let submenuRedirect = (el) => {
            window.location.replace($(el).attr("data-redirect"));
        }

        let openModal = (el) => {
            let ajaxRoute = $(el).attr("data-route");
            let recordId = $(el).attr("data-record");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: ajaxRoute,
                type: "POST",
                data: {
                    id: recordId
                },
                success: function (res) {
                    $('#dynamic_form').html(res);
                    $('#dynamic_modal').modal('show');
                }
            })
        }

        let openModalSubmit = () => {

        }

        return {
            init: () => {
// Sidebar Toggle
                $("#toggle_sidebar").on("click", sideBarCollapse);
// Searchbar Toggle
                $("#toggle_search").on("click", searchBarCollapse);
//searchbar2
                $("#toggle_searchnew").on("click", searchBarCollapseNew);
// Submenu Collapse
                $(".has-submenu").on("click", e => {
                    subMenuCollapse(e.currentTarget)
                });
// Main Menu redirect
                $(".has-redirect").on("click", e => {
                    mainMenuRedirect(e.currentTarget);
                });
// Sidebar reduced when open in mobile
                sideBarResize();
//..submenu sidebar
                $(".subnav li").on("click", e => {
                    e.preventDefault();
                    submenuRedirect(e.currentTarget);
                });
// Open Popup
                $html.on("click", ".open_popup_form", function (e) {
                    e.preventDefault();
                    openModal(e.currentTarget);
                });
            }
        }
    })();
    $('[data-toggle="tooltip"]').tooltip();
    AsignArt.init();
});

$(window).on('resize', function () {
    var win = $(this);
    if (win.width() <= 768) {
        if ($("body").hasClass("sidebar_expanded")) {
            $("body").removeClass("sidebar_expanded").addClass("sidebar_reduced");
        }
    }
});

function getModal(id = '') {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var formMethod = "addEdit";
    $.ajax({
        url: edit_model,
        type: 'POST',
        data: {
            id: id,

        },
        success: function (res) {
            $('#dynamic').modal('show');
            $('#dynamic').html(res);
        }
    })
}

function deleteModel(id = '') {
    $('#deleteForm input[name="id"]').val(id);
    $('#discardModal').modal('show');
}

$('body').on('click', '#dynamic-submit', function () {
    let form_data = new FormData($("#dynamic-form")[0]);
    $.ajax({
        url: check,
        type: "POST",
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.success) {

                $('.conform_header').empty();
                $('.conform_content').empty();
                $('.conform_header').append($('#title').val());
                if (($('#title').val()[0]) == 'E') {
                    $('.conform_content').append('Are you sure you want to save this change? It will reflect across all Masters.');
                } else {
                    let title_name = $('#title').val().replace('Add', '');
                    $('.conform_content').append('Are you sure you want to add this ' + title_name + ' to your Masters?');
                }
                $('#conform').modal('show');
                $('#dynamic').modal('hide');
            }
        },
        error: function (response) {
           $('.field-error').text(" ");
            $.each(response.responseJSON.errors, function (field_name, error) {
                $('#' + field_name + '-error').text(error[0]);
            });
        }
    });
});

var parentCheckbox1 = document.getElementById('flexCheckIndeterminate1');
document.addEventListener('DOMContentLoaded', function () {
    if (parentCheckbox1) {
        parentCheckbox1.addEventListener('change', function (e) {
            document.querySelectorAll('.city-new').forEach(function (checkbox) {
                checkbox.checked = e.target.checked;
            });
        });
    }
});

document.querySelectorAll('tbody .city-new').forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        var tbodyCheckbox = document.querySelectorAll('tbody .city-new').length;
        var tbodyCheckedbox = document.querySelectorAll('tbody .city-new:checked').length;
        if (tbodyCheckbox === tbodyCheckedbox) {
//console.log('All selected')
            parentCheckbox1.indeterminate = false;
            parentCheckbox1.checked = true;
        }
        if (tbodyCheckbox > tbodyCheckedbox && tbodyCheckedbox >= 1) {
// console.log('Some selected')
            parentCheckbox1.indeterminate = true;
        }
        if (tbodyCheckedbox === 0) {
// console.log('No any selected')
            parentCheckbox1.indeterminate = false;
            parentCheckbox1.checked = false;
        }

    })
});
$('.city-new').on('click', function(){
var total=$(".city-new").length;
var checkedCount = $("input[type='checkbox'].city-new:checked").length;
if(total != checkedCount){
$(".all-check").prop('checked',false);
}
if(total == checkedCount){
$(".all-check").prop('checked',true);
}
});

var parentCheckbox = document.getElementById('flexCheckIndeterminate');
document.addEventListener('DOMContentLoaded', function () {
    if (parentCheckbox) {
        parentCheckbox.addEventListener('change', function (e) {
            document.querySelectorAll('.form-check-input').forEach(function (checkbox) {
                checkbox.checked = e.target.checked;
            });
        });
    }
});
document.querySelectorAll('tbody .form-check-input').forEach(checkbox => {

    var trParent = checkbox.parentElement.parentElement.parentElement;
    if(checkbox.checked){
        trParent.classList.add("selected-row");
    }
    else{
        trParent.classList.remove("selected-row");
    }

    checkbox.addEventListener('change', (element) => {
        var tbodyCheckbox = document.querySelectorAll('tbody .form-check-input').length;
        var tbodyCheckedbox = document.querySelectorAll('tbody .form-check-input:checked').length;
        if (tbodyCheckbox === tbodyCheckedbox) {
            parentCheckbox.indeterminate = false;
            parentCheckbox.checked = true;
        }
        if (tbodyCheckbox > tbodyCheckedbox && tbodyCheckedbox >= 1) {
            parentCheckbox.indeterminate = true;
        }
        if (tbodyCheckedbox === 0) {
            parentCheckbox.indeterminate = false;
            parentCheckbox.checked = false;
        }

        var currentParent = checkbox.parentElement.parentElement.parentElement;
        if(element.target.checked){
            currentParent.classList.add("selected-row");
        }
        else{
            currentParent.classList.remove("selected-row");
        }
    })
});
function changeDateFormat(dateStr) {

    if (dateStr == null || dateStr === '')
        return null;

    dateStr = dateStr.replace(/\+/g, ' ');
    let date = new Date(dateStr);
    if (isNaN(date.getTime())) {
        return null;
    }
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();

    month = month < 10 ? '0' + month : month;
    day = day < 10 ? '0' + day : day;

    return `${year}-${month}-${day}`;

}

function changeTimeFormat(timeStr) {
    if (timeStr == null || timeStr === '')
        return null;
    let date = new Date("1970-01-01T" + timeStr + "Z");
    let hours = date.getUTCHours();
    let minutes = date.getUTCMinutes();
    let suffix = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;

    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return `${hours}:${minutes} ${suffix}`;

}

$(".toggle-password").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

function showErrorMessage(xhr) {
    if (xhr.status === 422) {
        var errors = xhr.responseJSON.errors;
        var errorHtml = '';
        $.each(errors, function (key, value) {
            errorHtml += value[0] + '<br>';
        });
        toastr.error(errorHtml);
    } else {
        if(xhr.responseJSON.error){
            toastr.error(xhr.responseJSON.error);
        } else {
            toastr.error('Something went wrong');
        }
    }

}

$('.read-more-content').addClass('d-none')
$('.read-more-show, .read-more-hide').removeClass('d-none')
// Set up the toggle effect:
$('.read-more-show').on('click', function (e) {
    $(this).next('.read-more-content').removeClass('d-none');
    $(this).addClass('d-none');
    e.preventDefault();
});
// Changes contributed by @diego-rzg
$('.read-more-hide').on('click', function (e) {
    var p = $(this).parent('.read-more-content');
    p.addClass('d-none');
    p.prev('.read-more-show').removeClass('d-none'); // Hide only the preceding "Read More"
    e.preventDefault();
});

function disabledButton(form) {

    let $form = $('#' + form);
    let disabledBtn = true;
    $form.find('button[type="submit"]').prop('disabled', disabledBtn);

    if(form === "product_stock_form") {
        $form.find('.ok-btn').prop('disabled', disabledBtn);
    }

    $form.on('change keyup paste', function (e) {
        let allValues = $(this).serializeArray();
        disabledBtn = false;
        for (let i = 0; i < allValues.length; i++) {
            let nameAttr = allValues[i].name;
            if ($form.find('[name="' + nameAttr + '"]').prop('required')) {
                if (allValues[i].value === '') {
                    disabledBtn = true;
                    break;
                }
            }
        }
        $form.find('button[type="submit"]').prop('disabled', disabledBtn);

        if(form === "product_stock_form") {
            $('#checkAvailable').show();
            $('#okAvailable').hide();
            $form.find('.ok-btn').prop('disabled', disabledBtn);
        }

    });

}

function validateNumber(e) {
    const pattern = /^[0-9]$/;
    return pattern.test(e.key )
}

function checkOverflow() {
    var elements = document.querySelectorAll('.overflow-tooltip');
    elements.forEach(function (element) {
// Check if the text is overflowing
        if (element.offsetWidth < element.scrollWidth) {
// Initialize tooltip for the element
            new bootstrap.Tooltip(element, {
                title: element.innerText,
                placement: 'top', // Optional: Set tooltip placement
                trigger: 'hover' // Show tooltip on hover
            });
        } else {
// If text is not overflowing, remove the tooltip
            var tooltipInstance = bootstrap.Tooltip.getInstance(element);
            if (tooltipInstance) {
                tooltipInstance.dispose();
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", checkOverflow);
window.addEventListener("resize", checkOverflow);
