<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendees</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        main {
            padding: 6rem 2rem 2rem;
        }
        h2 {
            border-bottom: 1px solid #fff;
            padding-bottom: 0.5rem;
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        li {
            margin: 0.25rem 0;
        }
        form input, form select {
            margin-bottom: 0.5rem;
            padding: 0.4rem;
            width: 100%;
            max-width: 300px;
            background-color: #111;
            color: white;
            border: 1px solid white;
            border-radius: 5px;
        }
        form {
            margin-top: 2rem;
        }
        input[type="submit"], button {
            background-color: white;
            color: black;
            font-weight: bold;
            border: none;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 5px;
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
    <script>
        function updateFee() {
            const category = document.getElementById('category').value;
            const feeField = document.getElementById('fee');
            if (category === 'student') {
                feeField.value = '50';
            } else if (category === 'professional') {
                feeField.value = '100';
            } else {
                feeField.value = '0';
            }
        }
    </script>
</head>
<body>
<main>

<h2>Students</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT fName, lName FROM attendee WHERE id IN (SELECT id FROM student)");
while ($row = $stmt->fetch()) {
    echo "<li>{$row['fName']} {$row['lName']}</li>";
}
?>
</ul>

<h2>Professionals</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT fName, lName FROM attendee WHERE id IN (SELECT id FROM professional)");
while ($row = $stmt->fetch()) {
    echo "<li>{$row['fName']} {$row['lName']}</li>";
}
?>
</ul>

<h2>Sponsors</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT fName, lName FROM attendee WHERE id IN (SELECT id FROM sponsor)");
while ($row = $stmt->fetch()) {
    echo "<li>{$row['fName']} {$row['lName']}</li>";
}
?>
</ul>

<h2>Add Attendee</h2>
<form method="post">
    Attendee ID: <input type="number" name="id" required><br>
    First Name: <input type="text" name="fName" required><br>
    Last Name: <input type="text" name="lName" required><br>
    Category:
    <select name="category" id="category" onchange="updateFee()" required>
        <option value="">-- Select Category --</option>
        <option value="student">Student</option>
        <option value="professional">Professional</option>
        <option value="sponsor">Sponsor</option>
    </select><br>
    Registration Fee: <input type="text" name="fee" id="fee" readonly><br>
    Room Number (if student):
    <select name="roomNum">
        <option value="">-- Select Room --</option>
        <?php
        $stmt = $pdo->query("
            SELECT r.num, r.numbed, COUNT(s.id) AS occupied
            FROM room r
            LEFT JOIN student s ON r.num = s.roomNum
            GROUP BY r.num, r.numbed
            ORDER BY r.num
        ");
        while ($row = $stmt->fetch()) {
            $label = "Room {$row['num']} ({$row['occupied']}/{$row['numbed']} beds)";
            echo "<option value='{$row['num']}'>{$label}</option>";
        }
        ?>
    </select><br>
    Company Name (if sponsor): <input type="text" name="company"><br>
    <input type="submit" value="Add Attendee">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $attendeeID = $_POST['id'];
        $stmt = $pdo->prepare("INSERT INTO attendee (id, fName, lName, fee) VALUES (?, ?, ?, ?)");
        $stmt->execute([$attendeeID, $_POST['fName'], $_POST['lName'], $_POST['fee']]);
        $category = $_POST['category'];

        if ($category === 'student') {
            $stmt = $pdo->prepare("INSERT INTO student (id, roomNum) VALUES (?, ?)");
            $stmt->execute([$attendeeID, $_POST['roomNum']]);
        } elseif ($category === 'professional') {
            $stmt = $pdo->prepare("INSERT INTO professional (id) VALUES (?)");
            $stmt->execute([$attendeeID]);
        } elseif ($category === 'sponsor') {
            $stmt = $pdo->prepare("INSERT INTO sponsor (id, companyName) VALUES (?, ?)");
            $stmt->execute([$attendeeID, $_POST['company']]);
        }

        echo "<p style='color: lightgreen; font-weight: bold;'>✅ Attendee added successfully!</p>";
        echo "<form method='get'><button type='submit'>🔄 Refresh Page</button></form>";

    } catch (PDOException $e) {
        echo "<p style='color: red; font-weight: bold;'>❌ Failed to add attendee: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<div class="back-home"><a href="conference.php">Return to Home</a></div>
</main>
</body>
</html>
