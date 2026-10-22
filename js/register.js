/* ============================================================
   StudentHub – register.js
   Practical 5: Registration Form with Frontend Validation
   & User-Friendly Error Handling
   Skills: HTML forms, JS validation, regex, password strength
           meter, UX error messages, real-time validation
   ============================================================ */

/* ── REGULAR EXPRESSIONS ───────────────────────────────────── */
const REGEX = {
  fullname: /^[A-Za-z\s]{2,50}$/,
  email:    /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
  mobile:   /^[6-9]\d{9}$/          // Indian 10-digit mobile
};

/* ── CANVAS CAPTCHA (Practical 5 – Advanced) ───────────────── */
let captchaCode = '';

function generateCaptcha() {
  const canvas = document.getElementById('captcha-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  // random 5-character code
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  captchaCode = '';
  for (let i = 0; i < 5; i++) {
    captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
  }

  // clear canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // background
  ctx.fillStyle = '#f0f0f0';
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  // noise lines
  for (let i = 0; i < 5; i++) {
    ctx.beginPath();
    ctx.moveTo(Math.random() * canvas.width, Math.random() * canvas.height);
    ctx.lineTo(Math.random() * canvas.width, Math.random() * canvas.height);
    ctx.strokeStyle = 'rgba(0,0,0,0.' + Math.floor(Math.random() * 3 + 1) + ')';
    ctx.lineWidth = 1;
    ctx.stroke();
  }

  // noise dots
  for (let i = 0; i < 30; i++) {
    ctx.beginPath();
    ctx.arc(Math.random() * canvas.width, Math.random() * canvas.height, 1, 0, 2 * Math.PI);
    ctx.fillStyle = 'rgba(0,0,0,0.3)';
    ctx.fill();
  }

  // draw each character with slight rotation
  const colors = ['#e74c3c', '#2ecc71', '#3498db', '#9b59b6', '#e67e22'];
  for (let i = 0; i < captchaCode.length; i++) {
    ctx.save();
    ctx.font = 'bold ' + (22 + Math.random() * 8) + 'px Arial';
    ctx.fillStyle = colors[i % colors.length];
    var x = 20 + i * 34;
    var y = 30 + Math.random() * 10;
    ctx.translate(x, y);
    ctx.rotate((Math.random() - 0.5) * 0.4);
    ctx.fillText(captchaCode[i], 0, 0);
    ctx.restore();
  }

  // clear input
  var input = document.getElementById('captcha-input');
  if (input) input.value = '';
  clearState('captcha-input');
}

function validateCaptcha() {
  var val = document.getElementById('captcha-input').value.trim().toUpperCase();
  if (!val) { showError('captcha-input', '⚠ Please enter the CAPTCHA code.'); return false; }
  if (val !== captchaCode) { showError('captcha-input', '⚠ CAPTCHA does not match. Try again.'); generateCaptcha(); return false; }
  showValid('captcha-input');
  return true;
}

/* ── HELPERS ────────────────────────────────────────────────── */
function showError(fieldId, message) {
  const input = document.getElementById(fieldId);
  const error = document.getElementById(fieldId + '-error');
  if (input)  input.classList.add('input-error');
  if (input)  input.classList.remove('input-valid');
  if (error)  error.textContent = message;
}

function showValid(fieldId) {
  const input = document.getElementById(fieldId);
  const error = document.getElementById(fieldId + '-error');
  if (input)  input.classList.remove('input-error');
  if (input)  input.classList.add('input-valid');
  if (error)  error.textContent = '';
}

function clearState(fieldId) {
  const input = document.getElementById(fieldId);
  const error = document.getElementById(fieldId + '-error');
  if (input)  { input.classList.remove('input-error', 'input-valid'); }
  if (error)  error.textContent = '';
}

/* ── INDIVIDUAL VALIDATORS (returns true = valid) ───────────── */
function validateFullName() {
  const val = document.getElementById('fullname').value.trim();
  if (!val)                       { showError('fullname', '⚠ Full name is required.'); return false; }
  if (!REGEX.fullname.test(val))  { showError('fullname', '⚠ Only letters and spaces (2–50 characters).'); return false; }
  showValid('fullname');
  return true;
}

function validateEmail() {
  const val = document.getElementById('email').value.trim();
  if (!val)                     { showError('email', '⚠ Email address is required.'); return false; }
  if (!REGEX.email.test(val))   { showError('email', '⚠ Enter a valid email (e.g. name@domain.com).'); return false; }
  showValid('email');
  return true;
}

function validateMobile() {
  const val = document.getElementById('mobile').value.trim();
  if (!val)                     { showError('mobile', '⚠ Mobile number is required.'); return false; }
  if (!REGEX.mobile.test(val))  { showError('mobile', '⚠ Enter a valid 10-digit Indian mobile number (starts with 6–9).'); return false; }
  showValid('mobile');
  return true;
}

function validateGender() {
  const selected = document.querySelector('input[name="gender"]:checked');
  const error    = document.getElementById('gender-error');
  if (!selected) {
    if (error) error.textContent = '⚠ Please select your gender.';
    return false;
  }
  if (error) error.textContent = '';
  return true;
}

function validateCourse() {
  const val = document.getElementById('course').value;
  if (!val) { showError('course', '⚠ Please select your course.'); return false; }
  showValid('course');
  return true;
}

function validateYear() {
  const val = document.getElementById('year').value;
  if (!val) { showError('year', '⚠ Please select your year of study.'); return false; }
  showValid('year');
  return true;
}

function validatePassword() {
  const val = document.getElementById('password').value;
  if (!val) { showError('password', '⚠ Password is required.'); return false; }
  if (val.length < 8) { showError('password', '⚠ Password must be at least 8 characters.'); return false; }
  if (!/[A-Z]/.test(val)) { showError('password', '⚠ Include at least one uppercase letter.'); return false; }
  if (!/[a-z]/.test(val)) { showError('password', '⚠ Include at least one lowercase letter.'); return false; }
  if (!/[0-9]/.test(val)) { showError('password', '⚠ Include at least one number.'); return false; }
  if (!/[^A-Za-z0-9]/.test(val)) { showError('password', '⚠ Include at least one special character (!@#$...).'); return false; }
  showValid('password');
  return true;
}

function validateConfirmPassword() {
  const pass    = document.getElementById('password').value;
  const confirm = document.getElementById('confirmpassword').value;
  if (!confirm) { showError('confirmpassword', '⚠ Please confirm your password.'); return false; }
  if (pass !== confirm) { showError('confirmpassword', '⚠ Passwords do not match.'); return false; }
  showValid('confirmpassword');
  return true;
}

function validateTerms() {
  const checked = document.getElementById('terms').checked;
  const error   = document.getElementById('terms-error');
  if (!checked) {
    if (error) error.textContent = '⚠ You must accept the Terms and Conditions.';
    return false;
  }
  if (error) error.textContent = '';
  return true;
}

/* ── PASSWORD STRENGTH METER ────────────────────────────────── */
function updateStrengthMeter(password) {
  const bar  = document.getElementById('strength-bar');
  const text = document.getElementById('password-strength-text');
  if (!bar || !text) return;

  let score = 0;
  if (password.length >= 8)          score++;
  if (/[A-Z]/.test(password))        score++;
  if (/[a-z]/.test(password))        score++;
  if (/[0-9]/.test(password))        score++;
  if (/[^A-Za-z0-9]/.test(password)) score++;

  const levels = [
    { label: '',         color: '#ddd',    width: '0%'   },
    { label: 'Very Weak',  color: '#e74c3c', width: '20%'  },
    { label: 'Weak',       color: '#e67e22', width: '40%'  },
    { label: 'Fair',       color: '#f1c40f', width: '60%'  },
    { label: 'Strong',     color: '#2ecc71', width: '80%'  },
    { label: 'Very Strong',color: '#27ae60', width: '100%' }
  ];

  const level = levels[score] || levels[0];
  bar.style.width           = password ? level.width : '0%';
  bar.style.backgroundColor = level.color;
  text.textContent          = password ? 'Strength: ' + level.label : '';
  text.style.color          = level.color;
}

/* ── PASSWORD VISIBILITY TOGGLE ─────────────────────────────── */
function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '🙈';
    btn.setAttribute('aria-label', 'Hide password');
  } else {
    input.type = 'password';
    btn.textContent = '👁';
    btn.setAttribute('aria-label', 'Show password');
  }
}

