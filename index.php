    <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Alkon hinnasto</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <?php
        //require("urlHandler.php");
        //require("handlePriceList.php");
        require("model.php");
        
        $alkoData = initModel();
        $filters = handleRequest();
        $alkoProductTable = generateView($alkoData, $filters, 'products');
        
        $rowsFound = count($alkoData);
        // echo "<h1>Alkon hinnasto $priceListDate Total rows found $rowsFound</h1>";
        echo "<div id=\"tbl-header\" class=\"alert alert-success\" role=\"\">Alkon hinnasto $priceListDate (Total items $rowsFound)</div>";

        // display products table here
        echo $alkoProductTable;
        
        // testXlxs();
        // fetchXlxs($remote_filename_xlsl, $local_filename_xlsl);
        ?>
    </body>
</html>
