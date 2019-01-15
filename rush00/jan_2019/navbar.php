<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {margin: 0;}

ul.topnav {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

ul.topnav li {float: left;}

ul.topnav li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

ul.topnav li a:hover:not(.active) {background-color: #111;}

ul.topnav li a.active {background-color: #4CAF50;}

ul.topnav li.right {float: right;}

@media screen and (max-width: 600px) {
  ul.topnav li.right, 
  ul.topnav li {float: none;}
}
</style>
</head>
<body>

<ul class="topnav">
  <li><a href="index.php">categories</a></li>
  <li><a href="products.php">all products</a></li>
  <li><a href="basket.php?action=view">basket</a></li>
<?php
    // if user is admin
    echo '<li class="right"><a href="editor.php">admin</a></li>';
    // if not logged in then
    echo '<li class="right"><a href="login.php">log in</a></li>';
    // if logged in then
    echo '<li class="right"><a href="logout.php">log out</a></li>';
?>
</ul>
