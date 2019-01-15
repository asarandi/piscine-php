<?php
   include 'subroutines.php';
   $db = mysql_connect();


/*
+------------------+--------------+------+-----+---------+----------------+
| Field            | Type         | Null | Key | Default | Extra          |
+------------------+--------------+------+-----+---------+----------------+
| id               | int(11)      | NO   | PRI | NULL    | auto_increment |
| title            | varchar(255) | YES  | UNI | NULL    |                |
| author           | varchar(255) | YES  | UNI | NULL    |                |
| isbn13           | varchar(255) | YES  |     | NULL    |                |
| description      | text         | YES  |     | NULL    |                |
| publication_date | text         | YES  |     | NULL    |                |
| price            | int(11)      | YES  |     | NULL    |                |
| pictures         | text         | YES  |     | NULL    |                |
| categories       | text         | YES  |     | NULL    |                |
| active           | int(11)      | YES  |     | NULL    |                |
| deleted          | int(11)      | YES  |     | 0       |                |
+------------------+--------------+------+-----+---------+----------------+
11 rows in set (0.00 sec)
*/



   $id = 0;
   $title = '';
   $author = '';
   $isbn13 = '';
   $description = '';
   $publication_date = '';
   $price = 0;
   $pictures = '';
   $categories = '';
   $active = 0;
   $info_code = 0;
	$info_text = 'use the `select` button to display a record, '. "\n";
   	$info_text .= '`update` button to modify an existing record ' . "\n";
	$info_text .= 'or the `insert` button to add a new record;'. "\n";
	$info_text .= 'the `clear` button will reset the page, ' . "\n";
	$info_text .= 'and the `delete` button will erase a record;' . "\n";

	$clear = 0;
   
   if (isset($_POST['action'])){
   
   	if (isset($_POST['id']))
		$id = mysqli_real_escape_string($db, $_POST['id']);

   	if (isset($_POST['title']))
		$title = mysqli_real_escape_string($db, $_POST['title']);

   	if (isset($_POST['author']))
		$author = mysqli_real_escape_string($db, $_POST['author']);

   	if (isset($_POST['isbn13']))
		$isbn13 = mysqli_real_escape_string($db, $_POST['isbn13']);

   	if (isset($_POST['description']))
		$description = mysqli_real_escape_string($db, $_POST['description']);

   	if (isset($_POST['publication_date']))
		$publication_date = mysqli_real_escape_string($db, $_POST['publication_date']);

   	if (isset($_POST['price']))
		$price = mysqli_real_escape_string($db, $_POST['price']);

   	if (isset($_POST['pictures']))
		$pictures = mysqli_real_escape_string($db, $_POST['pictures']);

   	if (isset($_POST['categories']))
		$categories = mysqli_real_escape_string($db, $_POST['categories']);

   	if (isset($_POST['active']))
   		$active = 1;

	$id = intval($id);
	$selected_fields = " id, title, author, isbn13, description, publication_date, price, pictures, categories, active ";
	$table_name = ' products ';

	if ($_POST['action'] === 'clear') {
		$clear = 1;
	} else if ($_POST['action'] === 'select'){	///// select
		if (intval($id) > 0) {
				$id = intval($id);
   				$query = "SELECT $selected_fields FROM $table_name WHERE id='$id';";
   				if (($result = mysqli_query($db, $query)) !== NULL) {
					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

   					$id = $row['id'];
					$title = $row['title'];
					$author = $row['author'];
					$isbn13 = $row['isbn13'];
					$description = $row['description'];
					$publication_date = $row['publication_date'];
					$price = $row['price'];
					$pictures = $row['pictures'];
					$categories = $row['categories'];
					$active = $row['active'];

   					$info_code = 1;
					$info_text = 'ok, record selected';
					$clear = 0;
					mysqli_free_result($result);
					
   				} else {
   					$info_code = -1;
   					$info_text = 'sql [select] query failed';
   				};	
   		} else {
   			$info_code = -2;	//if -1 is error; -2 is warning
   			$info_text = 'choose existing record, then click select';
   		};
   	} else if ($_POST['action'] === 'update') {	//////  UPDATE
   		if (intval($id) > 0) {
			$query = "UPDATE products SET title='$title', author='$author', isbn13='$isbn13', description='$description', publication_date='$publication_date', price='$price', pictures='$pictures', categories='$categories', active=$active WHERE id=$id;";

//			echo "<pre>"; var_dump($query); echo "</pre>";	///DEBUG

   				if (mysqli_query($db, $query) === TRUE) {
   					$info_code = 1;	//database updated
   					$info_text = 'ok, record updated';
   				} else {
   					$info_code = -1;
   					$info_text = 'sql [update] query failed';
   				};
   		} else {
   			$info_code = -1;
   			$info_text = 'update only works for existing records, use insert instead';
   		};
	} else if ($_POST['action'] === 'insert') { //// INSERT
		$query = "INSERT INTO products (title, author, isbn13, description, publication_date, price, pictures, categories, active) VALUES ('$title', '$author', '$isbn13', '$description', '$publication_date', '$price', '$pictures', '$categories', '$active');";
		if (strlen($title) > 0) {
	   		if (mysqli_query($db, $query) === TRUE) {
   				$info_code = 1;
   				$info_text = 'ok, record inserted';
   			} else {
   				$info_code = -1;
   				$info_text = 'sql [insert] query failed, title must be unique';
   			};
		} else {
			$info_code = -2;
			$info_text = 'sql [insert]: title cannot be empty';
		}
	} else if ($_POST['action'] === 'delete') {	//// DELETE
		if ($id > 0) {
			//	   		$query = "UPDATE categories SET deleted=1 WHERE id='$id';";
			$query = "DELETE FROM products WHERE id=$id;";
	   		if (mysqli_query($db, $query) === TRUE) {
	   			$info_code = 1;
				$info_text = 'ok, record deleted';
				$id = 0;

			   $title = '';
			   $author = '';
			   $isbn13 = '';
			   $description = '';
			   $publication_date = '';
			   $price = '';
			   $pictures = '';
			   $categories = '';
			   $active = 0;

	   		} else {
	   			$info_code = -1;
	   			$info_text = 'sql [delete] query failed';
			}
		} else {
			$info_code = -2;
			$info_text = 'choose a record you want to delete';
		};
   	};
   };
	if ($clear === 1) { 
		$id = 0;

	   $title = '';
	   $author = '';
	   $isbn13 = '';
	   $description = '';
	   $publication_date = '';
	   $price = '';
	   $pictures = '';
	   $categories = '';
	   $active = 0;

		$info_code = 0;
		$info_text = 'all clear';
		$clear = 0;
	};

	$query = 'SELECT id, title FROM products WHERE deleted=0 ORDER BY title ASC;';
	$result = mysqli_query($db, $query);
	$menu = [];

	while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== NULL) {
		$menu[$row['id']] = $row['title'];
	}
	mysqli_free_result($result);
	mysqli_close($db);

   $title = stripslashes($title);
   $author = stripslashes($author);
   $isbn13 = stripslashes($isbn13);
   $description = stripslashes($description);
   $publication_date = stripslashes($publication_date);
   $price = stripslashes($price);
   $pictures = stripslashes($pictures);
   $categories = stripslashes($categories);

