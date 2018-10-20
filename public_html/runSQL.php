<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="showPassengers.css">
</head>
<body>
    <p>
        <a href='index.php'>back</a>
    </p>
    <h3>
        Enter SQL Query:
    </h3>
    <p>
        <form action="" method="post">
            SQL Query: <input type="text" name="query"/><br/><br/>
            <input type="submit", name="submit">
        </form>
        <br />

        <?php
        echo "<table style='border: solid 1px black;'>";
        //path to the SQLite database file
        $db_file = './myDB/airport.db';

        try {
            //open connection to the airport database file
            $db = new PDO('sqlite:' . $db_file);

            //set errormode to use exceptions
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['submit'])) {

                //displays the query that the user entered
                echo "<b>SQL Query: </b>";
                echo $_POST['query'] . "<br /><br />";

                //displays the results of the query
                echo "<b>Query Results:</b><br /><br />";

                //check if query is empty
                //

                if(empty(trim_data($_POST['query']))){
                    echo "<font color='red'><b>Please enter a SQL query.</b></font><br />";
                }else{
                    $stmt = $db->prepare($_POST['query']);
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
                        echo "</tr>";
                    }
                }
            }   
            $db = null;
        }

        catch(PDOException $e) {
            //catching and displaying exceptions
            die('Exception : '.$e->getMessage());
        }

        function trim_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        }
        ?>

    </p>
</body>
</html>