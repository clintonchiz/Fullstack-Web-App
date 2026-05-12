<?php
require_once 'includes/db.php';

$pdo = getDbConnection();

// HANDLE FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        INSERT INTO observations 
        (gbif_species_key, locality, individual_count, latitude, longitude, observation_date)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['species_key'],
        $_POST['locality'] ?: null,
        $_POST['count'] ?: null,
        $_POST['lat'] ?: null,
        $_POST['lon'] ?: null,
        $_POST['date'] ?: null
    ]);

    $success = "Observation added successfully!";
}

require_once 'includes/header.php';
?>

<h2>Add Observation</h2>

<?php if (!empty($success)) echo "<p>$success</p>"; ?>

<form method="POST">

    <label>Species:</label>
    <select name="species_key" required>
        <?php
        $species = $pdo->query("SELECT gbif_species_key, common_name FROM species")->fetchAll();
        foreach ($species as $sp) {
            echo "<option value='{$sp['gbif_species_key']}'>{$sp['common_name']}</option>";
        }
        ?>
    </select>

    <label>Location:</label>
    <input type="text" name="locality">

    <label>Count:</label>
    <input type="number" name="count">

    <label>Latitude:</label>
    <input type="text" name="lat">

    <label>Longitude:</label>
    <input type="text" name="lon">

    <label>Date:</label>
    <input type="date" name="date">

    <button type="submit">Add Observation</button>

</form>

<?php require_once 'includes/footer.php'; ?>