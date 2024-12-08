<?php
require_once("db_config.php");
require_once("db_initialize.php");

function fetchProductsWithPagination($filters, $page = 0, $perPage = 25) {
    $pdo = create_db_connection();
    
    $whereClauses = [];
    $queryParams = [];

    if (!empty($filters['country'])) {
        $whereClauses[] = "Valmistusmaa = :country";
        $queryParams[':country'] = $filters['country'];
    }
    if (!empty($filters['type'])) {
        $whereClauses[] = "Tyyppi = :type";
        $queryParams[':type'] = $filters['type'];
    }
    if (!empty($filters['volume'])) {
        $whereClauses[] = "Pullokoko = :volume";
        $queryParams[':volume'] = $filters['volume'];
    }

    $whereSQL = count($whereClauses) > 0 ? "WHERE " . implode(" AND ", $whereClauses) : "";
    $offset = $page * $perPage;
    $query = "SELECT * FROM alko_price_list $whereSQL LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);

    foreach ($queryParams as $param => $value) {
        $stmt->bindValue($param, $value);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countTotalProducts($filters) {
    $pdo = create_db_connection();

    $whereClauses = [];
    $queryParams = [];

    // Add filters to the WHERE clause dynamically
    if (!empty($filters['country'])) {
        $whereClauses[] = "Valmistusmaa = :country";
        $queryParams[':country'] = $filters['country'];
    }
    if (!empty($filters['type'])) {
        $whereClauses[] = "Tyyppi = :type";
        $queryParams[':type'] = $filters['type'];
    }
    if (!empty($filters['volume'])) {
        $whereClauses[] = "Pullokoko = :volume";
        $queryParams[':volume'] = $filters['volume'];
    }

    $whereSQL = count($whereClauses) > 0 ? "WHERE " . implode(" AND ", $whereClauses) : "";
    $query = "SELECT COUNT(*) as total FROM alko_price_list $whereSQL";

    $stmt = $pdo->prepare($query);

    foreach ($queryParams as $param => $value) {
        $stmt->bindValue($param, $value);
    }

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
function fetchProducts() {
    try {
        $pdo = create_db_connection(); // Ensure the PDO connection is correct
        $stmt = $pdo->query("SELECT * FROM alko_price_list"); // Run the query

        // Fetch all rows
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debug: Check what is returned
        if (!$products) {
            echo "No products found in the database table.<br>";
            return []; // Return an empty array
        }

        return $products; // Return the fetched rows
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return []; // Return an empty array if an error occurs
    }
}

// Initialize model and fetch data
function initModel($filters = []) {
    $conn = create_db_connection();
    $query = "SELECT * FROM alko_price_list";
    $conditions = [];
    $params = [];

    // Check for filters
    if (!empty($filters['country'])) {
        $conditions[] = "Valmistusmaa = :country";
        $params[':country'] = $filters['country'];
    }
    if (!empty($filters['type'])) {
        $conditions[] = "Tyyppi = :type";
        $params[':type'] = $filters['type'];
    }
    if (!empty($filters['volume'])) {
        $conditions[] = "Pullokoko = :volume";
        $params[':volume'] = $filters['volume'];
    }

    // Add conditions to query
    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Pagination
    $limit = 20;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
    $offset = $page * $limit;
    $query .= " LIMIT :limit OFFSET :offset";
    
    $stmt = $conn->prepare($query);

    // Bind parameters
    foreach ($params as $key => &$value) {
        $stmt->bindParam($key, $value, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);


    $pdo = create_db_connection();
    $query = "SELECT * FROM alko_price_list";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}
?>