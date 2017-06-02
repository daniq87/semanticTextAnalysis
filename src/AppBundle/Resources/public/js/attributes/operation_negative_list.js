function getNegativeParams() {
    return '?attributeName=' + document.getElementById("filterNegativeAttribute").value + '&pag=1';
}

function clearNegativeFilter(method, action, params, div) {
    $('#filterNegativeAttribute').val('');
    ajaxRefreshDiv(method, action, params, div);
}

$(document).ready(function () {
    var $filterNegativeAttribute = $('#filterNegativeAttribute');
    // KNP Paginator Ajax
    $('#negative-container').on('click', ".navigation .pagination li a", function (e) {
        e.preventDefault();
        getCurrentPage($(this).html());
        var params = '?attributeName=' + $filterNegativeAttribute.val() + '&pag=' + $('#currentPage').val();

        ajaxRefreshDiv('GET', '/negative_attribute/index', params, 'negative-container');
    });
});
