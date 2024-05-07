<?php
include "config.php";
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['customer_id'];

if (isset($_POST['submit'])) {
    $desc = $_POST['desc'];

    $target_dir = "images/";
    $fileName = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $fileName;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $image_size = $_FILES["image"]["size"];

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

    $desc = mysqli_real_escape_string($conn, $desc);

    $sql = "INSERT INTO topicissue (customer_id,description, image) VALUES ('$id', '$desc','$fileName')";
    if (mysqli_query($conn, $sql)) {
        echo "Records inserted successfully.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM topicissue";
$result = mysqli_query($conn, $sql);

$sql2 = "SELECT * FROM customers WHERE customer_id = $id";
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
<style>
    .input {
        color: #8707ff;
        border: 2px solid #8707ff;
        padding: 10px 25px;
        background-color: transparent;
        border-radius: 15px;
        max-width: 190px;
    }

    .input:active {
        box-shadow: 2px 2px 15px #8707ff inset;
    }
</style>

<body class="bg-gray-100">
    <?php require "navbar.php"; ?>
    <div class="container mx-auto py-8 p-4">
        <div class="bg-white p-4 bg-rounded-md">
            <h1 class="text-2xl font-bold mb-4">Any Problem?</h1>
            <form method="post" enctype="multipart/form-data" class="">
                <div class="flex  flex-col gap-[1rem] justify-between mb-2">
                    <div class="flex">
                        <input type="file" name="image" class="block w-half text-sm text-slate-500
        file:mr-4 file:py-2 file:px-4 file:rounded-md
        file:border-0 file:text-sm file:font-semibold
        file:bg-blue-500 file:text-white
        hover:file:bg-pink-100" />
                        <input type="text" name="desc"
                            class="border-gray-300 border rounded-md p-2 w-full focus:outline-none focus:border-blue-500"
                            placeholder="Enter Description" />
                    </div>

                    <button type="submit" name="submit"
                        class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600 transition duration-300 w-[30rem] self-center">Submit</button>

                </div>
            </form>
        </div>
    </div>
    <div class="container mx-auto py-8 p-4">
        <h1 class="text-2xl font-bold mb-4">Problem</h1>
        <div class="space-y-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white rounded-md p-4 shadow-md hover:shadow-lg transition duration-300 flex items-center">
                    <?php
                    // Fetch customer info for this specific issue
                    $customerId = $row['customer_id'];
                    $sqlCustomer = "SELECT * FROM customers WHERE customer_id = $customerId";
                    $resultCustomer = mysqli_query($conn, $sqlCustomer);
                    $row2 = mysqli_fetch_assoc($resultCustomer);
                    ?>
                    <div>
                        <div class="rounded-full overflow-hidden h-12 w-12 flex-shrink-0 mr-4">
                            <img src="images/<?php echo $row2['image']; ?>" alt="Profile Image"
                                class="h-full w-full object-cover" />
                        </div>
                        <span class="p-2"><?php echo $row2['username']; ?></span><br>
                    </div>
                    <div>
                        <img src="images/<?php echo $row['image']; ?>" alt="Issue Image" width="100px" class="mb-2">
                        <span><?php echo $row['description']; ?></span><br>
                        <a href="issue.php?id=<?php echo $row['topicissue_id']; ?>" class="text-blue-500 italic">show all
                            comment</a>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>
    </div>

</body>

</html>