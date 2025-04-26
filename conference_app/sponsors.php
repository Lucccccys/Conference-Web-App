<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sponsors</title>
    <style>
        body { background-color: black; color: white; font-family: Arial, sans-serif; margin: 0; }
        main { padding: 6rem 2rem; }
        h2 { border-bottom: 1px solid white; padding-bottom: 0.5rem; }
        form { margin: 1.5rem 0; }
        input, select {
            padding: 0.4rem;
            margin: 0.3rem 0;
            background: #111;
            color: white;
            border: 1px solid white;
            border-radius: 4px;
            width: 100%;
            max-width: 300px;
        }
        input[type="submit"] {
            background-color: white;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #ccc;
        }
        ul { list-style: none; padding-left: 0; }
        li { margin: 0.4rem 0; }
        .back-home a {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.5rem 1rem;
            border: 1px solid white;
            border-radius: 6px;
            color: white;
            text-decoration: none;
        }
        .back-home a:hover {
            background-color: white;
            color: black;
        }
    </style>
</head>
<body>
<main>

<h2>Sponsors and Levels</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT name, level FROM company ORDER BY name");
while ($row = $stmt->fetch()) {
    echo "<li>{$row['name']} - <strong>{$row['level']}</strong></li>";
}
?>
</ul>

<hr>

<h2>Add Sponsor Company</h2>
<form method="post">
    Company Name:<br>
    <input type="text" name="name" required><br>
    Sponsorship Level:<br>
    <select name="level">
        <option>Gold</option>
        <option>Silver</option>
        <option>Bronze</option>
        <option>Platinum</option>
    </select><br>
    <input type="submit" name="add" value="Add Company">
</form>

<?php
if (isset($_POST['add'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO company (name, level, emailsSent) VALUES (?, ?, 0)");
        $stmt->execute([$_POST['name'], $_POST['level']]);
        echo "<p style='color: lightgreen; font-weight: bold;'>Company added successfully!</p>";
        echo "<form method='get'><button type='submit'>Refresh Page</button></form>";
    } catch (PDOException $e) {
        echo "<p style='color: red; font-weight: bold;'>Failed to add company: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<hr>

<h2>Delete Sponsoring Company & Related Attendees</h2>
<form method="post">
    <select name="deleteName">
        <?php
        $stmt = $pdo->query("SELECT name FROM company ORDER BY name");
        while ($row = $stmt->fetch()) {
            echo "<option value='{$row['name']}'>{$row['name']}</option>";
        }
        ?>
    </select><br>
    <input type="submit" name="delete" value="Delete Company & Attendees">
</form>

<?php
if (isset($_POST['delete'])) {
    try{
        $company = $_POST['deleteName'];

        // get sponsor IDs
        $stmt = $pdo->prepare("SELECT id FROM sponsor WHERE companyName = ?");
        $stmt->execute([$company]);
        $sponsorIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // delete attendees
        $stmt = $pdo->prepare("DELETE FROM attendee WHERE id = ?");
        foreach ($sponsorIDs as $id) {
            $stmt->execute([$id]);
        }

        // delete sponsors
        $stmt = $pdo->prepare("DELETE FROM company WHERE name = ?");
        $stmt->execute([$company]);

        echo "<p style='color: lightgreen; font-weight: bold;'>Company and related attendees deleted successfully!</p>";
        echo "<form method='get'><button type='submit'>Refresh Page</button></form>";
        } catch (PDOException $e) {
            echo "<p style='color: red; font-weight: bold;'>Failed to delete company: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    
}
?>

<div class="back-home"><a href="conference.php">Return to Home</a></div>
</main>
</body>
</html>
