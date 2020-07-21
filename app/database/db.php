<?php

require('connect.php');

// temporary function : 
function dd($value){  
    echo "<pre>", print_r($value, true) , "</pre>";
    die();
}

// function to return all records in database table: 
function selectALL($table , $conditions = []){

    global $conn;
    $sql = "SELECT * FROM $table";
    if (empty($conditions)){
        $stmnt = $conn->prepare($sql);
        $stmnt->execute();
        $records = $stmnt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $records;
    } else {
        $i = 0;
        foreach ($conditions as $key => $value){
            if($i===0){
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }
        $stmnt = $conn->prepare($sql);
        $values = array_values($conditions);
        $types = str_repeat('s', count($values));
        $stmnt->bind_param($types, ...$values);
        $stmnt->execute();
        $records = $stmnt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $records;
    }
}

$conditions = [
    'admin' => 0,
    'username' => 'marou'
];

$users = selectALL('users', $conditions);

dd($users);