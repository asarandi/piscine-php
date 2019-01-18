var id = 0;
var cookie = document.cookie;

if (cookie.length > 8) {
    cookie = cookie.substring(8);
    var arr = JSON.parse(cookie);
    for (var ks in arr) {
        var ki = parseInt(ks);
        if (ki >= id) {
            id = ki + 1;
        }
        insert_todo(ki, arr[ks]);
    }
}

function insert_todo(id, msg) {
    var div = document.createElement('div');
    div.setAttribute('id', id.toString());
    div.setAttribute('onclick', 'remove(' + id.toString() + ')');
    var content = document.createTextNode(msg);
    div.appendChild(content);
    var todolist = document.getElementById('ft_list');
    todolist.insertBefore(div, todolist.childNodes[0]);
}

function save_cookie() {
    var arr = {};
    p = document.getElementById('ft_list');
    for (c in p.children) {
        cdiv = p.children[c];
        var id = parseInt(cdiv.id);
        var val = cdiv.innerText;
        arr[id] = val;
    }
    json = JSON.stringify(arr);
    var d = new Date();
    d.setTime(d.getTime() + (7*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = 'ft_list=' + json + ';' + expires + ';path=/;';
}

function add() {
    var todo = prompt("new todo", "");
    if ((todo != null) && (todo.length > 0)) {
        insert_todo(id++, todo);
        save_cookie();
    }               
}

function remove(id) {
    var r = confirm('Delete item from list?');
    if (r == true) {
        var todo = document.getElementById(id);
        todo.parentNode.removeChild(todo);
        save_cookie();
    }
}
