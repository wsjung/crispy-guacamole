<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<h2>Create new passenger</h2>
<div>
    <span class="error">* required field</span><br><br>
    <form action="./form_handler.php" method="post">
    	First Name: <input type="text" name="f_name" <?php if(isset($_GET["f_name"])) echo "value=\"".$_GET["f_name"]."\""; ?>/>
        <span class="error">* 
            <?php if(isset($_GET["empty_f_name"])) {
                echo "First name is required";
                } else if (isset($_GET["format_f_name"])){
                    echo "First name must be alphabetic";
                } ?>
            </span><br><br>

    	Middle Name: <input type="text" name="m_name" <?php if(isset($_GET["m_name"])) echo "value=\"".$_GET["m_name"]."\""; ?>/><br><br>

    	Last Name: <input type="text" name="l_name" <?php if(isset($_GET["l_name"])) echo "value=\"".$_GET["l_name"]."\""; ?>/> <span class="error">* 
            <?php if(isset($_GET["empty_l_name"])) {
                echo "Last name is required";
                } else if (isset($_GET["format_l_name"])) {
                    echo "Last name must be alphabetic";
                } ?>
            </span><br><br> 

    	SSN: <input type="text" name="ssn" <?php if(isset($_GET["ssn"])) echo "value=\"".$_GET["ssn"]."\""; ?>/> <span class="error">* 
            <?php if(isset($_GET["empty_ssn"])) {
                echo "ssn is required"; 
            } else if (isset($_GET["format_ssn"])) {
                echo "ssn must be digits of form xxx-xx-xxxx";
            } else if (isset($_GET["exists_ssn"])) {
                echo "passenger with ssn already exists";
            } ?>
            </span><br><br>

    	<input type="submit">
</div>
</body>
</html>