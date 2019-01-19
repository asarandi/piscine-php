var debug = true;
$.get('select.php', function(data) {
    if (debug)
        console.log('select.php response: ' + data);    
    var arr = JSON.parse(data);
        for (i in arr) {
            insert_todo(arr[i]);
        }
});

function insert_todo(msg) {
    $('#ft_list').prepend('<div class="todo">' + msg + '</div>');
}

function save_cookie() {
    var arr = {};
    var i = 0;
    $('#ft_list').children().each(function() {
        arr[i++] = this.innerText;
    });
    $.post('insert.php', arr, function (res) {
        if (debug)
            console.log('insert.php response: ' + res);
    });
}

$('#add').click(function() {
    var todo = prompt("new todo", "");
    if ((todo != null) && (todo.length > 0)) {
        insert_todo(todo);
        save_cookie();
    }               
});

$(document).on('click', '.todo', function() {
    var r = confirm('Delete item from list?');
    if (r == true) {
        this.remove();
        save_cookie();
    }
});
