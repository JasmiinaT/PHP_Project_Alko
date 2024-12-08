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

        require_once("db_initialize.php");

        // Get filters from cookies (if set)
        $countryFilter = isset($_COOKIE['country']) ? $_COOKIE['country'] : '';
        $typeFilter = isset($_COOKIE['type']) ? $_COOKIE['type'] : '';

        // Define the base query
        $query = "SELECT * FROM alko_price_list";

        // Add filtering logic if country or type is set
        if ($countryFilter) {
            $query .= " WHERE Valmistusmaa = '$countryFilter'";
        }

        if ($typeFilter) {
            $query .= ($countryFilter ? " AND " : " WHERE ") . "Tyyppi = '$typeFilter'";
        }

        // Execute the query
        $result = $conn->query($query);
        $alkoData = $result->fetchAll(PDO::FETCH_ASSOC); // Fetch data as associative array

        $rowsFound = count($alkoData);
        echo "<div id=\"tbl-header\" class=\"alert alert-success\" role=\"\">Alkon hinnasto (Total items $rowsFound)</div>";

        // Function to generate table view
        function generateView($data, $filters, $tableName) {
            if (empty($data)) {
                return "<p>No data found matching the current filters.</p>";
            }

            // Start the table
            $html = "<table class='table table-striped'>";
            $html .= "<thead><tr>";

            // Table headers
            $headers = array_keys($data[0]);
            foreach ($headers as $header) {
                $html .= "<th>" . htmlspecialchars($header) . "</th>";
            }
            $html .= "</tr></thead><tbody>";

            // Table rows
            foreach ($data as $row) {
                $html .= "<tr>";
                foreach ($row as $value) {
                    $html .= "<td>" . htmlspecialchars($value) . "</td>";
                }
                $html .= "</tr>";
            }

            $html .= "</tbody></table>";
            return $html;
        }

        // Display the table
        echo generateView($alkoData, [], 'products');
?>
    </body>
</html>