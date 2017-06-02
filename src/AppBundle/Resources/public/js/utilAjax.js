
function ajaxRefreshDiv(method, url, params, refreshDiv) {

    $("#" + refreshDiv).html("Loading...");
    $('#loading-progress-bar').removeClass('hidden');

    $.ajax({
        type: method,
        url: url + params,
        success: function (data) {
            $("#" + refreshDiv).html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Ajax: ' + textStatus + ' errorThrown: ' + errorThrown);
        },
        complete: function () {
            $('#loading-progress-bar').addClass('hidden');
            $('#message').addClass('hidden');
            $('#noAjax-notification').empty();
        }
    });
}

function activeTab(tab, route) {
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    ajaxRefreshDiv("GET", route, "", tab);
}

function getCurrentPage(onClickValue) {
    if ($.isNumeric(onClickValue)) {
        $('#currentPage').val(onClickValue);
    } else {
        if (onClickValue.indexOf('next') !== -1) {
            $('#currentPage').val(parseInt($('#currentPage').val()) + 1);
        } else {
            $('#currentPage').val(parseInt($('#currentPage').val()) - 1);
        }
    }
}

