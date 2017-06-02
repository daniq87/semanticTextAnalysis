
function deleteRow(id, tableId,url) {
    
    var row = document.getElementById(id);
    bootbox.confirm('Are you sure ?', function (res) {
        if (res === true)
        {
            $('#delete-progress').removeClass('hidden');

            $.post(url, function (result) {

                removeRowFromView(tableId, row);
                $('#message').removeClass('hidden'); // remove hidden class to show notification
                $('#delete-progress').addClass('hidden'); // hidden progress bar
                $('#review-message').text(result.notification);
                $('#noAjax-notification').empty();

                // recalculate total rows
                var total = $('#total').text();

                $('#total').text(total - 1);


            }).fail(function () {
                alert('DELETE ERROR');
            });
        }
    });
}

function removeRowFromView(tableId, r) {
    var table = document.getElementById(tableId);
    if (table) {
        table.deleteRow(r.rowIndex);
    }
}