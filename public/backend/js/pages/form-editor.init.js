/**
 * @Author: Anwarul
 * @Date: 2025-12-31 11:31:40
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-07 14:19:52
 * @Description: Innova IT
 */
$(document).ready(function () {
    0 < $("#elm1").length &&
    tinymce.init({
        selector: '#elm1',
       convert_urls: false,
       statusbar: false,

       plugins: 'image code print preview fullpage  searchreplace autolink directionality  visualblocks visualchars fullscreen image link    table charmap hr pagebreak nonbreaking  toc insertdatetime advlist lists textcolor wordcount   imagetools    contextmenu colorpicker textpattern media ',
           toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat |undo redo | image code| link fontsizeselect  | ',

           image_title: true,
                   automatic_uploads: true,
                   images_upload_url: '{{url("/image_upload")}}',
                   images_upload_base_path: '/storages/',
                   file_picker_types: 'image',
                   images_upload_credentials: true,
                   file_picker_callback: function(cb, value, meta) {

                       var input = document.createElement('input');
                       input.setAttribute('type', 'file');
                       input.setAttribute('accept', 'image/*');

                       input.onchange = function() {
                           var file = this.files[0];

                           var reader = new FileReader();
                           reader.readAsDataURL(file);
                           reader.onload = function () {
                               var id = 'blobid' + (new Date()).getTime();
                               var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                               var base64 = reader.result.split(',')[1];
                               var blobInfo = blobCache.create(id, file, base64);
                               blobCache.add(blobInfo);
                               cb(blobInfo.blobUri(), { title: file.name });
                           };
                       };
                       input.click();
                   }
               });
});
