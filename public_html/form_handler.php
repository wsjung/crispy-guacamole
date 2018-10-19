<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="showPassengers.css">
</head>
<body>
<?php
	// hashmap of errors and error messages
	$errors = array();
	// start session to store entry values
	// session_start();
	// $args = $_POST;

	$args = $_POST;

	// check if each entry is not empty
	if(!empty(trim_data($_POST['f_name']))) {
		// check if alphabetic
		if(!preg_match('/[^A-Za-z]/', trim_data($_POST['f_name']))) {
			$f_name = trim_data($_POST['f_name']);
		} else {
			$errors['f_name'] = trim_data($_POST['f_name']);
			// $args['format_f_name'] = True;
			$args['format_f_name'] = True;
		}
	} else {
		$errors['f_name'] = trim_data($_POST['f_name']);
		// $args['empty_f_name'] = True;
		$args['empty_f_name'] = True;
	}

	$m_name = trim_data($_POST['m_name']);

	if(!empty(trim_data($_POST['l_name']))) {
		// check if alphabetic
		if(!preg_match('/[^A-Za-z]/', trim_data($_POST['l_name']))) {
			$l_name = trim_data($_POST['l_name']);
		} else {
			$errors['l_name'] = trim_data($_POST['l_name']);
			$args['format_l_name'] = True;
		}
	} else {
		$errors['l_name'] = trim_data($_POST['l_name']);
		$args['empty_l_name'] = True;
	}

	if(!empty(trim_data($_POST['ssn']))) {
		if(preg_match('/(^\d{3}-\d{2}-\d{4}$)/', trim_data($_POST['ssn']))) {
			$ssn = trim_data($_POST['ssn']);
		} else {
			$errors['ssn'] = trim_data($_POST['ssn']);
			$args['format_ssn'] = True;
		}
	} else {
		$errors['ssn'] = trim_data($_POST['ssn']);
		$args['empty_ssn'] = True;
	}

	// empty errors array means all required fields are set
	if(empty($errors)) {
		try {
			//path to the SQLite database file
			$db_file = './myDB/airport.db';
			
			// open connection to the airport databae file
			$db = new PDO('sqlite:' . $db_file);

			// check whether update or not
			if(isset($_POST['update'])) {
				// update relation prepared statement
				$stmt = $db->prepare("update passengers set f_name=(:f_name), m_name=(:m_name), l_name=(:l_name), ssn=(:ssn) where ssn=(:oldssn)");

				// bind parameters
				$stmt->bindParam(':f_name', $f_name);
				$stmt->bindParam(':m_name', $m_name);
				$stmt->bindParam(':l_name', $l_name);
				$stmt->bindParam(':ssn', $ssn);
				$stmt->bindParam(':oldssn',$_POST['oldssn']);

				// execute query
				$result = $stmt->execute();

				// redirect to now-updated passenger list
				header('Location: ./showPassengers.php?success=1');

			} else {
				// check that the ssn does not already exist
				// insert query prepared statement
				$stmt = $db->prepare("insert into passengers (f_name,m_name,l_name,ssn) values (:f_name, :m_name, :l_name, :ssn)");
				// bind parameters
				$stmt->bindParam(':f_name', $f_name);
				$stmt->bindParam(':m_name', $m_name);
				$stmt->bindParam(':l_name', $l_name);
				$stmt->bindParam(':ssn', $ssn);
				// execute query
				$result = $stmt->execute();

				// check that the query worked
				// true - if query worked
				// false - if ssn already exists in relation
				if($result) {
					// redirect to passenger list with success message
					header('Location: ./showPassengers.php?success=1');
				} else {
					// ssn already exists in table
					$args['exists_ssn'] = True;

					// redirect back to the form
					header('Location: ./passengerForm.php?' . http_build_query($args));
				}
			}

			

			// $result = $db->query("select * from passengers;");

			// foreach($result as $tuple) {
			// 	echo "<font color='blue'>$tuple[ssn]</font> $tuple[f_name] $tuple[m_name] $tuple[l_name]<br/>\n";
			// }

			// disconnect from db
			$db = null;
		} 
		catch(PDOException $e) {
			die('Exception : ' .$e->getMessage());
		}
		
	} else {
		// redirect back to the form
		header('Location: ./passengerForm.php?' . http_build_query($args));
	}

	// trims unnecessary charcters
	// removes backslashes
	// replaces html special characters
	function trim_data($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>
</body>
</html>