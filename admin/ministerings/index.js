const tbody = document.querySelector('tbody'),
   template = document.querySelector('template');

let table = [];

loadData();

function openEditModal(id) {
   const user = getUserById(id);

   document.querySelector('input[name=id]').value = id;
   document.querySelector('input[name=name]').value = user.name;
   document.querySelector('input[name=last_name]').value = user.last_name;
   document.querySelector('input[name=role]').value = user.role.capitalize();

   let counter = 1;
   user.ministerings.forEach(item => {
      document.querySelector(`select[name=day_of_week${counter}]`).value = item.day_of_week;
      document.querySelector(`input[name=hour${counter}]`).value = item.hour.length < 5 ? '0' + item.hour : item.hour;
      document.querySelector(`input[name=row_id${counter}]`).value = item.row_id;
      ++counter;
   });

   for (let i = counter; i <= 3; ++i) {
      document.querySelector(`select[name=day_of_week${i}]`).value = null;
      document.querySelector(`input[name=hour${i}]`).value = '';
      document.querySelector(`input[name=row_id${i}]`).value = '0';
   }

   UIkit.modal('#modal').show();
}

document.querySelector('form').onsubmit = e => {
   e.preventDefault();
   const id = document.querySelector('input[name=id]').value;
   let ministerings = [{
      day_of_week: document.querySelector('select[name=day_of_week1]').value,
      hour: document.querySelector('input[name=hour1]').value,
      row_id: document.querySelector('input[name=row_id1]').value
   },
   {
      day_of_week: document.querySelector('select[name=day_of_week2]').value,
      hour: document.querySelector('input[name=hour2]').value,
      row_id: document.querySelector('input[name=row_id2]').value
   },
   {
      day_of_week: document.querySelector('select[name=day_of_week3]').value,
      hour: document.querySelector('input[name=hour3]').value,
      row_id: document.querySelector('input[name=row_id3]').value
   }
   ];

   let f = true;
   ministerings.forEach(async el => {
      if (el.hour.substr(0, 1) == '0') el.hour = el.hour.slice(1);
      if (el.day_of_week === 'delete') {
         const formData = new FormData();
         formData.append('row_id', el.row_id);
         const res = await fetch('./delete.php', {
            method: 'POST',
            body: formData
         });
         const data = await res.json();
         if (data.error) f = false;
      } else if (el.day_of_week != 'null' && el.hour.length) {
         const formData = new FormData();
         formData.append('uid', id);
         formData.append('row_id', el.row_id);
         formData.append('day_of_week', el.day_of_week);
         formData.append('hour', el.hour);
         const res = await fetch('./add.php', {
            method: 'POST',
            body: formData
         });
         const data = await res.json();
         if (data.error) f = false;
      }
   });

   loadData();

   if (f) {
      UIkit.notification({
         message: 'Zapisano zmiany!',
         status: 'success',
         timeout: 5000
      });
      UIkit.modal('#modal').hide();
   } else UIkit.notification({
      message: 'database error',
      status: 'danger',
      timeout: 5000
   });
}

function renderTable(data) {
   tbody.innerHTML = '';
   data.forEach(user => {
      const div = template.content.cloneNode(true);

      div.querySelector('[data-id]').textContent = user.id;
      div.querySelector('[data-picture]').src = PROFILE_PICTURES_PATH + user.picture;
      div.querySelector('[data-name]').textContent = user.name;
      div.querySelector('[data-last_name]').textContent = user.last_name;
      div.querySelector('[data-role]').textContent = user.role;

      div.querySelector('tr').ondblclick = (e) => openEditModal(user.id);
      div.querySelector('[data-edit]').onclick = (e) => openEditModal(user.id)

      user.ministerings.forEach(item => {
         div.querySelector('[data-ministerings]').innerHTML += getDayOfWeekName(item.day_of_week) + ', ' + item.hour + '<br>';
      });

      if (!user.ministerings.length) div.querySelector('[data-ministerings]').innerHTML = 'brak';

      tbody.append(div);
   });

   $('.tablesorter').trigger('update');
}

function getDayOfWeekName(n) {
   arr = ['Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'];
   return arr[n - 1];
}

function getUserById(user_id) {
   return table.find(row => row.id == user_id);
}

async function loadData() {
   const res = await fetch('./getData.php');
   const data = await res.json();

   table = data;
   renderTable(data);
}

String.prototype.capitalize = function () {
   return this.charAt(0).toUpperCase() + this.slice(1);
};