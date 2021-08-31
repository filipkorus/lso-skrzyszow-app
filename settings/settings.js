const usernameInput = document.querySelector('input[name=username]'),
   emailInput = document.querySelector('input[name=email]'),
   pw1Input = document.querySelector('input[name=pw1]'),
   pw2Input = document.querySelector('input[name=pw2]'),
   phoneNoInput = document.querySelector('input[name=phone_no]'),
   fileInput = document.querySelector('input[name=image]'),
   passwordInput = document.querySelector('input[name=password-confirm]');

let action = '';

document.querySelector('form[name=username]').onsubmit = e => {
   e.preventDefault();
   action = 'username';

   if (!usernameInput.value.length) return UIkit.notification({
      message: 'Uzupełnij wszystkie pola!',
      status: 'danger',
      timeout: 5000
   });

   if (usernameInput.value.length < 4 || usernameInput.value.length > 30) return UIkit.notification({
      message: 'Login powienien posiadać od 4 do 30 znaków!',
      status: 'danger',
      timeout: 5000
   });

   document.getElementById('open-modal').click();
}

document.querySelector('form[name=email]').onsubmit = e => {
   e.preventDefault();
   action = 'email';

   if (!emailInput.value.length) return UIkit.notification({
      message: 'Uzupełnij wszystkie pola!',
      status: 'danger',
      timeout: 5000
   });

   document.getElementById('open-modal').click();
}

document.querySelector('form[name=password]').onsubmit = e => {
   e.preventDefault();
   action = 'password';

   if (!pw1Input.value.length || !pw1Input.value.length) return UIkit.notification({
      message: 'Uzupełnij wszystkie pola!',
      status: 'danger',
      timeout: 5000
   });

   if (pw1Input.value !== pw2Input.value) return UIkit.notification({
      message: 'Hasła nie są identyczne!',
      status: 'danger',
      timeout: 5000
   });

   if (pw1Input.value.length < 6 || pw1Input.value.length > 30) return UIkit.notification({
      message: 'Hasło powinno posiadać od 6 do 30 znaków!',
      status: 'danger',
      timeout: 5000
   });

   document.getElementById('open-modal').click();
}

document.querySelector('form[name=phone_no]').onsubmit = e => {
   e.preventDefault();
   action = 'phone_no';

   if (!phoneNoInput.value.length) return UIkit.notification({
      message: 'Uzupełnij wszystkie pola!',
      status: 'danger',
      timeout: 5000
   });

   document.getElementById('open-modal').click();
}

document.querySelector('form[name=image]').onsubmit = e => {
   e.preventDefault();

   action = 'image';

   if (!fileInput.files.length) return UIkit.notification({
      message: 'Dodaj zdjęcie!',
      status: 'danger',
      timeout: 5000
   });

   document.getElementById('open-modal').click();
}

document.getElementById('open-modal').onclick = () => {
   passwordInput.autofocus = true;
}

document.querySelector('button[name=password-confirm]').onclick = () => {
   if (!passwordInput.value.length) return UIkit.notification({
      message: 'Podaj hasło, aby zapisać zmiany!',
      status: 'danger',
      timeout: 5000
   });

   switch (action) {
      case 'username':
         updateUsername();
         break;
      case 'email':
         updateEmail();
         break;

      case 'password':
         updatePassword();
         break;

      case 'phone_no':
         updatePhoneNo();
         break;

      case 'image':
         updateImage();
         break;

      default:
         break;
   }
}

passwordInput.addEventListener('keyup', e => {
   if (e.keyCode === 13) {
      e.preventDefault();
      document.querySelector('button[name=password-confirm]').click();
   }
});

async function updateUsername() {
   const formData = new FormData();
   formData.append('username', usernameInput.value);
   formData.append('password', passwordInput.value);

   const res = await fetch('./scripts/update-username.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   passwordInput.value = '';
   if (data.msg !== 'Nieprawidłowe hasło!') document.getElementById('open-modal').click();

   if (data.error) return;

   usernameInput.value = '';
}

async function updateEmail() {
   const formData = new FormData();
   formData.append('email', emailInput.value);
   formData.append('password', passwordInput.value);

   const res = await fetch('./scripts/update-email.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   passwordInput.value = '';
   if (data.msg !== 'Nieprawidłowe hasło!') document.getElementById('open-modal').click();

   if (data.error) return;

   emailInput.value = '';
}

async function updatePassword() {
   const formData = new FormData();
   formData.append('new_password', pw1Input.value);
   formData.append('password', passwordInput.value);

   const res = await fetch('./scripts/update-password.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   passwordInput.value = '';
   if (data.msg !== 'Nieprawidłowe hasło!') document.getElementById('open-modal').click();

   if (data.error) return;

   pw1Input.value = '';
   pw2Input.value = '';
}

async function updatePhoneNo() {
   const formData = new FormData();
   formData.append('phone_no', phoneNoInput.value);
   formData.append('password', passwordInput.value);

   const res = await fetch('./scripts/update-phone-no.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   passwordInput.value = '';
   if (data.msg !== 'Nieprawidłowe hasło!') document.getElementById('open-modal').click();

   if (data.error) return;

   phoneNoInput.value = '';
}

async function updateImage() {
   const formData = new FormData();
   formData.append('image', fileInput.files[0]);
   formData.append('password', passwordInput.value);

   const res = await fetch('./scripts/update-picture.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   passwordInput.value = '';
   if (data.msg !== 'Nieprawidłowe hasło!') document.getElementById('open-modal').click();

   if (data.error) return;

   // clear input
   fileInput.value = null;
   document.querySelector('input[name=file-label]').value = 'Wybierz zdjęcie';
}