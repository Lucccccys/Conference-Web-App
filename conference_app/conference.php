<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('conference_banner.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed; /* Makes it scroll with a nice effect */
            color: white; /* Optional: make text white for contrast */
            font-family: sans-serif;
        }
        h1 {
            text-align: center;
            padding-top: 20px;
            font-size: 3rem;
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
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            padding: 2rem;
        }
        .card {
            background: #1a1a1a;
            border-radius: 12px;
            padding: 1.5rem 2rem;
            text-align: center;
            color: white;
            width: 220px;
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        .card:hover {
            transform: translateY(-10px);
            background: #333;
        }
        .card a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Welcome to the Conference Portal</h1>
<div class="cards">
    <a href="subcommittee.php" class="card-link"><div class="card">Subcommittees</div></a>
    <a href="hotel_rooms.php" class="card-link"><div class="card">Hotel Rooms</div></a>
    <a href="schedule.php" class="card-link"><div class="card">Schedule</div></a>
    <a href="sponsors.php" class="card-link"><div class="card">Sponsors</div></a>
    <a href="all_jobs.php" class="card-link"><div class="card">Jobs</div></a>
    <a href="attendees.php" class="card-link"><div class="card">Attendees</div></a>
    <a href="financial_summary.php" class="card-link"><div class="card">Financials</div></a>
</div>
</body>
</html>
