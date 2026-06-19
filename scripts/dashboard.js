jQuery(function () {
  const regenerateSecretKey$ = jQuery("button.regenerate-secret-key");
  const factoryReset$ = jQuery("button.factory-reset");

  regenerateSecretKey$.on("click", function () {
    const verify = confirm(
      "Are you sure you want to regenerate the secret key. This will create error for existing job.",
    );
    if (!verify) return;
    const options = {
      data: {
        action: "skriptx-congen-set-secret-key",
      },
      onSuccess: function (res) {
        alert("Secret key regenerated");
      },
    };
    window.skriptxAjax(options);
  });

  factoryReset$.on("click", function () {
    const verify = confirm(
      `Are you sure?

This will permanently delete:
- Jobs
- Schedules
- Credits
- Logs

This action cannot be undone.`,
    );
    if (!verify) return;
    const options = {
      data: {
        action: "skriptx-congen-factory-reset",
      },
      onSuccess: function (res) {
       window.location.reload();
      },
    };
    window.skriptxAjax(options);
  });


  getDashboardAnalytics();
  getDashboardStatus();
  setInterval(() => {
    getDashboardAnalytics();
  }, 60000);

  function getDashboardAnalytics() {
    const options = {
      data: {
        action: "skriptx-congen-dashboard-analytics",
      },
      onSuccess: function (res) {
        const resObj = res?.data??{};
        for(let key in resObj){
            const ref = `.ajax-${key}`;
            jQuery(ref).text(resObj[key]);
        }
      },
    };
    window.skriptxAjax(options);
  }

  function getDashboardStatus(){
    
     const options = {
      data: {
        action: "skriptx-congen-db-health-check",
      },
      onSuccess: function (res) {
          jQuery(".ajax-db").text(res.data);
      },
    };
    window.skriptxAjax(options);
  }
});
