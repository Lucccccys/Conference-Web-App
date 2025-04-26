<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subcommittee Members</title>
    <style>
        body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        main {
            padding: 6rem 2rem;
        }
        h2 {
            font-size: 2rem;
        }
        select, input[type="submit"] {
            padding: 0.5rem;
            font-size: 1rem;
            margin: 1rem 0;
            background-color: #222;
            color: white;
            border: 1px solid white;
            border-radius: 5px;
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 0.5rem;
        }
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
<h2>Select a Subcommittee</h2>
<form method="get">
    <select name="subcommittee">
        <?php
        $stmt = $pdo->query("SELECT name FROM subcommittee ORDER BY name");
        while ($row = $stmt->fetch()) {
            $selected = ($_GET['subcommittee'] ?? '') === $row['name'] ? 'selected' : '';
            echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
        }
        ?>
    </select>
    <input type="submit" value="Show Members">
</form>

<?php
if (isset($_GET['subcommittee'])) {
    $name = $_GET['subcommittee'];
    $stmt = $pdo->prepare("SELECT m.fName, m.lName 
                           FROM member m 
                           JOIN memberOf mo ON m.id = mo.id 
                           WHERE mo.subcommittee = ?");
    $stmt->execute([$name]);

    echo "<h3>Members of $name</h3><ul>";
    while ($row = $stmt->fetch()) {
        echo "<li>{$row['fName']} {$row['lName']}</li>";
    }
    echo "</ul>";
}
?>
<div class="back-home"><a href="conference.php">Return to Home</a></div>
</main>
</body>
</html>
