<?php

error_reporting(0);

require('mysql.class.php');

// Connect to the database (named test) using PDO interface
$conn = new PDO('mysql:host=localhost;dbname=test', 'root', '');

// Declare a MySQL class object
$sql = new MySQL($conn);

/* Table Structure used in all the examples:

   |-------------------------|
   | Column  |  Type         |
   |-------------------------|
   | Name    |  VARCHAR (50) |
   |-------------------------|
   | Age     |  INT (3)      |
   |-------------------------|
   
   Table Name: test1
*/

// Insert

$sql->put(
        array(
            'name' => 'Mike',
            'age'  => 42
        ),
       'test1'
    );

// OR

$sql->put(
        array('Mike', 42),
       'test1'
    );
