// Create animated particles
function createParticles() {
    const background = document.querySelector('.background');
    const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dfe6e9'];
    
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 15 + 5;
        const color = colors[Math.floor(Math.random() * colors.length)];
        const left = Math.random() * 100;
        const delay = Math.random() * 8;
        const duration = Math.random() * 5 + 5;
        
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        particle.style.backgroundColor = color;
        particle.style.left = left + '%';
        particle.style.bottom = '-20px';
        particle.style.animationDelay = delay + 's';
        particle.style.animationDuration = duration + 's';
        particle.style.boxShadow = `0 0 ${size * 2}px ${color}`;
        
        background.appendChild(particle);
    }
}

createParticles();

// Form validation
const form = document.getElementById('registrationForm');
const fullName = document.getElementById('fullName');
const email = document.getElementById('email');
const phone = document.getElementById('phone');
const terms = document.getElementById('terms');

function validateName() {
    const namePattern = /^[a-zA-Z\s]{2,50}$/;
    const nameError = document.getElementById('nameError');
    
    if (!namePattern.test(fullName.value.trim())) {
        nameError.textContent = 'Please enter a valid name (letters only, 2-50 characters)';
        return false;
    }
    nameError.textContent = '';
    return true;
}

function validateEmail() {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailError = document.getElementById('emailError');
    
    if (!emailPattern.test(email.value.trim())) {
        emailError.textContent = 'Please enter a valid email address';
        return false;
    }
    emailError.textContent = '';
    return true;
}

function validatePhone() {
    const phonePattern = /^[0-9]{10}$/;
    const phoneError = document.getElementById('phoneError');
    
    if (!phonePattern.test(phone.value.trim())) {
        phoneError.textContent = 'Please enter a valid 10-digit phone number';
        return false;
    }
    phoneError.textContent = '';
    return true;
}

fullName.addEventListener('blur', validateName);
email.addEventListener('blur', validateEmail);
phone.addEventListener('blur', validatePhone);

form.addEventListener('submit', function(e) {
    let isValid = true;

    if (!validateName()) isValid = false;
    if (!validateEmail()) isValid = false;
    if (!validatePhone()) isValid = false;

    if (!terms.checked) {
        alert('Please accept the terms and conditions');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
});

// Add input animation effects
const inputs = document.querySelectorAll('input, select, textarea');
inputs.forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});
