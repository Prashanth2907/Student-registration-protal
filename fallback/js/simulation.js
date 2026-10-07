const DEFAULT_USERS = [
    {
        id: 1,
        user_code: 'ADM001',
        name: 'Administrator',
        email: 'admin@example.com',
        password: 'admin123',
        role: 'admin',
        dob: '1985-01-01',
        gender: 'Other',
        qualification: "Master's",
        interests: 'Coding',
        class: 'Admin Staff',
        subject: 'Administration',
        marks: 100,
        aadhaar_file: null
    },
    {
        id: 2,
        user_code: 'STU001',
        name: 'John Doe',
        email: 'john.doe@example.com',
        password: 'student123',
        role: 'student',
        dob: '1994-03-20',
        gender: 'Male',
        qualification: 'Bachelors',
        interests: 'Coding,Design',
        class: 'form-control',
        subject: 'form-control',
        marks: 1200,
        aadhaar_file: 'sample_aadhaar.pdf'
    },
    {
        id: 3,
        user_code: 'STU002',
        name: 'Jane Smith',
        email: 'jane.smith@example.com',
        password: 'student123',
        role: 'student',
        dob: '1998-07-15',
        gender: 'Female',
        qualification: "Master's",
        interests: 'Design,Gaming',
        class: 'Class 12',
        subject: 'Physics',
        marks: 1150,
        aadhaar_file: 'sample_aadhaar.pdf'
    },
    {
        id: 4,
        user_code: 'STU003',
        name: 'Alex Johnson',
        email: 'alex.j@example.com',
        password: 'student123',
        role: 'student',
        dob: '2004-11-05',
        gender: 'Male',
        qualification: 'High School',
        interests: 'Sports,Coding',
        class: 'Class 10',
        subject: 'Mathematics',
        marks: 980,
        aadhaar_file: 'sample_aadhaar.pdf'
    }
];

function getUsers() {
    const data = localStorage.getItem('portal_users');
    if (!data) {
        localStorage.setItem('portal_users', JSON.stringify(DEFAULT_USERS));
        return DEFAULT_USERS;
    }
    return JSON.parse(data);
}

function saveUsers(users) {
    localStorage.setItem('portal_users', JSON.stringify(users));
}

function getCurrentUser() {
    const data = localStorage.getItem('portal_current_user');
    return data ? JSON.parse(data) : null;
}

function setCurrentUser(user) {
    localStorage.setItem('portal_current_user', JSON.stringify(user));
}

function clearCurrentUser() {
    localStorage.removeItem('portal_current_user');
}

function calculateAge(dobStr) {
    if (!dobStr) return null;
    const dob = new Date(dobStr);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    return age;
}

function formatDate(dobStr) {
    if (!dobStr) return '-';
    const parts = dobStr.split('-');
    if (parts.length === 3) {
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }
    return dobStr;
}

