<?php

// SETTINGS 
$filename = "data/alkon-hinnasto-ascii.csv";
$filename_xlxs = "data/alkon-hinnasto.xlsx";
$priceListDate = "14.09.2020";

// // CREATING A DATABASE
// $servername = "localhost";
// $username = "Group7";  // Your database username
// $password = "Koodaus1";  // Your database password
// $dbname = "alko_db"; 

// $pdo = null;

// try {
//     // create database
//     $pdo = new PDO("mysql:host=$servername", $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     // $conn->exec($sql);  // Execute the SQL to create the database
//     // echo "<p>Database created successfully</p>";
//     } catch (PDOException $e) {
//         die("Database connection failed: " . $e->getMessage());
//     }

// // PAGE SETTINGS 
// $rows_per_page = 25;

// COLUMNS THAT ARE DISPLAYED TO THE CUSTOMER ON THE LIST
$columns2Include = [ 
    "Numero",
    "Nimi",
    "Valmistaja",
    "Pullokoko",
    "Hinta",
    "Litrahinta",
    "Tyyppi",
    "Valmistusmaa",
];


/* all columns listed below
 * Numero;
 * Nimi;
 * Valmistaja;
 * Pullokoko;
 * Hinta;
 * Litrahinta;
 * Uutuus;
 * Hinnastojärjestyskoodi;
 * Tyyppi;
 * Alatyyppi;
 * Erityisryhmä;
 * Oluttyyppi;
 * Valmistusmaa;
 * Alue;
 * Vuosikerta;
 * Etikettimerkintöjä;
 * Huomautus;
 * Rypäleet;
 * Luonnehdinta;
 * Pakkaustyyppi;
 * Suljentatyyppi;
 * Alkoholi-%;
 * Hapot g/l;
 * Sokeri g/l;
 * Kantavierrep-%;
 * Väri EBC;
 * Katkerot EBU;
 * Energia kcal/100 ml;
 * Valikoima;
 * EAN
 */
