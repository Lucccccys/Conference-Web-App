<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Job Opportunities</title>
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
            margin-bottom: 1rem;
        }
        form {
            margin-bottom: 2rem;
        }
        input, select {
            padding: 0.4rem;
            margin: 0.3rem 1rem 0.3rem 0;
            background: #111;
            color: white;
            border: 1px solid white;
            border-radius: 4px;
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
    </style>
</head>
<body>
<main>
<h2>All Available Job Opportunities</h2>

<form method="get">
    <label for="company">Company:</label>
    <select name="company">
        <option value="">-- All Companies --</option>
        <?php
        $companies = $pdo->query("SELECT DISTINCT companyName FROM jobAd ORDER BY companyName");
        while ($c = $companies->fetch()) {
            $selected = ($_GET['company'] ?? '') === $c['companyName'] ? 'selected' : '';
            echo "<option value='{$c['companyName']}' $selected>{$c['companyName']}</option>";
        }
        ?>
    </select>

    <label for="keyword">Search:</label>
    <input type="text" name="keyword" placeholder="Title or Location" value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">

    <label for="sort">Sort by Salary:</label>
    <select name="sort">
        <option value="">-- None --</option>
        <option value="asc" <?php if (($_GET['sort'] ?? '') === 'asc') echo 'selected'; ?>>Low to High</option>
        <option value="desc" <?php if (($_GET['sort'] ?? '') === 'desc') echo 'selected'; ?>>High to Low</option>
    </select>

    <input type="submit" value="Filter">
</form>


<table>
    <tr>
        <th>Company</th>
        <th>Job Title</th>
        <th>Salary</th>
        <th>Location</th>
    </tr>

<?php
// Prepare the SQL query with filters
$sql = "SELECT * FROM jobAd WHERE 1=1";
$params = [];

// Filter by company
if (!empty($_GET['company'])) {
    $sql .= " AND companyName = ?";
    $params[] = $_GET['company'];
}

// Filter by keyword
if (!empty($_GET['keyword'])) {
    $sql .= " AND (jobTitle LIKE ? OR location LIKE ?)";
    $kw = "%" . $_GET['keyword'] . "%";
    $params[] = $kw;
    $params[] = $kw;
}

// Sort by salary if specified
if (!empty($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc'])) {
    $sql .= " ORDER BY salary " . strtoupper($_GET['sort']);
} else {
    $sql .= " ORDER BY companyName, jobTitle";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Fetch and display the results
while ($row = $stmt->fetch()) {
    echo "<tr>
            <td>{$row['companyName']}</td>
            <td>{$row['jobTitle']}</td>
            <td>\${$row['salary']}</td>
            <td>{$row['location']}</td>
          </tr>";
}
?>
</table>

<div class="back-home"><a href="conference.php">Return to Home</a></div>
</main>
</body>
</html>
