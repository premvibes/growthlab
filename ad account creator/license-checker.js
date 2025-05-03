// license-checker.js
const SERVER_URL = "https://successunlock.com/verify.php";

async function getStoredData(keys) {
  return new Promise((resolve) => chrome.storage.local.get(keys, resolve));
}

async function setStoredData(data) {
  return new Promise((resolve) => chrome.storage.local.set(data, resolve));
}

async function checkLicense() {
  let { license_key, device_id } = await getStoredData(['license_key', 'device_id']);

  if (!device_id) {
    device_id = Math.random().toString(36).substr(2, 9);
    await setStoredData({ device_id });
  }

  if (!license_key) {
    console.log("No license key found.");
    return false;
  }

  try {
    const response = await fetch(SERVER_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ license_key, device_id }),
    });

    const data = await response.json();
    return data.valid;
  } catch (error) {
    console.error("License server error", error);
    return false;
  }
}
