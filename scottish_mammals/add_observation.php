<?php
require_once 'includes/db.php';

$pdo = getDbConnection();

// HANDLE FORM
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

<main>

    <h2>Add Observation</h2>

    <?php if (!empty($success)) echo "<p>$success</p>"; ?>

    <div class="form-container">
        <form method="POST">

            <div class="form-group">
                <label>Species</label>
                <select name="species_key" required>
                    <?php
                    $species = $pdo->query("SELECT gbif_species_key, common_name FROM species")->fetchAll();
                    foreach ($species as $sp) {
                        echo "<option value='{$sp['gbif_species_key']}'>{$sp['common_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Location</label>
                <input type="text" name="locality">
            </div>

            <div class="form-group">
                <label>Count</label>
                <input type="number" name="count">
            </div>

            <div class="form-group">
                <label>Latitude</label>
                <input type="text" name="lat">
            </div>

            <div class="form-group">
                <label>Longitude</label>
                <input type="text" name="lon">
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date">
            </div>
			<div class="image-preview">
    <img id="previewImg" src="https://via.placeholder.com/250x150?text=No+Image" alt="Preview">
</div>
<div class="form-group">
    <label>Upload Image</label>
    <input type="file" id="imageInput" accept="image/*">
</div>

            <button type="submit">Add Observation</button>

        </form>
    </div>

</main>

<?php require_once 'includes/footer.php'; ?>