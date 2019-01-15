<?php
   include 'subroutines.php';
   $db = mysql_connect();
   
   $id = 0;
   $name = '';
   $short_description = '';
   $description = '';
   $pictures = '';
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
   	if (isset($_POST['name']))
   		$name = mysqli_real_escape_string($db, $_POST['name']);
   	if (isset($_POST['short_description']))
   		$short_description = mysqli_real_escape_string($db, $_POST['short_description']);
   	if (isset($_POST['description']))
   		$description = mysqli_real_escape_string($db, $_POST['description']);
   	if (isset($_POST['pictures']))
   		$pictures = mysqli_real_escape_string($db, $_POST['pictures']);
   	if (isset($_POST['active']))
		$active = 1;


	if ($_POST['action'] === 'clear') {
		$clear = 1;
	} else if ($_POST['action'] === 'select'){	///// select
		if (intval($id) > 0) {
				$id = intval($id);
   				$query = "SELECT id, name, short_description, description, pictures, active FROM categories WHERE id='$id';";
   				if (($result = mysqli_query($db, $query)) !== NULL) {
   					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
   					$name = $row['name'];
   					$short_description = $row['short_description'];
   					$description = $row['description'];
   					$pictures = $row['pictures'];
   					$active = intval($row['active']);
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
   				$query = "UPDATE categories SET name='$name', short_description='$short_description', description='$description', pictures='$pictures', active=$active WHERE id=$id;";
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
		$query = "INSERT INTO categories (name, short_description, description, pictures, active) VALUES ('$name', '$short_description', '$description', '$pictures', '$active');";
		if (strlen($name) > 0) {
	   		if (mysqli_query($db, $query) === TRUE) {
   				$info_code = 1;
   				$info_text = 'ok, record inserted';
   			} else {
   				$info_code = -1;
   				$info_text = 'sql [insert] query failed, name must be unique';
   			};
		} else {
			$info_code = -2;
			$info_text = 'sql [insert]: name cannot be empty';
		}
	} else if ($_POST['action'] === 'delete') {	//// DELETE
		if ($id > 0) {
			$query = "DELETE FROM categories WHERE id=$id;";
//	   		$query = "UPDATE categories SET deleted=1, name='', short_description='', description='', pictures='', active=0 WHERE id='$id';";
	   		if (mysqli_query($db, $query) === TRUE) {
	   			$info_code = 1;
				$info_text = 'ok, record deleted';
				$id = 0;
				$name = '';
				$short_description = '';
				$description = '';
				$pictures = '';
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
		$name = '';
		$short_description = '';
		$description = '';
		$pictures = '';
		$active = 0;
		$info_code = 0;
		$info_text = 'all clear';
		$clear = 0;
	};

	$query = 'SELECT id, name FROM categories WHERE deleted=0 ORDER BY name ASC;';
	$result = mysqli_query($db, $query);
	$menu = [];

	while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== NULL) {
		$menu[$row['id']] = $row['name'];
	}
	mysqli_free_result($result);
	mysqli_close($db);

	$name = stripslashes($name);
	$short_description = stripslashes($short_description);
	$description = stripslashes($description);
	$picutres = stripslashes($pictures);

?>
<!DOCTYPE html>
<html>
   <head>
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
       <title>categories</title>
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
           <li><a href="admin_categories.php" style="height: 64px;"><i><h1>categories</h1></i></a></li>
           <li><a href="admin_products.php" style="height: 64px"><h1>products</h1></a></li>
           <li><a href="admin_users.php" style="height: 64px"><h1>users</h1></a></li>
           <li><a href="index.php" style="height: 64px"><h1>back to store</h1></a></li>
       </ul>
   </div>
      <div class="container">
          <h1>genres</h1>
         <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" id="category">
            <div class="form-group">
               <label for="select1">choose existing</label>
               <select class="form-control" id="category_selected" name="id" form="category">
				  <option disabled selected value="0"> -- [select category] -- </option>
<?php
//	echo "<pre>";
//	var_dump($menu);
//	echo "</pre>";
?>
				<?php
					foreach ($menu as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>' . "\n";
					}
				?>
               </select>
               <br />
               <input type="submit" class="btn btn-primary" name="action" value="select" form="category">
			   <input type="submit" class="btn btn-danger" name="action" value="delete" form="category">
			   <input type="submit" class="btn btn-light" name="action" value="clear" form="category">

            </div>
            <div class="form-group">
               <label for="category_name">category name</label>
               <input type="text"	class="form-control" name="name" 				id="category_name" placeholder="category name" value="">
            </div>
            <div class="form-group">
               <label for="category_short_description">short description</label>
               <input type="text"	class="form-control" name="short_description"	id="category_short_description" placeholder="short description" value="">
            </div>
            <div class="form-group">
               <label for="category_description">description</label>
               <textarea			class="form-control" name="description"			id="category_description" rows="3" placeholder="description" value=""></textarea>
            </div>
            <div class="form-group">
               <label for="category_description">pictures, semi-colon separated</label>
               <textarea			class="form-control" name="pictures" 			id="category_pictures" rows="2" placeholder="pictures" value=""></textarea>
            </div>
            <div class="form-check">
               <input type="checkbox" class="form-check-input" name="active" id="category_active" value="1" <?php if (intval($active) === 1) {echo "checked";};?> >
               <label for="category_active">active</label>
            </div>
            <input type="submit"	class="btn btn-success" name="action" value="update" form="category">
            <input type="submit"	class="btn btn-info" name="action" value="insert" form="category">
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
      </div>
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
		function setCategoryValues(){
			setElementValue('form-control','category_selected', '<?php echo $id;?>');
			setElementValue('form-control','category_name', '<?php echo addslashes(preg_replace('/\s+/S', " ", $name));?>');
			setElementValue('form-control','category_short_description', '<?php echo addslashes(preg_replace('/\s+/S', " ", $short_description));?>');
			setElementValue('form-control','category_description', '<?php echo addslashes(preg_replace('/\s+/S', " ", $description));?>');
			setElementValue('form-control','category_pictures', '<?php echo addslashes(preg_replace('/\s+/S', " ", $pictures));?>');
		}
		window.onload = setCategoryValues();
      </script>
      </body>
</html>
