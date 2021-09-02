loadRecords(new Date().getMonth() + 1, new Date().getFullYear());

const tbody = document.querySelector('tbody'),
   template = document.querySelector('template');

document.querySelectorAll('select').forEach(e => {
   e.oninput = () => {
      loadRecords(
         document.querySelector('select[name=month]').value,
         document.querySelector('select[name=year]').value
      );
   }
});

function savePoints() {
   let f = true;
   const formData = new FormData();
   tbody.querySelectorAll('tr').forEach(async row => {
      formData.append('uid', row.querySelector('td[data-id]').textContent);
      formData.append('row_id', row.querySelector('td[data-row_id]').textContent);
      formData.append('month', row.querySelector('td[data-month]').textContent);
      formData.append('year', row.querySelector('td[data-year]').textContent);
      formData.append('points_plus', row.querySelector('input[name=points_plus]').value);
      formData.append('points_minus', row.querySelector('input[name=points_minus]').value);

      const res = await fetch('./updatePoints.php', {
         method: 'POST',
         body: formData
      });
      const data = await res.json();
      if (data.error) f = false;
   });

   if (f) UIkit.notification({
      message: 'Zapisano zmiany!',
      status: 'success',
      timeout: 5000
   });
   else UIkit.notification({
      message: 'database error',
      status: 'danger',
      timeout: 5000
   });
}

async function loadRecords(month, year) {
   const res = await fetch(`./getData.php?month=${month}&year=${year}`);
   const data = await res.json();

   if (data.error) return UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   tbody.innerHTML = '';
   data.points.forEach(row => {
      const div = template.content.cloneNode(true);

      div.querySelector('[data-id]').textContent = row.id;
      div.querySelector('[data-row_id]').textContent = row.row_id;
      div.querySelector('[data-name]').textContent = row.name;
      div.querySelector('[data-last_name]').textContent = row.last_name;
      div.querySelector('[data-role]').textContent = row.role;
      div.querySelector('[data-month]').textContent = month;
      div.querySelector('[data-year]').textContent = year;
      div.querySelector('[data-points_plus]').querySelector('input').value = row.points_plus;
      div.querySelector('[data-points_minus]').querySelector('input').value = row.points_minus;

      tbody.append(div);
   });

   $('.tablesorter').trigger('update');
}