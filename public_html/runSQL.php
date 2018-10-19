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

                //check if query is empty
                if($_POST['query'] == ''){
                    echo "<font color='red'><b>Please enter a SQL query.</b></font><br />";
                }
                else{
                    $splitquery = preg_split("/ /", $_POST['query']);

                    //check if it is a select statement
                    if(!strcasecmp($splitquery[0], 'SELECT') == 0){
                        echo "<font color='red'><b>Please enter a valid SELECT query.</b></font><br />";
                    }
                    else{
                        //holds all the attributes to project
                        $attr_array = array();
                        //holds the index of 'from'
                        $from_index = array_search(strtolower('from'), array_map('strtolower', $splitquery));
                        $valid_attr = True;

                        if($from_index == 2 && $splitquery[1] == '*'){
                            //adding * to the attribute array
                            $attr_array[] = $splitquery[1];
                        }
                        else if ($from_index > 1){
                            //check for sus things in the attribute
                            for($i = 1; $i < $from_index && $valid_attr; $i++){
                                if(preg_match('/[\'^£$%&*()}{@#~?><>!|=+¬-]/', $splitquery[$i])){
                                    $valid_attr = False;
                                }
                                //formatting and adding valid attributes
                                else{
                                    if(preg_match("/,/",$splitquery[$i])){
                                        $temp = strtr($splitquery[$i], array(',' => ''));
                                        $attr_array[] = $temp;
                                    }
                                    else{
                                        $attr_array[] = $splitquery[$i];
                                    }
                                }
                            }
                            //if the validity check of attributes fails
                            if(!$valid_attr){
                                echo "<font color='red'><b>Attributes in SELECT query contain illegal characters.</b></font><br />";
                            }
                        }
                        //if the from_index doesn't exist
                        else{
                            echo "<font color='red'><b>SELECT query does not contain FROM.</b></font><br />";
                        }

                        //time to check the database
                        if($valid_attr){

                            echo "time to do this!";
                        }

                    }
                }

                /*$stmt = $db->prepare("SELECT :projection FROM :data where :attr :op :value");
                if ($stmt->execute(array($_POST['query']))) {
                  while ($row = $stmt->fetch()) {
                    print_r($row);
                  }
              }*/

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