document.addEventListener('DOMContentLoaded', function () {
    getUsers();

    const currentUser = getCurrentUser();
    const pathname = window.location.pathname;

    const isStudentPage = pathname.includes('student_dashboard');
    const isAdminPage = pathname.includes('admin_dashboard');
    const isAuthPage = pathname.includes('login') || pathname.includes('signup') || pathname.includes('forgot_password');

    if (isStudentPage) {
        if (!currentUser || currentUser.role !== 'student') {
            window.location.href = 'login.html';
            return;
        }
        initStudentDashboard(currentUser);
    }

    if (isAdminPage) {
        if (!currentUser || currentUser.role !== 'admin') {
            window.location.href = 'login.html';
            return;
        }
        initAdminDashboard();
    }

    if (isAuthPage && currentUser) {
        if (currentUser.role === 'admin') {
            window.location.href = 'admin_dashboard.html';
            return;
        } else if (currentUser.role === 'student') {
            window.location.href = 'student_dashboard.html';
            return;
        }
    }

    const logoutButtons = document.querySelectorAll('.action-logout');
    logoutButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            clearCurrentUser();
            window.location.href = 'login.html';
        });
    });

    const loginForm = document.getElementById('simulationLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;
            const alertBox = document.getElementById('loginAlert');

            const users = getUsers();
            const user = users.find(u => u.email.toLowerCase() === email.toLowerCase() && u.password === password);

            if (user) {
                setCurrentUser(user);
                if (user.role === 'admin') {
                    window.location.href = 'admin_dashboard.html';
                } else {
                    window.location.href = 'student_dashboard.html';
                }
            } else {
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertBox.innerHTML = 'Invalid email or password.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                alertBox.style.display = 'block';
            }
        });
    }

    const signupForm = document.getElementById('simulationSignupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const alertBox = document.getElementById('signupAlert');
            const name = document.getElementById('signupName').value.trim();
            const email = document.getElementById('signupEmail').value.trim();
            const password = document.getElementById('signupPassword').value;
            const dob = document.getElementById('signupDob').value;
            const genderEl = document.querySelector('input[name="gender"]:checked');
            const gender = genderEl ? genderEl.value : 'Male';
            const qualification = document.getElementById('signupQualification').value;
            const classVal = document.getElementById('signupClass').value.trim();
            const subjectVal = document.getElementById('signupSubject').value.trim();
            const marksVal = document.getElementById('signupMarks').value;
            const fileInput = document.getElementById('signupAadhaarFile');

            const interestEls = document.querySelectorAll('input[name="interests[]"]:checked');
            const interests = Array.from(interestEls).map(el => el.value).join(',');

            const users = getUsers();
            const emailExists = users.some(u => u.email.toLowerCase() === email.toLowerCase());

            if (emailExists) {
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertBox.innerHTML = 'This email is already registered.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                alertBox.style.display = 'block';
                return;
            }

            if (!fileInput.files || fileInput.files.length === 0) {
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertBox.innerHTML = 'Please upload your Aadhaar document.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                alertBox.style.display = 'block';
                return;
            }

            const fileName = fileInput.files[0].name.toLowerCase();
            if (!fileName.endsWith('.pdf')) {
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertBox.innerHTML = 'Only PDF documents are allowed.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                alertBox.style.display = 'block';
                return;
            }

            const randomizedFileName = 'aadhaar_' + Date.now() + '_' + Math.random().toString(36).substring(2, 8) + '.pdf';

            const nextId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1;
            const studentCount = users.filter(u => u.role === 'student').length + 1;
            const userCode = 'STU' + String(studentCount).padStart(3, '0');

            const newUser = {
                id: nextId,
                user_code: userCode,
                name: name,
                email: email,
                password: password,
                role: 'student',
                dob: dob,
                gender: gender,
                qualification: qualification,
                interests: interests,
                class: classVal,
                subject: subjectVal,
                marks: parseInt(marksVal, 10),
                aadhaar_file: randomizedFileName
            };

            users.push(newUser);
            saveUsers(users);
            setCurrentUser(newUser);

            window.location.href = 'student_dashboard.html';
        });
    }

    const forgotForm = document.getElementById('simulationForgotForm');
    if (forgotForm) {
        forgotForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const alertBox = document.getElementById('forgotAlert');
            const email = document.getElementById('forgotEmail').value.trim();
            const password = document.getElementById('forgotPassword').value;
            const confirmPassword = document.getElementById('forgotConfirmPassword').value;

            if (password !== confirmPassword) {
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertBox.innerHTML = 'Passwords do not match.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                alertBox.style.display = 'block';
                return;
            }

            const users = getUsers();
            const userIndex = users.findIndex(u => u.email.toLowerCase() === email.toLowerCase());

            if (userIndex === -1) {
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertBox.innerHTML = 'No account found with this email address.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                alertBox.style.display = 'block';
                return;
            }

            users[userIndex].password = password;
            saveUsers(users);

            localStorage.setItem('portal_flash_success', 'Password reset successfully. Please log in with your new password.');
            window.location.href = 'login.html';
        });
    }

    const flashSuccess = localStorage.getItem('portal_flash_success');
    if (flashSuccess) {
        localStorage.removeItem('portal_flash_success');
        const loginAlert = document.getElementById('loginAlert');
        if (loginAlert) {
            loginAlert.className = 'alert alert-success alert-dismissible fade show';
            loginAlert.innerHTML = flashSuccess + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            loginAlert.style.display = 'block';
        }
    }
});

