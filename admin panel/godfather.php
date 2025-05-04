<?php
// START SESSION
session_start();

// Set timezone to Indian Time
date_default_timezone_set('Asia/Kolkata');

// Current UTC time and user
$current_time = '2025-05-04 18:01:53';
$current_user = 'Parmeshwar';

// SECRET CODE
$secret_code = 'parmeshwar@123';

// HANDLE LOGIN
if (isset($_POST['secret_code'])) {
    if ($_POST['secret_code'] === $secret_code) {
        $_SESSION['authenticated'] = true;
        $_SESSION['login_time'] = $current_time;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Manager Admin Panel</title>
    
    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 1200px;
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

        .bulk-actions {
            text-align: left;
            margin: 10px 0;
        }

        .bulk-delete-btn {
            background: linear-gradient(to right, #f44336, #e53935);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 14px;
            display: none;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .bulk-delete-btn i {
            font-size: 16px;
        }

        .bulk-delete-btn:hover {
            background: linear-gradient(to right, #e53935, #f44336);
        }

        select, button, input[type="text"], input[type="password"], input[type="checkbox"] {
            padding: 12px;
            margin: 10px 5px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            font-family: 'Montserrat', sans-serif;
        }

        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            padding: 0;
            margin: 0;
            vertical-align: middle;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        .sortable {
            cursor: pointer;
            user-select: none;
        }

        .sortable:hover {
            background-color: #e8e8e8;
        }

        tbody tr {
            cursor: pointer;
        }

        tbody tr:hover {
            background: #f1f1f1;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-expired {
            background: #ffebee;
            color: #c62828;
        }

        .status-unused {
            background: #fff3e0;
            color: #ef6c00;
        }

        .greeting-bar {
            background: linear-gradient(to right, #4CAF50, #45a049);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
        }

        .select-tooltip {
            position: relative;
            display: inline-block;
        }

        .select-tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            pointer-events: none;
        }

        .select-tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            margin-top: 10px;
        }

        .auto-refresh-indicator {
            display: inline-block;
            margin-left: 10px;
            color: #45a049;
            font-size: 14px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }

        .generate-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        #message {
            margin-top: 15px;
            padding: 10px;
            font-weight: bold;
            color: #4CAF50;
            min-height: 20px;
        }

        .time-info {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
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
    </style>
</head>
<body>
<div class="container">
    <?php if (!isset($_SESSION['authenticated'])): ?>
        <!-- LOGIN FORM -->
        <h2>🔒 License Manager Login</h2>
        <form method="POST">
            <input type="password" name="secret_code" placeholder="Enter Secret Code" required><br>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($login_error)) echo "<div style='color:red;font-weight:bold;margin-top:10px;'>$login_error</div>"; ?>
    <?php else: ?>
        <div class="greeting-bar">
            <div id="greetingText"></div>
            <div class="user-info">
                <a href="?logout" style="color: white; text-decoration: none;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <h1>License Manager <span id="refreshIndicator" class="auto-refresh-indicator">• Auto-Refreshing</span></h1>
    

        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Licenses</h3>
                <div class="value" id="totalLicensesValue">0</div>
            </div>
            <div class="stat-card">
                <h3>Active Licenses</h3>
                <div class="value" id="activeLicensesValue">0</div>
            </div>
            <div class="stat-card">
                <h3>Expired Licenses</h3>
                <div class="value" id="expiredLicensesValue">0</div>
            </div>
            <div class="stat-card">
                <h3>Unused Licenses</h3>
                <div class="value" id="unusedLicensesValue">0</div>
            </div>
        </div>

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
        <!-- Bulk Delete Button moved here -->
        <div class="bulk-actions">
            <button id="bulkDeleteBtn" class="bulk-delete-btn" onclick="bulkDelete()">
                <i class="fas fa-trash-alt"></i> Delete Selected Licenses
            </button>
        </div>
        <div class="table-responsive">
            <table id="licensesTable">
                <thead>
                    <tr>
                        <th style="text-align: center; font-size: 14px;" class="select-tooltip">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="margin: 0;">
                            <span class="tooltiptext">Select All Pages</span>
                        </th>
                        <th>License Key (Click to Copy)</th>
                        <th class="sortable" onclick="sortTable('status')">
                            Status <i class="fa-solid fa-sort"></i>
                        </th>
                        <th>IP Address</th>
                        <th class="sortable" onclick="sortTable('lastAccess')">
                            Last Access <i class="fa-solid fa-sort"></i>
                        </th>
                        <th class="sortable" onclick="sortTable('expiresAt')">
                            Expires At <i class="fa-solid fa-sort"></i>
                        </th>
                        <th>Change Validity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="pagination" id="pagination"></div>
        <div id="message"></div>

        <div class="time-info">
            Current Time (IST): <?php echo date('Y-m-d H:i:s'); ?>
        </div>
    <?php endif; ?>
</div>

<script>
let licensesData = {};
let currentPage = 1;
const licensesPerPage = 5;
let selectedLicenses = new Set();
let currentSortColumn = '';
let currentSortDirection = 'asc';
let autoRefreshInterval;

function updateGreeting() {
    const now = new Date();
    const hour = now.getHours();
    let greeting = "Good Evening <?php echo $current_user; ?> Sir! 🌙";
    if (hour < 12) {
        greeting = "Good Morning <?php echo $current_user; ?> Sir! 👋";
    } else if (hour < 18) {
        greeting = "Good Afternoon <?php echo $current_user; ?> Sir! ☀️";
    }
    document.getElementById('greetingText').innerText = greeting;
}

function formatDate(dateStr, includeTime = false) {
    if (!dateStr || dateStr.includes('2099')) return 'Lifetime';
    const date = new Date(dateStr.replace(' ', 'T'));
    if (includeTime) {
        return date.toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } else {
        return date.toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }
}

function formatIP(ip) {
    return ip || 'Not used yet';
}

function getStatus(info) {
    if (!info.expires_at) return 'Invalid';
    const now = new Date('<?php echo $current_time; ?>');
    const expiry = new Date(info.expires_at.replace(' ', 'T'));
    if (expiry <= now) return 'Expired';
    return info.ip && info.last_access ? 'Active' : 'Unused';
}

function getStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'active': return 'status-active';
        case 'expired': return 'status-expired';
        case 'unused': return 'status-unused';
        default: return '';
    }
}

function renderLicenses() {
    const tbody = document.getElementById('licensesTable').querySelector('tbody');
    tbody.innerHTML = '';

    const keys = getSortedKeys();
    const start = (currentPage - 1) * licensesPerPage;
    const paginatedKeys = keys.slice(start, start + licensesPerPage);

    paginatedKeys.forEach(key => {
        const info = licensesData[key];
        const status = getStatus(info);
        const statusClass = getStatusClass(status);
        
        const row = document.createElement('tr');
        row.onclick = () => copyText(key);
        row.innerHTML = `
            <td onclick="event.stopPropagation()">
                <input type="checkbox" class="license-checkbox" 
                       ${selectedLicenses.has(key) ? 'checked' : ''} 
                       onclick="toggleLicenseSelection('${key}')">
            </td>
            <td>${key}</td>
            <td><span class="status-badge ${statusClass}">${status}</span></td>
            <td>${formatIP(info.ip)}</td>
            <td>${info.last_access ? formatDate(info.last_access, true) : 'Never'}</td>
            <td>${formatDate(info.expires_at, false)}</td>
            <td onclick="event.stopPropagation()">
                <select id="validity-${key}" onclick="event.stopPropagation()">
                    <option value="1">1 Month</option>
                    <option value="3">3 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">12 Months</option>
                    <option value="lifetime">Lifetime</option>
                </select>
            </td>
            <td onclick="event.stopPropagation()">
                <button onclick="saveLicense('${key}', event)">Save</button>
                <button class="delete-btn" onclick="deleteLicense('${key}', event)">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    updateStats();
    renderPagination(keys.length);
    updateBulkDeleteButton();
}
function getSortedKeys() {
    const keys = Object.keys(licensesData);
    
    if (!currentSortColumn) {
        return keys.sort((a, b) => new Date(licensesData[b].created_at || 0) - new Date(licensesData[a].created_at || 0));
    }

    return keys.sort((a, b) => {
        const infoA = licensesData[a];
        const infoB = licensesData[b];
        let comparison = 0;

        switch(currentSortColumn) {
            case 'status':
                comparison = getStatus(infoA).localeCompare(getStatus(infoB));
                break;
            case 'lastAccess':
                const accessA = infoA.last_access ? new Date(infoA.last_access.replace(' ', 'T')) : new Date(0);
                const accessB = infoB.last_access ? new Date(infoB.last_access.replace(' ', 'T')) : new Date(0);
                comparison = accessA - accessB;
                break;
            case 'expiresAt':
                const expA = infoA.expires_at.includes('2099') ? new Date('2099-12-31') : new Date(infoA.expires_at.replace(' ', 'T'));
                const expB = infoB.expires_at.includes('2099') ? new Date('2099-12-31') : new Date(infoB.expires_at.replace(' ', 'T'));
                comparison = expA - expB;
                break;
        }

        return currentSortDirection === 'asc' ? comparison : -comparison;
    });
}

function updateStats() {
    const stats = {
        total: 0,
        active: 0,
        expired: 0,
        unused: 0
    };

    Object.values(licensesData).forEach(license => {
        stats.total++;
        const status = getStatus(license);
        if (status === 'Active') stats.active++;
        else if (status === 'Expired') stats.expired++;
        else if (status === 'Unused') stats.unused++;
    });

    document.getElementById('totalLicensesValue').textContent = stats.total;
    document.getElementById('activeLicensesValue').textContent = stats.active;
    document.getElementById('expiredLicensesValue').textContent = stats.expired;
    document.getElementById('unusedLicensesValue').textContent = stats.unused;
}

function generateLicense() {
    const validity = document.getElementById('validity').value;
    const licenseKey = 'XXXXXX-XXXXXX'.replace(/X/g, () => 
        "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789".charAt(Math.floor(Math.random() * 36))
    );

    fetch('manage-licenses-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'generate',
            license_key: licenseKey,
            validity: validity
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('licenseKey').value = licenseKey;
            navigator.clipboard.writeText(licenseKey);
            showMessage("✅ License Generated & Copied!");
            setTimeout(loadLicenses, 500);
        } else {
            showMessage("❌ Error generating license!");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage("❌ Error generating license!");
    });
}

function loadLicenses() {
    fetch('licenses.json?' + new Date().getTime())
        .then(response => response.json())
        .then(data => {
            licensesData = data;
            renderLicenses();
        })
        .catch(error => {
            console.error('Error loading licenses:', error);
            showMessage("❌ Error loading licenses!");
        });
}

function renderPagination(total) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    const pages = Math.ceil(total / licensesPerPage);
    
    for (let i = 1; i <= pages; i++) {
        const btn = document.createElement('button');
        btn.innerText = i;
        if (i === currentPage) btn.classList.add('active');
        btn.onclick = () => {
            currentPage = i;
            renderLicenses();
        };
        pagination.appendChild(btn);
    }
}

function sortTable(column) {
    const icons = document.querySelectorAll('.sortable i');
    icons.forEach(icon => {
        icon.className = 'fa-solid fa-sort';
    });

    if (currentSortColumn === column) {
        currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortColumn = column;
        currentSortDirection = 'asc';
    }

    const icon = document.querySelector(`th[onclick="sortTable('${column}')"] i`);
    icon.className = `fa-solid fa-sort-${currentSortDirection === 'asc' ? 'up' : 'down'}`;

    renderLicenses();
}

function toggleLicenseSelection(key) {
    if (selectedLicenses.has(key)) {
        selectedLicenses.delete(key);
    } else {
        selectedLicenses.add(key);
    }
    updateBulkDeleteButton();
}

function updateBulkDeleteButton() {
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    bulkDeleteBtn.style.display = selectedLicenses.size > 0 ? 'flex' : 'none';
}

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.license-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const key = row.children[1].textContent;
        if (selectAllCheckbox.checked) {
            selectedLicenses.add(key);
            checkbox.checked = true;
        } else {
            selectedLicenses.delete(key);
            checkbox.checked = false;
        }
    });
    
    updateBulkDeleteButton();
}

function saveLicense(key, event) {
    if (event) {
        event.stopPropagation();
    }
    const validity = document.getElementById('validity-' + key).value;
    fetch('manage-licenses-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ 
            action: 'edit', 
            license_key: key, 
            validity: validity 
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showMessage("✅ License Updated!");
            loadLicenses();
        } else {
            showMessage("❌ Error updating license!");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage("❌ Error updating license!");
    });
}

function deleteLicense(key, event) {
    if (event) {
        event.stopPropagation();
    }
    if (!confirm("Are you sure you want to delete this license key?")) return;
    
    fetch('manage-licenses-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ 
            action: 'delete', 
            license_key: key 
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showMessage("✅ License Deleted!");
            selectedLicenses.delete(key);
            loadLicenses();
        } else {
            showMessage("❌ Error deleting license!");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage("❌ Error deleting license!");
    });
}

function bulkDelete() {
    if (selectedLicenses.size === 0) return;
    
    if (!confirm(`Are you sure you want to delete ${selectedLicenses.size} selected license key(s)?`)) return;
    
    const deletePromises = Array.from(selectedLicenses).map(key => 
        fetch('manage-licenses-api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'delete', license_key: key })
        }).then(res => res.json())
    );
    
    Promise.all(deletePromises)
        .then(() => {
            showMessage(`✅ ${selectedLicenses.size} License(s) Deleted!`);
            selectedLicenses.clear();
            loadLicenses();
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage("❌ Error deleting licenses!");
        });
}

function showMessage(message) {
    const messageElement = document.getElementById('message');
    messageElement.innerText = message;
    setTimeout(() => {
        messageElement.innerText = '';
    }, 3000);
}

function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        showMessage(`✅ License Key Copied!`);
    });
}

function startAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
    autoRefreshInterval = setInterval(loadLicenses, 10000);
}

// Initialize the dashboard
document.addEventListener('DOMContentLoaded', function() {
    updateGreeting();
    loadLicenses();
    startAutoRefresh();
});

// Handle page visibility
document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'visible') {
        loadLicenses();
        startAutoRefresh();
    } else {
        clearInterval(autoRefreshInterval);
    }
});
</script>
</body>
</html>
