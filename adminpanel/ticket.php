<?php
include "../config.php";
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["order_id"]) && !empty($_POST["order_id"])) {
        $order_id = $_POST["order_id"];

        if (isset($_POST["status"]) && !empty($_POST["status"])) {
            $status = $_POST["status"];

            $update_sql = "UPDATE payment SET payment_status = ? WHERE order_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $status, $order_id);
            if ($stmt->execute()) {
                echo "Status updated successfully!";
            } else {
                echo "Error updating status: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Status is not set or empty.";
        }
    } else {
        echo "Order ID is not set or empty.";
    }
}

if(isset($_POST['repair'])) {
    if (isset($_POST["order_id"]) && !empty($_POST["order_id"])) {
        $order_id = $_POST["order_id"];
        $status = $_POST['status'];
        $price = $_POST['price'];
        $currentTime = date("Y-m-d H:i:s");

        // Update payment_status in payment table
        $updatePaymentStatusQuery = "UPDATE payment SET payment_status = ? WHERE order_id = ?";
        $stmt = $conn->prepare($updatePaymentStatusQuery);
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();

        // Update price and end_time in orderdetails table
        $updateOrderDetailsQuery = "UPDATE orderdetails SET price = ?, end_time = ? WHERE order_id = ?";
        $stmt = $conn->prepare($updateOrderDetailsQuery);
        $stmt->bind_param("dsi", $price, $currentTime, $order_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Order ID is not set or empty.";
    }
}

$sql = "SELECT customers.username, orderdetails.start_time, orderdetails.end_time, ordermaster.order_category, orderdetails.price, payment.payment_status, ordermaster.order_id
        FROM ordermaster
        INNER JOIN customers ON ordermaster.customer_id = customers.customer_id
        INNER JOIN orderdetails ON ordermaster.order_id = orderdetails.order_id
        INNER JOIN payment ON ordermaster.order_id = payment.order_id
        WHERE payment.payment_status = 'pending' AND ordermaster.order_category ='meeting'";

$result = mysqli_query($conn, $sql);

$sql2 = "SELECT customers.username, orderdetails.start_time, orderdetails.end_time, ordermaster.order_category, orderdetails.price, payment.payment_status, ordermaster.order_id
        FROM ordermaster
        INNER JOIN customers ON ordermaster.customer_id = customers.customer_id
        INNER JOIN orderdetails ON ordermaster.order_id = orderdetails.order_id
        INNER JOIN payment ON ordermaster.order_id = payment.order_id
        WHERE payment.payment_status = 'pending' AND ordermaster.order_category ='pairing'";

$result2 = mysqli_query($conn, $sql2);
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
    <div class="container mx-auto p-4">
        <h1>Meeting</h1>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="py-2 px-4">Username</th>
                    <th class="py-2 px-4">Start</th>
                    <th class="py-2 px-4">End</th>
                    <th class="py-2 px-4">Category</th>
                    <th class="py-2 px-4">Status</th>
                    <th class="py-2 px-4">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr class="border-b border-gray-200">
                        <td class="py-2 px-4"><?php echo $row["username"]; ?></td>
                        <td class="py-2 px-4"><?php echo $row["start_time"]; ?></td>
                        <td class="py-2 px-4"><?php echo $row["end_time"]; ?></td>
                        <td class="py-2 px-4"><?php echo $row["order_category"]; ?></td>
                        <td class="py-2 px-4">
                            <form action="" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $row["order_id"]; ?>">
                                <select class="py-1 px-2 bg-white rounded border" name="status">
                                    <option value="pending" <?php if ($row["payment_status"] == "pending") echo "selected"; ?>>Pending</option>
                                    <option value="completed" <?php if ($row["payment_status"] == "completed") echo "selected"; ?>>Completed</option>
                                </select>
                                <input type="submit" value="Update" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="container mx-auto p-4">
        <h1>Repair</h1>
        <form action="" method="post">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-2 px-4">Username</th>
                        <th class="py-2 px-4">Start</th>
                        <th class="py-2 px-4">Category</th>
                        <th class="py-2 px-4">Status</th>
                        <th class="py-2 px-4">Price</th>
                        <th class="py-2 px-4">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    <?php while ($row = mysqli_fetch_assoc($result2)) : ?>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 px-4"><?php echo $row["username"]; ?></td>
                            <td class="py-2 px-4"><?php echo $row["start_time"]; ?></td>
                            <td class="py-2 px-4"><?php echo $row["order_category"]; ?></td>
                            <td class="py-2 px-4">
                                <input type="hidden" name="order_id" value="<?php echo $row["order_id"]; ?>">
                                <select class="py-1 px-2 bg-white rounded border" name="status">
                                    <option value="pending" <?php if ($row["payment_status"] == "pending") echo "selected"; ?>>Pending</option>
                                    <option value="completed" <?php if ($row["payment_status"] == "completed") echo "selected"; ?>>Completed</option>
                                </select>
                            </td>
                            <td class="py-2 px-4"><input type="text" name="price" placeholder="Enter price"></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="6" class="py-2 px-4"><button type="submit" name="repair" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">Update</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
