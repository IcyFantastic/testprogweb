document.addEventListener('DOMContentLoaded', function() {
    // Role selection functionality
    const roleButtons = document.querySelectorAll('.role-btn');
    const loginForm = document.querySelector('.login-form');
    const selectedRoleInput = document.getElementById('selectedRole');

    roleButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            roleButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedRoleInput.value = btn.getAttribute('data-role');
            btn.style.transform = 'scale(0.95)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 100);
        });
    });

    // Form submission with loading animation
    loginForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.login-btn');
        const btnText = submitBtn.querySelector('.btn-text');
        const loading = submitBtn.querySelector('.loading');
        
        btnText.style.opacity = '0';
        loading.style.display = 'block';
        submitBtn.style.pointerEvents = 'none';
        
        setTimeout(() => {
            btnText.style.opacity = '1';
            loading.style.display = 'none';
            submitBtn.style.pointerEvents = 'auto';
        }, 2000);
    });

    // Input field animations
    const inputFields = document.querySelectorAll('.input-field');
    inputFields.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Add floating animation to login container
    const loginContainer = document.querySelector('.login-container');
    if (loginContainer) {
        setInterval(() => {
            loginContainer.style.transform = 'translateY(-2px)';
            setTimeout(() => {
                loginContainer.style.transform = 'translateY(0)';
            }, 2000);
        }, 4000);
    }
});