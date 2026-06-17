jQuery(function () {
  const table$ = jQuery("table.skriptx-congen-prompts");
  if (!table$?.length) return;

  getPrompts();

  table$.on('click', '.btn-toggle', toggleRow);
  table$.on('click', '.btn-edit', editRow);
  table$.on('click', '.btn-trash', trashRow);
  table$.on('click', '.btn-schedules', schedulesRow);

  function getPrompts() {
    const options = {
      method: "POST",
      data: {
        action: "skriptx-congen-prompts",
      },
      onSuccess: function (res) {
        if(!res.data?.length){
            alert("no prompt found");
            return;
        }
        renderTableRows(res.data);
      },
    };
    window.skriptxAjax(options);
  }

  function toggleRow(event){
    const id = jQuery(event.target).closest('tr').attr('data-prompt-id');
    if(!id) return;
    const verify =  confirm('Are you sure you want to toggle this row?');
    if(!verify) return;
      const options = {
      method: "POST",
      data: {
        'id':id,
        action: "skriptx-congen-prompt-toggle",
      },
      onSuccess: function (res) {
                window.location.href = `${window.skriptxcongen.admin_url}?page=skriptx-congen--prompts`;

      },
    };
    window.skriptxAjax(options);
  }

   function editRow(event){
    const id = jQuery(event.target).closest('tr').attr('data-prompt-id');
    if(!id) return;
     window.location.href = `${window.skriptxcongen.admin_url}?page=skriptx-congen--prompts&view=edit&id=${id}`;
  }

   function schedulesRow(event){
    const id = jQuery(event.target).closest('tr').attr('data-prompt-id');
    if(!id) return;
     window.location.href = `${window.skriptxcongen.admin_url}?page=skriptx-congen--prompts&view=schedules&id=${id}`;
  }

   function trashRow(event){
    const id = jQuery(event.target).closest('tr').attr('data-prompt-id');
    if(!id) return;
    const verify =  confirm('Are you sure you want to delete this row?');
    if(!verify) return;
      const options = {
      method: "POST",
      data: {
        'id':id,
        action: "skriptx-congen-prompt-delete",
      },
      onSuccess: function (res) {
                window.location.href = `${window.skriptxcongen.admin_url}?page=skriptx-congen--prompts`;

      },
    };
    window.skriptxAjax(options);
  }

  function renderTableRows(data){
      let trs = '';
        data.forEach(function(row){
            trs += `<tr data-prompt-id="${row.id}">
                <td>${window.skriptxTruncate(row.prompt)}</td>
                <td>${row.language}</td>
                <td>${row.hours?.toString()?.padStart(2, '0')}:${row.mins?.toString()?.padStart(2, '0')}</td>
                <td>${row.last_run}</td>
                <td>${row.next_run}</td>
                <td>${row.runs_count}</td>
                <td>
                    <button class="button btn-toggle">${row.is_active == 1?'Stop':'Start'}</button>
                </td>
                <td>
                    <button class="btn btn-schedules cp">${skriptxcongen?.svgs?.list}</button>
                    <button class="btn btn-edit cp">${skriptxcongen?.svgs?.edit}</button>
                    <button class="btn btn-trash cp">${skriptxcongen?.svgs?.trash}</button>
                </td>
            </tr>`;
        })
        table$.find('tbody').html(trs);
  }
});
