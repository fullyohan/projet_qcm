<?php
require_once "db.php";
$sql_file = "schema.sql";

if (file_exists($sql_file)) {
    $sql = file_get_contents($sql_file);
    if (mysqli_multi_query($db, $sql)) {
        echo "Tables creees";
        while (mysqli_next_result($db)) {
            if ($result = mysqli_store_result($db)) {
                mysqli_free_result($result);
            }
        }
    } else {
        echo mysqli_error($db);
    }
} else {
    echo "Not found";
}