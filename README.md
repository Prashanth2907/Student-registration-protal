# Student Registration Portal & Management System

A full-stack web application designed for student registration, document management, and role-based administration.

---

## Tech Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Backend**: PHP 8 (with PDO) / Node.js
- **Database**: MySQL (`student_portal`)
- **Document Handling**: PDF upload validation with randomized unique filename hashing

---

## Directory Structure

```text
student-registration-portal/
├── config/
│   └── db.php
├── database.sql
├── actions/
│   ├── login.php
│   ├── signup.php
│   ├── logout.php
│   ├── edit_profile.php
│   ├── admin_edit.php
│   ├── admin_delete.php
│   └── forgot_password.php
├── views/
│   ├── login.php
│   ├── signup.php
│   ├── student_dashboard.php
│   ├── admin_dashboard.php
│   └── forgot_password.php
├── admin/
│   └── dashboard/
│       └── index.php
├── student/
│   └── dashboard/
│       └── index.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── uploads/
│   └── sample_aadhaar.pdf
├── fallback/
│   ├── index.html
│   ├── login.html
│   ├── signup.html
│   ├── student_dashboard.html
│   ├── admin_dashboard.html
│   ├── forgot_password.html
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── simulation.js
├── index.php
├── .htaccess
├── server.js
├── package.json
└── README.md
```

---

## Features

### 1. Student Signup
- Captures Full Name, Email, Password, Date of Birth, Gender (radio), Qualification (dropdown), Interests (checkboxes), Class, Subject, Marks, and Aadhaar Document (PDF only).
- **Duplicate Email Prevention**: Returns the exact error message `"This email is already registered."` when attempting to register with an existing email.
- **Upload Security**: Uploaded PDF files are verified and renamed on the server (`aadhaar_[timestamp]_[hash].pdf`) to prevent file collisions.

### 2. Unified Login & Session Access Control
- A single login screen for both students and administrators.
- Admin credentials redirect to `/admin/dashboard`.
- Student credentials redirect to `/student/dashboard`.
- Protected routes enforce server-side session checks, blocking unauthorized URL access.
- Includes a functional Forgot Password flow.

### 3. Student Dashboard
- Welcome banner displayed as: `Welcome, [User Name] (User ID: #[ID])`.
- Displays all submitted student details with a lock icon beside the uneditable email address.
- Provides an **Edit Profile** feature permitting updates to information and document replacement, while strictly keeping Email locked.

### 4. Admin Dashboard
- Displays all registered students in a responsive data table with computed ages.
- **Real-Time Instant Search**: Dynamic filtering by Name and Class as you type.
- **Age Filter**: Filters table rows dynamically by minimum and maximum age.
- **Document Access**: Clickable links to view Aadhaar PDF documents in a separate browser tab.
- **CRUD Operations**: Ability to delete student records and edit student information with Full Name and Email strictly locked (`readonly`/`disabled`).

### 5. Evaluation Fallback Simulation
- An offline, client-side simulation (`fallback/`) powered by `localStorage` as requested in the evaluation guidelines for testing without an active database server.

---

## Default Login Credentials

| Role | Email | Password | Target View |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `admin123` | Admin Dashboard |
| **Student** | `john.doe@example.com` | `student123` | Student Dashboard |

---

## How to Run

### Method 1: Instant Node.js Runner (Zero Configuration)
If you have Node.js installed:
```powershell
node server.js
```
Open `http://localhost:3000/` in your browser.

### Method 2: PHP + Apache + MySQL (XAMPP / WAMP)
1. Copy this folder into your web root (e.g., `C:/xampp/htdocs/student-registration-portal`).
2. Start Apache and MySQL services.
3. Open phpMyAdmin (`http://localhost/phpmyadmin/`) and import `database.sql`.
4. Update database credentials in `config/db.php` if required.
5. Visit `http://localhost/student-registration-portal/` in your browser.

### Method 3: Direct Browser Run (Offline Simulation)
Open `fallback/login.html` directly in any web browser.
