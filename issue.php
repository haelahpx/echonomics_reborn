<?php
include "config.php";
session_start();

$id = $_GET['id'] ?? null;

$idd = $_SESSION['customer_id'];

if (isset($_POST['submit'])) {
    $desc = $_POST['desc'];

    $target_dir = "images/";
    $fileName = basename($_FILES["image"]["name"]);
    $image_size = $_FILES["image"]["size"];

    // Check if an image is uploaded
    if (!empty($fileName)) {
        $target_file = $target_dir . $fileName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
            echo "File is not an image.";
            exit();
        }

        if ($image_size > 500000) {
            echo "Sorry, your file is too large.";
            exit();
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            echo "Sorry, only JPG, JPEG & PNG files are allowed.";
            exit();
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    } else {
        // If no image uploaded, set fileName to NULL
        $fileName = null;
    }

    $desc = mysqli_real_escape_string($conn, $desc);

    $sql = "INSERT INTO topicissuedetails (customer_id,topicissue_id, `comment`, image) VALUES ('$idd','$id', '$desc',";

    if (!empty($fileName)) {
        $sql .= "'$fileName')";
    } else {
        $sql .= "NULL)";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Records inserted successfully.";
        header("Location: issue.php?id=$id");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM topicissue WHERE topicissue_id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$sql2 = "SELECT * FROM topicissuedetails WHERE topicissue_id = $id";
$result2 = mysqli_query($conn, $sql2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <?php require "navbar.php"; ?>


    <div class="container mx-auto py-8 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-md p-4 shadow-md hover:shadow-lg transition duration-300 mb-4">
                <h1 class="text-2xl font-bold mb-4">Issue</h1>
                <p><?php echo $row['description']; ?></p>
                <?php if (!empty($row['image'])): ?>
                    <img src="images/<?php echo $row['image']; ?>" alt="Issue Image" class="mt-4" width="100px">
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-md p-4 shadow-md hover:shadow-lg transition duration-300 mb-4">
                <h1 class="text-2xl font-bold mb-4">Make Comment</h1>
                <form method="post" enctype="multipart/form-data" class="mb-4">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <input type="file" name="image" class="block w-half text-sm text-slate-500
        file:mr-4 file:py-2 file:px-4 file:rounded-md
        file:border-0 file:text-sm file:font-semibold
        file:bg-blue-500 file:text-white
        hover:file:bg-pink-100" />
                        <input type="text" name="desc"
                            class="border-gray-300 border rounded-md p-2 mb-2 md:mr-2 md:w-3/4 focus:outline-none focus:border-blue-500"
                            placeholder="Enter Description">
                        <button type="submit" name="submit"
                            class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600 transition duration-300">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-md p-4 shadow-md hover:shadow-lg transition duration-300">
            <h1 class="text-2xl font-bold mb-4">Comments</h1>
            <div class="space-y-4">
                <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                    <div
                        class="bg-gray-100 rounded-md p-4 shadow-md hover:shadow-lg transition duration-300 flex items-start">
                        <?php
                        $customer_id = $row['customer_id'];
                        $customer_image_sql = "SELECT image FROM customers WHERE customer_id = $customer_id";
                        $customer_image_result = mysqli_query($conn, $customer_image_sql);
                        $customer_image_row = mysqli_fetch_assoc($customer_image_result);
                        $customerId = $row['customer_id'];
                        $sqlCustomer = "SELECT * FROM customers WHERE customer_id = $customerId";
                        $resultCustomer = mysqli_query($conn, $sqlCustomer);
                        $row2 = mysqli_fetch_assoc($resultCustomer);
                        ?>
                        <?php if (!empty($customer_image_row['image'])): ?>
                            <img src="images/<?php echo $customer_image_row['image']; ?>" alt="Customer Image"
                                class="mt-2 rounded-full w-10 h-10 mr-4">
                        <?php endif; ?>
                        <div class="flex-grow">
                            <?php if (!empty($customer_image_row['image'])): ?>
                                <span class="block mb-1"><?php echo $row2['username']; ?></span>
                            <?php else: ?>
                                <span class="block mb-1"><?php echo $row2['username']; ?></span>
                            <?php endif; ?>
                            <p class="text-gray-800"><?php echo $row['comment']; ?></p>
                            <?php if (!empty($row['image'])): ?>
                            <img src="images/<?php echo $row['image']; ?>" alt="Issue Image" width="100px" class="mb-2">
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($row['customer_id'] == $idd): ?>
                            <form class="ml-auto">
                                <button
                                    class="bg-red-500 text-white rounded-md px-4 py-2 hover:bg-red-600 transition duration-300">Delete
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>


    </div>
</body>

</html>