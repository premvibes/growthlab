// inject.js
(async function() {
    const script = document.createElement('script');
    script.src = chrome.runtime.getURL('content.js');
    script.onload = function() {
      this.remove();
      chrome.runtime.sendMessage({
        type: "show_notification",
        title: "FB Ad Account Creator",
        message: "Content Script Injected Successfully!"
      });
    };
    (document.head || document.documentElement).appendChild(script);
  })();
  