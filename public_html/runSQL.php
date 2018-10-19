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

                        /*if($from_index == 2 && $splitquery[1] == '*'){
                            //adding * to the attribute array
                            $attr_array[] = $splitquery[1];
                        }*/
                        if ($from_index > 1){
                            //check for sus things in the attribute
                            for($i = 1; $i < $from_index && $valid_attr; $i++){
                                if(preg_match('/[\'^£$%&}{@#~?><>;!|=+¬-]/', $splitquery[$i])){
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
                            //time to check the database
                            if($valid_attr){
                                //holds all the relation info
                                $relation_array = array();
                                //holds the index of 'where'
                                $where_index = array_search(strtolower('where'), array_map('strtolower', $splitquery));
                                $valid_relation = True;

                                if($where_index > ($from_index + 1)){
                                    //check for sus things in the relation reference
                                    for($i = $from_index + 1; $i < $where_index && $valid_relation; $i++){
                                        if(preg_match('/[\'^£$%&*}{@#~?><>!|;=+¬-]/', $splitquery[$i])){
                                            $valid_relation = False;
                                        }
                                        else {
                                            $relation_array[] = $splitquery[$i];
                                        }
                                    }

                                    //relation is good, so now we check our where clause!
                                    if($valid_relation){
                                        //holds all the constraint info
                                        $constraint_array = array();
                                        $valid_constraint = True;

                                        //check for sus things in the where clause
                                        for($i = $where_index + 1; $i < count($splitquery) && $valid_constraint; $i++){
                                            if(preg_match('/[\£$%&}{@#~?|¬]/', $splitquery[$i])){
                                                $valid_constraint = False;
                                            }
                                            else {
                                                $constraint_array[] = $splitquery[$i];
                                            }
                                        }

                                        //TIME TO FINAL QUERY
                                        if($valid_constraint){
                                            $operators = array("ALL","ANY","BETWEEN","EXISTS","IN",
                                                                "LIKE");
                                            $finalcheck = True;

                                            //constructing the statement before up to where
                                            $qy = '';
                                            for($i = 0; $i <= $where_index; $i++){
                                                $qy .= $splitquery[$i] . ' ';
                                            }

                                            $values_indices = array();
                                            for($i = ($where_index +1); $i < count($splitquery) && $finalcheck; $i++){

                                                if(preg_match('/[!=><]/', $splitquery[$i])){
                                                    $values_indices[] = ($i+1);
                                                }
                                                if(in_array($splitquery[$i],$operators)){
                                                    $values_indices[] = ($i+1);
                                                }

                                                if(in_array($i, $values_indices)){
                                                    $qy .= "? ";
                                                }
                                                else{
                                                    if(preg_match('/[-;\']/', $splitquery[$i])){
                                                        $finalcheck = False;
                                                    }
                                                    $qy .= "$splitquery[$i] ";
                                                }
                                            }

                                            if($finalcheck){
                                                $stmt = $db -> prepare($qy);
                                                $values = array();
                                                for($i=0; $i < count($values_indices); $i++){
                                                    $values[] = $splitquery[$values_indices[$i]];
                                                }
                                                $stmt -> bindParam(1, $ssn, PDO::PARAM_STR);
                                                $stmt -> bindParam(2, $name, PDO::PARAM_STR);

                                                $ssn = $values[0];
                                                $name = 'Homer';
                                                $stmt->execute();
                                                $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

                                                var_export($results);
                                            }
                                            else{
                                                echo "<font color='red'><b>WHERE clause in SELECT query contains illegal characters.</b></font><br />";
                                            }
                                        }
                                        //BAD BAD NO ACCESS
                                        else{
                                            echo "<font color='red'><b>WHERE clause in SELECT query contains illegal characters.</b></font><br />";
                                        }

                                    }
                                    //the relation reference is sketchy
                                    else{
                                        echo "<font color='red'><b>Relation reference in SELECT query contains illegal characters.</b></font><br />";
                                    }

                                }
                                //if there is no WHERE clause
                                else{
                                    //check for sus things in the relation reference
                                    for($i = $from_index + 1; $i < count($splitquery) && $valid_relation; $i++){
                                        if(preg_match('/[\'^£$%&*}{@#~?><>!|=+¬-]/', $splitquery[$i])){
                                            $valid_relation = False;
                                        }
                                        else {
                                            $relation_array[] = $splitquery[$i];
                                        }
                                    }
                                    //proceed with query
                                    if($valid_relation){
                                        $result_set = $db->query($_POST['query']);

                                        if($attr_array[0] == '*'){
                                            foreach($result_set as $tuple) {
                                                for($i = 0; $i < (count($tuple)/2); $i++){
                                                    echo "$tuple[$i] ";
                                                    if($i >= ((count($tuple)/2)-1)){
                                                        echo "<br />";
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            //loop through each tuple in result set and print out the data
                                            //ssn will be shown in blue (see below)
                                            foreach($result_set as $tuple) {
                                                for($i = 0; $i < count($attr_array); $i++){
                                                    $index = $attr_array[$i];
                                                    echo "$tuple[$index] \t";
                                                    if($i == (count($attr_array)-1)){
                                                        echo "<br />";
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    //the relation reference is sketchy
                                    else{
                                        echo "<font color='red'><b>Relation reference in SELECT query contains illegal characters.</b></font><br />";
                                    }
                                }
                                //there are invalid attributes
                            }
                            //there are invalid attributes
                            else{
                                echo "<font color='red'><b>Attributes in SELECT query contain illegal characters.</b></font><br />";
                            }
                        }
                        //no from in the select statement
                        else{
                            echo "<font color='red'><b>SELECT query is missing statement: FROM</b></font><br />";
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
