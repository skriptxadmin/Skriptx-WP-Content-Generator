jQuery(function () {
  const regenerateSecretKey$ = jQuery("button.regenerate-secret-key");

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

  getDashboardAnalytics();

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
});
