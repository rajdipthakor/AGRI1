// Form Validation
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap Form Validation
    const forms = document.querySelectorAll('form[novalidate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
});

// Search and Filter functionality
function filterJobs() {
    const searchInput = document.querySelector('input[name="search"]');
    const categorySelect = document.querySelector('select[name="category"]');
    const locationInput = document.querySelector('input[name="location"]');
    
    if (searchInput && categorySelect && locationInput) {
        // Auto-submit form on filter change
        const filterForm = searchInput.closest('form');
        if (filterForm) {
            categorySelect.addEventListener('change', () => filterForm.submit());
        }
    }
}

// Save/Unsave Job
function saveJob(jobId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_job.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            showNotification('Job saved successfully!', 'success');
        } else {
            showNotification('Error saving job', 'error');
        }
    };
    xhr.send('job_id=' + jobId);
}

// Show Notification
function showNotification(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'info': 'alert-info',
        'warning': 'alert-warning'
    }[type] || 'alert-info';

    const alertHTML = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;

    const container = document.querySelector('.container') || document.body;
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = alertHTML;
    container.insertBefore(tempDiv.firstElementChild, container.firstChild);
}

// Smooth Scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Character Counter for Textarea
const textareas = document.querySelectorAll('textarea[maxlength]');
textareas.forEach(textarea => {
    textarea.addEventListener('input', function() {
        const counter = this.nextElementSibling;
        if (counter && counter.classList.contains('char-count')) {
            counter.textContent = `${this.value.length} / ${this.maxLength}`;
        }
    });
});

// Tooltip Initialization
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Format Currency Input
const currencyInputs = document.querySelectorAll('input[type="currency"]');
currencyInputs.forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
});

// Date validation
const dateInputs = document.querySelectorAll('input[type="date"]');
dateInputs.forEach(input => {
    input.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        if (selectedDate < today) {
            alert('Please select a future date');
            this.value = '';
        }
    });
});

// Confirm Delete
function confirmDelete(message = 'Are you sure you want to delete this?') {
    return confirm(message);
}

// Animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeIn 0.5s ease';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.card').forEach(card => {
    observer.observe(card);
});

// Toggle Password Visibility
const togglePasswordBtns = document.querySelectorAll('.toggle-password');
togglePasswordBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input[type="password"], input[type="text"]');
        if (input.type === 'password') {
            input.type = 'text';
            this.classList.add('active');
        } else {
            input.type = 'password';
            this.classList.remove('active');
        }
    });
});

// Live Search
let searchTimeout;
const liveSearchInput = document.querySelector('.live-search');
if (liveSearchInput) {
    liveSearchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            // Implement live search functionality
            console.log('Searching for:', this.value);
        }, 300);
    });
}

// Print Functionality
function printPage() {
    window.print();
}

// Export to CSV
function exportTableToCSV(filename = 'export.csv') {
    const table = document.querySelector('table');
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        let csvRow = [];
        cols.forEach(col => {
            csvRow.push('"' + col.innerText + '"');
        });
        csv.push(csvRow.join(','));
    });

    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Mobile Menu Toggle
const navToggle = document.querySelector('.navbar-toggler');
if (navToggle) {
    navToggle.addEventListener('click', function() {
        this.classList.toggle('active');
    });
}

// Auto-hide alerts
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }, 5000);
});
