<script>
// JavaScript to set cookies for filters and reload the page
function setCountryFilter() {
    let selected = document.getElementById("country");
    if (selected.selectedIndex == 0) {
        document.cookie = "country=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        window.location = "./index.php?page=0";
    } else {
        document.cookie = "country=" + selected.value;
        window.location = "./index.php?page=0&country=" + selected.value;
    }
}

function setTypeFilter() {
    let selected = document.getElementById("type");
    if (selected.selectedIndex == 0) {
        document.cookie = "type=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        window.location = "./index.php?page=0";
    } else {
        document.cookie = "type=" + selected.value;
        window.location = "./index.php?page=0&type=" + selected.value;
    }
}

function setVolumeFilter() {
    let selected = document.getElementById("volume");
    if (selected.selectedIndex == 0) {
        document.cookie = "volume=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        window.location = "./index.php?page=0";
    } else {
        document.cookie = "volume=" + selected.value;
        window.location = "./index.php?page=0&volume=" + selected.value;
    }
}
</script>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Alkon hinnasto</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <?php
        require_once("model.php");
        require_once("db_initialize.php");
        require_once("controller.php");

        $products = fetchProducts();
        if (count($products) > 0) {
            echo "Number of products: " . count($products);
        } else {
            echo "No products available to count.";
        }
        // Handle request and get filtered data
        $filters = handleRequest();
        $alkoData = initModel($filters);
        $alkoProductTable = generateView($alkoData, $filters, 'products');

        // Pagination logic
        $currpage = isset($_GET['page']) ? (int)$_GET['page'] : 0;
        $prevpage = $currpage > 0 ? $currpage - 1 : 0;
        $nextpage = $currpage + 1;

        $country = isset($_COOKIE['country']) ? "&country=" . $_COOKIE['country'] : "";
        $type = isset($_COOKIE['type']) ? "&type=" . $_COOKIE['type'] : "";
        $volume = isset($_COOKIE['volume']) ? "&volume=" . $_COOKIE['volume'] : "";

        $filtersUrl = $country . $type . $volume;

        // Header
        echo "<div id=\"tbl-header\" class=\"alert alert-success\" role=\"alert\">Alkon hinnasto (Total items: " . count($alkoData) . ")</div>";

        // Navigation buttons
        echo "<div class='container mb-3'>";
        echo "<input type='button' class='btn btn-primary' onClick=\"location.href='./index.php?page=" . $prevpage . $filtersUrl . "'\" value='Previous'>";
        echo "<input type='button' class='btn btn-primary ml-2' onClick=\"location.href='./index.php?page=" . $nextpage . $filtersUrl . "'\" value='Next'>";
        echo "</div>";

        // Filter dropdowns
        echo "<div class='container mb-3'>";
        echo "<form>";
        echo "<select class='form-control mb-2' id='country'><option value=''>--- Select Country ---</option>
              <option value='Espanja'>Spain</option>
              <option value='Suomi'>Finland</option>
              </select>";
        echo "<input type='button' class='btn btn-success mb-2' onClick='setCountryFilter()' value='Set Country Filter'>";

        echo "<select class='form-control mb-2' id='type'><option value=''>--- Select Item Type ---</option>
              <option value='punaviinit'>Punaviinit</option>
              <option value='viskit'>Viskit</option>
              <option value='roseeviinit'>Roseeviinit</option>
              </select>";
        echo "<input type='button' class='btn btn-success mb-2' onClick='setTypeFilter()' value='Set Type Filter'>";

        echo "<select class='form-control mb-2' id='volume'><option value=''>--- Select Volume ---</option>
              <option value='0,75 l'>0,75 l</option>
              <option value='1 l'>1 l</option>
              </select>";
        echo "<input type='button' class='btn btn-success mb-2' onClick='setVolumeFilter()' value='Set Volume Filter'>";
        echo "</form>";
        echo "</div>";

        // Display product table
        echo $alkoProductTable;

        // Pagination logic (at the bottom of the file)
        $currpage = isset($_GET['page']) ? (int)$_GET['page'] : 0;
        $prevpage = $currpage > 0 ? $currpage - 1 : 0;
        $nextpage = $currpage + 1;

        $country = isset($_COOKIE['country']) ? "&country=" . $_COOKIE['country'] : "";
        $type = isset($_COOKIE['type']) ? "&type=" . $_COOKIE['type'] : "";
        $volume = isset($_COOKIE['volume']) ? "&volume=" . $_COOKIE['volume'] : "";

        $filtersUrl = $country . $type . $volume;

        // Header
        echo "<div id=\"tbl-header\" class=\"alert alert-success\" role=\"alert\">Alkon hinnasto (Total items: " . count($alkoData) . ")</div>";

        // Navigation buttons
        echo "<div class='container mb-3'>";
        echo "<input type='button' class='btn btn-primary' onClick=\"location.href='./index.php?page=" . $prevpage . $filtersUrl . "'\" value='Previous'>";
        echo "<input type='button' class='btn btn-primary ml-2' onClick=\"location.href='./index.php?page=" . $nextpage . $filtersUrl . "'\" value='Next'>";
        echo "</div>";
        ?>
    </body>
</html>