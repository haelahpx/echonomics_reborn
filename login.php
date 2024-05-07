<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    if(isset($_POST['email'], $_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = md5($_POST['password']);

        $select = "SELECT * FROM customers WHERE email = '$email' AND password = '$pass'";
        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_array($result);
            $_SESSION['customer_id'] = $row['customer_id'];
    
            if ($row['user_type'] == 'admin') {
    
                $_SESSION['admin_name'] = $row['username'];
                header('location:adminpanel/index.php');
                $_SESSION['user_name'] = $row['username'];
                header('location:adminpanel/index.php');
                $_SESSION['email'] = $row['email'];
                header('location:adminpanel/index.php');
                $_SESSION['title'] = $row['title']; 
                header('location:adminpanel/index.php');
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['username'];
                header('location:index.php');
                $_SESSION['email'] = $row['email'];
                header('location:index.php');
                $_SESSION['title'] = $row['title'];
                header('location:index.php');
            }
        } else {
            $error = 'Incorrect email or password!';
        }
    } else {
        $error = 'Please fill in all the fields!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital@1&family=Roboto:ital,wght@0,300;0,500;1,400&family=VT323&display=swap" rel="stylesheet">
</head>

<body class="flex items-center font-[VT323] bg-white  select-none">
    <section class="flex items-center justify-center w-full h-[100vh]">
        <div class="flex flex-col items-center justify-center space-y-4 w-full md:w-[60%] lg:w-[40%]">
            <form action="" method="post" class="flex flex-col items-center justify-center space-y-4">
                <h1 class="font-bold text-[2.5rem] border-b-4 px-4">Login</h1>
                <input type="email" name="email" id="" placeholder="Enter your Email Address" class="border-2 rounded-full px-4 py-2 text-[1.2rem] w-[20rem]">
                <input type="password" name="password" id="" placeholder="Enter your Password" class="border-2 rounded-full px-4 py-2 text-[1.2rem] w-[20rem]">
                <button name="submit" class="border-2 rounded-lg px-2 py-2 w-[14rem] hover:scale-110 transition-all duration-300 hover:text-white hover:bg-blue-400">Login</button>
                <p>Don't have an acccount? <a href="register.php" class="text-blue-400">Register here</a>!</p>
                <p>Wanna Go Home? <a href="index.php" class="text-blue-400">click here</a>!</p>
            </form>
        </div>
        <div class="hidden md:flex md:w-[40%] lg:w-[60%] justify-center items-center">
            <img src="image/loginreg.png" class="w-full" alt="Phone image" />
        </div>
    </section>
</body>

</html>
