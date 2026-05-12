<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' - ' : ''; ?>Scottish Mammal Observations Database</title>

    <!-- RESET CSS -->
<link rel="stylesheet" href="../css/style.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- LEAFLET -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- CHART JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<header>
    <h1>Scottish Mammal Observations Database</h1>

    <nav>
        <a href="index.php">Home</a>
        <a href="add_observation.php">Add Observation</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<main>