/* ── ACCEPT TERMS FROM MODAL ────────────────────────────────── */
function acceptTerms() {
  const checkbox = document.getElementById('terms');
  if (checkbox) checkbox.checked = true;
  validateTerms();
  closeModal('terms-modal');
  showNotification('✅ Terms accepted!', 'success');
}

/* ── RESET FORM ─────────────────────────────────────────────── */
function resetForm() {
  ['fullname', 'email', 'mobile', 'password', 'confirmpassword', 'course', 'year', 'captcha-input'].forEach(clearState);
  generateCaptcha();
  const genderError = document.getElementById('gender-error');
  if (genderError) genderError.textContent = '';
  const termsError = document.getElementById('terms-error');
  if (termsError)  termsError.textContent = '';
  const bar = document.getElementById('strength-bar');
  if (bar) { bar.style.width = '0%'; bar.style.backgroundColor = '#ddd'; }
  const text = document.getElementById('password-strength-text');
  if (text) text.textContent = '';
}

/* ── REAL-TIME VALIDATION (keyup / change) ──────────────────── */
document.addEventListener('DOMContentLoaded', function () {

  // Text / email / tel fields — validate on keyup
  const realtimeFields = [
    { id: 'fullname',        fn: validateFullName },
    { id: 'email',           fn: validateEmail },
    { id: 'mobile',          fn: validateMobile },
    { id: 'confirmpassword', fn: validateConfirmPassword }
  ];

  realtimeFields.forEach(({ id, fn }) => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('blur',  fn);
      el.addEventListener('input', fn);
    }
  });

  // Password — strength meter + validation on input
  const passwordInput = document.getElementById('password');
  if (passwordInput) {
    passwordInput.addEventListener('input', function () {
      updateStrengthMeter(this.value);
      if (this.value) validatePassword();
      // re-validate confirm if already typed
      const confirm = document.getElementById('confirmpassword');
      if (confirm && confirm.value) validateConfirmPassword();
    });
    passwordInput.addEventListener('blur', validatePassword);
  }

  // Select fields — validate on change
  const selectFields = [
    { id: 'course', fn: validateCourse },
    { id: 'year',   fn: validateYear }
  ];
  selectFields.forEach(({ id, fn }) => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('change', fn);
  });

  // Gender radios
  document.querySelectorAll('input[name="gender"]').forEach(radio => {
    radio.addEventListener('change', validateGender);
  });

  // Terms checkbox
  const terms = document.getElementById('terms');
  if (terms) terms.addEventListener('change', validateTerms);

  // CAPTCHA — generate on page load
  generateCaptcha();

  // CAPTCHA input validation on blur
  const captchaInput = document.getElementById('captcha-input');
  if (captchaInput) captchaInput.addEventListener('blur', validateCaptcha);

  /* ── FORM SUBMIT ─────────────────────────────────────────── */
  const form = document.getElementById('registration-form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const allValid = [
        validateFullName(),
        validateEmail(),
        validateMobile(),
        validateGender(),
        validateCourse(),
        validateYear(),
        validatePassword(),
        validateConfirmPassword(),
        validateTerms(),
        validateCaptcha()
      ].every(Boolean);

      if (allValid) {
        // Show success
        const success = document.getElementById('form-success');
        if (success) { success.style.display = 'block'; }
        showNotification('🎉 Registration successful! Welcome to StudentHub.', 'success');
        form.reset();
        resetForm();
        // Scroll to top of form
        success.scrollIntoView({ behavior: 'smooth' });
      } else {
        showNotification('⚠ Please fix the errors below before submitting.', 'error');
        // Focus first invalid field
        const firstError = form.querySelector('.input-error');
        if (firstError) firstError.focus();
      }
    });
  }

});
