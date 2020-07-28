<?php
session_start();
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

// function to return only one record from database table: 
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

// function to create data : 
function create($table, $data){
    global $conn;
    // $sql = "INSERT INTO users SET username=?, admin=?, email=?, password=?"
    $sql = "INSERT INTO $table SET ";
    $i = 0;
    foreach ($data as $key => $value){
        if($i===0){
            $sql = $sql . " $key=?";
        } else {
            $sql = $sql . ", $key=?";
        }
        $i++;
    }
    $stmnt = executeQuery($sql, $data);
    $id = $stmnt->insert_id;
    return $id;
}

// function to update data : 
function update($table, $id,$data){
    global $conn;
    // $sql = "UPDATE users SET username=?, admin=?, email=?, password=? WHERE id=?"
    $sql = "UPDATE $table SET ";
    $i = 0;
    foreach ($data as $key => $value){
        if($i===0){
            $sql = $sql . " $key=?";
        } else {
            $sql = $sql . ", $key=?";
        }
        $i++;
    }
    $sql = $sql . " WHERE id=?";
    $data['id'] = $id;
    $stmnt = executeQuery($sql, $data);
    return $stmnt->affected_rows;
}

// function to delete data : 
function delete($table, $id){
    global $conn;
    // $sql = "DELETE FROM users WHERE id=?"
    $sql = "DELETE FROM $table WHERE id=?";
    
    $data['id'] = $id;
    $stmnt = executeQuery($sql, [$id => $id]);
    return $stmnt->affected_rows;
}