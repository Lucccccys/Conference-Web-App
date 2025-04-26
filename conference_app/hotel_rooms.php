<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hotel Room Occupancy</title>
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
        .info-box {
            background-color: #111;
            border: 1px solid white;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
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
        table {
            width: 100%;
            margin-top: 2rem;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid white;
            padding: 0.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
<main>
<h2>Select a Room</h2>
<form method="get">
    <select name="room">
        <?php
        $stmt = $pdo->query("
        SELECT r.num, r.numbed, COUNT(s.id) AS occupied
        FROM room r
        LEFT JOIN student s ON r.num = s.roomNum
        GROUP BY r.num, r.numbed
        ORDER BY occupied DESC
    ");
    while ($row = $stmt->fetch()) {
        $selected = ($_GET['room'] ?? '') == $row['num'] ? 'selected' : '';
        $label = "Room {$row['num']} ({$row['occupied']}/{$row['numbed']} beds)";
        echo "<option value='{$row['num']}' $selected>$label</option>";
    }
        ?>
    </select>
    <input type="submit" value="Show Students">
</form>

<?php
if (isset($_GET['room'])) {
    $room = $_GET['room'];

    // Fetch room details
    $stmt = $pdo->prepare("SELECT numbed FROM room WHERE num = ?");
    $stmt->execute([$room]);
    $numbed = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM student WHERE roomNum = ?");
    $stmt->execute([$room]);
    $occupied = $stmt->fetchColumn();

    $available = $numbed - $occupied;

    echo "<div class='info-box'>
            <strong>Room $room</strong><br>
            Total beds: $numbed<br>
            Occupied: $occupied<br>
            Available: $available
          </div>";

    $stmt = $pdo->prepare("SELECT a.fName, a.lName 
                           FROM attendee a 
                           JOIN student s ON a.id = s.id 
                           WHERE s.roomNum = ?");
    $stmt->execute([$room]);

    // List students in the selected room
    echo "<h3>Students in Room $room</h3><ul>";
    while ($row = $stmt->fetch()) {
        echo "<li>{$row['fName']} {$row['lName']}</li>";
    }
    echo "</ul>";
}
?>
<h3>All Room Occupancy</h3>
<table>
    <tr><th>Room</th><th>Total Beds</th><th>Occupied</th><th>Available</th></tr>
<?php
$stmt = $pdo->query("
    SELECT r.num, r.numbed, COUNT(s.id) AS occupied
    FROM room r
    LEFT JOIN student s ON r.num = s.roomNum
    GROUP BY r.num, r.numbed
    ORDER BY r.num
");
while ($row = $stmt->fetch()) {
    $avail = $row['numbed'] - $row['occupied'];
    echo "<tr>
            <td>{$row['num']}</td>
            <td>{$row['numbed']}</td>
            <td>{$row['occupied']}</td>
            <td>$avail</td>
          </tr>";
}
?>
</table>
<div class="back-home"><a href="conference.php">Return to Home</a></div>
</main>
</body>
</html>
