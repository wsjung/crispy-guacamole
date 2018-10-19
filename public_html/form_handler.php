<?php
	// hashmap of errors and error messages
	$errors = array();
	// start session to store entry values
	session_start();
	$_SESSION = $_POST;

	// check if each entry is not empty
	if(!empty(trim_data($_POST['f_name']))) {
		// check if alphabetic
		if(!preg_match('/[^A-Za-z]/', trim_data($_POST['f_name']))) {
			$f_name = trim_data($_POST['f_name']);
		} else {
			$errors['f_name'] = trim_data($_POST['f_name']);
			$_SESSION['format_f_name'] = True;
		}
	} else {
		$errors['f_name'] = trim_data($_POST['f_name']);
		$_SESSION['empty_f_name'] = True;
	}

	$m_name = trim_data($_POST['m_name']);

	if(!empty(trim_data($_POST['l_name']))) {
		// check if alphabetic
		if(!preg_match('/[^A-Za-z]/', trim_data($_POST['l_name']))) {
			$l_name = trim_data($_POST['l_name']);
		} else {
			$errors['l_name'] = trim_data($_POST['l_name']);
			$_SESSION['format_l_name'] = True;
		}
	} else {
		$errors['l_name'] = trim_data($_POST['l_name']);
		$_SESSION['empty_l_name'] = True;
	}

	if(!empty(trim_data($_POST['ssn']))) {
		if(preg_match('/(^\d{3}-\d{2}-\d{4}$)/', trim_data($_POST['ssn']))) {
			$ssn = trim_data($_POST['ssn']);
		} else {
			$errors['ssn'] = trim_data($_POST['ssn']);
			$_SESSION['format_ssn'] = True;
		}
	} else {
		$errors['ssn'] = trim_data($_POST['ssn']);
		$_SESSION['empty_ssn'] = True;
	}

	// empty errors array means all required fields are set
	if(empty($errors)) {
		try {
			//path to the SQLite database file
			$db_file = './myDB/airport.db';
			
			// open connection to the airport databae file
			$db = new PDO('sqlite:' . $db_file);

			// query string
			// echo "insert into passengers values ('$f_name','$m_name',$l_name','$ssn'); <br><br>";

			// insert the new passenger
			$query_str = "insert into passengers values ('$f_name','$m_name','$l_name','$ssn');";
			$db->query($query_str);

			// $result = $db->query("select * from passengers;");

			// foreach($result as $tuple) {
			// 	echo "<font color='blue'>$tuple[ssn]</font> $tuple[f_name] $tuple[m_name] $tuple[l_name]<br/>\n";
			// }

			// success message
			echo "Success!";

			// disconnect from db
			$db = null;
		} 
		catch(PDOException $e) {
			die('Exception : ' .$e->getMessage());
		}
		
	} else {
		header('Location: ./passengerForm.php');
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