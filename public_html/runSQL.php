<!DOCTYPE html>
<html>
<body>
<h3>
    Enter SQL Query:
</h3>

<p>

    <form action="" method="post">
        SQL Query: <input type="text" name="query"/><br/>
        <input type="submit", name="submit">
    </form>
    <br />

    <?php
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

                $splitquery = preg_split("/ /", $_POST['query']);

                $result_set = $db->query($_POST['query']);

                //loop through each tuple in result set and print out the data
                //ssn will be shown in blue (see below)
                foreach($result_set as $tuple) {
                    echo "<font color='blue'>$tuple[ssn]</font> $tuple[f_name] $tuple[m_name] $tuple[l_name]<br/>\n";
                }
                //disconnect from db
                $db = null;

            }

        }
        catch(PDOException $e) {
            //catching and displaying exceptions
            die('Exception : '.$e->getMessage());
        }
    ?>

</p>
</body>
</html>
