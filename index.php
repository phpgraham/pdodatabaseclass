<?php
require('Classes/database.php');

$database = new Database();

//cretae db table
$database->query("CREATE TABLE mytable (
    ID int(11) NOT NULL AUTO_INCREMENT,
    FName varchar(50) NOT NULL,
    LName varchar(50) NOT NULL,
    Age int(11) NOT NULL,
    Gender enum('male','female') NOT NULL,
    PRIMARY KEY (ID)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
$database->execute();

//single db entry
$database->query('INSERT INTO mytable (FName, LName, Age, Gender) VALUES (:fname, :lname, :age, :gender)');

$database->bind(':fname', 'John');
$database->bind(':lname', 'Smith');
$database->bind(':age', '24');
$database->bind(':gender', 'male');

$database->execute();
echo 'Last ID inserted: '.$database->lastInsertId().'<br />';

//multiple db transactions
$database->beginTransaction();
$database->query('INSERT INTO mytable (FName, LName, Age, Gender) VALUES (:fname, :lname, :age, :gender)');

$database->bind(':fname', 'Jenny');
$database->bind(':lname', 'Smith');
$database->bind(':age', '23');
$database->bind(':gender', 'female');

$database->execute();

$database->bind(':fname', 'Jilly');
$database->bind(':lname', 'Smith');
$database->bind(':age', '25');
$database->bind(':gender', 'female');

$database->execute();
echo 'Last ID inserted: '.$database->lastInsertId().'<br />';
$database->endTransaction();

//select single record
$database->query('SELECT FName, LName, Age, Gender FROM mytable WHERE FName = :fname');
$database->bind(':fname', 'Jenny');
$row = $database->single();
echo "<pre>";
print_r($row);
echo "</pre>";

//select multiple records
$database->query('SELECT FName, LName, Age, Gender FROM mytable WHERE LName = :lname');
$database->bind(':lname', 'Smith');
$rows = $database->resultset();
echo "<pre>";
print_r($rows);
echo "</pre>";
echo 'Row Count: '.$database->rowCount();
