// content.js
(function() {
  function injectScript(file) {
    const script = document.createElement('script');
    script.src = chrome.runtime.getURL(file);
    script.onload = function() {
      this.remove();
    };
    (document.head || document.documentElement).appendChild(script);
  }

  injectScript('facebook-token-extractor.js');
  injectScript('adaccount-creator.js');
})();
