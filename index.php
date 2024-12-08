<script>
// a js addition by Olli who sets cookie(s) by drop down list if 'set filter' button is pressed
// if the first is selected it will delete the cookie(s)
// (js solution because it may not be possible to read such values in php without posting?)
function setCountryFilter() {
    // gets the selection from dropdown list
    let index = document.getElementById("country").selectedIndex;
    let selected = document.getElementById("country");

    // COUNTRY FILTER
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
function setTypeFilter() {
    // gets the selection from dropdown list
    let index = document.getElementById("type").selectedIndex;
    let selected = document.getElementById("type");

    // TYPE FILTER
    if (index == 0) {
        document.cookie = "type=; expires = Thu, 01 Jan 1970 00:00:00 GMT";
        window.location = "./index.php?page=0"; // and the page is reloaded to the first page 
    } else {
        document.cookie = "type=" + selected.value;
        window.location = "./index.php?page=0&type="+selected.value; // and the first paget is loaded with get params (because the cookie is not yet set until reloaded)
    }

}
function setVolumeFilter() {
    //gets the selection from dropdown list
    let index = document.getElementById("volume").selectedIndex;
    let selected = document.getElementById("volume");

    // VOLUME FILTER
    if(index == 0) {
        document.cookie = "volume=; expires = Thu, 01 Jan 1970 00:00:00 GMT";
        window.location = "./index.php?page=0"; // and the page is reloaded to the first page
    } else {
        document.cookie = "volume=" + selected.value;
        window.location = "./index.php?page=0&volume="+selected.value; // and the first page is loaded with get params (because cookie is not yet set until reloaded)
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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
    </head>
    <body>
        <h1>- Alko Hinnasto -</h1>
        <h3>By Jasmiina Tauriainen, Adelaine Herranen, Valtteri Lankinen, Miko Kosola</h3>
        <?php
        //require("urlHandler.php");
        //require("handlePriceList.php");
        require("model.php");
        require_once("controller.php");
        require_once("db_initialize.php");
        $alkoData = initModel();
        $filters = handleRequest();
        $alkoProductTable = generateView($alkoData, $filters, 'products');
        
        $rowsFound = count($alkoData);
        // echo "<h1>Alkon hinnasto $priceListDate Total rows found $rowsFound</h1>";
        echo "<div id=\"tbl-header\" class=\"alert alert-success\" role=\"\">Alkon hinnasto $priceListDate (Total items $rowsFound)</div>";

        // --- this is the ugly addition by Olli (this or similar should be in view according to MVC architecture) ------------------------
        $currpage = isset($filters['PAGE']) ? $filters['PAGE'] : 0;
        $prevpage = $currpage > 0 ? $currpage - 1 : 0; // previous page (0 is the minimum)
        $nextpage = $currpage + 1 ;   // next page (no max checked ;) )
        $country = isset($_COOKIE['country']) ? "&country=".$_COOKIE['country'] : "";
        $type = isset($_COOKIE['type']) ? "&type=".$_COOKIE['type'] : "";
        $volume = isset($_COOKIE['volume']) ? "&volume=".$_COOKIE['volume'] : "";
        // Combine filters
        $filters = $country . $type . $volume;
        
        // Naviagtion

        echo "<input type=button onClick=\"location.href='./index.php?page=" . $prevpage . $filters . "'\" value='prev'>";
        echo "<input type=button onClick=\"location.href='./index.php?page=" . $nextpage . $filters . "'\" value='next'>";

        // Country input

        echo "<input type=button onClick=setCountryFilter() value='set country filter'";
        
        echo "<form><select name='country' id='country'><option value='sel'>--- select country ---</option>
        <option value='Espanja'>Spain</option>
        <option value='Suomi'>Finland</option>
        <option value='Argentiina'>Argetiina</option>
        <option value='Australia'>Australia</option>
        <option value='Italia'>Italia</option>
        </select></form>";

        // Type input

        echo "<input type=button onClick=setTypeFilter() value='set type filter'";
        
        echo "<form><select name='type' id='type'><option value='sel'>--- select item type---</option>
        <option value='punaviinit'>Punaviinit</option>
        <option value='viskit'>Viskit</option>
        <option value='valkoviinit'>Valkoviinit</option>
        <option value='rommit'>Rommit</option>
        <option value='konjakit'>Konjakit</option>
        <option value='siiderit'>Siiderit</option>
        <option value='alkoholittomat'>Alkoholittomat</option>
        </select></form>";

        // Volume input

        echo "<input type=button onClick=setVolumeFilter() value='set volume filter'";

        echo "<form><select name='volume' id='volume'><option value='sel'>--- selected item volume---</option>
        <option value='0,75 l'>0,75 l</option>
        <option value='1 l'>1 l</option>
        <option value='1,5 l'>1,5 l</option>
        <option value='3 l'>3 l</option>
        <option value='5 l'>5 l</option>
        <option value='15 l'>15 l</option>
        </select></form>";
        // --- end of the ugly addition by Olli (this or similar should be in view according to MVC architecture) -------------------------


        // display products table here
        echo $alkoProductTable;
   
        //testXlxs();
        //fetchXlxs($remote_filename_xlsl, $local_filename_xlsl);
        ?>
    </body>
</html>