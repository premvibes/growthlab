// adaccount-creator.js

(function() {
  console.log("✅ Copyright DigitalParmeshwar");

  const currency = `EUR`; // Currency
  const timezone_id = `17`; // Timezone ID
  const nameads = `digitalparmeshwar`; // Ad Account name prefix
  const delay = 3; // Delay in seconds
  const length = 500; // Number of ad accounts to create

  if (!window.location.host.includes("business.facebook.com")) {
    alert(`⚠️ Please go to https://business.facebook.com/settings then try again`);
    window.location.href = "https://business.facebook.com/select";
    return;
  }

  let access_token, businessId;
  try {
    access_token = require('WebApiApplication').getAccessToken();
    businessId = require('BusinessUnifiedNavigationContext').businessID;
  } catch (e) {
    alert(`❌ Error fetching access token or business ID`);
    console.error(e);
    window.location.href = "https://business.facebook.com/select";
    return;
  }

  if (!access_token || !businessId) {
    alert(`❌ Missing Business ID or Access Token. Select BM first.`);
    window.location.href = "https://business.facebook.com/select";
    return;
  }

  maxvia88com(1);

  async function maxvia88com(index) {
    if (index > length) {
      console.log(`🎯 Done creating all ad accounts!`);
      alert(`🎯 Successfully created ${index-1}/${length} accounts.`);
      autoReload();
      return;
    }

    const url = `https://graph.facebook.com/v17.0/${businessId}/adaccount?access_token=${access_token}`;
    const params = {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `currency=${currency}&timezone_id=${timezone_id}&name=${nameads} ${index}&end_advertiser=${businessId}&ad_account_created_from_bm_flag=true&media_agency=UNFOUND&partner=UNFOUND&method=post&suppress_http_code=1`
    };

    try {
      const response = await fetch(url, params);
      const data = await response.json();
      const now = new Date();

      if (!response.ok) {
        console.error(`❌ HTTP Error ${response.status}`);
        alert(`❌ HTTP Error ${response.status}`);
        autoReload();
        return;
      }

      if (data.account_id) {
        console.log(`✅ Created: ${data.id} [${now.toLocaleTimeString()}]`);
      } else if (data.error) {
        console.error(`❌ API Error: ${data.error.message}`);
        alert(`❌ Error: ${data.error.error_user_msg || data.error.message}`);
        autoReload();
        return;
      } else {
        console.error(`❌ Unknown API response`);
        autoReload();
        return;
      }
    } catch (error) {
      console.error('❌ Fetch error:', error.message);
      autoReload();
      return;
    }

    setTimeout(() => {
      maxvia88com(index + 1);
    }, delay * 1000);
  }

  function autoReload() {
    console.log("🔄 Reloading page in 2 seconds...");
    setTimeout(() => {
      window.location.reload();
    }, 2000);
  }
})();
