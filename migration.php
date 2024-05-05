<?php

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "wsd";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create the users table
$sqlCreateTable = "CREATE TABLE user (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Increase length to accommodate hashed password
    gender ENUM('male', 'female') NOT NULL,
    mobile VARCHAR(50),
    designation VARCHAR(50),
    image VARCHAR(250),
    type VARCHAR(250) DEFAULT 'general',
    status ENUM('active', 'pending', 'deleted', '') DEFAULT 'pending',
    authtoken VARCHAR(250) NOT NULL
)";

// Execute the query to create the table
if ($conn->query($sqlCreateTable) === TRUE) {
    echo "Table user created successfully<br>";

    // SQL query to insert one user
    $hash = md5('123456');
    $sqlInsertUser = "INSERT INTO user (first_name, last_name, email, password, gender,type,status, authtoken)
                      VALUES ('Abir', 'Ahmad', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'male','administrator','active', 'abc123xyz')";

    // Execute the query to insert the user
    if ($conn->query($sqlInsertUser) === TRUE) {
        echo "One user inserted successfully";
    } else {
        echo "Error inserting user: " . $conn->error;
    }
} else {
    echo "Error creating table: " . $conn->error;
}

// Close connection
$conn->close();
