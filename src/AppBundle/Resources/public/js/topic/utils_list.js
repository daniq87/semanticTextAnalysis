function getParams() {
    return '?topicName=' + document.getElementById("filterTopic").value + '&pag=1';
}

function clearFilter(method, action, params, div) {
    $('#filterTopic').val('');
    ajaxRefreshDiv(method, action, params, div);
}

$(document).ready(function () {
    var $filterTopic = $('#filterTopic');
    // KNP Paginator Ajax
    $('#topics-container').on('click', ".navigation .pagination li a", function (e) {
        e.preventDefault();
        getCurrentPage($(this).html());
        var params = '?topicName=' + $filterTopic.val() + '&pag=' + $('#currentPage').val();
        ajaxRefreshDiv('GET', 'index', params, 'topics-container');
    });
});

