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

    <section class="bg-[url('images/bag1.jpg')] bg-cover bg-center bg-no-repeat h-screen flex items-center justify-center">
        <div class="text-white text-center">
            <h1 class="text-6xl">PUTA</h1>
            <h1 class="text-2xl tracking-wider">PRESIDENT UNIVERSITY TECH ASSISTANT</h1>
        </div>
    </section>

    <section class="bg-[url('images/aiperson.png')] bg-cover bg-center bg-no-repeat h-screen flex justify-end items-center">
        <div class="flex flex-col gap-4 px-4 py-2 max-w-md text-white">
            <h1 class="font-bold text-3xl">Meet Puta AI.</h1>
            <p class="text-base">Puta AI is a smart virtual assistant that helps you with simple technology questions. It's equipped to provide quick and accurate answers, making it easy for you to navigate tech-related queries, whether you're a beginner or an expert.</p>
            <a href="https://chat.socialintents.com/c/chat-1707203230419" target="_blank" class="mt-4 border-2 rounded-lg px-4 py-2 inline-block hover:bg-white hover:text-black hover:scale-110 transition-all duration-300">Chat Now</a>
        </div>
    </section>

</body>

</html>