function initStudentDashboard(user) {
    const banner = document.getElementById('studentWelcomeBanner');
    if (banner) {
        banner.textContent = 'Welcome, ' + user.name + ' (User ID: #' + user.user_code + ')';
    }

    document.getElementById('dispEmail').textContent = user.email;
    document.getElementById('dispDob').textContent = formatDate(user.dob);
    document.getElementById('dispGender').textContent = user.gender || '-';
    document.getElementById('dispQualification').textContent = user.qualification || '-';
    document.getElementById('dispClass').textContent = user.class || '-';
    document.getElementById('dispSubject').textContent = user.subject || '-';
    document.getElementById('dispMarks').textContent = user.marks !== null ? user.marks : '-';

    const interestsList = (user.interests || '').split(',').map(s => s.trim());
    ['Coding', 'Design', 'Gaming', 'Sports'].forEach(interest => {
        const cb = document.getElementById('dispInterest_' + interest);
        if (cb) cb.checked = interestsList.includes(interest);
    });

    const docLink = document.getElementById('dispAadhaarLink');
    if (docLink) {
        docLink.href = '../uploads/' + (user.aadhaar_file || 'sample_aadhaar.pdf');
    }

    const editName = document.getElementById('editProfileName');
    const editEmail = document.getElementById('editProfileEmail');
    const editDob = document.getElementById('editProfileDob');
    const editQual = document.getElementById('editProfileQualification');
    const editClass = document.getElementById('editProfileClass');
    const editSubject = document.getElementById('editProfileSubject');
    const editMarks = document.getElementById('editProfileMarks');

    if (editName) editName.value = user.name;
    if (editEmail) editEmail.value = user.email;
    if (editDob) editDob.value = user.dob || '';
    if (editQual) editQual.value = user.qualification || 'Bachelor\'s';
    if (editClass) editClass.value = user.class || '';
    if (editSubject) editSubject.value = user.subject || '';
    if (editMarks) editMarks.value = user.marks || '';

    const genderRadios = document.querySelectorAll('input[name="gender"].edit-gender');
    genderRadios.forEach(radio => {
        radio.checked = radio.value === user.gender;
    });

    const intCheckboxes = document.querySelectorAll('input[name="interests[]"].edit-interest');
    intCheckboxes.forEach(cb => {
        cb.checked = interestsList.includes(cb.value);
    });

    const editForm = document.getElementById('studentEditForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const users = getUsers();
            const index = users.findIndex(u => u.id === user.id);
            if (index !== -1) {
                users[index].name = editName.value.trim();
                users[index].dob = editDob.value;
                const checkedGender = document.querySelector('input[name="gender"].edit-gender:checked');
                users[index].gender = checkedGender ? checkedGender.value : users[index].gender;
                users[index].qualification = editQual.value;
                users[index].class = editClass.value.trim();
                users[index].subject = editSubject.value.trim();
                users[index].marks = parseInt(editMarks.value, 10);

                const checkedInts = Array.from(document.querySelectorAll('input[name="interests[]"].edit-interest:checked')).map(c => c.value);
                users[index].interests = checkedInts.join(',');

                const fileInput = document.getElementById('editProfileAadhaar');
                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    const fileName = fileInput.files[0].name.toLowerCase();
                    if (fileName.endsWith('.pdf')) {
                        users[index].aadhaar_file = 'aadhaar_' + Date.now() + '_' + Math.random().toString(36).substring(2, 8) + '.pdf';
                    }
                }

                saveUsers(users);
                setCurrentUser(users[index]);

                const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                if (modal) modal.hide();

                initStudentDashboard(users[index]);

                const successAlert = document.getElementById('dashboardAlert');
                if (successAlert) {
                    successAlert.className = 'alert alert-success alert-dismissible fade show';
                    successAlert.innerHTML = 'Profile updated successfully.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    successAlert.style.display = 'block';
                }
            }
        });
    }
}

