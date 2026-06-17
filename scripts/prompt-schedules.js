jQuery(function () {
  const table$ = jQuery("table.skriptx-congen-schedules");
  if (!table$?.length) return;
  const promptId = table$.attr('data-prompt-id');
  getPromptSchedules();


  function getPromptSchedules() {
    const options = {
      method: "POST",
      data: {
        promptId:promptId,
        action: "skriptx-congen-prompt-schedules",
      },
      onSuccess: function (res) {
        if(!res.data?.length){
            alert("no schedule found");
            return;
        }
        renderTableRows(res.data);
      },
    };
    window.skriptxAjax(options);
  }



  function renderTableRows(data){
      let trs = '';
        data.forEach(function(row){
            trs += `<tr data-prompt-id="${row.id}">
                <td>${window.skriptsScheduleStatus(row.status_id)}</td>
                <td>${row.post_id}</td>
                <td>${row.started_at}</td>
                <td>${row.completed_at}</td>
                <td>${row.error_message}</td>
                <td>${row.created_at}</td>
              
            </tr>`;
        })
        table$.find('tbody').html(trs);
  }
});
