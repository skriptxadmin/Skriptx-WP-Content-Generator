jQuery(function () {
  const form$ = jQuery("form.prompt");
  if (!form$?.length) return;
  const hours$ = form$.find("#hours");
  const mins$ = form$.find("#mins");
  const frequencyText$ = form$.find(".frequency-text");
  const id = form$.find("#promptId").val();
  renderHoursAndMins();
  renderFrequencyText();
  hours$.on("change", renderFrequencyText);
  mins$.on("change", renderFrequencyText);
  form$.on("submit", onFormSubmit);
  if (id && parseInt(id)) {
    const data = {
      action: "skriptx-congen-prompt-get",
      id: id,
    };
    const options = {
      data: data,
      method: "POST",
      onSuccess: function (res) {
        if(!res?.data?.id) return;
        const row = res.data;
        form$.find("#promptId").val(row?.id);
        form$.find("#prompt").val(row?.prompt);
        form$.find("#language").val(row?.language);
        hours$.val(row?.hours?.toString()?.padStart(2, "0"));
        mins$.val(row?.mins?.toString()?.padStart(2, "0"));
        if(row.generate_image && JSON.parse(row.generate_image)){
          form$.find("#generateImage").prop('checked', true);
        }
      },
    };
    window.skriptxAjax(options);
  }

  function onFormSubmit(event) {
    event.preventDefault();
    const data = {
      id: form$.find("#promptId").val(),
      prompt: form$.find("#prompt").val(),
      language: form$.find("#language").val(),
      hours: hours$.val(),
      mins: mins$.val(),
      generateImage: form$.find("#generateImage").prop("checked"),
      action: "skriptx-congen-prompt-save",
    };
    const options = {
      data: data,
      method: "POST",
      onSuccess: function (res) {
        window.location.href = `${window.skriptxcongen.admin_url}?page=skriptx-congen--prompts`;
      },
    };
    window.skriptxAjax(options);
  }
  function renderHoursAndMins() {
    let hoptions = "";
    let moptions = "";
    for (let i = 0; i < 24; i++) {
      let hr = i.toString().padStart(2, "0");
      hoptions += `<option value=${hr}>${hr}</option>`;
    }
    for (let i = 0; i < 60; i = i + 15) {
      let min = i.toString().padStart(2, "0");
      moptions += `<option value=${min}>${min}</option>`;
    }
    hours$.html(hoptions);
    mins$.html(moptions);
  }
  function renderFrequencyText() {
    const hours = parseInt(hours$.val());
    const mins = parseInt(mins$.val());
    if (!hours && !mins) {
      frequencyText$.text("Your prompt will not be executed");
      return;
    }
    frequencyText$.text(
      `Your prompt will be executed at an interval of ${hours} hours and ${mins} mins`,
    );
  }
});
