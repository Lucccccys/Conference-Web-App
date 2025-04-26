<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Conference Schedule</title>
    <style>
    body {
        background-color: black;
        color: white;
        font-family: Arial, sans-serif;
        margin: 0;
    }

    main {
        padding: 4rem 2rem;
    }

    .date-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid white;
    }

    .date-tab {
        background-color: #222;
        border: 1px solid white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        color: white;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .date-tab:hover,
    .date-tab.active {
        background-color: white;
        color: black;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2rem;
    }

    th, td {
        border: 1px solid white;
        padding: 0.5rem;
        text-align: left;
    }

    .edit-form {
        margin-top: 3rem;
        padding: 1.5rem;
        border: 1px solid white;
        border-radius: 10px;
        background-color: #111;
    }

    .edit-form h3 {
        margin-top: 0;
    }

    .edit-form input {
        display: block;
        margin: 0.5rem 0;
        padding: 0.4rem;
        width: 100%;
        max-width: 400px;
        background-color: #000;
        color: white;
        border: 1px solid #555;
        border-radius: 4px;
    }

    .edit-form input[type="submit"] {
        background-color: white;
        color: black;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .edit-form input[type="submit"]:hover {
        background-color: #ccc;
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

    a {
        text-decoration: none;
        color: inherit;
    }

    a:visited,
    a:active,
    a:hover,
    a:focus {
        color: inherit;
        text-decoration: none;
    }
</style>
</head>
<body>
<main>
<h2>Conference Schedule</h2>

<!-- Date Tabs -->
<div class="date-tabs">
<?php
$selectedDate = $_GET['date'] ?? '';
$dates = $pdo->query("SELECT DISTINCT sessionDate FROM session ORDER BY sessionDate")->fetchAll(PDO::FETCH_COLUMN);

foreach ($dates as $d) {
    $active = ($d == $selectedDate) ? 'active' : '';
    echo "<a class='date-tab $active' href='schedule.php?date=$d'>$d</a>";
}
?>
</div>

<form method="get" style="margin-top: 1rem;">
    <label for="date">Or select a date manually:</label><br>
    <input type="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
    <input type="submit" value="Show Schedule">
</form>

<!-- Schedule Table -->
<?php
if ($selectedDate) {
    $stmt = $pdo->prepare("SELECT s.location, s.sessionDate, s.startTime, s.endTime, a.fName, a.lName 
                           FROM session s 
                           JOIN speaker sp ON s.speakerID = sp.id 
                           JOIN attendee a ON sp.id = a.id 
                           WHERE s.sessionDate = ? ORDER BY s.startTime");
    $stmt->execute([$selectedDate]);
    echo "<h3>Schedule for $selectedDate</h3><table><tr><th>Time</th><th>Location</th><th>Speaker</th><th>Edit</th></tr>";
    while ($row = $stmt->fetch()) {
        $key = $row['location'] . "_" . $row['sessionDate'] . "_" . $row['startTime'];
        echo "<tr>
                <td>{$row['startTime']} - {$row['endTime']}</td>
                <td>{$row['location']}</td>
                <td>{$row['fName']} {$row['lName']}</td>
                <td><a href='schedule.php?date={$selectedDate}&edit={$key}'>Edit</a></td>
              </tr>";
    }
    echo "</table>";
}
?>

<!-- Switch Session Form at Bottom -->
<?php
if (isset($_GET['edit'])) {
    list($loc, $date, $start) = explode("_", $_GET['edit']);
?>
<div class="edit-form">
    <h3>Edit Session (<?php echo "$start @ $loc"; ?>)</h3>
    <form method="post">
        <input type="hidden" name="orig_key" value="<?php echo $_GET['edit']; ?>">
        New Date: <input type="date" name="newDate"><br>
        New Start: <input type="time" name="newStart"><br>
        New End: <input type="time" name="newEnd"><br>
        New Location: <input type="number" name="newLoc"><br>
        <input type="submit" value="Save Changes">
    </form>
</div>
<?php } ?>

<!-- Handle Session Update -->
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['orig_key'])) {
    list($loc, $date, $start) = explode("_", $_POST['orig_key']);
    $stmt = $pdo->prepare("UPDATE session SET sessionDate=?, startTime=?, endTime=?, location=? 
                           WHERE location=? AND sessionDate=? AND startTime=?");
    $stmt->execute([
        $_POST['newDate'] ?: $date,
        $_POST['newStart'] ?: $start,
        $_POST['newEnd'],
        $_POST['newLoc'] ?: $loc,
        $loc, $date, $start
    ]);
    echo "<p>✅ Session updated. <a href='schedule.php?date=" . ($_GET['date'] ?? $date) . "'>Refresh</a></p>";
}
?>
<div class="back-home"><a href="conference.php" style="color:white;border:1px solid white;padding:0.5rem 1rem;border-radius:6px;text-decoration:none;">Return to Home</a></div>
</main>
</body>
</html>
