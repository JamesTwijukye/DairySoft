<?php
session_start();
include 'config.php';

error_reporting(E_ALL);

ini_set('display_errors', 1);

function getCount($conn, $table) {
    $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $table") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($res);
    return $row['total'] ?? 0;
}


if (isset($_GET['fetch'])) {

    $farmers   = getCount($conn, "farmers");
    $milk      = getCount($conn, "milk_records");
    $payments  = getCount($conn, "payments");
    $employees = getCount($conn, "employees");

    $recent = [];


    $res = mysqli_query($conn, "SELECT name, created_at FROM farmers ORDER BY id DESC LIMIT 2");

    while ($row = mysqli_fetch_assoc($res)) {
        $recent[] = [
            'category' => 'Farmer Added',
            'detail'   => $row['name'],
            'date'     => $row['created_at']
        ];
    }


    $res = mysqli_query($conn, "SELECT liters, milk_date FROM milk_records ORDER BY id DESC LIMIT 2");

    while ($row = mysqli_fetch_assoc($res)) {
        $recent[] = [
            'category' => 'Milk Recorded',
            'detail'   => $row['liters'] . ' L',
            'date'     => $row['milk_date']
        ];
    }


    $res = mysqli_query($conn, "SELECT amount, payment_date FROM payments ORDER BY id DESC LIMIT 2");

    while ($row = mysqli_fetch_assoc($res)) {
        $recent[] = [
            'category' => 'Payment Made',
            'detail'   => 'UGX ' . $row['amount'],
            'date'     => $row['payment_date']
        ];
    }


    usort($recent, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    header('Content-Type: application/json');
    echo json_encode([
        'farmers'   => $farmers,
        'milk'      => $milk,
        'payments'  => $payments,
        'employees' => $employees,
        'recent'    => $recent
    ]);

    exit;
}

if (isset($_GET['image'])) {

    $records = [];

    $res = mysqli_query(
        $conn,
        "SELECT id, name, image, 'Farmer' AS category, '' AS type 
         FROM farmers 
         WHERE image IS NOT NULL AND image != ''"
    );

    while ($row = mysqli_fetch_assoc($res)) {
        $records[] = $row;
    }

    $res = mysqli_query(
        $conn,
        "SELECT id, name, type, image, 'Product' AS category 
         FROM products 
         WHERE image IS NOT NULL AND image != ''"
    );

    while ($row = mysqli_fetch_assoc($res)) {
        $records[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

if (isset($_GET['chart']) && $_GET['chart'] == 'milkPerFarmer') {

    $data = [];

    $res = mysqli_query(
        $conn,
        "SELECT 
            f.name,
            SUM(m.liters) AS total_milk 
         FROM farmers f 
         LEFT JOIN milk_records m ON f.id = m.farmer_id 
         GROUP BY f.id 
         ORDER BY f.id ASC 
         LIMIT 10"
    );

    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>
