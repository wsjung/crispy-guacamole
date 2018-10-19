<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<h2>Create new passenger</h2>
<div>
	<?php
    session_start();
	?>
    <span class="error">* required field</span><br><br>
    <form action="./form_handler.php" method="post">
    	First Name: <input type="text" name="f_name" <?php if(isset($_SESSION["f_name"])) echo "value=\"".$_SESSION["f_name"]."\""; ?>/>
        <span class="error">* 
            <?php if(isset($_SESSION["empty_f_name"])) {
                echo "First name is required";
                } else if (isset($_SESSION["format_f_name"])){
                    echo "First name must be alphabetic";
                } ?>
            </span><br><br>

    	Middle Name: <input type="text" name="m_name" <?php if(isset($_SESSION["m_name"])) echo "value=\"".$_SESSION["m_name"]."\""; ?>/><br><br>

    	Last Name: <input type="text" name="l_name" <?php if(isset($_SESSION["l_name"])) echo "value=\"".$_SESSION["l_name"]."\""; ?>/> <span class="error">* 
            <?php if(isset($_SESSION["empty_l_name"])) {
                echo "Last name is required";
                } else if (isset($_SESSION["format_l_name"])) {
                    echo "Last name must be alphabetic";
                } ?>
            </span><br><br> 

    	SSN: <input type="text" name="ssn" <?php if(isset($_SESSION["ssn"])) echo "value=\"".$_SESSION["ssn"]."\""; ?>/> <span class="error">* 
            <?php if(isset($_SESSION["empty_ssn"])) {
                echo "ssn is required"; 
            } else if (isset($_SESSION["format_ssn"])) {
                echo "ssn must be digits of form xxx-xx-xxxx";
            } ?>    
            </span><br><br>

    	<input type="submit">
</div>
</body>
</html>