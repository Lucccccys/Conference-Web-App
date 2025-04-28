# Conference Management System

## Overview

This web application is a **Conference Management System** designed to manage and streamline conference activities such as attendee registration, scheduling sessions, managing sponsors, hotel room bookings, financial summaries, and job postings. It is built using **PHP** and **MySQL** with **HTML/CSS** for the front-end.

The system provides an integrated portal for conference organizers to efficiently handle attendees, sponsors, sessions, subcommittees, and financial information.

## Features

- **Conference Homepage** (`conference.php`):  
  Central hub providing navigation to all major functionalities.

- **Attendee Management** (`attendees.php`):  
  Add, view, and manage conference attendees (students, professionals, speakers).

- **Session Scheduling** (`schedule.php`):  
  View sessions by date, edit session assignments, and manage the conference schedule.

- **Sponsor Management** (`sponsors.php`, `delete_sponsor.php`):  
  Add or delete sponsor companies, dynamically manage sponsorship details.

- **Subcommittee Management** (`subcommittee.php`):  
  Manage subcommittees and assign members.

- **Job Board** (`all_jobs.php`):  
  View job postings with filtering, searching, and salary sorting options.

- **Hotel Room Booking** (`hotel_rooms.php`):  
  Manage available hotel rooms for conference attendees.

- **Financial Summary** (`financial_summary.php`):  
  View dynamic financial breakdowns including sponsorship amounts and registration fees. Visual charts are available to enhance understanding.

- **Database Connection** (`db.php`):  
  Centralized script for secure and reusable database access using PDO.

## Technologies Used

- PHP (Backend)
- MySQL (Database)
- HTML/CSS (Frontend)
- PDO (PHP Data Objects) for secure database interaction

## Setup Instructions

1. **Clone the Repository**

   ```bash
   git clone https://github.com/Lucccccys/Conference-Web-App
   cd conference_app
   ```

2. **Database Setup**

   - Create a MySQL database called `conferenceDB`.
   - Import the provided SQL schema (if available) to set up necessary tables:
     - `attendee`, `student`, `professional`, `speaker`
     - `session`, `company`, `sponsor`, `jobAd`
     - `member`, `subcommittee`, `memberOf`

3. **Configuration**

   - Update your `db.php` file with your local database credentials:

     ```php
     $host = 'localhost';
     $db   = 'conferenceDB';
     $user = 'your_db_user';
     $pass = 'your_db_password';
     ```

4. **Launch the Application**

   - Start your local server (e.g., XAMPP, MAMP).
   - Place the project folder inside your server's root directory (`htdocs` for XAMPP).
   - Access the application via `http://localhost/[your-folder]/conference.php`.

## Notes

- The system uses simple sessionless navigation for administrative tasks.
- Database transactions are handled carefully, with proper error reporting via PDO.
- Ensure your PHP environment is configured with PDO MySQL extensions enabled.
- The frontend adopts a minimalist and user-friendly design for ease of use.

## Future Improvements

- Implement user authentication (admin login).
- Add attendee search and edit functionalities.
- Add email notifications for registration confirmations.
- Improve UI/UX with a modern frontend framework like Bootstrap or Tailwind CSS.
