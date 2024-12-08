<?php
require_once('config.php');

// CREATE DATABASE CONNECTION
function create_db_connection() {
    $servername = "localhost";
    $username = "root";  // Your database username
    $password = "";      // Your database password
    $dbname = "alko_db";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

// CHECK FOR DATABASE CONNECTION 
// $conn = create_db_connection();
// if ($conn) {
//     echo "Database connected successfully.<br>";
// } else {
//     echo "Database connection failed.<br>";
// }

// CREATE THE TABLE
function create_table() {
    $conn = create_db_connection();
    $sql = "
        CREATE TABLE IF NOT EXISTS alko_price_list (
            id INT AUTO_INCREMENT PRIMARY KEY,
            Numero VARCHAR(50),
            Nimi VARCHAR(255),
            Valmistaja VARCHAR(255),
            Pullokoko VARCHAR(50),
            Hinta FLOAT,
            Litrahinta FLOAT,
            Tyyppi VARCHAR(100),
            Valmistusmaa VARCHAR(100),
            Vuosikerta VARCHAR(50),
            Alkoholi_percent FLOAT,
            Energia FLOAT
        )
    ";
    $conn->exec($sql);
    // echo "Table created successfully.<br>";
}

// READ DATA FROM CSV FILE
function read_csv_data($csvFile) {
    $rows = [];
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        // Skip the first three lines (headers or metadata)
        fgetcsv($handle, 1000, ";");
        fgetcsv($handle, 1000, ";");
        fgetcsv($handle, 1000, ";");

        // Read column headers
        $columns = fgetcsv($handle, 1000, ";");

        // Read data rows
        // Read the data rows
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // Extract only the required 11 columns
            $filtered_row = [
                $data[0],   // Numero
                $data[1],   // Nimi
                $data[2],   // Valmistaja
                $data[3],   // Pullokoko
                $data[4],   // Hinta
                $data[5],   // Litrahinta
                $data[8],   // Tyyppi
                $data[12],  // Valmistusmaa
                $data[14],  // Vuosikerta
                $data[21],  // Alkoholi_percent
                $data[22]   // Energia
            ];
        // Only add valid rows
        if (count($filtered_row) === 11) {
            $rows[] = $filtered_row;
        } else {
            echo "Skipping invalid row: " . print_r($data, true) . "<br>";
        }
    }
    fclose($handle);
    }
    return $rows;
}

// INSERT DATA INTO THE TABLE
function insert_data($data) {
    $conn = create_db_connection();

    $sql = $conn->prepare("
        INSERT INTO alko_price_list 
        (Numero, Nimi, Valmistaja, Pullokoko, Hinta, Litrahinta, Tyyppi, Valmistusmaa, Vuosikerta, Alkoholi_percent, Energia)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    try {
        $conn->beginTransaction();

        foreach ($data as $row) {
            if (count($row) === 11) {  // Double-check row length
                $sql->execute($row);
            } else {
                echo "Invalid row skipped: " . print_r($row, true) . "<br>";
            }
        }

        $conn->commit();
        // echo "Data imported successfully!<br>";
    } catch (PDOException $e) {
        $conn->rollBack();
        die("Database error: " . $e->getMessage());
    }

}
// MAIN SCRIPT EXECUTION
$csvFile = "data/alkon-hinnasto-ascii.csv";  // Path to your CSV file

if (!file_exists($csvFile)) {
    die("Error: File not found: " . $csvFile);
}

try {
    // Step 1: Create the table
    create_table();

    // Step 2: Read data from the CSV
    $table_data = read_csv_data($csvFile);
    // echo "Read " . count($table_data) . " rows from the CSV file.<br>";

    // Step 3: Insert data into the database
    insert_data($table_data);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>