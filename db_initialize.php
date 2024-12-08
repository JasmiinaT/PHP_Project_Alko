<?php
// Increase memory and execution time limits for large files
ini_set('memory_limit', '512M');  // Set memory limit to 512MB or higher
set_time_limit(300);  // Set execution time limit to 5 minutes
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root";  // Your database username
$password = "";  // Your database password
$dbname = "alko_db";  // Your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Create table with all 30 columns (you may need to adjust data types based on your CSV)
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS alko_price_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Numero INT NOT NULL,
    Nimi VARCHAR(255) NOT NULL,
    Valmistaja VARCHAR(255) NOT NULL,
    Pullokoko VARCHAR(100) NOT NULL,
    Hinta DECIMAL(10, 2) NOT NULL,
    Litrahinta DECIMAL(10, 2) NOT NULL,
    Uutuus VARCHAR(50),
    Hinnastojärjestyskoodi VARCHAR(50),
    Tyyppi VARCHAR(100),
    Alatyyppi VARCHAR(100),
    Erityisryhmä VARCHAR(100),
    Oluttyyppi VARCHAR(100),
    Valmistusmaa VARCHAR(100),
    Alue VARCHAR(100),
    Vuosikerta VARCHAR(10),
    Etikettimerkintöjä TEXT,
    Huomautus TEXT,
    Rypäleet TEXT,
    Luonnehdinta TEXT,
    Pakkaustyyppi VARCHAR(100),
    Suljentatyyppi VARCHAR(100),
    Alkoholiprosentti DECIMAL(5, 2),
    Hapot_g_l DECIMAL(5, 2),
    Sokeri_g_l DECIMAL(5, 2),
    Kantavierrep_percent DECIMAL(5, 2),
    Väri_EBC DECIMAL(5, 2),
    Katkerot_EBU DECIMAL(5, 2),
    Energia INT,
    Valikoima VARCHAR(100),
    EAN VARCHAR(100)
)";
$conn->query($sqlCreateTable);

// Step 2: Import the data from the CSV file
$csvFile = "Alko_data_300rows.csv";  // Update the path if necessary

// Checking if the file exists
if (!file_exists($csvFile)) {
    die("Error: File not found: " . $csvFile);
}

// Open the CSV file for reading
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Skip the first few rows (e.g., notes, extra headers)
    while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
        // Check if the header is found, then stop skipping
        if ($row[0] === "Numero" && $row[1] === "Nimi") {
            break;
        }
    }

    // Step 3: Loop through CSV rows and build the query string directly
    while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
        // Skip empty rows or rows with missing data
        if (empty($row)) continue;

        // Map CSV data to variables with checks for empty values
        $numero = (int)$row[0];
        $nimi = $conn->real_escape_string($row[1] ?? '');  // Escape special characters
        $valmistaja = $conn->real_escape_string($row[2] ?? '');
        $pullokoko = $conn->real_escape_string($row[3] ?? '');
        $hinta = (float)str_replace(',', '.', $row[4] ?? '0'); // Replace commas and set default if empty
        $litrahinta = (float)str_replace(',', '.', $row[5] ?? '0');
        $uutuus = $conn->real_escape_string($row[6] ?? '');
        $hinnastojarjestyskoodi = $conn->real_escape_string($row[7] ?? '');
        $tyyppi = $conn->real_escape_string($row[8] ?? '');
        $alatyyppi = $conn->real_escape_string($row[9] ?? '');
        $erityisryhma = $conn->real_escape_string($row[10] ?? '');
        $oluttyyppi = $conn->real_escape_string($row[11] ?? '');
        $valmistusmaa = $conn->real_escape_string($row[12] ?? '');
        $alue = $conn->real_escape_string($row[13] ?? '');
        $vuosikerta = $conn->real_escape_string($row[14] ?? '');
        $etikettimerkintoja = $conn->real_escape_string($row[15] ?? '');
        $huomautus = $conn->real_escape_string($row[16] ?? '');
        $rypaeleet = $conn->real_escape_string($row[17] ?? '');
        $luonnehdinta = $conn->real_escape_string($row[18] ?? '');
        $pakkautyyppi = $conn->real_escape_string($row[19] ?? '');
        $suljentatyyppi = $conn->real_escape_string($row[20] ?? '');
        $alkoholiprosentti = isset($row[21]) && $row[21] !== '' ? (float)str_replace(',', '.', $row[21]) : null;
        $hapot_gl = isset($row[22]) && $row[22] !== '' ? (float)$row[22] : null;
        $sokeri_gl = isset($row[23]) && $row[23] !== '' ? (float)$row[23] : null;
        $kantavierrep_percent = isset($row[24]) && $row[24] !== '' ? (float)$row[24] : null;
        $vari_ebc = isset($row[25]) && $row[25] !== '' ? (float)$row[25] : null;
        $katkerot_ebu = isset($row[26]) && $row[26] !== '' ? (float)$row[26] : null;
        $energia = isset($row[27]) && $row[27] !== '' ? (int)$row[27] : null;
        $valikoima = $conn->real_escape_string($row[28] ?? '');
        $ean = $conn->real_escape_string($row[29] ?? '');

        // Build the INSERT SQL query string
        $sql = "INSERT INTO alko_price_list (
            Numero, Nimi, Valmistaja, Pullokoko, Hinta, Litrahinta, Uutuus, Hinnastojärjestyskoodi, Tyyppi, Alatyyppi, Erityisryhmä, 
            Oluttyyppi, Valmistusmaa, Alue, Vuosikerta, Etikettimerkintöjä, Huomautus, Rypäleet, Luonnehdinta, Pakkaustyyppi, 
            Suljentatyyppi, Alkoholiprosentti, Hapot_g_l, Sokeri_g_l, Kantavierrep_percent, Väri_EBC, Katkerot_EBU, Energia, 
            Valikoima, EAN
        ) VALUES (
            '$numero', '$nimi', '$valmistaja', '$pullokoko', '$hinta', '$litrahinta', '$uutuus', '$hinnastojarjestyskoodi', 
            '$tyyppi', '$alatyyppi', '$erityisryhma', '$oluttyyppi', '$valmistusmaa', '$alue', '$vuosikerta', '$etikettimerkintoja', 
            '$huomautus', '$rypaeleet', '$luonnehdinta', '$pakkautyyppi', '$suljentatyyppi', '$alkoholiprosentti', '$hapot_gl', 
            '$sokeri_gl', '$kantavierrep_percent', '$vari_ebc', '$katkerot_ebu', '$energia', '$valikoima', '$ean'
        )";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully.<br>";
        } else {
            echo "Error inserting record: " . $conn->error . "<br>";
        }
    }

    fclose($handle);
    echo "Data imported successfully!";
} else {
    echo "Error opening file.";
}

// Close the connection
$conn->close();
?>