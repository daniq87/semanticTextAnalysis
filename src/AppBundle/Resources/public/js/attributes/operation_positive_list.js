function getPositiveParams() {
    return '?attributeName=' + document.getElementById("filterPositiveAttribute").value + '&pag=1';
}

function clearPositiveFilter(method, action, params, div) {
    $('#filterPositiveAttribute').val('');
    ajaxRefreshDiv(method, action, params, div);
}


$(document).ready(function () {
    var $filterPositiveAttribute = $('#filterPositiveAttribute');
    // KNP Paginator Ajax
    $('#positive-container').on('click', ".navigation .pagination li a", function (e) {
        e.preventDefault();
        getCurrentPage($(this).html());
        var params = '?attributeName=' + $filterPositiveAttribute.val() + '&pag=' + $('#currentPage').val();

        ajaxRefreshDiv('GET', 'index', params, 'positive-container');
    });
});


