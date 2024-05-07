<?php
include "../config.php";
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT order_date, COUNT(*) as total_orders FROM ordermaster GROUP BY order_date";
$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['order_date'];
        $data[] = $row['total_orders'];
    }
}

$chartData = [
    'labels' => $labels,
    'datasets' => [
        [
            'label' => 'Jumlah Order',
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
            'borderColor' => 'rgba(54, 162, 235, 1)',
            'borderWidth' => 1,
            'data' => $data,
        ],
    ],
];

$sql2 = "SELECT ordermaster.order_id, customers.username, ordermaster.order_category, ordermaster.order_date 
         FROM ordermaster 
         INNER JOIN customers ON ordermaster.customer_id = customers.customer_id 
         WHERE ordermaster.order_status = 'pending'";
$result2 = $conn->query($sql2);


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Document</title>
</head>

<body>
    <?php require "navbar.php"; ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 pl-72 pt-12 p-6">
        <div class="lg:col-span-6">
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h1 class="text-xl font-bold mb-4">To Do List</h1>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-200 text-gray-600">Order ID</th>
                                <th class="px-6 py-3 bg-gray-200 text-gray-600">Username</th>
                                <th class="px-6 py-3 bg-gray-200 text-gray-600">Order Category</th>
                                <th class="px-6 py-3 bg-gray-200 text-gray-600">Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result2)) : ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $row['order_id'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $row['username'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $row['order_category'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $row['order_date'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="lg:col-span-6">
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h1 class="text-xl font-bold mb-4">Order Chart</h1>
                <canvas id="orderChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
        const orderData = <?= json_encode($chartData) ?>;

        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'line',
            data: orderData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


</body>

</html>