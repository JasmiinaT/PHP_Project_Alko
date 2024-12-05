<script>
// a js addition by Olli who sets cookie(s) by drop down list if 'set filter' button is pressed
// if the first is selected it will delete the cookie(s)
// (js solution because it may not be possible to read such values in php without posting?)
function setCookies() {
    // gets the selection from dropdown list
    let index = document.getElementById("country").selectedIndex;
    let selected = document.getElementById("country");
    // if the first is selected... 
    if(index==0){
        document.cookie = "country= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"; //  --> cookie is deleted by setting it's value to "" and expries to somewhere in the past 
        window.location = "./index.php?page=0"; // and the page is reloaded to the first page
    // otherwise...
    }else{
        document.cookie = "country="+selected.value; // cookie is set and no expiration --> it will expire when browser is closed
        window.location = "./index.php?page=0&country="+selected.value; // and the first paget is loaded with get params (because the cookie is not yet set until reloaded)
    }
}
</script>
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
        require_once("controller.php");
        $alkoData = initModel();
        $filters = handleRequest();
        $alkoProductTable = generateView($alkoData, $filters, 'products');
        
        $rowsFound = count($alkoData);
        // echo "<h1>Alkon hinnasto $priceListDate Total rows found $rowsFound</h1>";
        echo "<div id=\"tbl-header\" class=\"alert alert-success\" role=\"\">Alkon hinnasto $priceListDate (Total items $rowsFound)</div>";

        // --- this is the ugly addition by Olli (this or similar should be in view according to MVC architecture) ------------------------
        $currpage = isset($filters['PAGE']) ? $filters['PAGE'] : 0;
        $prevpage = $currpage > $filters['LIMIT'] ? $currpage-$filters['LIMIT'] : 0; // previous page (0 is the minimum)
        $nextpage = $currpage+$filters['LIMIT'];    // next page (no max checked ;) )
        $country = isset($_COOKIE['country']) ? "&country=".$_COOKIE['country'] : "";
        echo "<input type=button onClick=\"location.href='./index.php?page=".$prevpage.$country."'\" value='prev'>";
        echo "<input type=button onClick=\"location.href='./index.php?page=".$nextpage.$country."'\" value='next'>";
        echo "<input type=button onClick=setCookies() value='set filter'";
        echo "<form><select name='country' id='country'><option value='sel'>--- select country ---</option><option value='Espanja'>Spain</option><option value='Suomi'>Finland</option></select></form>";
        // --- end of the ugly addition by Olli (this or similar should be in view according to MVC architecture) -------------------------


        // display products table here
        echo $alkoProductTable;
   
        //testXlxs();
        //fetchXlxs($remote_filename_xlsl, $local_filename_xlsl);
        ?>
    </body>
</html>