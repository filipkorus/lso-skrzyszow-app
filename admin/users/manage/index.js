getUsers();

const tbody = document.querySelector('tbody'),
   template = document.querySelector('template'),
   form = document.querySelector('form');

async function getUsers() {
   const res = await fetch('./../../scripts/get-users.php');
   const data = await res.json();

   data.users.forEach(user => {
      const div = template.content.cloneNode(true);

      user.birthdate = new Date(user.birthdate);
      user.last_time_online = new Date(user.last_time_online);

      div.querySelector('tr').dataset.row_id = user.id;
      div.querySelector('[data-id]').textContent = user.id;
      div.querySelector('[data-picture]').src = PROFILE_PICTURES_PATH + user.picture;
      div.querySelector('[data-picture_name]').textContent = user.picture;
      div.querySelector('[data-name]').textContent = user.name;
      div.querySelector('[data-last_name]').textContent = user.last_name;
      div.querySelector('[data-role]').textContent = user.role;
      div.querySelector('[data-birthdate]').textContent = formatDate(user.birthdate);
      div.querySelector('[data-username]').textContent = user.username;
      div.querySelector('[data-email]').textContent = user.email;
      div.querySelector('[data-phone_no]').textContent = user.phone_no.match(/.{1,3}/g).join('-');
      div.querySelector('[data-admin]').textContent = user.admin ? 'TAK' : 'NIE';
      div.querySelector('[data-online]').textContent = formatDate(user.last_time_online) + ' ' + formatTime(user.last_time_online);
      div.querySelector('[data-edit]').dataset.id = user.id;

      div.querySelector('tr').ondblclick = (e) => openEditModal(e.target.parentNode.dataset.row_id);
      div.querySelector('[data-edit]').onclick = (e) => openEditModal(e.target.dataset.id)

      tbody.append(div);
   });
   $('.tablesorter').trigger('update');
}

function openEditModal(id) {
   const div = document.querySelector(`[data-row_id="${id}"]`);

   document.querySelector('input[name=id]').value = id;
   document.querySelector('input[name=birthdate]').value = formatDateBack(div.querySelector('[data-birthdate]').textContent);
   document.querySelector('input[name=name]').value = div.querySelector('[data-name]').textContent;
   document.querySelector('input[name=last_name]').value = div.querySelector('[data-last_name]').textContent;
   document.querySelector('select[name=role]').value = div.querySelector('[data-role]').textContent;
   document.querySelector('input[name=username]').value = div.querySelector('[data-username]').textContent;
   document.querySelector('input[name=email]').value = div.querySelector('[data-email]').textContent;
   document.querySelector('input[name=phone_no]').value = div.querySelector('[data-phone_no]').textContent.split('-').join('');
   document.querySelector('input[name=admin]').checked = (div.querySelector('[data-admin]').textContent == 'TAK' ? true : false);
   document.querySelector('input[name=online]').value = div.querySelector('[data-online]').textContent;
   form.querySelector('img').src = PROFILE_PICTURES_PATH + div.querySelector('[data-picture_name]').textContent;

   if (div.querySelector('[data-picture_name]').textContent === DEFAULT_PROFILE_PICTURE_NAME)
      form.querySelector('label').hidden = true;

   UIkit.modal("#modal").show();
}

form.onsubmit = async e => {
   e.preventDefault();
   const inputs = document.querySelectorAll('input');

   let f = false;
   inputs.forEach(input => {
      if (!input.value.length) {
         input.classList.add('uk-form-danger');
         f = true;
      } else input.classList.remove('uk-form-danger');
   });

   if (f) return UIkit.notification({
      message: 'Uzupełnij wszystkie pola!',
      status: 'danger',
      timeout: 5000
   });

   const formData = new FormData();
   formData.append('id', form.querySelector('input[name=id]').value);
   formData.append('name', form.querySelector('input[name=name]').value);
   formData.append('last_name', form.querySelector('input[name=last_name]').value);
   formData.append('birthdate', form.querySelector('input[name=birthdate]').value);
   formData.append('username', form.querySelector('input[name=username]').value);
   formData.append('email', form.querySelector('input[name=email]').value);
   formData.append('phone_no', form.querySelector('input[name=phone_no]').value);
   formData.append('role', form.querySelector('select[name=role]').value);
   formData.append('admin', form.querySelector('input[name=admin]').checked);
   formData.append('delete-picture', form.querySelector('input[name=delete-picture]').checked);

   const res = await fetch('./../../scripts/update-user.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   if (data.error) return;

   UIkit.modal("#modal").hide();

   const div = document.querySelector(`[data-row_id="${formData.get('id')}"]`);

   div.querySelector('[data-birthdate]').textContent = formatDate(new Date(formData.get('birthdate')));
   div.querySelector('[data-name]').textContent = formData.get('name');
   div.querySelector('[data-last_name]').textContent = formData.get('last_name');
   div.querySelector('[data-role]').textContent = formData.get('role');
   div.querySelector('[data-username]').textContent = formData.get('username');
   div.querySelector('[data-email]').textContent = formData.get('email');
   div.querySelector('[data-phone_no]').textContent = formData.get('phone_no').match(/.{1,3}/g).join('-');
   div.querySelector('[data-admin]').textContent = (formData.get('admin') == 'true' ? 'TAK' : 'NIE');

   if (formData.get('delete-picture') == 'true') {
      div.querySelector('[data-picture_name]').textContent = DEFAULT_PROFILE_PICTURE_NAME;
      div.querySelector('[data-picture]').src = PROFILE_PICTURES_PATH + div.querySelector('[data-picture_name]').textContent;
   }
   $('.tablesorter').trigger('update');
}

function formatDate(date) {
   let day = date.getDate(),
      month = date.getMonth() + 1;
   day = (day < 10 ? `0${day}` : day);
   month = (month < 10 ? `0${month}` : month);
   return `${day}-${month}-${date.getFullYear()}`;
}

function formatDateBack(date) {
   return `${date.substr(6, 4)}-${date.substr(3, 2)}-${date.substr(0, 2)}`;
}

function formatTime(time) {
   let minutes = time.getMinutes();
   return `${time.getHours()}:${minutes < 10 ? '0' + minutes : minutes}`;
}