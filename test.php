<?php
    

    $html = file_get_contents("https://api.nasa.gov/insight_weather/?api_key=v2Yy8qSVnJNBnL4TgyV5nKn0qsKMXoK5SfnSmhTD&feedtype=json&ver=1.0");
    $json = json_decode($html,true);

    $result = printValues($json);
    echo "<h3>" . $result["total"] . " value(s) found: </h3>";
    echo implode("<br>", $result["values"]);
    echo "<hr>";

    //$query = "INSERT INTO tiempo (mes,estacion) VALUES " . 
?>

<?php
// Define recursive function to extract nested values
function printValues($arr) {
    $db = mysqli_connect("localhost", 'root', 'root') or die ('Unable to connect. Check your connection parameters.');
    mysqli_select_db($db,'nasa') or die(mysqli_error($db));
    global $count;
    global $values;
    global $keys;
    global $mes;
    global $estacion;
    // Check input is an array
    if(!is_array($arr)){
        die("ERROR: Input is not an array");
    }
    
    /*
    Loop through array, if value is itself an array recursively call the
    function else add the value found to the output items array,
    and increment counter by 1 for each value found
    */
    foreach($arr as $key=>$value){
        
        if(is_array($value)){
            if($key == "813"){
                // $query = "INSERT INTO tiempo (mes,estacion) VALUES (" . $value["Month_ordinal"] . ", '" . $value["Season"] . "')";
                // mysqli_query($db,$query) or die(mysqli_error($db));
                foreach($value as $key=>$valor){
                    if($key == "Month_ordinal"){
                        $mes = $valor;
                    }
                    if($key == "Season"){
                        $estacion = $valor;
                    }
                }
                
                $query = "INSERT INTO tiempo (mes,estacion) VALUES (" . $mes . ", '" . $estacion . "')";
                mysqli_query($db,$query) or die(mysqli_error($db));
            }
            printValues($value);
        } else{
            $values[] = $value;
            $keys[] = $key;
            $count++;
        }
    }
    
    // Return total count and values found in array
    return array('total' => $count, 'values' => $values, 'keys' => $keys);
}
 
?>

