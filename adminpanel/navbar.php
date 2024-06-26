<style>
    @media (max-width: 640px) {

        #logo-sidebar {
            display: none;
        }
    }

    @media (min-width: 641px) {

        nav {
            display: none;
        }
    }
</style>
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800 dark:bg-gray-800">
        <a href="index.php" class="flex items-center ps-2.5 mb-5">
            <img src="../images/logo2.jpeg" class="h-6 me-3 sm:h-7" />
            <span class="self-center text-xl font-semibold text-white  whitespace-nowrap dark:text-white">PUTA</span>
        </a>
        <ul class="space-y-2 font-medium">
            <li>
                <a href="index.php" class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                    <svg class="w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-white dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Home</span>
                </a>
            </li>
            <li>
                <a href="ticket.php" class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                    </svg>
                    <span class="ms-3">Status</span>
                </a>
            </li>
            <li>
                <a href="../logout.php" class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                    </svg>
                    <span class="ms-3">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<nav class="font-sans flex flex-col text-center sm:flex-row sm:text-left sm:justify-between py-4 px-6 bg-gray-800 shadow sm:items-baseline w-full">
    <div class="flex flex-wrap justify-center sm:justify-start">
        <a href="index.php" class="text-lg no-underline text-white hover:bg-gray-700 my-1 mx-2 rounded-lg px-4 py-2">
            Home
        </a>
        <a href="category.php" class="text-lg no-underline text-white hover:bg-gray-700 my-1 mx-2 rounded-lg px-4 py-2">
            Category
        </a>
        <a href="product.php" class="text-lg no-underline text-white hover:bg-gray-700 my-1 mx-2 rounded-lg px-4 py-2">
            Product
        </a>
        <a href="../logout.php" class="text-lg no-underline text-white hover:bg-gray-700 my-1 mx-2 rounded-lg px-4 py-2">
            Logout
        </a>
    </div>
</nav>


<script>
    const aside = document.getElementById('logo-sidebar');
    const nav = document.querySelector('nav');

    window.addEventListener('resize', () => {
        if (window.innerWidth <= 640) {
            aside.style.display = 'none'; 
            nav.style.display = 'flex'; 
        } else {
            aside.style.display = 'block'; 
            nav.style.display = 'none'; 
        }
    });

    if (window.innerWidth <= 640) {
        aside.style.display = 'none';
        nav.style.display = 'flex';
    } else {
        aside.style.display = 'block';
        nav.style.display = 'none';
    }
</script>