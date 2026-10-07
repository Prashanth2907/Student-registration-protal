<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        header('Location: student_dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Signup Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg portal-navbar">
        <div class="container-fluid">
            <span class="navbar-brand me-4">Student Registration Portal</span>
            <div class="collapse navbar-collapse show">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="signup.php">Signup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="signup-card">
            <h3 class="card-title-custom">Student Signup Form</h3>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="../actions/signup.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Email Address (email)" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password (password)" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold d-block">Gender</label>
                        <div class="pt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" checked>
                                <label class="form-check-label" for="genderMale">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female">
                                <label class="form-check-label" for="genderFemale">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Other">
                                <label class="form-check-label" for="genderOther">Other</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Qualification</label>
                        <select name="qualification" class="form-select" required>
                            <option value="High School">High School</option>
                            <option value="Bachelor's">Bachelor's</option>
                            <option value="Master's">Master's</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold d-block">Interests</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestCoding" value="Coding">
                            <label class="form-check-label" for="interestCoding">Coding</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestDesign" value="Design">
                            <label class="form-check-label" for="interestDesign">Design</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestGaming" value="Gaming">
                            <label class="form-check-label" for="interestGaming">Gaming</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestSports" value="Sports">
                            <label class="form-check-label" for="interestSports">Sports</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Class</label>
                        <input type="text" name="class" class="form-control" placeholder="Class" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Marks</label>
                        <input type="number" name="marks" class="form-control" placeholder="No. to, number" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Aadhaar Document Upload</label>
                        <input type="file" name="aadhaar_file" class="form-control" accept=".pdf,application/pdf" required>
                        <div class="form-text text-muted">Upload PDF only</div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2">Register</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="portal-footer">
        Basic Coding Test - 1 | Student Signup | Task Document
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
