document.addEventListener("DOMContentLoaded", function () {
  const checkbox = document.getElementById("skriptx_congen_consent");
  const button = document.getElementById("skriptx_generate_key");

  checkbox.addEventListener("change", function () {
    button.disabled = !this.checked;
  });

  button.addEventListener("click", function () {
    const options = {
      data: {
        action: "skriptx-congen-set-secret-key",
      },
      onSuccess: function (res) {
        window.location.href = `${skriptxcongen.admin_url}?page=skriptx-congen`
      },
    };
    window.skriptxAjax(options);
  });
});
