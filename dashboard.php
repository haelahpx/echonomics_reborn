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
    } elseif (isset($_POST['editProfile'])) {
        $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';

        // Fetch user data to get image file name if exists
        $userDataQuery = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = $idd");
        $data_customer = mysqli_fetch_assoc($userDataQuery);

        // Handling image upload
        if (!empty($_FILES['image']['name'])) {
            // Process image upload
            $target_dir = "images/";
            $imageName = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $imageName;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $image_size = $_FILES["image"]["size"];

            // Check if image file is a actual image or fake image
            if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
                echo "File is not an image.";
                exit();
            }

            // Check file size
            if ($image_size > 500000) {
                echo "Sorry, your file is too large.";
                exit();
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
                echo "Sorry, only JPG, JPEG & PNG files are allowed.";
                exit();
            }

            // Move the uploaded file to its destination
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
                exit();
            }
        } else {
            $imageName = $data_customer['image'];
        }

        // Update user profile
        if(empty($username)){
            $updateQuery = mysqli_query($conn, "UPDATE customers SET image = '$imageName' WHERE customer_id = '$idd'");
            header("Location: dashboard.php");
            exit();
        } else {
            $updateQuery = mysqli_query($conn, "UPDATE customers SET image = '$imageName', username ='$username' WHERE customer_id = '$idd'");
            header("Location: dashboard.php");
            exit();
        }
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

    <div class="flex flex-wrap justify-center">
        <!-- Profile change -->
        <div class="w-full lg:w-3/12 p-4">
            <div class="profile-container bg-white rounded-lg p-4 shadow-md mt-8 mx-auto">
                <form class="max-w-lg mx-auto flex flex-col" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <div class="form-group flex flex-col justify-center">
                            <img src="images/<?php echo $data_customer['image'] ?>" alt="Profile Image" class="w-40 h-40 rounded-full mx-auto block"><br>
                            <div class="self-center">
                                <label for="image-upload" class="bg-gray-900 border-gray-200 self-center dark:bg-gray-900 text-white font-bold py-2 px-4 rounded cursor-pointer">
                                    Change Image
                                    <input type="file" name="image" id="image-upload" class="hidden">
                                </label>
                            </div>
                            <hr class="my-4 border-gray-400">
                            <div class="flex items-center mb-2 pl-18">
                                <input type="text" name="username" placeholder="Change username" class="border-2 rounded-full px-4 py-2 text-[1.2rem] w-full lg:w-[20rem] placeholder-center">
                            </div>
                        </div>
                    </div>
                    <div class="self-center">
                        <button name="editProfile" class="bg-gray-900 border-gray-200 dark:bg-gray-900 text-white font-bold py-2 px-4 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Status -->
        <div class="w-full lg:w-7/12">
            <div class="w-full  lg:w-2/3 p-4">
                <div class="history-section mt-8 mx-auto rounded-lg bg-white border p-4 shadow-md">
                    <h1 class="text-2xl font-bold mb-4 text-gray-800">Order Status</h1>
                    <div class="overflow-x-auto">
                        <table class="w-full  px-[4rem]">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Category</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
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
                                    echo "<td class='px-6 py-4 whitespace-no-wrap border-b border-gray-200'>";
                                    if ($row['payment_status'] == 'pending') {
                                        echo "Pending";
                                    } elseif ($row['payment_status'] == 'completed') {
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
        </div>
    </div>

    <!-- History Payment -->
    <div class="w-full p-4">
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
