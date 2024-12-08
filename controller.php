<?php
require_once("model.php");

$products = fetchProducts();

if (!is_array($products)) {
    echo "Error: \$products is not an array.<br>";
    $products = []; // Fallback to an empty array
}

foreach ($products as $product) {
    echo "Product Name: " . htmlspecialchars($product['Nimi']) . "<br>";
}

function handleRequest() {
    $filters = [];
    if (isset($_COOKIE['country']) && $_COOKIE['country'] != "") {
        $filters['country'] = $_COOKIE['country'];
    }
    if (isset($_COOKIE['type']) && $_COOKIE['type'] != "") {
        $filters['type'] = $_COOKIE['type'];
    }
    if (isset($_COOKIE['volume']) && $_COOKIE['volume'] != "") {
        $filters['volume'] = $_COOKIE['volume'];
    }
    return $filters;
}

function generateView($data, $filters, $tableName) {
    $html = "<table class='table table-bordered'>";
    $html .= "<thead><tr>";
    $html .= "<th>#</th><th>Nimi</th><th>Valmistaja</th><th>Pullokoko</th><th>Hinta (€)</th><th>Tyyppi</th><th>Valmistusmaa</th>";
    $html .= "</tr></thead>";
    $html .= "<tbody>";

    foreach ($data as $row) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($row['id']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['Nimi']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['Valmistaja']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['Pullokoko']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['Hinta']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['Tyyppi']) . "</td>";
        $html .= "<td>" . htmlspecialchars($row['Valmistusmaa']) . "</td>";
        $html .= "</tr>";
    }

    $html .= "</tbody></table>";
    return $html;
};
?>