function initAdminDashboard() {
    renderAdminTable();

    const nameInput = document.getElementById('filterName');
    const classInput = document.getElementById('filterClass');
    const minAgeInput = document.getElementById('filterMinAge');
    const maxAgeInput = document.getElementById('filterMaxAge');
    const resetBtn = document.getElementById('resetFiltersBtn');

    function filterTable() {
        renderAdminTable();
    }

    if (nameInput) nameInput.addEventListener('input', filterTable);
    if (classInput) classInput.addEventListener('input', filterTable);
    if (minAgeInput) minAgeInput.addEventListener('input', filterTable);
    if (maxAgeInput) maxAgeInput.addEventListener('input', filterTable);

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (nameInput) nameInput.value = '';
            if (classInput) classInput.value = '';
            if (minAgeInput) minAgeInput.value = '';
            if (maxAgeInput) maxAgeInput.value = '';
            renderAdminTable();
        });
    }

    const editForm = document.getElementById('adminEditStudentForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const studentId = parseInt(document.getElementById('adminEditStudentId').value, 10);
            const users = getUsers();
            const index = users.findIndex(u => u.id === studentId && u.role === 'student');

            if (index !== -1) {
                users[index].dob = document.getElementById('adminEditStudentDob').value;
                const checkedGender = document.querySelector('input[name="gender"].edit-gender:checked');
                users[index].gender = checkedGender ? checkedGender.value : users[index].gender;
                users[index].qualification = document.getElementById('adminEditStudentQualification').value;
                users[index].class = document.getElementById('adminEditStudentClass').value.trim();
                users[index].subject = document.getElementById('adminEditStudentSubject').value.trim();
                users[index].marks = parseInt(document.getElementById('adminEditStudentMarks').value, 10);

                const checkedInts = Array.from(document.querySelectorAll('input[name="interests[]"].edit-interest:checked')).map(c => c.value);
                users[index].interests = checkedInts.join(',');

                const fileInput = document.getElementById('adminEditStudentAadhaar');
                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    const fileName = fileInput.files[0].name.toLowerCase();
                    if (fileName.endsWith('.pdf')) {
                        users[index].aadhaar_file = 'aadhaar_' + Date.now() + '_' + Math.random().toString(36).substring(2, 8) + '.pdf';
                    }
                }

                saveUsers(users);

                const modal = bootstrap.Modal.getInstance(document.getElementById('editStudentModal'));
                if (modal) modal.hide();

                renderAdminTable();

                const successAlert = document.getElementById('adminAlert');
                if (successAlert) {
                    successAlert.className = 'alert alert-success alert-dismissible fade show';
                    successAlert.innerHTML = 'Student record updated successfully.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    successAlert.style.display = 'block';
                }
            }
        });
    }
}

