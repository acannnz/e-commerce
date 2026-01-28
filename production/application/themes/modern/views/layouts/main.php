<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'E-Commerce System'; ?></title>
    <!-- Tailwind CSS via CDN for development -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        /* Custom styles if needed */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <!-- Navbar Partials -->
    <?php $this->load->view('partials/navbar'); ?>

    <!-- Main Content -->
    <main class="p-4 md:ml-64 h-auto pt-20">
        <?php echo $content; ?>
    </main>

    <!-- Footer Partials -->
    <?php $this->load->view('partials/footer'); ?>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
