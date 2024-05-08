<?php
include "config.php";
session_start();

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    echo "Invalid ID";
    exit();
}

$idd = $_SESSION['customer_id'] ?? null;

if (isset($_POST['submit'])) {
    $desc = $_POST['desc'];

    // Check if image file is uploaded or not
    if (!isset($_FILES['image']) || $_FILES['image']['size'] == 0) {
        $fileName = null; // Set fileName to null if no image uploaded
    } else {
        $target_dir = "images/";
        $fileName = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $fileName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $image_size = $_FILES["image"]["size"];

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            exit();
        }

        // Check file size
        if ($image_size > 500000) {
            echo "Sorry, your file is too large.";
            exit();
        }

        // Allow only certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            echo "Sorry, only JPG, JPEG & PNG files are allowed.";
            exit();
        }

        // Move uploaded file to destination directory
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    $desc = mysqli_real_escape_string($conn, $desc);

    // Insert comment into database
    $sql = "INSERT INTO topicissuedetails (customer_id, topicissue_id, `comment`, image) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $idd, $id, $desc, $fileName);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: issue.php?id=$id");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} elseif (isset($_POST['delete'])) {
    $commentId = $_POST['comment_id'];
    $sql = "DELETE FROM topicissuedetails WHERE topicissuedetails_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $commentId);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: issue.php?id=$id");
        exit();
    } else {
        echo "Error deleting comment: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Fetch topic details
$sql = "SELECT * FROM topicissue WHERE topicissue_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Fetch comments for the topic
$sql2 = "SELECT * FROM topicissuedetails WHERE topicissue_id = ?";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
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
                        $customer_image_sql = "SELECT image FROM customers WHERE customer_id = ?";
                        $stmt3 = mysqli_prepare($conn, $customer_image_sql);
                        mysqli_stmt_bind_param($stmt3, "i", $customer_id);
                        mysqli_stmt_execute($stmt3);
                        $customer_image_result = mysqli_stmt_get_result($stmt3);
                        $customer_image_row = mysqli_fetch_assoc($customer_image_result);
                        mysqli_stmt_close($stmt3);

                        $customerId = $row['customer_id'];
                        $sqlCustomer = "SELECT * FROM customers WHERE customer_id = ?";
                        $stmt4 = mysqli_prepare($conn, $sqlCustomer);
                        mysqli_stmt_bind_param($stmt4, "i", $customerId);
                        mysqli_stmt_execute($stmt4);
                        $resultCustomer = mysqli_stmt_get_result($stmt4);
                        $row2 = mysqli_fetch_assoc($resultCustomer);
                        mysqli_stmt_close($stmt4);
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
                            <form method="post">
                                <input type="hidden" name="comment_id" value="<?php echo $row['topicissuedetails_id']; ?>">
                                <button type="submit" name="delete"
                                    class="bg-red-500 text-white rounded-md px-4 py-2 hover:bg-red-600 transition duration-300">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>

</html>
