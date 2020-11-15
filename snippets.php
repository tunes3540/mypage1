			<?
			print "<pre>";
      print_r($_POST);
      print "</pre>";
      
      // CONVERT ALL POST AND GET DATA
      foreach ($_REQUEST as $param_name => $param_val) {
					if($param_name == "PHPSESSID"){continue;}
			    $$param_name = $param_val;
			}
      
      // for multiple records
      $db = connect_pdo("checkout");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = "SELECT * from pcf  order by id";
	    $stmt = $db->query($sql);
	    $rows = $stmt->fetchAll();
    	foreach($rows as $row){
		    echo $row['id'];
		  }
		  
		  // for one record
		  $db = connect_pdo("checkout");
		  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql= "SELECT * from $mod_order_checkout_table where eo = '$mod_order_number'";
	    $stmt = $db->query($sql);
	    $row = $stmt->fetchObject();
	    if(count($row) == 0){echo "nothing found";}
	    $rev = $row->rev;
		  
		  //INSERT
		  $db = connect_pdo("checkout");
		  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $q = $db -> prepare("INSERT INTO accounts (username, email, password) VALUES (?, ?, ?)");
			$q->execute(array($_POST['username'], $_POST['email'], $_POST['password']));
			
				  
		  //updating some data
			$sqlInsert = 'UPDATE test set name=:name where id=:id';
			$preparedStatement = $conn->prepare($sqlInsert);
			$preparedStatement->execute(array(':name' => 'MICHAEL', ':id' => 1));
			
			
			//UPDATE
			$db = connect_pdo("checkout");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE checklist SET item=:item WHERE id=:id"; 
			$stmt = $db->prepare($sql); 
			$result = $stmt->execute( 
			    array( 
			        ':item'   => $item, 
			        ':id'    => 5
			    ) 
			); 
			
			// INSERT
			$db = connect_pdo("checkout");
		  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO movies(filmName,
            filmDescription,
            filmImage,
            filmPrice,
            filmReview) VALUES (
            :filmName,
            :filmDescription,
            :filmImage,
            :filmPrice,
            :filmReview)";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':filmName', $_POST['filmName'], PDO::PARAM_STR);
			$stmt->bindParam(':filmDescription', $_POST['filmDescription'], PDO::PARAM_STR);
			$stmt->bindParam(':filmImage', $_POST['filmImage'], PDO::PARAM_STR);
			$stmt->bindParam(':filmPrice', $_POST['filmPrice'], PDO::PARAM_STR);
			$stmt->bindParam(':filmReview', $_POST['filmReview'], PDO::PARAM_STR);
			$stmt->execute();
		  
		  // INSERT
		  $sql = "INSERT INTO blog_posts (postTitle,postDesc,postCont,postDate) VALUES (:postTitle, :postDesc, :postCont, :postDate)";
		  $stmt = $db->prepare($sql) ;
			$stmt->execute(array(
							'postTitle' => $postTitle,
			        'postDesc' => $postDesc,
			        'postCont' => $postCont,
			        'postDate' => date('Y-m-d H:i:s')
			
			    ));
		  
		  // OLD WAY to GET 
		  $db = connect('checkout');
	    $sql = "SELECT  * FROM pcf where id = '$pcf_number' ";
	    $result = mysql_query($sql,$db) or die(mysql_error());
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $model = $row['model'];
		  
		  // DELETE
		  $db = connect_pdo("checkout");$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "DELETE FROM refdes WHERE id =  :id";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
      $stmt->execute();
		  