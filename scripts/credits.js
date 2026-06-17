jQuery(function () {
  const addTable$ = jQuery("table.skriptx-congen-credits-add");
  const subTable$ = jQuery("table.skriptx-congen-credits-sub");
  getCredits();

  function getCredits() {
    const options = {
      data: {
        action: "skriptx-congen-credits",
      },
      onSuccess: function (res) {
        if (res?.data?.add?.length) {
          renderAddTable(res.data.add);
        }
        if (res?.data?.sub?.length) {
          renderSubTable(res.data.sub);
        }
        if (res?.data?.balance) {
          jQuery(".balance").text(res.data.balance);
        }
      },
    };
    window.skriptxAjax(options);
  }

  function renderAddTable(data) {
    let trs$ = "";
    data.forEach(function (row) {
      trs$ += `<tr>
                <td>${row?.reason}</td>
                <td>${row?.credits}</td>
                <td>${row?.created_at}</td>
            </tr>`;
    });
    addTable$.find("tbody").html(trs$);
  }

  function renderSubTable(data) {
    let trs$ = "";
    data.forEach(function (row) {
      trs$ += `<tr>
                <td>${row?.credits}</td>
                <td>${row?.created_at}</td>
            </tr>`;
    });
    subTable$.find("tbody").html(trs$);
  }
});
