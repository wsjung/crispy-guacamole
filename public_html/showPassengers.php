<!DOCTYPE html>
<html><head>
    <link rel="stylesheet" type="text/css" href="showPassengers.css">
</head>
<body>
    <p>
        <a href='index.php'>back</a>
    </p>

    <?php
        echo (isset($_GET['success'])) ? 
        '<p>success!</p>' : '';
    ?>

<h2>List of all passengers</h2>
<p>
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
            // $query_str = "select * from passengers where ssn='$_GET[passenger_ssn]';";
            // $result_set = $db->query($query_str);
            // 
            // arbitrarily print out a table of passengers 
            $stmt = $db->prepare("select * from passengers");
            // var_dump($stmt);
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $relation = $stmt->fetchAll();

            echo"<table>";

            $first = true;
            foreach($relation as $key => $tuple) {
                if($first) {
                    echo "<tr>";
                    foreach($tuple as $a => $v) {
                        echo "<th>$a</th>";
                    }
                    echo "</tr>";
                    $first = false;
                }
                echo "<tr>";
                foreach($tuple as $a => $v) {
                    echo "<td>$v</td>";
                }
                echo "<td><a href='./passengerForm.php?ssn=$tuple[ssn]&f_name=$tuple[f_name]&m_name=$tuple[m_name]&l_name=$tuple[l_name]&update=True'>update</a></td>";
                echo "</tr>";
            }

            // // table headers
            // echo "<table>
            // <tr>
            // <th>First name</th>
            // <th>Middle name</th>
            // <th>Last name</th>
            // <th>SSN</th>
            // </tr>";

            // foreach($result_set as $tuple) {
            //     echo "<tr>
            //     <td>$tuple[f_name]</td>
            //     <td>$tuple[m_name]</td>
            //     <td>$tuple[l_name]</td>
            //     <td>$tuple[ssn]</td>
            //     <td><a href='./passengerForm.php?ssn=$tuple[ssn]&f_name=$tuple[f_name]&m_name=$tuple[m_name]&l_name=$tuple[l_name]&update=True'>update</a></td>
            //     </tr>";
            // }



            //loop through each tuple in result set and print out the data
            //ssn will be shown in blue (see below)
            //update href passes the ssn variable 
            // foreach($result_set as $tuple) {
            //      echo "<font color='blue'>$tuple[ssn]</font> $tuple[f_name] $tuple[m_name] $tuple[l_name] 
            //      <a href='./passengerForm.php?ssn=$tuple[ssn]&f_name=$tuple[f_name]&m_name=$tuple[m_name]&l_name=$tuple[l_name]&update=True'>update</a> 
            //      <br/>\n";
            // }

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