function renderAdminTable() {
    const tbody = document.querySelector('#studentTable tbody');
    if (!tbody) return;

    const nameQuery = (document.getElementById('filterName')?.value || '').toLowerCase().trim();
    const classQuery = (document.getElementById('filterClass')?.value || '').toLowerCase().trim();
    const minAgeVal = document.getElementById('filterMinAge')?.value;
    const maxAgeVal = document.getElementById('filterMaxAge')?.value;
    const minAge = minAgeVal !== '' && !isNaN(minAgeVal) ? parseInt(minAgeVal, 10) : null;
    const maxAge = maxAgeVal !== '' && !isNaN(maxAgeVal) ? parseInt(maxAgeVal, 10) : null;

    const users = getUsers().filter(u => u.role === 'student');
    const badge = document.getElementById('studentTotalBadge');
    if (badge) badge.textContent = users.length + ' Students';

    let html = '';
    let visibleCount = 0;

    users.forEach(student => {
        const age = calculateAge(student.dob);
        const nameMatches = nameQuery === '' || student.name.toLowerCase().includes(nameQuery);
        const classMatches = classQuery === '' || (student.class || '').toLowerCase().includes(classQuery);
        const minAgeMatches = minAge === null || (age !== null && age >= minAge);
        const maxAgeMatches = maxAge === null || (age !== null && age <= maxAge);

        if (nameMatches && classMatches && minAgeMatches && maxAgeMatches) {
            visibleCount++;
            html += `
                <tr class="student-row">
                    <td class="fw-bold text-primary">#${student.user_code}</td>
                    <td>${student.name}</td>
                    <td>${student.email}</td>
                    <td>${formatDate(student.dob)}</td>
                    <td>${age !== null ? age : '-'}</td>
                    <td>${student.gender || '-'}</td>
                    <td>${student.qualification || '-'}</td>
                    <td>${student.interests || '-'}</td>
                    <td>${student.class || '-'}</td>
                    <td>${student.subject || '-'}</td>
                    <td>${student.marks !== null ? student.marks : '-'}</td>
                    <td>
                        <a href="../uploads/${student.aadhaar_file || 'sample_aadhaar.pdf'}" target="_blank" class="pdf-icon-badge">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            </svg>
                            <span>View</span>
                        </a>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="openAdminEditModal(${student.id})">Edit</button>
                            <button type="button" class="btn btn-outline-danger" onclick="deleteStudentRecord(${student.id})">Delete</button>
                        </div>
                    </td>
                </tr>
            `;
        }
    });

    if (visibleCount === 0) {
        html = '<tr><td colspan="13" class="text-center text-muted py-4">No matching student records found.</td></tr>';
    }

    tbody.innerHTML = html;
}

function openAdminEditModal(id) {
    const users = getUsers();
    const student = users.find(u => u.id === id);
    if (!student) return;

    document.getElementById('adminEditStudentId').value = student.id;
    document.getElementById('adminEditStudentName').value = student.name;
    document.getElementById('adminEditStudentEmail').value = student.email;
    document.getElementById('adminEditStudentDob').value = student.dob || '';
    document.getElementById('adminEditStudentQualification').value = student.qualification || 'Bachelor\'s';
    document.getElementById('adminEditStudentClass').value = student.class || '';
    document.getElementById('adminEditStudentSubject').value = student.subject || '';
    document.getElementById('adminEditStudentMarks').value = student.marks || '';

    const genderRadios = document.querySelectorAll('input[name="gender"].edit-gender');
    genderRadios.forEach(radio => {
        radio.checked = radio.value === student.gender;
    });

    const interestsList = (student.interests || '').split(',').map(s => s.trim());
    const intCheckboxes = document.querySelectorAll('input[name="interests[]"].edit-interest');
    intCheckboxes.forEach(cb => {
        cb.checked = interestsList.includes(cb.value);
    });

    const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    modal.show();
}

function deleteStudentRecord(id) {
    if (confirm('Are you sure you want to delete this student record? This action cannot be undone.')) {
        let users = getUsers();
        users = users.filter(u => u.id !== id);
        saveUsers(users);
        renderAdminTable();

        const successAlert = document.getElementById('adminAlert');
        if (successAlert) {
            successAlert.className = 'alert alert-success alert-dismissible fade show';
            successAlert.innerHTML = 'Student record deleted successfully.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            successAlert.style.display = 'block';
        }
    }
}
