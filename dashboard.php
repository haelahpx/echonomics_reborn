<?php
include 'config.php';
session_start();
$idd = $_SESSION['customer_id'];

// Fetch data from the database
$query = "SELECT ordermaster.order_id, customers.username, ordermaster.order_category, ordermaster.order_date
          FROM ordermaster
          INNER JOIN customers ON ordermaster.customer_id = customers.customer_id
          INNER JOIN payment ON ordermaster.order_id = payment.order_id
          WHERE ordermaster.order_status = 'completed' AND customers.customer_id = $idd";
$result = mysqli_query($conn, $query);

$query2 = "SELECT ordermaster.order_id, customers.username, ordermaster.order_category, ordermaster.order_date, ordermaster.order_status, payment.payment_status
          FROM ordermaster
          INNER JOIN customers ON ordermaster.customer_id = customers.customer_id
          INNER JOIN payment ON ordermaster.order_id = payment.order_id
          WHERE customers.customer_id = $idd AND ordermaster.order_status = 'pending'";
$result2 = mysqli_query($conn, $query2);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
        $update_query = "UPDATE ordermaster SET order_status = 'completed' WHERE order_id = $order_id";
        mysqli_query($conn, $update_query);
        header("Location: dashboard.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>

<body>
    <?php require "navbar.php"; ?>

    <div class="w-full lg:w-2/3 p-4">
        <div class="history-section mt-8 mx-auto rounded-lg bg-white border p-4 shadow-md">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">History Payment</h1>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Category</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Status</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result2)) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['order_id'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['username'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['order_category'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['order_date'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['payment_status'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>";
                            if ($row['payment_status'] == 'pending') {
                                echo "Pending";
                            } elseif ($row['payment_status'] == 'completed' && $row['payment_status'] == 'completed') {
                                echo "<form action='' method='POST'>";
                                echo "<input type='hidden' name='order_id' value='" . $row['order_id'] . "'>";
                                echo "<button type='submit' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>Take it</button>";
                                echo "</form>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-2/3 p-4">
        <div class="history-section mt-8 mx-auto rounded-lg bg-white border p-4 shadow-md">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">History Payment</h1>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Category</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['order_id'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['username'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['order_category'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>" . $row['order_date'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>