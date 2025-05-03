// background.js
importScripts('license-checker.js');

checkLicense().then(valid => {
  if (!valid) {
    console.log('❌ License invalid, activation required.');
    return;
  }

  chrome.action.onClicked.addListener(async (tab) => {
    if (tab.url.includes("facebook.com")) {
      try {
        await chrome.scripting.executeScript({
          target: { tabId: tab.id },
          files: ["content.js"]
        });

        chrome.notifications.create({
          type: "basic",
          iconUrl: "icon128.png",
          title: "FB Ad Account Creator",
          message: "🚀 Ad account creation started!"
        });
      } catch (error) {
        console.error('Injection failed:', error);
      }
    } else {
      chrome.notifications.create({
        type: "basic",
        iconUrl: "icon128.png",
        title: "FB Ad Account Creator",
        message: "❗ Please open business.facebook.com"
      });
    }
  });
});
