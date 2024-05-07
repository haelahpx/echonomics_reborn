<?php 
session_start();
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
    <section class="w-[100%] md:h-[100vh] h-[100%] my-[10rem] md:my-0 flex items-center justify-center">
        <div class="px-[8rem] py-[4rem] bg-transparent border-2 backdrop-blur-lg rounded-lg flex flex-col items-center justify-center  shadow-lg">
            <h1 class="mb-4 text-[2.5rem] font-bold border-b-4">Contact Us!</h1>
            <div class="flex flex-col items-center justify-center md:flex-row gap-[6rem]">
                <div>
                    <form action="" class="flex flex-col gap-4">
                        <input type="text" name="name" id="" placeholder="Your Name" class="border-2 rounded-lg px-2 py-1 text-[1.2rem] w-[20rem]">
                        <input type="text" name="email" id="" placeholder="Your Email Address" class="border-2 rounded-lg px-2 py-1 text-[1.2rem] w-[20rem]">
                        <textarea name="message" class="resize-none border-2 rounded-lg px-2 py-1 text-[1.2rem] w-[20rem]" rows="8" placeholder="Type a message..."></textarea>
                        <button id="submit" type="submit" value="SEND" class="px-2 py-4 border-2 bg-white rounded-lg hover:bg-blue-400 transition-all hover:scale-105 hover:text-white duration-300 gap-2 flex items-center justify-center"><i class="fa fa-telegram"></i>Submit</button>
                    </form>
                </div>
                <div class="border-2 bg-white rounded-lg w-[15rem] h-[26.7rem] gap-4 flex flex-col items-center justify-center">
                    <h1>Address</h1>
                    <h1>email@gmail.com</h1>
                    <h1>+6291212435678</h1>
                </div>
            </div>
        </div>
    </section>
</body>

</html>