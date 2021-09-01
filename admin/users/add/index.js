const inputs = document.querySelectorAll('input');

document.querySelector('form').onsubmit = async e => {
   e.preventDefault();

   let f = false;
   inputs.forEach(input => {
      if (!input.value.length) {
         if (input.name === 'username') {
            input.value = document.querySelector('input[name=name]').value.toLowerCase() + document.querySelector('input[name=last_name]').value.toLowerCase();
         } else {
            input.classList.add('uk-form-danger');
            f = true;
         }
      } else input.classList.remove('uk-form-danger');
   });

   if (f) return UIkit.notification({
      message: 'Uzupełnij wszystkie pola!',
      status: 'danger',
      timeout: 5000
   });

   const formData = new FormData();
   formData.append('name', document.querySelector('input[name=name]').value);
   formData.append('last_name', document.querySelector('input[name=last_name]').value);
   formData.append('birthdate', document.querySelector('input[name=birthdate]').value);
   formData.append('username', document.querySelector('input[name=username]').value);
   formData.append('password', document.querySelector('input[name=password]').value);
   formData.append('email', document.querySelector('input[name=email]').value);
   formData.append('phone_no', document.querySelector('input[name=phone_no]').value);
   formData.append('role', document.querySelector('select[name=role]').value);
   formData.append('admin', document.querySelector('input[name=admin]').checked);

   const res = await fetch('./../../scripts/add-user.php', {
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

   // clear all inputs
   document.querySelector('select[name=role]').selectedIndex = 0
   inputs.forEach(input => {
      if (input.type === 'text' || input.type === 'email' || input.type === 'date') input.value = '';
      else if (input.type === 'checkbox') input.checked = false;

      if (input.type === 'date') input.type = 'text';
   });
}

inputs.forEach(input => {
   input.addEventListener('keydown', () => {
      if (input.value.length) input.classList.remove('uk-form-danger');
   });
});