<?php
session_start();
/*
 * DONE: make sure user is authenticated via $_SESSION and has admin privileges
 */

include_once 'minishop.php';
include_once 'navbar.php';
if (!is_admin_user()) {
    echo "<h3>unauthorized</h3>\n";
    header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
    exit;
}

echo make_html_editor_links();
if (!$link = db_connect()) {
    die("failed to connect to db\n");
}

if (!isset($_GET['table'])) {
//    die("table parameter missing\n"); /* XXX */
    $_GET['table'] = 'products';
}

$TABLE_NAME = $_GET['table'];

if (!($query = mysqli_query($link, "DESCRIBE $TABLE_NAME;"))) {
    mysqli_close($link);
    die("describe $TABLE_NAME query failed\n");
}

$columns = [];
while ($row = mysqli_fetch_array($query, MYSQLI_NUM)) {
    $columns[$row[0]] = $row[1];
}

/*
 * translation of SQL data types to HTML input types
 */
$translation_table = [
    "text" => "text",
    "int(11)" => "number"
];

$input_name_decorator = [
    'id'            => 'readonly',
    'title'         => 'style="width: 200px;"',
    'description'   => 'style="width: 600px;"',
    'photo'         => 'style="width: 200px;"',
    'passwd'        => 'style="width: 900px;"',
    'login'         => 'style="width: 100px;"'
];

$input_type_decorator = [
//    'text' => 'style="width: 200px;"',
    'int(11)' => 'style="width: 100px;"'
];

$UPDATE_DB_SCRIPT = 'editor_update.php';
$INSERT_DB_SCRIPT = 'editor_insert.php';

$column_names_form = '<form autocomplete="off">';

$update_form = sprintf('<form action="%s" autocomplete="off" method="POST">', $UPDATE_DB_SCRIPT);
$update_form .= sprintf('<input type="hidden" name="table" value="%s">', $TABLE_NAME);

$insert_form = sprintf('<form action="%s" autocomplete="off" method="POST">', $INSERT_DB_SCRIPT);
$insert_form .= sprintf('<input type="hidden" name="table" value="%s">', $TABLE_NAME);
foreach ($columns as $input_name => $input_type) {

    $decorator = '';

    if (array_key_exists($input_name, $input_name_decorator)) {
        $decorator .= ' ' . $input_name_decorator[$input_name];
    }

    if (array_key_exists($input_type, $input_type_decorator)) {
        $decorator .= ' ' . $input_type_decorator[$input_type];
    }

    $update_form .= sprintf('<input type="%s" name="%s" value="PLACEHOLDER" %s>', $translation_table[$input_type], $input_name, $decorator);

    $insert_decorator = $decorator;

    if ($input_name === 'id') {
        if (substr($insert_decorator, -2) == ';"') {
            $insert_decorator = substr($decorator, 0, -1) . ' visibility:hidden;" disabled';
        }
    }
    $insert_form .= sprintf('<input type="%s" name="%s" %s>', $translation_table[$input_type], $input_name, $insert_decorator);

    /*
     * for column names remove border frame, make font bold and make disabled
     */
    if (substr($decorator, -2) == ';"') {
        $decorator = substr($decorator, 0, -1) . ' border:none; font-weight:bold;" disabled';
    }

    $column_names_form .= sprintf('<input type="text" name="column_%s" value="%s" %s>', $input_name, $input_name, $decorator);

}
$update_form .= '<input type="submit" name="submit" value="update"></form>';
$insert_form .= '<input type="submit" name="submit" value="insert" style="font-size:24;"></form>';
$column_names_form .= '</form>';

echo "$column_names_form\n";
echo "$insert_form\n";

$template = str_replace('PLACEHOLDER', '%s', $update_form);

if (!($query2 = mysqli_query($link, "SELECT * FROM $TABLE_NAME;"))) {
    mysqli_close($link);
    die("select * from $TABLE_NAME query failed\n");
}
while ($row = mysqli_fetch_array($query2, MYSQLI_ASSOC)) {
    $values = array_values($row);
    foreach ($values as &$value) {
        $value = htmlentities($value);
    }
    echo vsprintf($template, $values) . "\n";
}
mysqli_close($link);
?>
</body>
</html>
