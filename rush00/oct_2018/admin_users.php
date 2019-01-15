<?php
   include 'subroutines.php';
   $db = mysql_connect();
   
   $id = 0;
   $access_level = 0;
   $user_name = '';
   $password_hash = '';
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
   	if (isset($_POST['access_level']))
   		$access_level = mysqli_real_escape_string($db, $_POST['access_level']);
   	if (isset($_POST['user_name']))
   		$user_name = mysqli_real_escape_string($db, $_POST['user_name']);
   	if (isset($_POST['password_hash']))
   		$password_hash = mysqli_real_escape_string($db, $_POST['password_hash']);
   	if (isset($_POST['active']))
		$active = 1;

   $id = intval($id);
   $selected_fields = " id, access_level, user_name, password_hash, active ";
   $table_name = ' users ';


	if ($_POST['action'] === 'clear') {
		$clear = 1;
	} else if ($_POST['action'] === 'select'){	///// select
		if (intval($id) > 0) {
				$id = intval($id);
   				$query = "SELECT id, access_level, user_name, password_hash, active FROM users WHERE id='$id';";
   				if (($result = mysqli_query($db, $query)) !== NULL) {
   					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
   					$access_level = $row['access_level'];
   					$user_name = $row['user_name'];
   					$password_hash = $row['password_hash'];
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
   				$query = "UPDATE users SET access_level='$access_level', user_name='$user_name', password_hash='$password_hash', active=$active WHERE id=$id;";
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
		$query = "INSERT INTO users (access_level, user_name, password_hash, active) VALUES ('$access_level', '$user_name', '$password_hash', '$active');";
		if (strlen($user_name) > 0) {
	   		if (mysqli_query($db, $query) === TRUE) {
   				$info_code = 1;
   				$info_text = 'ok, record inserted';
   			} else {
   				$info_code = -1;
   				$info_text = 'sql [insert] query failed, name must be unique'; //CHECK ME
   			};
		} else {
			$info_code = -2;
			$info_text = 'sql [insert]: name cannot be empty'; //ADD OTHER FIELDS??
		}
	} else if ($_POST['action'] === 'delete') {	//// DELETE
		if ($id > 0) {
			$query = "DELETE FROM users WHERE id=$id;";
//	   		$query = "UPDATE users SET deleted=1, name='', user_name='', password_hash='', active=0 WHERE id='$id';";
	   		if (mysqli_query($db, $query) === TRUE) {
	   			$info_code = 1;
				$info_text = 'ok, record deleted';
				$id = 0;
				$access_level = '';
				$user_name = '';
				$password_hash = '';
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
		$access_level = '';
		$user_name = '';
		$password_hash = '';
		$active = 0;
		$info_code = 0;
		$info_text = 'all clear';
		$clear = 0;
	};

	$query = 'SELECT id, user_name, access_level FROM users WHERE deleted=0 ORDER BY user_name ASC;'; //ORDER BY UPDATE
	$result = mysqli_query($db, $query);
	$menu = [];

	while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== NULL) {
		$menu[$row['id']] = $row['user_name'];
	}
	mysqli_free_result($result);
	mysqli_close($db);

	$access_level = stripslashes($access_level);
	$user_name = stripslashes($user_name);
	$password_hash = stripslashes($password_hash);

?>
<!DOCTYPE html>
<html>
   <head>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <title>users</title>
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
               <li><a href="admin_products.php" style="height: 64px"><h1>products</h1></a></li>
               <li><a href="admin_users.php" style="height: 64px"><i><h1>users</h1></i></a></li>
               <li><a href="index.php" style="height: 64px"><h1>back to store</h1></a></li>
           </ul>
       </div>
      <div class="container">
         <h1>users</h1>
         <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" id="user">
            <div class="form-group">
               <label for="select1">choose existing</label>
               <select class="form-control" id="user_selected" name="id" form="user">
				  <option disabled selected value="0"> -- [select user] -- </option>
<?php
	echo "<pre>";
	var_dump($menu);
	echo "</pre>";
?>
				<?php
					foreach ($menu as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>' . "\n";
					}
				?>
               </select>
               <br />
               <input type="submit" class="btn btn-primary" name="action" value="select" form="user">
			   <input type="submit" class="btn btn-danger" name="action" value="delete" form="user">
			   <input type="submit" class="btn btn-light" name="action" value="clear" form="user">

            </div>
            <div class="form-group">
               <label for="user_access_level">user access_level</label>
               <input type="text"	class="form-control" name="access_level" 				id="user_access_level" placeholder="user access_level" value="">
            </div>
            <div class="form-group">
               <label for="user_user_name">username</label>
               <input type="text"	class="form-control" name="user_name"	id="user_user_name" placeholder="username" value="">
            </div>
            <div class="form-group">
               <label for="user_password_hash">password_hash</label>
               <textarea			class="form-control" name="password_hash"			id="user_password_hash" rows="3" placeholder="password_hash" value=""></textarea>
            </div>
            <div class="form-check">
               <input type="checkbox" class="form-check-input" name="active" id="user_active" value="1" <?php if (intval($active) === 1) {echo "checked";};?> >
               <label for="user_active">active</label>
            </div>
            <input type="submit"	class="btn btn-success" name="action" value="update" form="user">
            <input type="submit"	class="btn btn-info" name="action" value="insert" form="user">
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
?>

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
		function setUserValues(){
			setElementValue('form-control','user_selected', '<?php echo $id;?>');
			setElementValue('form-control','user_access_level', '<?php echo addslashes(preg_replace('/\s+/S', " ", $access_level));?>');
			setElementValue('form-control','user_user_name', '<?php echo addslashes(preg_replace('/\s+/S', " ", $user_name));?>');
			setElementValue('form-control','user_password_hash', '<?php echo addslashes(preg_replace('/\s+/S', " ", $password_hash));?>');
		}
		window.onload = setUserValues();
      </script>
      </body>
   </body>
</html>
