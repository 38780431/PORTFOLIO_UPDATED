        document.addEventListener('DOMContentLoaded', function() {
            showSection('home');
            
            const navLinks = document.querySelectorAll('.nav-links a');
            navLinks.forEach(link => {
                const href = link.getAttribute('href') || '';
                // If link is an internal section anchor (starts with #), handle normally.
                if (href.startsWith('#')) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const sectionId = this.getAttribute('href').substring(1);
                        showSection(sectionId);
                        document.querySelector('nav').classList.remove('mobile-menu');
                    });
                }
            });

            // Logout confirmation: handle links that point to server routes like /logout
            const logoutLink = document.querySelector('.nav-links a[href="/logout"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const ok = confirm('Are you sure you want to log out?');
                    if (ok) {
                        // Navigate to server logout route which clears session and redirects to login page
                        window.location.href = '/logout';
                    }
                });
            }
            
            const hamburger = document.querySelector('.hamburger');
            hamburger.addEventListener('click', function() {
                document.querySelector('nav').classList.toggle('mobile-menu');
            });
        });

        function showSection(id) {
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
                section.classList.remove('active-section');
            });
            
            const section = document.getElementById(id);
            if (section) {
                section.style.display = 'block';
                section.classList.add('active-section');
            }
            
            document.querySelectorAll('.nav-links li').forEach(item => {
                item.classList.remove('active');
            });
            const activeLink = document.querySelector(`.nav-links a[href="#${id}"]`);
            if (activeLink) {
                activeLink.parentElement.classList.add('active');
            }
            
            window.scrollTo(0, 0);
        }
