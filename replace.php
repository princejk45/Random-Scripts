<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";
 
$old_url = "https://url.com";
$new_url = "https://newurl.com";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all tables and columns with relevant data types
$sql = "SELECT TABLE_NAME, COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = '$dbname'
          AND DATA_TYPE IN ('varchar', 'text', 'char', 'mediumtext', 'longtext')";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $table = $row['TABLE_NAME'];
        $column = $row['COLUMN_NAME'];
        $update_sql = "UPDATE $table
                       SET $column = REPLACE($column, '$old_url', '$new_url')
                       WHERE $column LIKE '%$old_url%'";
        if ($conn->query($update_sql) === TRUE) {
            echo "Updated $table.$column successfully.\n";
        } else {
            echo "Error updating $table.$column: " . $conn->error . "\n";
        }
    }
} else {
    echo "No relevant columns found.\n";
}

$conn->close();
?>
