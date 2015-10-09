<?php

$param = isset($argv[1]) ? $argv[1] : "put";

$file_path = __FILE__;
$f1 = explode('/',$file_path);
unset($f1[count($f1)-1]);
unset($f1[count($f1)-1]);

$dot_env_path = implode("/",$f1);

$f1[count($f1)] = "vendor";
$f1[count($f1)] = "autoload.php";
$f1 = implode("/",$f1);

require $f1;

Dotenv::load($dot_env_path);

$servername = $_ENV['DB_HOST'];
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

$old_url = $_ENV['LOCAL_URL'];
$new_url = $_ENV['PROD_URL'];

if($param == 'get')
{
    $old_url = $_ENV['PROD_URL'];
    $new_url = $_ENV['LOCAL_URL'];
}

echo "Changing from {$old_url} to {$new_url}\n";

// Create connection
$conn = new mysqli($servername, $username, $password,$_ENV['DB_NAME']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "\nConnected successfully \n";

$changed = 0;

$sql = "show tables";
$rs = $conn->query($sql);
if($rs->num_rows > 0){
    while($r = $rs->fetch_array()){
        $table = $r[0];
        $pos = strpos("wp_woocommerce_order", $table);
        if($pos)
        {
            continue;
        }
        $pkquery = "SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'";
        $pkres = $conn->query($pkquery);
        $pk = mysqli_fetch_assoc($pkres);
        $pk = $pk['Column_name'];

        $sql_search = "select * from ".$table." where ";
        $sql_search_fields = Array();
        $sql2 = "SHOW COLUMNS FROM ".$table;
        $rs2 = $conn->query($sql2);

        if($rs2->num_rows > 0){
            while($r2 = $rs2->fetch_array()){
                $colum = $r2[0];
                $sql_search_fields[] = $colum." like('%".$old_url."%')";
            }
            $rs2->close();
        }
        $sql_search .= implode(" OR ", $sql_search_fields);
        $rs3 = $conn->query($sql_search);
        if($rs3->num_rows > 0){
            while($row = mysqli_fetch_assoc($rs3))
            {
                $found = false;
                foreach($row as $key=>$value)
                {
                    $pos = strpos($value, $old_url);
                    if($pos)
                    {
                        $found = true;
                        $data = @unserialize($value);

                        if ($data !== false) {
                            replace_value($old_url,$new_url,$data);
                            $row[$key] = serialize($data);
                        } else {
                            replace_value($old_url,$new_url,$row[$key]);
                        }
                    }
                }
                $update_query = "UPDATE {$table} SET ";
                $updates = Array();
                foreach($row as $key=>$value)
                {
                    $updates[] = "{$key}='".$conn->real_escape_string($value)."'";
                }

                if(!$pk){
                    $pk = array_keys($row)[0];
                }

                $update_query .= implode(",",$updates);
                $update_query .= " WHERE {$pk}={$row[$pk]}";
                $result = $conn->query($update_query);
                $changed += $conn->affected_rows;
            }
            $rs3->close();
        }
    }
    $rs->close();
}

echo "Affected rows:".$changed."\n\n";

function replace_value($text,$replace,&$item)
{
    if(is_array($item))
    {
        replace_text_in_array($text,$replace,$item);
    }

    if(is_object($item))
    {
        replace_text_in_object($text,$replace,$item);
    }

    if(is_string($item))
    {
        replace_text_in_string($text,$replace,$item);
    }
}

function replace_text_in_array($text,$replace,&$array)
{
    foreach($array as $key=>&$value)
    {
        replace_value($text,$replace,$value);
    }
}

function replace_text_in_object($text,$replace,&$object)
{
    $props = get_object_vars($object);
    foreach($props as $key=>$value)
    {
        replace_value($text,$replace,$object->$key);
    }
}

function replace_text_in_string($text,$replace,&$string)
{
    $string = str_ireplace($text,$replace,$string);
}

