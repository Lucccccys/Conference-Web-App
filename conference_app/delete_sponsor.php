<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Delete Sponsoring Company</title></head>
<body>
<h2>Delete a Sponsoring Company and Its Attendees</h2>
<form method="post">
    <label>Select Company:</label>
    <select name="company">
        <?php
        $stmt = $pdo->query("SELECT name FROM company");
        while ($row = $stmt->fetch()) {
            echo "<option value='{$row['name']}'>{$row['name']}</option>";
        }
        ?>
    </select>
    <input type="submit" name="delete" value="Delete Company and Attendees">
</form>

<?php
if (isset($_POST['delete'])) {
    $company = $_POST['company'];
    $stmt = $pdo->prepare("SELECT id FROM sponsor WHERE companyName = ?");
    $stmt->execute([$company]);
    $sponsorIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt = $pdo->prepare("DELETE FROM attendee WHERE id = ?");
    foreach ($sponsorIDs as $id) {
        $stmt->execute([$id]);
    }
    $stmt = $pdo->prepare("DELETE FROM company WHERE name = ?");
    $stmt->execute([$company]);
    echo "<p>Sponsoring company and associated attendees deleted.</p>";
}
?>
</body>
</html>