
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

const password = document.getElementById('password');
const strengthBar = document.getElementById('strength-bar');

password.addEventListener('input', function() {
    const value = password.value;
    let strength = 0;

    if (value.length >= 8) strength += 20;
    if (/[A-Z]/.test(value)) strength += 20;
    if (/[a-z]/.test(value)) strength += 20;
    if (/[0-9]/.test(value)) strength += 20;
    if (/[^A-Za-z0-9]/.test(value)) strength += 20;

    strengthBar.style.width = strength + '%';

    if (strength < 40) {
        strengthBar.style.backgroundColor = '#dc3545';
    } else if (strength < 80) {
        strengthBar.style.backgroundColor = '#ffc107';
    } else {
        strengthBar.style.backgroundColor = '#28a745';
    }
});

const confirmPassword = document.getElementById('confirm_password');
const passwordError = document.getElementById('password-error');

function checkPasswordMatch() {
    if (password.value !== confirmPassword.value) {
        passwordError.style.display = "block";
        confirmPassword.style.border = "2px solid red";
    } else {
        passwordError.style.display = "none";
        confirmPassword.style.border = "2px solid green";
    }
}

confirmPassword.addEventListener('input', checkPasswordMatch);
