# StudySync - Capstone Web Project (23CSE404)

StudySync is a multi-page student productivity web application built to satisfy the requirements of the Web Technologies Capstone Project.

## Project Purpose
StudySync helps students:
- Register and log in securely
- Create and manage study tasks
- Track focus sessions using a built-in Pomodoro timer
- Submit feedback through a contact form
- Upload a profile picture

## Features
- Multi-page architecture (Home, About, Register, Login, Dashboard, Contact, Profile)
- Responsive UI using HTML/CSS (Box Model, Positioning, Floats, media queries)
- JavaScript interactivity:
  - Client-side form validation
  - Dynamic Pomodoro timer (DOM + events)
- PHP backend:
  - Form handling
  - Session management
  - Cookie handling (Remember Me)
  - File upload (JPG/PNG profile image)
- MySQL integration:
  - User authentication
  - Task CRUD operations
  - Contact message storage

## Technologies Used
- HTML5
- CSS3
- JavaScript (Vanilla)
- PHP 8+
- MySQL

## Setup / Installation
1. Clone the repository.
2. Place the project in your local PHP server directory (XAMPP `htdocs`, MAMP `htdocs`, etc.).
3. Create a MySQL database named `studysync_db`.
4. Import `schema.sql` into the database.
5. Update DB credentials in `includes/db.php` if needed.
6. Start Apache and MySQL.
7. Visit `http://localhost/projectsForFriends/index.php`.

## Deployment Notes
- Push code to a public GitHub repository with meaningful incremental commits.
- Deploy static pages through GitHub Pages if needed.
- Deploy PHP + MySQL app to a live host such as InfinityFree, 000webhost, etc.
- On shared hosting, avoid `CREATE DATABASE` and `USE` commands in SQL imports.

## Rubric Mapping Summary
- Design/UI: Consistent and responsive multi-page layout.
- JavaScript: Validation + timer with DOM/event handling.
- PHP: Server-side form processing, sessions, cookies, file upload.
- Database: CRUD with task table and data retrieval.
- GitHub/Deployment: README and repository-ready structure.
