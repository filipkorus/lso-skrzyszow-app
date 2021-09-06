let editor;

ClassicEditor
   .create(document.querySelector('textarea[name=ckeditor]'), {
      toolbar: {
         items: [
            'heading', '|',
            'fontSize', 'fontColor', 'fontFamily', 'fontBackgroundColor', '|',
            'bold', 'italic', 'underline', '|',
            'alignment', '|',
            'numberedList', 'bulletedList', '|',
            'insertTable', 'horizontalLine', 'link', 'blockQuote', 'code', 'highlight', 'specialCharacters', '|',
            'indent', 'outdent', '|',
            'undo', 'redo', '|',
            'sourceEditing'
         ],
         shouldNotGroupWhenFull: true,
         viewportTopOffset: 30
      },
      language: 'pl',
      table: {
         contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells',
            'tableCellProperties',
            'tableProperties'
         ]
      },
      licenseKey: ''
   })
   .then(newEditor => {
      editor = newEditor;
   })
   .catch(error => {
      console.error('Oops, something went wrong!');
      console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
      console.warn('Build id: 5nm2tema1l6r-ciour6nn4q13');
      console.error(error);
   });