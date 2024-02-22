<?php
define('DB_name', 'C:\\Projects\\crud_student/data/db.txt');
function seed()
{
    $students = array(
        array(
            'id' => 1,
            'fname' => 'Galib',
            'lname' => 'Mia',
            'roll' => 10
        ),
        array(
            'id' => 2,
            'fname' => 'Sadia',
            'lname' => 'Meem',
            'roll' => 11
        ),
        array(
            'id' => 3,
            'fname' => 'Afroza',
            'lname' => 'Akter',
            'roll' => 35
        ),
        array(
            'id' => 4,
            'fname' => 'Rostom',
            'lname' => 'Ali',
            'roll' => 45
        ),
        array(
            'id' => 5,
            'fname' => 'Abu',
            'lname' => 'Ali',
            'roll' => 6
        ),

    );

    $data = serialize($students);
    file_put_contents(DB_name, $data, LOCK_EX);
}


function displayData()
{
    $serializeData = file_get_contents(DB_name);
    $students = unserialize($serializeData);
    foreach ($students as $student) {
        echo "<tr>";
        printf("<td>%s %s </td>", $student['fname'], $student['lname']);
        printf("<td>%s</td>", $student['roll']);
        $id = $student['id'];
        printf("<td><a href='/index.php?task=edit&id={$id}'>Edit</a> | <a class='delete' href='/index.php?task=delete&id={$id}'>Delete</a></td>");
        echo "</tr>";
    }
}

function addStudent($fname, $lname, $roll)
{
    $found = false;
    $serializedData = file_get_contents(DB_name);
    $students = unserialize($serializedData);
    $newID = getMax($students) ?? 1;
    foreach($students as $_student){
       if( $_student['roll']==$roll){
        $found = true;
        break;
       }
    }
    if(!$found){
        $student = array(
            'id' => $newID,
            'fname' => $fname,
            'lname' => $lname,
            'roll' => $roll
        );
        array_push($students, $student);
        $data = serialize($students);
        file_put_contents(DB_name, $data, LOCK_EX);
        return true;
    }
    return false;
}


function getMax($students)
{
    $ids = array_column($students, 'id');
    if (!empty($ids)) {
        $maxId = max($ids);
        return $maxId + 1;
    }
    return 1;
}

function getStudentId($id){
    $serializeData = file_get_contents(DB_name);
    $students = unserialize($serializeData);
    foreach($students as $student){
        if($student['id'] == $id){
            return $student;
        }
    }
    return false;
}

function updateStudent($id, $fname, $lname, $roll){
    $found = false;
    $serializedData = file_get_contents(DB_name);
    $students = unserialize($serializedData);
    $newID = getMax($students) ?? 1;
    foreach($students as $_student){
       if( $_student['roll']==$roll && $_student['id'] !=$id){
        $found = true;
        break;
       }
    }
    if(!$found){
        $students[$id-1]['fname'] = $fname;
        $students[$id-1]['lname'] = $lname;
        $students[$id-1]['roll'] = $roll;
        $data = serialize($students);
        file_put_contents(DB_name, $data, LOCK_EX);
        return true;
    }
    return false;
}
function deleteStudent($id){
    $serializeData = file_get_contents(DB_name);
    $students = unserialize($serializeData);
    foreach($students as $offset=>$student){
        if($student['id']==$id){
            unset($students[$offset]);
        }
    }
    $data = serialize($students);
    file_put_contents(DB_name, $data, LOCK_EX);
}

function displayArray()
{
    $serializeData = file_get_contents(DB_name);
    $students = unserialize($serializeData);
    return $students;
}
