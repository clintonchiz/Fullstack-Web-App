<?php
require_once 'includes/db.php';

$pdo = getDbConnection();

// GET ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// FETCH EXISTING OBSERVATION
$stmt = $pdo->prepare("SELECT * FROM observations WHERE id = ?");
$stmt->execute([$id]);
$obs = $stmt->fetch();

if (!$obs) {
    echo "Observation not found";
    exit;
}

// HANDLE UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        UPDATE observations 
        SET locality = ?, individual_count = ?, latitude = ?, longitude = ?, observation_date = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $_POST['locality'],
        $_POST['count'],
        $_POST['lat'],
        $_POST['lon'],
        $_POST['date'],
        $id
    ]);

    header("Location: species.php?key=" . $obs['gbif_species_key']);
    exit;
}

require_once 'includes/header.php';
?>

<h2>Edit Observation</h2>

<form method="POST">

    <label>Location:</label>
    <input type="text" name="locality" value="<?php echo $obs['locality']; ?>">

    <label>Count:</label>
    <input type="number" name="count" value="<?php echo $obs['individual_count']; ?>">

    <label>Latitude:</label>
    <input type="text" name="lat" value="<?php echo $obs['latitude']; ?>">

    <label>Longitude:</label>
    <input type="text" name="lon" value="<?php echo $obs['longitude']; ?>">

    <label>Date:</label>
    <input type="date" name="date" value="<?php echo $obs['observation_date']; ?>">

    <button type="submit">Update Observation</button>

</form>

<?php require_once 'includes/footer.php'; ?>