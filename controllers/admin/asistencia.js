
document.getElementById('searchInput').addEventListener('keyup', function() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var table = document.getElementById('tableBody');
    var rows = table.getElementsByTagName('tr');

    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        var match = false;

        for (var j = 0; j < cells.length; j++) {
            if (cells[j]) {
                if (cells[j].innerHTML.toLowerCase().indexOf(input) > -1) {
                    match = true;
                    break;
                }
            }
        }

        if (match) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
});
