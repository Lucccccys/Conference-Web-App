<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Financial Summary</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        h2, h3 {
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }
        form {
            margin-bottom: 2rem;
        }
        input[type="number"] {
            width: 100px;
            padding: 0.4rem;
            margin-right: 1rem;
            background-color: #111;
            color: white;
            border: 1px solid white;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 0.4rem 1rem;
            background-color: white;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid white;
            padding: 0.6rem;
            text-align: left;
        }
        th {
            background-color: #111;
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
        canvas {
            max-width: 250px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<main>
<h2>Financial Summary</h2>

<!-- Form: Sponsorship & Registration Settings -->
<form method="post">
    <h3>Sponsorship Amounts</h3>
    <label>Platinum: $<input type="number" name="Platinum" value="<?php echo $_POST['Platinum'] ?? 10000; ?>"></label>
    <label>Gold: $<input type="number" name="Gold" value="<?php echo $_POST['Gold'] ?? 5000; ?>"></label>
    <label>Silver: $<input type="number" name="Silver" value="<?php echo $_POST['Silver'] ?? 2500; ?>"></label>
    <label>Bronze: $<input type="number" name="Bronze" value="<?php echo $_POST['Bronze'] ?? 1000; ?>"></label>

    <h3 style="margin-top:2rem;">Registration Fees</h3>
    <label>Student: $<input type="number" name="studentFee" value="<?php echo $_POST['studentFee'] ?? 0; ?>"></label>
    <label>Professional: $<input type="number" name="professionalFee" value="<?php echo $_POST['professionalFee'] ?? 50; ?>"></label>
    <label>Sponsor: $<input type="number" name="sponsorFee" value="<?php echo $_POST['sponsorFee'] ?? 100; ?>"></label>

    <br><br><input type="submit" value="Update Summary">
</form>

<?php
// Sponsor settings
$sponsorLevels = [
    "Platinum" => $_POST['Platinum'] ?? 10000,
    "Gold" => $_POST['Gold'] ?? 5000,
    "Silver" => $_POST['Silver'] ?? 2500,
    "Bronze" => $_POST['Bronze'] ?? 1000,
];

// Registration fees
$studentFee = $_POST['studentFee'] ?? 0;
$professionalFee = $_POST['professionalFee'] ?? 50;
$sponsorFee = $_POST['sponsorFee'] ?? 100;

// Registration counts
$studentCount = $pdo->query("SELECT COUNT(*) FROM student")->fetchColumn();
$professionalCount = $pdo->query("SELECT COUNT(*) FROM professional")->fetchColumn();
$sponsorCount = $pdo->query("SELECT COUNT(*) FROM sponsor")->fetchColumn();

$registrationTotal =
    $studentCount * $studentFee +
    $professionalCount * $professionalFee +
    $sponsorCount * $sponsorFee;

// Sponsorship totals
$sponsorshipTotal = 0;
$details = [];

foreach ($sponsorLevels as $level => $amount) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM company WHERE level = ?");
    $stmt->execute([$level]);
    $count = $stmt->fetchColumn();
    $subtotal = $count * $amount;
    $sponsorshipTotal += $subtotal;
    $details[] = ["level" => $level, "count" => $count, "total" => $subtotal];
}

$totalIncome = $registrationTotal + $sponsorshipTotal;
?>

<!-- Summary Table -->
<table>
    <tr><th>Description</th><th>Amount</th></tr>
    <tr><td>Total Registration Fees</td><td>$<?php echo $registrationTotal; ?></td></tr>
    <tr><td>Total Sponsorship Amount</td><td>$<?php echo $sponsorshipTotal; ?></td></tr>
    <tr><td><strong>Grand Total</strong></td><td><strong>$<?php echo $totalIncome; ?></strong></td></tr>
</table>

<!-- Sponsorship Breakdown -->
<h3 style="margin-top: 2rem;">Sponsorship Breakdown</h3>
<table>
    <tr><th>Level</th><th>Companies</th><th>Subtotal</th></tr>
    <?php foreach ($details as $row): ?>
        <tr>
            <td><?php echo $row['level']; ?></td>
            <td><?php echo $row['count']; ?></td>
            <td>$<?php echo $row['total']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Chart.js Pie Chart -->
<h3 style="margin-top: 3rem;">Sponsorship Breakdown Chart</h3>
<canvas id="incomeChart" width="300" height="300" style="margin: auto;"></canvas>

<script>
const ctx = document.getElementById('incomeChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [<?php foreach ($details as $row) echo "'{$row['level']}',"; ?>],
        datasets: [{
            data: [<?php foreach ($details as $row) echo "{$row['total']},"; ?>],
            backgroundColor: ['#e5e4e2', '#ffd700', '#808080', '#cd7f32']
        }]
    },
    options: {
        plugins: {
            legend: { labels: { color: 'white' } }
        }
    }
});
</script>

<!-- Return button -->
<div class="back-home"><a href="conference.php">Return to Home</a></div>
</main>
</body>
</html>
