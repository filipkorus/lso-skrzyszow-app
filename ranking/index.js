loadRanking(RANKING == 'month' ? new Date().getMonth() : null, new Date().getFullYear(), RANKING, (USER_ROLE !== 'ministrant' && USER_ROLE !== 'lektor') ? '' : USER_ROLE);

const tbody = document.querySelector('tbody'),
   template = document.querySelector('template');

document.querySelectorAll('select').forEach(e => {
   e.oninput = () => {
      if (RANKING === 'year') {
         loadRanking(
            null,
            document.querySelector('select[name=year]').value,
            RANKING,
            document.querySelector('select[name=role]').value
         );
      } else {
         loadRanking(
            document.querySelector('select[name=month]').value,
            document.querySelector('select[name=year]').value,
            RANKING,
            document.querySelector('select[name=role]').value
         );
      }
   }
});

async function loadRanking(month, year, ranking, role = '') {
   const res = await fetch(`./../getData.php?month=${month}&year=${year}&role=${role}&ranking=${ranking}`);
   const data = await res.json();

   if (data.error) return UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   let i = 1;
   tbody.innerHTML = '';
   data.points.forEach(row => {
      const div = template.content.cloneNode(true);

      div.querySelector('[data-place]').textContent = i;
      div.querySelector('[data-picture]').src = PROFILE_PICTURES_PATH + row.picture;
      div.querySelector('[data-name]').textContent = row.name;
      div.querySelector('[data-last_name]').textContent = row.last_name;
      div.querySelector('[data-points]').textContent = row.points;
      div.querySelector('[data-role]').textContent = row.role;

      tbody.append(div);
      ++i;
   });

   $('.tablesorter').trigger('update');
}