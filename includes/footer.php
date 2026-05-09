</div> <footer class="text-center mt-5 py-3 border-top">
    <p class="text-muted small">&copy; 2026 Library Management System | Group Project</p>
</footer>

<script src="../assets/js/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script>
    const toggle = document.getElementById('darkModeToggle');
    const label = document.getElementById('themeLabel');
    const html = document.documentElement;

    function updateInterface(theme) {
        html.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        
        
        const icon = document.getElementById('themeIcon');

        if (theme === 'dark') {
            toggle.checked = true;
            if (label) label.innerText = 'Light Mode';
            if (icon) {
                icon.setAttribute('data-icon', 'sun'); 
                icon.className = 'fas fa-sun me-1';   
                icon.style.color = 'var(--bs-warning)';
            }
        } else {
            toggle.checked = false;
            if (label) label.innerText = 'Dark Mode';
            if (icon) {
                icon.setAttribute('data-icon', 'moon'); 
                icon.className = 'fas fa-moon me-1';    
                icon.style.color = 'var(--bs-white)';
            }
        }
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    updateInterface(savedTheme);

    toggle.addEventListener('change', () => {
        updateInterface(toggle.checked ? 'dark' : 'light');
    });
</script>
</body>
</html>