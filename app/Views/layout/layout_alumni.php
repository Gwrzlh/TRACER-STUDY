<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tracer Study Alumni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Optional: Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body class="bg-gray-100 font-sans">

    <!-- Sidebar -->
    <?= $this->include('layout/sidebar_alumni') ?>

    <!-- Content -->
    <main class="ml-64 p-6 min-h-screen">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Optional JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>

</html>