?>
<!DOCTYPE html>
<html>
   <head>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <title>titles</title>
       <style>
           ul {
               list-style-type: none;
               margin: 0;
               padding: 0;
               overflow: hidden;
           }

           li {
               float: left;
           }

           li a {
               display: block;
               color: black;
               text-align: center;
               padding: 14px 16px;
           }
       </style>
   </head>
   <body>
   <div class="container main">
       <ul>
           <li><a href="admin_categories.php" style="height: 64px"><h1>categories</h1></a></li>
           <li><a href="admin_products.php" style="height: 64px"><i><h1>products</h1></i></a></li>
           <li><a href="admin_users.php" style="height: 64px"><h1>users</h1></a></li>
           <li><a href="index.php" style="height: 64px"><h1>back to store</h1></a></li>
       </ul>
   </div>
<!--?php echo "<pre>"; var_dump($_POST); echo "</pre>";?-->

      <div class="container">
         <h1>titles</h1>
         <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" id="product">
            <div class="form-group">
               <label for="select1">choose existing</label>
               <select class="form-control" id="product_selected" name="id" form="product">
				  <option disabled selected value="0"> -- [select product] -- </option>
				<?php
					foreach ($menu as $key => $value) {
					echo '<option value="'.$key.'">'.stripslashes($value).'</option>' . "\n";
					}
				?>
               </select>
               <br />
               <input type="submit" class="btn btn-primary" name="action" value="select" form="product">
			   <input type="submit" class="btn btn-danger" name="action" value="delete" form="product">
			   <input type="submit" class="btn btn-light" name="action" value="clear" form="product">

			</div>



