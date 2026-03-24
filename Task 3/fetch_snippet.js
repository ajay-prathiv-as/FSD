// ==============================
// GET INPUT ELEMENTS
// ==============================
const emailInput = document.getElementById('emailInput');
const passInput  = document.getElementById('passInput');

// ==============================
// FIELD HELPERS
// ==============================
function setField(inputId, msgId, state, msg) {
  const input = document.getElementById(inputId);
  const msgEl = document.getElementById(msgId);

  input.classList.remove('valid', 'invalid');
  msgEl.className = 'field-msg';

  if (state === 'error') {
    input.classList.add('invalid');
    msgEl.classList.add('error');
  }

  if (state === 'success') {
    input.classList.add('valid');
    msgEl.classList.add('success');
  }

  msgEl.textContent = msg;
}

function clearField(inputId, msgId) {
  document.getElementById(inputId).classList.remove('valid','invalid');
  document.getElementById(msgId).textContent = '';
  document.getElementById(msgId).className = 'field-msg';
}

// ==============================
// ALERT BOX
// ==============================
function showAlert(type, icon, msg) {
  const box = document.getElementById('alertBox');
  box.className = `alert show ${type}`;
  box.innerHTML = `<span>${icon}</span><span>${msg}</span>`;
}

function clearAlert() {
  const box = document.getElementById('alertBox');
  box.className = 'alert';
  box.innerHTML = '';
}

// ==============================
// TOGGLE PASSWORD
// ==============================
function togglePass() {
  const inp = document.getElementById('passInput');
  const btn = document.getElementById('eyeBtn');

  if (inp.type === 'password') {
    inp.type = 'text';
    btn.textContent = '🙈';
  } else {
    inp.type = 'password';
    btn.textContent = '👁️';
  }
}

// ==============================
// HANDLE LOGIN (MAIN)
// ==============================
function handleLogin() {
  const email    = emailInput.value.trim();
  const password = passInput.value;

  if (!email || !password) {
    showAlert('error','⚠️','Please fill all fields');
    return;
  }

  const btn = document.getElementById('loginBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span>Checking...';

  const formData = new FormData();
  formData.append('email', email);
  formData.append('password', password);

  fetch('login.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    btn.disabled = false;
    btn.innerHTML = 'Login →';

    if (data.status === 'success') {
      showSuccessScreen(data);
    } else {
      showAlert('error','❌', data.message);
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = 'Login →';
    showAlert('error','⚠️','Server error. Check PHP.');
  });
}

// ==============================
// SUCCESS SCREEN
// ==============================
function showSuccessScreen(user) {
  document.getElementById('loginForm').style.display = 'none';
  document.getElementById('successScreen').style.display = 'flex';

  document.getElementById('sbName').textContent = '👤 ' + user.name;
  document.getElementById('sbRole').textContent = 'Role: ' + user.role;
  document.getElementById('sbTime').textContent =
    'Logged in at: ' + new Date().toLocaleTimeString();
}

// ==============================
// LOGOUT
// ==============================
function handleLogout() {
  emailInput.value = '';
  passInput.value  = '';

  clearField('emailInput','emailMsg');
  clearField('passInput','passMsg');
  clearAlert();

  document.getElementById('loginForm').style.display = 'flex';
  document.getElementById('successScreen').style.display = 'none';
}