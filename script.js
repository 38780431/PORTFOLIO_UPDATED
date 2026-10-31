document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.container');
    const loginBtn = document.getElementById('login');
    const registerBtn = document.getElementById('register');

    // Check URL for action parameter
    const urlParams = new URLSearchParams(window.location.search);
    const action = urlParams.get('action');
    
    if (action === 'login') {
        container.classList.add('active');
    }

    registerBtn.addEventListener('click', () => {
        container.classList.add('active');
        // Update URL without reload
        history.pushState(null, '', '?action=register');
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove('active');
        // Update URL without reload
        history.pushState(null, '', '?action=login');
    });
});