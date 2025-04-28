document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    const verifyForm = document.getElementById('verifyForm');

    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/; // At least 8 characters, including numbers and letters.

    // Toggle password visibility on Register Page
    const togglePasswordRegister = document.getElementById('togglePasswordRegister');
    const registerPasswordField = document.getElementById('password');
    if (togglePasswordRegister && registerPasswordField) {
        togglePasswordRegister.addEventListener('click', () => {
            const type = registerPasswordField.type === 'password' ? 'text' : 'password';
            registerPasswordField.type = type;
            togglePasswordRegister.innerHTML = type === 'password' ? '&#128065;' : '&#x1F441;'; // Mata tertutup/mata terbuka
        });
    }

    // Toggle password visibility on Login Page
    const togglePasswordLogin = document.getElementById('togglePasswordLogin');
    const loginPasswordField = document.getElementById('loginPassword');
    if (togglePasswordLogin && loginPasswordField) {
        togglePasswordLogin.addEventListener('click', () => {
            const type = loginPasswordField.type === 'password' ? 'text' : 'password';
            loginPasswordField.type = type;
            togglePasswordLogin.innerHTML = type === 'password' ? '&#128065;' : '&#x1F441;'; // Mata tertutup/mata terbuka
        });
    }

    // Registrasi
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            const password = document.getElementById('password').value;
            if (!passwordPattern.test(password)) {
                alert('Password must be at least 8 characters long and contain both numbers and letters.');
                e.preventDefault();
                return;
            }

            e.preventDefault();
            const formData = new FormData(registerForm);
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Registration successful, please verify your account.');
                    window.location.href = 'verify.html';
                } else {
                    alert('Registration failed: ' + data.message);
                }
            });
        });
    }

    // Login
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            const password = document.getElementById('loginPassword').value;
            if (!passwordPattern.test(password)) {
                alert('Password must be at least 8 characters long and contain both numbers and letters.');
                e.preventDefault();
                return;
            }

            e.preventDefault();
            const formData = new FormData(loginForm);
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'homepage.html';
                } else {
                    alert('Login failed: ' + data.message);
                }
            });
        });
    }

    // Verifikasi
    if (verifyForm) {
        verifyForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(verifyForm);
            fetch('verify.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Account verified successfully! Please log in.');
                    window.location.href = 'index.html';
                } else {
                    alert('Verification failed: ' + data.message);
                }
            });
        });
    }
});
