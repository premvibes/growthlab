<?php
// START SESSION
session_start();

// SECRET CODE
$secret_code = 'parmeshwar@123';

// HANDLE LOGIN
if (isset($_POST['secret_code'])) {
    if ($_POST['secret_code'] === $secret_code) {
        $_SESSION['authenticated'] = true;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = "❌ Invalid secret code.";
    }
}

// HANDLE LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage License Keys</title>

  <!-- Montserrat Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background: #f0f4f8;
      margin: 0;
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 1000px;
      text-align: center;
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px);}
      to { opacity: 1; transform: translateY(0);}
    }

    h1, h2 {
      color: #333;
      margin-bottom: 20px;
      font-weight: 700;
    }

    select, button, input[type="text"], input[type="password"] {
      padding: 12px;
      margin: 10px 5px;
      font-size: 16px;
      border-radius: 10px;
      border: 1px solid #ccc;
      outline: none;
      font-family: 'Montserrat', sans-serif;
    }

    input[type="text"], input[type="password"] {
      width: 300px;
      background: #f9f9f9;
    }

    button {
      background: linear-gradient(to right, #4CAF50, #45a049);
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: linear-gradient(to right, #45a049, #4CAF50);
    }

    .delete-btn {
      background: linear-gradient(to right, #f44336, #e53935);
    }

    .delete-btn:hover {
      background: linear-gradient(to right, #e53935, #f44336);
    }

    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
      font-size: 15px;
    }

    th, td {
      padding: 14px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    tbody tr:hover {
      background: #f1f1f1;
    }

    .pagination {
      margin-top: 20px;
    }

    .pagination button {
      margin: 0 5px;
      padding: 8px 14px;
      border-radius: 8px;
      background: #eee;
      color: #333;
      font-weight: bold;
      cursor: pointer;
      border: none;
      transition: background 0.3s;
    }

    .pagination button:hover, .pagination button.active {
      background: #4CAF50;
      color: white;
    }

    .message {
      margin-top: 15px;
      font-weight: bold;
      color: #4CAF50;
    }

    .greeting-bar {
      background: linear-gradient(to right, #4CAF50, #45a049);
      color: white;
      padding: 15px;
      border-radius: 12px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 18px;
      font-weight: bold;
    }

    .greeting-bar a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .generate-section {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin: 20px 0;
      flex-wrap: wrap;
    }
  </style>
</head>

<body>

<div class="container">

<?php if (!isset($_SESSION['authenticated'])): ?>
    <!-- LOGIN FORM -->
    <h2>🔒 Enter Your Secret Code</h2>
    <form method="POST">
      <input type="password" name="secret_code" placeholder="Secret Code" required><br>
      <button type="submit">Unlock</button>
    </form>
    <?php if (isset($login_error)) echo "<div style='color:red;font-weight:bold;margin-top:10px;'>$login_error</div>"; ?>
<?php else: ?>
    <!-- AFTER LOGIN: SHOW DASHBOARD -->

    <div class="greeting-bar">
      <div id="greetingText"></div>
      <div><a href="?logout">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 512 512"><path d="M497 273l-80 80c-15 15-41 4-41-17v-40H192c-13 0-24-11-24-24v-16c0-13 11-24 24-24h184v-40c0-21 26-32 41-17l80 80c9 9 9 25 0 34zM432 32c27 0 48 21 48 48v144h-48V80H80v352h352v-144h48v144c0 27-21 48-48 48H80c-27 0-48-21-48-48V80c0-27 21-48 48-48h352z"/></svg>
        Logout
      </a></div>
    </div>

    <h1>Manage License Keys</h1>

    <div class="generate-section">
      <select id="validity">
        <option value="1">1 Month</option>
        <option value="3">3 Months</option>
        <option value="6">6 Months</option>
        <option value="12">12 Months</option>
        <option value="lifetime">Lifetime</option>
      </select>
      
      <input type="text" id="licenseKey" readonly placeholder="Generated license will appear here">

      <button onclick="generateLicense()">Generate License Key</button>
    </div>

    <h3 id="totalLicenses"></h3>

    <table id="licensesTable">
      <thead>
        <tr>
          <th>License Key (Click to Copy)</th>
          <th>Expires At</th>
          <th>Change Validity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <div class="pagination" id="pagination"></div>
    <div class="message" id="message"></div>

<?php endif; ?>

</div>

<!-- Greeting JS -->
<script>
function updateGreeting() {
  const now = new Date();
  const hour = now.getHours();
  let greeting = "Good Evening Parmeshwar Sir! 🌙";
  if (hour < 12) {
    greeting = "Good Morning Parmeshwar Sir! 👋";
  } else if (hour < 18) {
    greeting = "Good Afternoon Parmeshwar Sir! ☀️";
  }
  document.getElementById('greetingText').innerText = greeting;
}
updateGreeting();
</script>

<?php if (isset($_SESSION['authenticated'])): ?>
<script>
// LICENSE MANAGER JAVASCRIPT
// (same as I sent earlier - if you want, I can attach the rest too)

loadLicenses();
</script>
<script>
// License Manager Full JavaScript
let licensesData = {};
let currentPage = 1;
const licensesPerPage = 5;

function formatDate(dateStr) {
  if (!dateStr || dateStr.includes('2099')) return 'Lifetime';
  const options = { day: '2-digit', month: 'short', year: 'numeric' };
  return new Date(dateStr.replace(' ', 'T')).toLocaleDateString('en-GB', options);
}

function renderLicenses() {
  const tbody = document.getElementById('licensesTable').querySelector('tbody');
  tbody.innerHTML = '';

  const keys = Object.keys(licensesData).sort((a, b) => new Date(licensesData[b].created_at) - new Date(licensesData[a].created_at));
  const start = (currentPage - 1) * licensesPerPage;
  const paginatedKeys = keys.slice(start, start + licensesPerPage);

  paginatedKeys.forEach(key => {
    const info = licensesData[key];
    const row = document.createElement('tr');
    row.innerHTML = `
      <td onclick="copyText('${key}')" style="cursor: pointer;">${key}</td>
      <td>${formatDate(info.expires_at)}</td>
      <td>
        <select id="validity-${key}">
          <option value="1">1 Month</option>
          <option value="3">3 Months</option>
          <option value="6">6 Months</option>
          <option value="12">12 Months</option>
          <option value="lifetime">Lifetime</option>
        </select>
      </td>
      <td>
        <button onclick="saveLicense('${key}')">Save</button>
        <button class="delete-btn" onclick="deleteLicense('${key}')">Delete</button>
      </td>
    `;
    tbody.appendChild(row);
  });

  document.getElementById('totalLicenses').innerText = `Total Licenses: ${keys.length}`;
  renderPagination(keys.length);
}

function renderPagination(total) {
  const pagination = document.getElementById('pagination');
  pagination.innerHTML = '';
  const pages = Math.ceil(total / licensesPerPage);
  for (let i = 1; i <= pages; i++) {
    const btn = document.createElement('button');
    btn.innerText = i;
    if (i === currentPage) btn.classList.add('active');
    btn.onclick = () => { currentPage = i; renderLicenses(); };
    pagination.appendChild(btn);
  }
}

function loadLicenses() {
  fetch('licenses.json')
    .then(response => response.json())
    .then(data => {
      licensesData = data;
      renderLicenses();
    });
}

function generateLicense() {
  const validity = document.getElementById('validity').value;
  const licenseKey = 'XXXXXX-XXXXXX'.replace(/X/g, () => "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789".charAt(Math.floor(Math.random() * 36)));
  fetch('manage-licenses-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ action: 'generate', license_key: licenseKey, validity: validity })
  }).then(res => res.json()).then(data => {
    if (data.success) {
      document.getElementById('licenseKey').value = licenseKey;
      navigator.clipboard.writeText(licenseKey);
      document.getElementById('message').innerText = "✅ License Generated & Copied!";
      setTimeout(loadLicenses, 500);
    }
  });
}

function saveLicense(key) {
  const validity = document.getElementById('validity-' + key).value;
  fetch('manage-licenses-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ action: 'edit', license_key: key, validity: validity })
  }).then(res => res.json()).then(() => {
    document.getElementById('message').innerText = "✅ License Updated!";
    setTimeout(loadLicenses, 500);
  });
}

function deleteLicense(key) {
  if (!confirm("Are you sure you want to delete this license key?")) return;
  fetch('manage-licenses-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ action: 'delete', license_key: key })
  }).then(res => res.json()).then(() => {
    document.getElementById('message').innerText = "✅ License Deleted!";
    setTimeout(loadLicenses, 500);
  });
}

function copyText(text) {
  navigator.clipboard.writeText(text).then(() => {
    document.getElementById('message').innerText = `✅ License Key Copied!`;
  });
}

// Load licenses on page load
loadLicenses();
</script>

<?php endif; ?>

</body>
</html>
