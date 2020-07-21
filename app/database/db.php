<?php

require('connect.php');

// temporary function : 
function dd($value){  
    echo "<pre>", print_r($value, true) , "</pre>";
    die();
}

function executeQuery($sql, $data){
    global $conn;
    $stmnt = $conn->prepare($sql);
    $values = array_values($data);
    $types = str_repeat('s', count($values));
    $stmnt->bind_param($types, ...$values);
    $stmnt->execute();
    return $stmnt;
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
        $stmnt = executeQuery($sql, $conditions);
        $records = $stmnt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $records;
    }
}

function selectOne($table , $conditions){

    global $conn;
    $sql = "SELECT * FROM $table";

        $i = 0;
        foreach ($conditions as $key => $value){
            if($i===0){
                $sql = $sql . " WHERE $key=?";
            } else {
                $sql = $sql . " AND $key=?";
            }
            $i++;
        }

        $sql = $sql . " LIMIT 1";
        $stmnt = executeQuery($sql, $conditions);
        $records = $stmnt->get_result()->fetch_assoc();
        return $records;
}

$conditions = [
    'admin' => 0,
    'username' => 'marou'
];

$users = selectOne('users', $conditions);

dd($users);