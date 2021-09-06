const tbody = document.querySelector('tbody'),
   template = document.querySelector('template'),
   loadMoreBtn = document.querySelector('button[name=loadNews]'),
   form = document.querySelector('form');

let newsCounter = 10;
let newsArr;

loadNews();

loadMoreBtn.onclick = () => {
   newsCounter += 10;
   loadNews();
}


async function loadNews() {
   const res = await fetch(`./../../../news/get.php?n=${newsCounter}`);
   const data = await res.json();

   if (data.disable_btn) {
      loadMoreBtn.disabled = true;
      loadMoreBtn.hidden = true;
   }

   if (data.error) return UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });;

   tbody.innerHTML = '';

   data.news.forEach(news => {
      const div = template.content.cloneNode(true);
      div.querySelector('[data-title]').href = '/news/' + news.id;
      div.querySelector('[data-body]').href = '/news/' + news.id;
      div.querySelector('[data-id]').textContent = news.id;
      div.querySelector('[data-title]').textContent = news.title;
      div.querySelector('[data-body]').innerHTML = news.body.replace(/(<([^>]+)>)/gi, ' ').substr(0, 30) + '...';
      div.querySelector('[data-added_at]').textContent = news.added_at;
      div.querySelector('[data-author]').textContent = news.author;
      div.querySelector('[data-edit]').dataset.id = news.id;

      div.querySelector('tr').ondblclick = (e) => openEditModal(news.id);
      div.querySelector('[data-edit]').onclick = (e) => openEditModal(news.id);

      tbody.append(div);
   });

   newsArr = data.news;
   $('.tablesorter').trigger('update');
}

function openEditModal(id) {
   const news = getNewsById(id);

   document.querySelector('input[name=id]').value = id;
   document.querySelector('input[name=added_at]').value = news.added_at;
   document.querySelector('input[name=author]').value = news.author;
   document.querySelector('input[name=title]').value = news.title;
   editor.setData(news.body);

   UIkit.modal('#modal').show();
}

form.onsubmit = async e => {
   e.preventDefault();
   const idInput = document.querySelector('input[name=id]'),
      titleInput = document.querySelector('input[name=title]'),
      body = editor.getData();

   if (!(titleInput.value.length && idInput.value.length && body.length)) return UIkit.notification({
      message: 'Uzupełnij wszystkie pola!',
      status: 'danger',
      timeout: 5000
   });

   const formData = new FormData();
   formData.append('id', idInput.value);
   formData.append('title', titleInput.value);
   formData.append('body', body);

   const res = await fetch('./../edit.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   if (!data.error) {
      loadNews();
      UIkit.modal('#modal').hide();
   }
}

async function deleteNews(id) {

   const formData = new FormData();
   formData.append('id', id);

   const res = await fetch('./../delete.php', {
      method: 'POST',
      body: formData
   });
   const data = await res.json();

   UIkit.notification({
      message: data.msg,
      status: data.status || 'danger',
      timeout: 5000
   });

   if (!data.error) loadNews();
}

function getNewsById(id) {
   return newsArr.find(news => news.id == id);
}