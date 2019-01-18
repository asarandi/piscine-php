var cookie = decodeURIComponent(document.cookie);
if (cookie.substring(0,8) === 'ft_list=') {
    var arr = JSON.parse(cookie.substring(8));
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
    var d = new Date();
    d.setTime(d.getTime() + (7*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();    
    document.cookie = 'ft_list=' + encodeURIComponent(JSON.stringify(arr)) + ';' + expires + 'path=/;';
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
