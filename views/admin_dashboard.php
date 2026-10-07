<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../config/db.php';

$stmt = $pdo->query('SELECT * FROM users WHERE role = "student" ORDER BY id DESC');
$students = $stmt->fetchAll();

function calculateStudentAge($dob) {
    if (empty($dob)) return null;
    $birth = new DateTime($dob);
    $today = new DateTime('today');
    return $birth->diff($today)->y;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg portal-navbar">
        <div class="container-fluid">
            <span class="navbar-brand me-4">Admin Dashboard</span>
            <div class="collapse navbar-collapse show">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-secondary small fw-semibold">Logged in as Administrator</span>
                    <a href="../actions/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content d-block">
        <div class="admin-container py-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="m-0 text-dark">Registered Students</h4>
                <span class="badge bg-primary fs-6"><?= count($students) ?> Students</span>
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

            <div class="table-filter-bar shadow-sm">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label text-secondary small fw-bold mb-1">Search by Name</label>
                        <input type="text" id="filterName" class="form-control form-control-sm" placeholder="Enter student name...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-secondary small fw-bold mb-1">Search by Class</label>
                        <input type="text" id="filterClass" class="form-control form-control-sm" placeholder="Enter class name...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-secondary small fw-bold mb-1">Min Age</label>
                        <input type="number" id="filterMinAge" class="form-control form-control-sm" placeholder="e.g. 18" min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-secondary small fw-bold mb-1">Max Age</label>
                        <input type="number" id="filterMaxAge" class="form-control form-control-sm" placeholder="e.g. 30" min="0">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="resetFiltersBtn" class="btn btn-outline-secondary btn-sm w-100">
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 align-middle" id="studentTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 90px;">User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>DOB</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Qualification</th>
                                <th>Interests</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Document</th>
                                <th style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr id="noRecordsRow">
                                    <td colspan="13" class="text-center text-muted py-4">No student records found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $row): 
                                    $student_age = calculateStudentAge($row['dob']);
                                    $dob_display = !empty($row['dob']) ? date('d/m/Y', strtotime($row['dob'])) : '-';
                                ?>
                                    <tr class="student-row" 
                                        data-name="<?= htmlspecialchars($row['name']) ?>" 
                                        data-class="<?= htmlspecialchars($row['class'] ?? '') ?>" 
                                        data-age="<?= $student_age !== null ? $student_age : '' ?>">
                                        <td class="fw-bold text-primary">#<?= htmlspecialchars($row['user_code']) ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($dob_display) ?></td>
                                        <td><?= $student_age !== null ? $student_age : '-' ?></td>
                                        <td><?= htmlspecialchars($row['gender'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['qualification'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['interests'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['class'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['subject'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['marks'] ?? '-') ?></td>
                                        <td>
                                            <?php if (!empty($row['aadhaar_file'])): ?>
                                                <a href="../uploads/<?= htmlspecialchars($row['aadhaar_file']) ?>" target="_blank" class="pdf-icon-badge">
                                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                    </svg>
                                                    <span>View</span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">None</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editStudentModal"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                                    data-dob="<?= htmlspecialchars($row['dob'] ?? '') ?>"
                                                    data-gender="<?= htmlspecialchars($row['gender'] ?? '') ?>"
                                                    data-qualification="<?= htmlspecialchars($row['qualification'] ?? '') ?>"
                                                    data-interests="<?= htmlspecialchars($row['interests'] ?? '') ?>"
                                                    data-class="<?= htmlspecialchars($row['class'] ?? '') ?>"
                                                    data-subject="<?= htmlspecialchars($row['subject'] ?? '') ?>"
                                                    data-marks="<?= htmlspecialchars($row['marks'] ?? '') ?>">
                                                    Edit
                                                </button>
                                                <a href="../actions/admin_delete.php?id=<?= $row['id'] ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this student record? This action cannot be undone.')">
                                                    Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr id="noRecordsRow" style="display: none;">
                                    <td colspan="13" class="text-center text-muted py-4">No matching student records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="../actions/admin_edit.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="student_id" id="editStudentId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStudentModalLabel">Edit Student Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Full Name (Locked)</label>
                                <input type="text" id="editStudentName" class="form-control bg-light" readonly disabled>
                                <div class="form-text text-muted">Student name cannot be modified by admin.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Email Address (Locked)</label>
                                <input type="email" id="editStudentEmail" class="form-control bg-light" readonly disabled>
                                <div class="form-text text-muted">Student email cannot be modified by admin.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Date of Birth</label>
                                <input type="date" name="dob" id="editStudentDob" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold d-block">Gender</label>
                                <div class="pt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-gender" type="radio" name="gender" id="adminGenderMale" value="Male">
                                        <label class="form-check-label" for="adminGenderMale">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-gender" type="radio" name="gender" id="adminGenderFemale" value="Female">
                                        <label class="form-check-label" for="adminGenderFemale">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-gender" type="radio" name="gender" id="adminGenderOther" value="Other">
                                        <label class="form-check-label" for="adminGenderOther">Other</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Qualification</label>
                                <select name="qualification" id="editStudentQualification" class="form-select">
                                    <option value="High School">High School</option>
                                    <option value="Bachelor's">Bachelor's</option>
                                    <option value="Master's">Master's</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold d-block">Interests</label>
                                <div class="pt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-interest" type="checkbox" name="interests[]" id="adminIntCoding" value="Coding">
                                        <label class="form-check-label" for="adminIntCoding">Coding</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-interest" type="checkbox" name="interests[]" id="adminIntDesign" value="Design">
                                        <label class="form-check-label" for="adminIntDesign">Design</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-interest" type="checkbox" name="interests[]" id="adminIntGaming" value="Gaming">
                                        <label class="form-check-label" for="adminIntGaming">Gaming</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-interest" type="checkbox" name="interests[]" id="adminIntSports" value="Sports">
                                        <label class="form-check-label" for="adminIntSports">Sports</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Class</label>
                                <input type="text" name="class" id="editStudentClass" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Subject</label>
                                <input type="text" name="subject" id="editStudentSubject" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">Marks</label>
                                <input type="number" name="marks" id="editStudentMarks" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label text-secondary small fw-bold">Replace Aadhaar File (Optional)</label>
                                <input type="file" name="aadhaar_file" class="form-control" accept=".pdf,application/pdf">
                                <div class="form-text text-muted">Upload a new PDF to replace the existing Aadhaar document.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="portal-footer">
        Basic Coding Test - 1 | Admin Dashboard | Task Document
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
