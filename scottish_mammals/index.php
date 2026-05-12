
<?php

/**
 * Scottish Mammal Observations Database - Home Page
 */

require_once 'includes/db.php';

$pageTitle = 'All Species';

// Fetch all species from the database
$pdo = getDbConnection();
$diet = $_GET['diet'] ?? '';

if ($diet) {
    $stmt = $pdo->prepare('
        SELECT
            gbif_species_key,
            species_name,
            common_name,
            iucn_red_list_category,
            dietary_category,
            body_mass_kg,
            habitat,
            image_url
        FROM species
        WHERE dietary_category = ?
        ORDER BY common_name
    ');
    $stmt->execute([$diet]);
} else {
    $stmt = $pdo->query('
        SELECT
            gbif_species_key,
            species_name,
            common_name,
            iucn_red_list_category,
            dietary_category,
            body_mass_kg,
            habitat,
            image_url
        FROM species
        ORDER BY common_name
    ');
}

$species = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<h2>Scottish Mammal Species</h2>

<form method="GET">
    <label>Filter by Diet:</label>
    <select name="diet">
        <option value="">All</option>
        <option value="Herbivore">Herbivore</option>
        <option value="Carnivore">Carnivore</option>
        <option value="Omnivore">Omnivore</option>
        <option value="Insectivore">Insectivore</option>
        <option value="Piscivore">Piscivore</option>
    </select>

    <button type="submit">Filter</button>
</form>

<p>Explore our database of 34 mammal species found in Scotland.</p>

<?php if (empty($species)): ?>
    <p>No species found in the database.</p>
<?php else: ?>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Image</th>
                <th>Common Name</th>
                <th>Scientific Name</th>
                <th>Conservation Status</th>
                <th>Dietary Category</th>
                <th>Body Mass (kg)</th>
                <th>Habitat</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($species as $sp): ?>
                <tr>

                    <td>
                        <img src="<?php echo htmlspecialchars($sp['image_url'] ?? 'images/placeholder.svg'); ?>" width="100">
                    </td>

                    <td><?php echo e($sp['common_name']); ?></td>

                    <td>
                        <em><?php echo e($sp['species_name']); ?></em>
                    </td>

                    <td>
                        <?php echo $sp['iucn_red_list_category'] ? e($sp['iucn_red_list_category']) : '—'; ?>
                    </td>

                    <td><?php echo e($sp['dietary_category']); ?></td>

                    <td><?php echo e($sp['body_mass_kg']); ?></td>

                    <td><?php echo e($sp['habitat']); ?></td>

                    <td>
                        <a href="species.php?key=<?php echo e($sp['gbif_species_key']); ?>">
                            View Details
                        </a>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>