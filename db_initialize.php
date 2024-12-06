<?php
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
$csvFile = "./data/alkon-hinnasto.csv";  // Update the path if necessary

if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Skip the first few rows (e.g., notes, extra headers)
    while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
        // Check if the header is found, then stop skipping
        if ($row[0] === "Numero" && $row[1] === "Nimi") {
            break;
        }
    }

    // Step 3: Prepare SQL statement to insert all columns (30 columns)
    $sqlInsert = "INSERT INTO alko_price_list (
        Numero, Nimi, Valmistaja, Pullokoko, Hinta, Litrahinta, Uutuus, Hinnastojärjestyskoodi,
        Tyyppi, Alatyyppi, Erityisryhmä, Oluttyyppi, Valmistusmaa, Alue, Vuosikerta,
        Etikettimerkintöjä, Huomautus, Rypäleet, Luonnehdinta, Pakkaustyyppi, Suljentatyyppi,
        Alkoholiprosentti, Hapot_g_l, Sokeri_g_l, Kantavierrep_percent, Väri_EBC, Katkerot_EBU,
        Energia, Valikoima, EAN
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sqlInsert);

    // Bind parameters (adjust based on the data types for each column)
    $stmt->bind_param(
        "isssddssssssssssssssssddds", 
        $numero, $nimi, $valmistaja, $pullokoko, $hinta, $litrahinta, $uutuus, $hinnastojarjestyskoodi,
        $tyyppi, $alatyyppi, $erityisryhma, $oluttyyppi, $valmistusmaa, $alue, $vuosikerta,
        $etikettimerkintoja, $huomautus, $rypaeleet, $luonnehdinta, $pakkautyyppi, $suljentatyyppi,
        $alkoholiprosentti, $hapot, $sokeri, $kantavierrep, $vari_ebc, $katkerot_ebu,
        $energia, $valikoima, $ean
    );

    // Step 4: Read the CSV rows and insert them into the database
    while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
        // Skip empty rows or rows with missing data
        if (empty($row) || count($row) < 30) continue;

        // Map CSV data to variables
        $numero = (int)$row[0];
        $nimi = $row[1];
        $valmistaja = $row[2];
        $pullokoko = $row[3];
        $hinta = (float)str_replace(',', '.', $row[4]);
        $litrahinta = (float)str_replace(',', '.', $row[5]);
        $uutuus = $row[6];
        $hinnastojarjestyskoodi = $row[7];
        $tyyppi = $row[8];
        $alatyyppi = $row[9];
        $erityisryhma = $row[10];
        $oluttyyppi = $row[11];
        $valmistusmaa = $row[12];
        $alue = $row[13];
        $vuosikerta = $row[14];
        $etikettimerkintoja = $row[15];
        $huomautus = $row[16];
        $rypaeleet = $row[17];
        $luonnehdinta = $row[18];
        $pakkautyyppi = $row[19];
        $suljentatyyppi = $row[20];
        $alkoholiprosentti = isset($row[21]) ? (float)str_replace(',', '.', $row[21]) : null;
        $hapot = isset($row[22]) ? (float)$row[22] : null;
        $sokeri = isset($row[23]) ? (float)$row[23] : null;
        $kantavierrep = isset($row[24]) ? (float)$row[24] : null;
        $vari_ebc = isset($row[25]) ? (float)$row[25] : null;
        $katkerot_ebu = isset($row[26]) ? (float)$row[26] : null;
        $energia = isset($row[27]) ? (int)$row[27] : null;
        $valikoima = $row[28];
        $ean = $row[29];

        // Execute the prepared statement
        $stmt->execute();
    }

    fclose($handle);
    echo "Data imported successfully!";
} else {
    echo "Error opening file.";
}

// Close the connection
$conn->close();
?>
