document.addEventListener('DOMContentLoaded', function () {
    const filterNameInput = document.getElementById('filterName');
    const filterClassInput = document.getElementById('filterClass');
    const filterMinAgeInput = document.getElementById('filterMinAge');
    const filterMaxAgeInput = document.getElementById('filterMaxAge');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');
    const studentTable = document.getElementById('studentTable');

    function filterStudents() {
        if (!studentTable) return;
        const nameQuery = (filterNameInput ? filterNameInput.value : '').toLowerCase().trim();
        const classQuery = (filterClassInput ? filterClassInput.value : '').toLowerCase().trim();
        const minAge = filterMinAgeInput && filterMinAgeInput.value !== '' ? parseInt(filterMinAgeInput.value, 10) : null;
        const maxAge = filterMaxAgeInput && filterMaxAgeInput.value !== '' ? parseInt(filterMaxAgeInput.value, 10) : null;

        const rows = studentTable.querySelectorAll('tbody tr.student-row');
        let visibleCount = 0;

        rows.forEach(function (row) {
            const studentName = (row.getAttribute('data-name') || '').toLowerCase();
            const studentClass = (row.getAttribute('data-class') || '').toLowerCase();
            const studentAgeAttr = row.getAttribute('data-age');
            const studentAge = studentAgeAttr !== '' && !isNaN(studentAgeAttr) ? parseInt(studentAgeAttr, 10) : null;

            let matchesName = nameQuery === '' || studentName.includes(nameQuery);
            let matchesClass = classQuery === '' || studentClass.includes(classQuery);
            let matchesMinAge = minAge === null || (studentAge !== null && studentAge >= minAge);
            let matchesMaxAge = maxAge === null || (studentAge !== null && studentAge <= maxAge);

            if (matchesName && matchesClass && matchesMinAge && matchesMaxAge) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noRecordsRow = document.getElementById('noRecordsRow');
        if (noRecordsRow) {
            noRecordsRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    if (filterNameInput) filterNameInput.addEventListener('input', filterStudents);
    if (filterClassInput) filterClassInput.addEventListener('input', filterStudents);
    if (filterMinAgeInput) filterMinAgeInput.addEventListener('input', filterStudents);
    if (filterMaxAgeInput) filterMaxAgeInput.addEventListener('input', filterStudents);

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function () {
            if (filterNameInput) filterNameInput.value = '';
            if (filterClassInput) filterClassInput.value = '';
            if (filterMinAgeInput) filterMinAgeInput.value = '';
            if (filterMaxAgeInput) filterMaxAgeInput.value = '';
            filterStudents();
        });
    }

    const fileInputs = document.querySelectorAll('input[type="file"][accept*="pdf"]');
    fileInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const fileName = file.name.toLowerCase();
                if (!fileName.endsWith('.pdf')) {
                    alert('Only PDF documents are allowed. Please select a valid PDF file.');
                    this.value = '';
                }
            }
        });
    });

    const editStudentModal = document.getElementById('editStudentModal');
    if (editStudentModal) {
        editStudentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            document.getElementById('editStudentId').value = button.getAttribute('data-id') || '';
            document.getElementById('editStudentName').value = button.getAttribute('data-name') || '';
            document.getElementById('editStudentEmail').value = button.getAttribute('data-email') || '';
            document.getElementById('editStudentDob').value = button.getAttribute('data-dob') || '';
            document.getElementById('editStudentClass').value = button.getAttribute('data-class') || '';
            document.getElementById('editStudentSubject').value = button.getAttribute('data-subject') || '';
            document.getElementById('editStudentMarks').value = button.getAttribute('data-marks') || '';

            const gender = button.getAttribute('data-gender') || '';
            const genderRadios = document.querySelectorAll('input[name="gender"].edit-gender');
            genderRadios.forEach(function (radio) {
                radio.checked = radio.value === gender;
            });

            const qualification = button.getAttribute('data-qualification') || '';
            const qualSelect = document.getElementById('editStudentQualification');
            if (qualSelect) qualSelect.value = qualification;

            const interestsStr = button.getAttribute('data-interests') || '';
            const interestsArray = interestsStr.split(',').map(s => s.trim());
            const interestCheckboxes = document.querySelectorAll('input[name="interests[]"].edit-interest');
            interestCheckboxes.forEach(function (cb) {
                cb.checked = interestsArray.includes(cb.value);
            });
        });
    }
});
