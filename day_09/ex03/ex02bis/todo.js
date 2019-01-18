var cookie = decodeURI($.cookie('ft_list'));
if (cookie !== 'undefined') {
    var arr = JSON.parse(cookie);
    for (i in arr) {
        insert_todo(arr[i]);
    }
}

function insert_todo(msg) {
    $('#ft_list').prepend('<div class="todo">' + msg + '</div>');
}

function save_cookie() {
    var arr = [];
    $('#ft_list').children().each(function() {
        arr.unshift(this.innerText);
    });
    $.cookie('ft_list', JSON.stringify(arr), {expires: 7});
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
