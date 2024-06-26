<?php
session_start();
include 'config.php';

$error_message = ""; // Initialize error message variable

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $customer_id = $_SESSION['customer_id'];
    $order_category = $_POST['order_category'];
    $description = $_POST['description'];

    if ($order_category === "meeting") {
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // Format start_time and end_time as datetime
        $start_datetime = date('Y-m-d H:i:s', strtotime($start_time));
        $end_datetime = date('Y-m-d H:i:s', strtotime($end_time));

        // Use prepared statement for existing order query
        $existing_order_sql = "SELECT * FROM orderdetails WHERE (start_time BETWEEN ? AND ?) OR (end_time BETWEEN ? AND ?)";
        $existing_order_stmt = $conn->prepare($existing_order_sql);
        $existing_order_stmt->bind_param("ssss", $start_datetime, $end_datetime, $start_datetime, $end_datetime);
        $existing_order_stmt->execute();
        $existing_order_result = $existing_order_stmt->get_result();

        if ($existing_order_result->num_rows > 0) {
            $error_message = "An order already exists for the selected date and time. Please choose a different date and time.";
        } else {
            // Insert new order
            $order_master_sql = "INSERT INTO ordermaster (customer_id, order_category, order_date) VALUES (?, ?, NOW())";
            $order_master_stmt = $conn->prepare($order_master_sql);
            $order_master_stmt->bind_param("is", $customer_id, $order_category);
            if ($order_master_stmt->execute()) {
                $order_id = $conn->insert_id;

                // Insert order details
                $order_details_sql = "INSERT INTO orderdetails (order_id, start_time, end_time, description) VALUES (?, ?, ?, ?)";
                $order_details_stmt = $conn->prepare($order_details_sql);
                $order_details_stmt->bind_param("isss", $order_id, $start_datetime, $end_datetime, $description);

                if ($order_details_stmt->execute()) {
                    // Insert order_id into the payment table
                    $payment_sql = "INSERT INTO payment (order_id) VALUES (?)";
                    $payment_stmt = $conn->prepare($payment_sql);
                    $payment_stmt->bind_param("i", $order_id);
                    $payment_stmt->execute();

                    header("Location: ticket.php");
                    exit();
                } else {
                    $error_message = "Error: Unable to create ticket. Please try again later.";
                }
            } else {
                $error_message = "Error: Unable to create ticket. Please try again later.";
            }
        }
    } else {
        // If not a meeting, handle other order categories
        $start_datetime = date('Y-m-d H:i:s'); // Use current datetime for other categories

        // Insert new order
        $order_master_sql = "INSERT INTO ordermaster (customer_id, order_category, order_date) VALUES (?, ?, NOW())";
        $order_master_stmt = $conn->prepare($order_master_sql);
        $order_master_stmt->bind_param("is", $customer_id, $order_category);
        if ($order_master_stmt->execute()) {
            $order_id = $conn->insert_id;

            // Insert order details
            $order_details_sql = "INSERT INTO orderdetails (order_id, description, start_time) VALUES (?, ?, ?)";
            $order_details_stmt = $conn->prepare($order_details_sql);
            $order_details_stmt->bind_param("iss", $order_id, $description, $start_datetime);

            if ($order_details_stmt->execute()) {
                // Insert order_id into the payment table   
                $payment_sql = "INSERT INTO payment (order_id) VALUES (?)";
                $payment_stmt = $conn->prepare($payment_sql);
                $payment_stmt->bind_param("i", $order_id);
                $payment_stmt->execute();

                header("Location: ticket.php");
                exit();
            } else {
                $error_message = "Error: Unable to create ticket. Please try again later.";
            }
        } else {
            $error_message = "Error: Unable to create ticket. Please try again later.";
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
</head>

<body>
    <?php require "navbar.php"; ?>

    <section class="p-8">
        <div
            class="bg-transparent border-2 backdrop-blur-lg  rounded-lg p-12 flex flex-col items-center justify-center  shadow-lg">
            <div class="mx-auto p-6 w-[20rem]   items-center justify-center flex flex-col ">
                <h1 class="text-3xl font-bold mb-4  ">Make Ticket</h1>
                <form method="post" class="space-y-4">

                    <label for="order_category" class="block">Order Category:</label>
                    <select name="order_category" id="order_category" required onchange="toggleTimeInputs()"
                        class="block w-full rounded-md border-gray-300  shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="meeting">Meeting</option>
                        <option value="pairing" selected>Repair</option>
                    </select>


                    <div id="timeInputs" class="">
                        <!-- <label for="start_time" id="start_label" class="block">Start Time:</label>
                        <input type="datetime-local" name="start_time" id="start_time"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                        <label for="end_time" id="end_label" class="block">End Time:</label>
                        <input type="datetime-local" name="end_time" id="end_time"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"> -->

                        <div date-rangepicker datepicker-format="dd/mm/yyyy" class="flex items-center">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input name="start_time" type="text" id="startbox"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date start">
                            </div>
                            <span class="mx-4 text-gray-500">to</span>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input name="end_time" type="text" id="endbox"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date end">
                            </div>
                        </div>

                    </div>



                    <label for="description" class="block">Description:</label>
                    <textarea id="description" name="description"
                        class="block w-full resize-none rounded-md border border-black shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>

                    <button type="submit" name="submit"
                        class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600 transition duration-300">Submit</button>
                </form>
                <?php if (!empty($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mt-4 rounded relative" role="alert">
                        <strong class="font-bold">Error:</strong>
                        <span class="block sm:inline"><?php echo $error_message; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toggleTimeInputs();
        });

        function toggleTimeInputs() {
            let startbox = document.getElementById("startbox");
            let endbox = document.getElementById("endbox");
            var category = document.getElementById("order_category").value;
            var timeInputs = document.getElementById("timeInputs");

            if (category == "pairing") {
                startbox.disabled = true;
                endbox.disabled = true;
                
            } else { 
                startbox.disabled = false;
                endbox.disabled = false;
            }
        }    


    </script>


</body>

</html>