// facebook-token-extractor.js
console.log("Running Facebook Token Extractor...");

try {
  const access_token = require('WebApiApplication').getAccessToken();
  const businessId = require("BusinessUnifiedNavigationContext").businessID;

  window.postMessage({
    type: 'FB_AD_CREATOR_TOKENS',
    token: access_token,
    businessId: businessId
  }, '*');
} catch (error) {
  window.postMessage({
    type: 'FB_AD_CREATOR_ERROR',
    error: error.message
  }, '*');
}
