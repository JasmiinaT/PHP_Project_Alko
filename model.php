<?php

require_once("config.php");
require_once("view.php");
require_once("controller.php");

// setLocale(LC_ALL, 'fi_FI:UTF-8');
$cs = ini_get("default_charset");
ini_set("auto_detect_line_endings", true);

// vars
$columnNames = [];
$columnNamesMap = [];
$alkoData = [];

function readPriceList($filename) {
    // return values as global data
    global $priceListDate, $columnNames; 
    
    $row = 0;
    $alkoDataIndex = 0;
    $alkoData = [];
    
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if( $row === 0 ) {
                // Alkon hinnasto xx.xx.xxxx                
                $key = "Alkon hinnasto ";                
                $keyLen = strlen($key);
                
                if( $key == substr($data[0], 0, strlen($key)) ) {
                    $priceListDate = substr($data[0],strlen($key));
                } else {
                    ;
                }
            } else if( $row === 1 ) {
                // Huom! ...- skipping
                ;
            } else if( $row === 2 ) {
                // empty line - skipping
                ;
            } else if ( $row === 3 ) {
                // header names
                $columnNames = $data;
            } else {
                // normal rows starts here
                $alkoData[$alkoDataIndex] = $data;
                $alkoDataIndex++;
            }
            $row++;
        }
        fclose($handle);
    }    
    return $alkoData;
}

function createColumnNamesMap($cn) {
    $cnMap = [];
    for($i = 0; $i < count($cn); $i++) {
        $cnMap[$cn[$i]] = $i;
    }
    return $cnMap;
}

function initModel() {
    global $alkoData, $columnNames, $columnNamesMap, $filename;
    
    $alkoData = readPriceList($filename);
    $columnNamesMap = createColumnNamesMap($columnNames);
        
    return $alkoData;
}

// Install 
require_once(__DIR__.'/vendor/shuchkin/simplexlsx/src/SimpleXLSX.php');
function testXlxs() {
    global $filename_xlxs;
    
    if ( $xlsx = SimpleXLSX::parse($filename_xlxs) ) {
            print_r( $xlsx->rows() );
    } else {
        $msg = "Error:".$filename_xlxs;
        echo $msg;
        echo SimpleXLSX::parseError();
    }    
}



$remote_filename_xlsl ="https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx";
$local_filename_xlsl = "alko.xlsl";

function fetchXlxs($remote_filename_xlsl, $local_filename_xlsl) {
    $xlslContent = file_get_contents($remote_filename_xlsl);
    if($xlslContent != false) {
        echo ("fetch success..saving");
        $bytesWritten = file_put_contents($local_filename_xlsl , $xlslContent);
        if($bytesWritten != false) {
            echo "$bytesWritten bytes written to $local_filename_xlsl";
        }
        else {
            echo "error saving the file content";
        }
    }
    else {
        echo "failed";
    }
}