<!--	$selected_fields = " id, title, author, isbn13, description, publication_date, price, pictures, categories, active "; -->


            <div class="form-group">
               <label for="product_title">title</label>
               <input type="text"	class="form-control" name="title" 	id="product_title" placeholder="title" value="">
			</div>
            <div class="form-group">
               <label for="product_author">author</label>
               <input type="text"	class="form-control" name="author"	id="product_author" placeholder="author" value="">
			</div>
            <div class="form-group">
               <label for="product_isbn13">isbn13</label>
               <input type="text"	class="form-control" name="isbn13" 	id="product_isbn13" placeholder="isbn13" value="">
			</div>
            <div class="form-group">
               <label for="product_description">description</label>
               <textarea			class="form-control" name="description"	id="product_description" rows="3" placeholder="description" value=""></textarea>
            </div>
            <div class="form-group">
               <label for="product_publication_date">publication date</label>
               <input type="text"	class="form-control" name="publication_date" 	id="product_publication_date" placeholder="publication date" value="">
			</div>
            <div class="form-group">
               <label for="product_price">price</label>
               <input type="text"	class="form-control" name="price" 	id="product_price" placeholder="price" value="">
			</div>

            <div class="form-group">
               <label for="product_pictures">pictures, semi-colon separated</label>
               <textarea			class="form-control" name="pictures" id="product_pictures" rows="2" placeholder="pictures" value=""></textarea>
            </div>

            <div class="form-group">
               <label for="product_categories">categories, semi-colon separated</label>
               <textarea			class="form-control" name="categories" 	id="product_categories" rows="2" placeholder="categories" value=""></textarea>
            </div>

            <div class="form-check">
               <input type="checkbox" class="form-check-input" name="active" id="product_active" value="1" <?php if (intval($active) === 1) {echo "checked";};?> >
               <label for="product_active">active</label>
            </div>
            <input type="submit"	class="btn btn-success" name="action" value="update" form="product">
            <input type="submit"	class="btn btn-info" name="action" value="insert" form="product">
		 </form>
		<br />

<?php
					 if ($info_code == 0) { echo '<div class="alert alert-primary" role="alert">'."\n"; }
					 else if ($info_code == 1) { echo '<div class="alert alert-success" role="alert">'."\n"; }
					 else if ($info_code == -1) {echo '<div class="alert alert-danger" role="alert">'."\n"; }
					 else if ($info_code == -2) {echo '<div class="alert alert-warning" role="alert">'."\n"; }
					 echo $info_text;
					 echo "</div>\n";
					 echo "<br />";

					 $pic_array = explode(";", $pictures);
					 foreach ($pic_array as $picture) {
						 $image = trim($picture);
						 if (strlen($image) > 0) {
							echo '<hr><img src="'.$image.'" class="img-fluid"><br />' . "\n";
						 }
					 }
?>

</div>
      </body>
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
      <script type="text/javascript">
         function setElementValue(class_name, element, value){
         	console.log(element, value);
         	var fc = document.getElementsByClassName(class_name);
         	var i;
         	for (i = 0; i < fc.length; i++) {
         		if (fc[i].id == element) {
         		var e = fc[i];
         			if (e) {
         				e.value = value;
         			}
         		}
         	}
		 }
		function setproductValues(){
			setElementValue('form-control','product_selected',			'<?php echo $id;?>');
			setElementValue('form-control','product_title',				'<?php echo preg_replace('/\s+/S', " ", addslashes($title));?>');
			setElementValue('form-control','product_author',			'<?php echo preg_replace('/\s+/S', " ", addslashes($author));?>');
			setElementValue('form-control','product_isbn13',			'<?php echo preg_replace('/\s+/S', " ", addslashes($isbn13));?>');
			setElementValue('form-control','product_description',		'<?php echo preg_replace('/\s+/S', " ", addslashes($description));?>');
			setElementValue('form-control','product_publication_date',	'<?php echo preg_replace('/\s+/S', " ", addslashes($publication_date));?>');
			setElementValue('form-control','product_price',				'<?php echo preg_replace('/\s+/S', " ", addslashes($price));?>');
			setElementValue('form-control','product_categories',		'<?php echo preg_replace('/\s+/S', " ", addslashes($categories));?>');
			setElementValue('form-control','product_pictures',			'<?php echo preg_replace('/\s+/S', " ", addslashes($pictures));?>');
		}
		window.onload = setproductValues();
      </script>
      </script>
   </body>
</html>
