<!DOCTYPE html>
<html>
<body>
<h2>List of all passengers</h2>
<p>

    <form action="formHandler.php" method="post">
        Name: <input type="text" name="name"/><br/>
        ssn: <input type="text" name="ssn"/><br/>
    <input type="submit">
    </form>

    <?php

        //path to the SQLite database file
        $db_file = './myDB/airport.db';

        try {
            //open connection to the airport database file
            $db = new PDO('sqlite:' . $db_file);

            //set errormode to use exceptions
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //return all passengers, and store the result set
            // echo "$_GET[passenger_ssn]";
            // $query_str = "select * from passengers;";
            $query_str = "select * from passengers where ssn='$_GET[passenger_ssn]';";
            $result_set = $db->query($query_str);

            //loop through each tuple in result set and print out the data
            //ssn will be shown in blue (see below)
            foreach($result_set as $tuple) {
                 echo "<font color='blue'>$tuple[ssn]</font> $tuple[f_name] $tuple[m_name] $tuple[l_name]<br/>\n";
            }

            //disconnect from db
            $db = null;
        }
        catch(PDOException $e) {
            die('Exception : '.$e->getMessage());
        }
    ?>

</p>
</body>
</html>