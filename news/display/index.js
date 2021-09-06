const tbody = document.querySelector('tbody'),
   template = document.querySelector('template'),
   loadMoreBtn = document.querySelector('button[name=loadNews]');

let newsCounter = 10;

loadMoreBtn.onclick = () => {
   newsCounter += 5;
   loadNews();
}

loadNews();

async function loadNews() {
   const res = await fetch(`./../get.php?n=${newsCounter}`);
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
      div.querySelector('[data-title]').textContent = news.title;
      div.querySelector('[data-body]').innerHTML = news.body.replace(/(<([^>]+)>)/gi, ' ').substr(0, 30) + '...';
      div.querySelector('[data-added_at]').textContent = news.added_at;
      div.querySelector('[data-author]').textContent = news.author;
      tbody.append(div);
   });

   $('.tablesorter').trigger('update');
}