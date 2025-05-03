// activation.js
const SERVER_URL = "https://successunlock.com/verify.php";

async function getStoredData(keys) {
  return new Promise((resolve) => chrome.storage.local.get(keys, resolve));
}

async function setStoredData(data) {
  return new Promise((resolve) => chrome.storage.local.set(data, resolve));
}

async function getDeviceId() {
  let { device_id } = await getStoredData(['device_id']);
  if (!device_id) {
    device_id = Math.random().toString(36).substr(2, 9);
    await setStoredData({ device_id });
  }
  return device_id;
}

async function checkLicense(licenseKey, deviceId) {
  try {
    const response = await fetch(SERVER_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ license_key: licenseKey, device_id: deviceId }),
    });
    const data = await response.json();
    return data.valid;
  } catch (error) {
    console.error("Error connecting to server:", error);
    return false;
  }
}

document.addEventListener('DOMContentLoaded', async () => {
  const licenseStatus = document.getElementById('license-status');
  const activationForm = document.getElementById('activation-form');
  const activationMessage = document.getElementById('activation-message');
  const activateButton = document.getElementById('activate-button');

  const storedData = await getStoredData(['license_key', 'device_id']);
  const licenseKey = storedData.license_key || '';
  const deviceId = storedData.device_id || await getDeviceId();

  if (licenseKey) {
    const isValid = await checkLicense(licenseKey, deviceId);

    if (isValid) {
      licenseStatus.innerHTML = `<div class="status valid">\u2705 License is active!</div>`;
      activationForm.style.display = 'none';
      chrome.action.setPopup({ popup: "" });
    } else {
      licenseStatus.innerHTML = `<div class="status invalid">\u274C Invalid license. Please re-activate.</div>`;
      activationForm.style.display = 'block';
    }
  } else {
    licenseStatus.innerHTML = `<div class="status invalid">\u274C No license found. Please activate.</div>`;
    activationForm.style.display = 'block';
  }

  activateButton.addEventListener('click', async () => {
    const enteredKey = document.getElementById('license-key').value.trim();
    if (!enteredKey) {
      activationMessage.innerText = "Please enter a license key!";
      activationMessage.className = 'error';
      return;
    }

    const isValid = await checkLicense(enteredKey, deviceId);

    if (isValid) {
      await setStoredData({ license_key: enteredKey });
      activationMessage.innerText = "\u2705 License Activated Successfully!";
      activationMessage.className = 'success';
      chrome.action.setPopup({ popup: "" });

      // 🚀 Force reload the extension to apply activation immediately
      setTimeout(() => {
        chrome.runtime.reload();
      }, 1000);
    } else {
      activationMessage.innerText = "\u274C Invalid or Expired License.";
      activationMessage.className = 'error';
    }
  });
});
