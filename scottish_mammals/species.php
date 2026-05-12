<?php
require_once 'includes/db.php';

// Validate key
if (!isset($_GET['key']) || !is_numeric($_GET['key'])) {
    header('Location: index.php');
    exit;
}

$speciesKey = (int)$_GET['key'];
$pdo = getDbConnection();

// Fetch species
$stmt = $pdo->prepare('
    SELECT
        gbif_species_key,
        species_name,
        common_name,
        iucn_red_list_category,
        body_mass_kg,
        dietary_category,
        uk_protection_status,
        habitat,
        image_url
    FROM species
    WHERE gbif_species_key = ?
');
$stmt->execute([$speciesKey]);
$species = $stmt->fetch();

// Fetch observations
$stmtObs = $pdo->prepare("
    SELECT * FROM observations
    WHERE gbif_species_key = ?
");
$stmtObs->execute([$speciesKey]);
$observations = $stmtObs->fetchAll();

// If not found
if (!$species) {
    header('Location: index.php');
    exit;
}

$pageTitle = $species['common_name'];

require_once 'includes/header.php';
?>

<p><a href="index.php">&larr; Back to all species</a></p>


<h2><?php echo e($species['common_name']); ?></h2>

<!-- Image -->
<?php if (!empty($species['image_url'])): ?>
    <img src="<?php echo htmlspecialchars($species['image_url']); ?>" width="200">
<?php endif; ?>

<dl>
    <dt>Scientific Name</dt>
    <dd><em><?php echo e($species['species_name']); ?></em></dd>

    <dt>Conservation Status</dt>
    <dd><?php echo $species['iucn_red_list_category'] ? e($species['iucn_red_list_category']) : 'Not listed'; ?></dd>

    <dt>Body Mass</dt>
    <dd><?php echo e($species['body_mass_kg']); ?> kg</dd>

    <dt>Dietary Category</dt>
    <dd><?php echo e($species['dietary_category']); ?></dd>

    <dt>Habitat</dt>
    <dd><?php echo e($species['habitat']); ?></dd>

    <dt>UK Protection Status</dt>
    <dd><?php echo e($species['uk_protection_status']); ?></dd>
</dl>

<!-- OBSERVATIONS TABLE -->
<h3>Observations</h3>

<?php if (empty($observations)): ?>
    <p>No observations found.</p>
<?php else: ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Location</th>
            <th>Date</th>
            <th>Count</th>
			<th>Actions</th>
        </tr>

        <?php foreach ($observations as $obs): ?>
            <tr>
                <td><?php echo $obs['locality'] ?? 'Not recorded'; ?></td>
                <td><?php echo $obs['observation_date'] ?? 'Unknown'; ?></td>
                <td><?php echo $obs['individual_count']; ?></td>
			   <td>
				<a href="edit_observation.php?id=<?php echo $obs['id']; ?>">Edit</a> |
    <a href="delete_observation.php?id=<?php echo $obs['id']; ?>" 
       onclick="return confirm('Are you sure?');">
        Delete
    </a>
</td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<!-- MAP -->
<h3>Observation Map</h3>
<div id="map" style="height: 400px;"></div>

<script>
    const observations = <?php echo json_encode($observations); ?>;

    const map = L.map('map').setView([56.8, -4], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    observations.forEach(obs => {
        if (obs.latitude && obs.longitude) {
            L.marker([obs.latitude, obs.longitude])
                .addTo(map)
                .bindPopup(
                    "Location: " + (obs.locality ?? "Unknown") +
                    "<br>Count: " + obs.individual_count
                );
        }
    });
</script>

<!-- CHART -->
<h3>Observation Count Chart</h3>
<canvas id="obsChart" height="100"></canvas>

<script>
document.addEventListener("DOMContentLoaded", function () {

    if (observations.length > 0) {

        const counts = observations.map(o => o.individual_count);
        const labels = observations.map((o, i) => "Obs " + (i + 1));

        const ctx = document.getElementById('obsChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Individuals Observed',
                    data: counts
                }]
            }
        });

    }

});
</script>

<?php require_once 'includes/footer.php'; ?>