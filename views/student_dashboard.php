<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit();
}

require_once '../config/db.php';

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? AND role = "student"');
$stmt->execute([$_SESSION['user_id']]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: ../actions/logout.php');
    exit();
}

$user_interests = array_filter(array_map('trim', explode(',', $student['interests'] ?? '')));
$dob_formatted = !empty($student['dob']) ? date('d/m/Y', strtotime($student['dob'])) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg portal-navbar">
        <div class="container-fluid">
            <span class="navbar-brand me-4">Student Dashboard</span>
            <div class="collapse navbar-collapse show">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Signup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="../actions/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="dashboard-container">
            <div class="student-welcome-banner">
                Welcome, <?= htmlspecialchars($student['name']) ?> (User ID: #<?= htmlspecialchars($student['user_code']) ?>)
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    Edit Profile
                </button>
            </div>

            <div class="profile-card">
                <div class="profile-field-row">
                    <div class="profile-field-label">Email:</div>
                    <div class="profile-field-value">
                        <span><?= htmlspecialchars($student['email']) ?></span>
                        <svg class="lock-icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 0-2 2v4H5a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zm1 6V3a1 1 0 0 0-2 0v4h2z"/>
                        </svg>
                    </div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Date of Birth:</div>
                    <div class="profile-field-value"><?= htmlspecialchars($dob_formatted) ?></div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Gender:</div>
                    <div class="profile-field-value"><?= htmlspecialchars($student['gender'] ?? '') ?></div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Qualification:</div>
                    <div class="profile-field-value"><?= htmlspecialchars($student['qualification'] ?? '') ?></div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Interests:</div>
                    <div class="profile-field-value">
                        <div class="form-check form-check-inline me-3 mb-0">
                            <input class="form-check-input" type="checkbox" disabled <?= in_array('Coding', $user_interests) ? 'checked' : '' ?>>
                            <label class="form-check-label text-dark">Coding</label>
                        </div>
                        <div class="form-check form-check-inline me-3 mb-0">
                            <input class="form-check-input" type="checkbox" disabled <?= in_array('Design', $user_interests) ? 'checked' : '' ?>>
                            <label class="form-check-label text-dark">Design</label>
                        </div>
                        <div class="form-check form-check-inline me-3 mb-0">
                            <input class="form-check-input" type="checkbox" disabled <?= in_array('Gaming', $user_interests) ? 'checked' : '' ?>>
                            <label class="form-check-label text-dark">Gaming</label>
                        </div>
                        <div class="form-check form-check-inline me-3 mb-0">
                            <input class="form-check-input" type="checkbox" disabled <?= in_array('Sports', $user_interests) ? 'checked' : '' ?>>
                            <label class="form-check-label text-dark">Sports</label>
                        </div>
                    </div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Class:</div>
                    <div class="profile-field-value"><?= htmlspecialchars($student['class'] ?? '') ?></div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Subject:</div>
                    <div class="profile-field-value"><?= htmlspecialchars($student['subject'] ?? '') ?></div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Marks:</div>
                    <div class="profile-field-value"><?= htmlspecialchars($student['marks'] ?? '') ?></div>
                </div>

                <div class="profile-field-row">
                    <div class="profile-field-label">Aadhaar Document:</div>
                    <div class="profile-field-value">
                        <?php if (!empty($student['aadhaar_file'])): ?>
                            <a href="../uploads/<?= htmlspecialchars($student['aadhaar_file']) ?>" target="_blank" class="pdf-icon-badge">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.127.288-.48 1.056-.874 2.296-1.177.34-.083.7-.156 1.077-.217.382-.062.775-.107 1.173-.135.28-.482.52-1.01.716-1.576.224-.648.374-1.32.448-2.002.043-.393.13-.787.262-1.168.138-.4.364-.728.675-.98.318-.258.694-.388 1.125-.388.423 0 .783.125 1.077.373.3.253.473.59.518 1.01.045.422-.058.855-.308 1.298-.24.425-.595.77-1.063 1.033a8.91 8.91 0 0 1-1.28.59c-.062.24-.132.483-.21.728-.152.478-.344.957-.573 1.433.398.24.83.447 1.294.62.472.176.96.307 1.464.394.462.08.835.253 1.118.518.286.268.428.618.428 1.05 0 .438-.156.812-.468 1.12-.31.306-.71.46-1.2.46-.57 0-1.155-.213-1.753-.64-.59-.42-1.17-1.014-1.74-1.782-.44.03-.89.076-1.35.137-.46.06-.92.14-1.38.24-.8.88-1.55 1.48-2.25 1.8-.7.32-1.37.38-2.01.18z"/>
                                </svg>
                                <span>View Aadhaar PDF</span>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Not uploaded</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../actions/edit_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Email Address</label>
                                <div class="input-group">
                                    <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($student['email']) ?>" readonly disabled>
                                    <span class="input-group-text bg-light text-muted">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 1a2 2 0 0 0-2 2v4H5a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zm1 6V3a1 1 0 0 0-2 0v4h2z"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="form-text text-muted">Email is locked and cannot be changed.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($student['dob'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold d-block">Gender</label>
                                <div class="pt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="editGenderMale" value="Male" <?= ($student['gender'] ?? '') === 'Male' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="editGenderMale">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="editGenderFemale" value="Female" <?= ($student['gender'] ?? '') === 'Female' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="editGenderFemale">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="editGenderOther" value="Other" <?= ($student['gender'] ?? '') === 'Other' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="editGenderOther">Other</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Qualification</label>
                                <select name="qualification" class="form-select">
                                    <option value="High School" <?= ($student['qualification'] ?? '') === 'High School' ? 'selected' : '' ?>>High School</option>
                                    <option value="Bachelor's" <?= ($student['qualification'] ?? '') === "Bachelor's" || ($student['qualification'] ?? '') === "Bachelors" ? 'selected' : '' ?>>Bachelor's</option>
                                    <option value="Master's" <?= ($student['qualification'] ?? '') === "Master's" ? 'selected' : '' ?>>Master's</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold d-block">Interests</label>
                                <div class="pt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="interests[]" id="editIntCoding" value="Coding" <?= in_array('Coding', $user_interests) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="editIntCoding">Coding</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="interests[]" id="editIntDesign" value="Design" <?= in_array('Design', $user_interests) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="editIntDesign">Design</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="interests[]" id="editIntGaming" value="Gaming" <?= in_array('Gaming', $user_interests) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="editIntGaming">Gaming</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="interests[]" id="editIntSports" value="Sports" <?= in_array('Sports', $user_interests) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="editIntSports">Sports</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Class</label>
                                <input type="text" name="class" class="form-control" value="<?= htmlspecialchars($student['class'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Subject</label>
                                <input type="text" name="subject" class="form-control" value="<?= htmlspecialchars($student['subject'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Marks</label>
                                <input type="number" name="marks" class="form-control" value="<?= htmlspecialchars($student['marks'] ?? '') ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label text-secondary small fw-bold">Replace Aadhaar Document (PDF only)</label>
                                <input type="file" name="aadhaar_file" class="form-control" accept=".pdf,application/pdf">
                                <div class="form-text text-muted">Leave empty if you do not wish to replace the current document.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="portal-footer">
        Basic Coding Test - 1 | Student Dashboard | Task Document
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
