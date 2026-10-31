document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('container');
  const loginBtn = document.getElementById('login');
  const registerBtn = document.getElementById('register');
  const forgotPasswordLink = document.getElementById('forgotPasswordLink');
  const forgotPasswordForm = document.getElementById('forgotPasswordForm');
  const resetPasswordForm = document.getElementById('resetPasswordForm');
  const signIn = document.querySelector('.sign-in');
  const signUp = document.querySelector('.sign-up');
  const toggleContainer = document.querySelector('.toggle-container');

  const urlParams = new URLSearchParams(window.location.search);
  const action = urlParams.get('action');

  function showMode(mode) {
    const recoveryMode = mode === 'forgot' || mode === 'reset';
    container.classList.toggle('active', mode === 'register');
    forgotPasswordForm.hidden = mode !== 'forgot';
    resetPasswordForm.hidden = mode !== 'reset';
    signIn.style.display = recoveryMode ? 'none' : 'block';
    signUp.style.display = recoveryMode ? 'none' : 'block';
    toggleContainer.style.display = recoveryMode ? 'none' : 'block';
  }

  if (action === 'register') {
    showMode('register');
  } else if (action === 'forgot') {
    showMode('forgot');
  } else if (action === 'reset' || urlParams.has('token')) {
    showMode('reset');
  }

  document.getElementById('resetToken').value = urlParams.get('token') || '';

  registerBtn.addEventListener('click', () => {
    showMode('register');
    history.pushState(null, '', '?action=register');
  });

  loginBtn.addEventListener('click', () => {
    showMode('login');
    history.pushState(null, '', '?action=login');
  });

  forgotPasswordLink.addEventListener('click', (e) => {
    e.preventDefault();
    showMode('forgot');
    history.pushState(null, '', '?action=forgot');
  });

  [document.getElementById('backToLoginBtn'), document.getElementById('resetBackToLoginBtn')]
    .forEach((button) => {
      button.addEventListener('click', () => {
        showMode('login');
        history.pushState(null, '', '?action=login');
      });
    });

  const error = urlParams.get('error');
  if (error) {
    const messages = {
      email_exists: 'Email already exists!',
      invalid_credentials: 'Invalid email or password!',
      invalid_email: 'Please enter a valid email address!',
      password_short: 'Password must be at least 8 characters!',
      missing_fields: 'Please complete all required fields.',
      reset_invalid: 'This password reset link is invalid or has expired. Request a new link.',
      mail_unavailable: 'Password reset email is temporarily unavailable. Please try again later.',
      server_error: 'An error occurred. Please try again.',
    };
    const target = action === 'forgot'
      ? document.getElementById('forgotError')
      : action === 'reset'
        ? document.getElementById('resetError')
        : action === 'register'
          ? document.getElementById('registerError')
          : document.getElementById('loginError');
    target.textContent = messages[error] || messages.server_error;
    target.hidden = false;
  }

  if (urlParams.get('resetRequested') === '1') {
    showMode('forgot');
    document.getElementById('forgotSuccess').hidden = false;
  }

  if (urlParams.get('registered') === '1') {
    document.getElementById('registeredSuccess').hidden = false;
    showMode('login');
  }

  if (urlParams.get('loggedout') === '1') {
    document.getElementById('loggedOutSuccess').hidden = false;
    showMode('login');
  }

  if (urlParams.get('passwordReset') === '1') {
    document.getElementById('passwordResetSuccess').hidden = false;
    showMode('login');
  }
});
