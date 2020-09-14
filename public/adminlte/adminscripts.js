
// =================  upload product images  ================= //

var buttonSingle = $("#single"),
    buttonMulti = $("#multi"),
    file;

new AjaxUpload(buttonSingle, {
    action: adminpath + '/' + buttonSingle.data('url') + "?upload=1",
    data: {name: buttonSingle.data('name')},
    name: buttonSingle.data('name'),
    onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
            alert('Ошибка! Разрешены только картинки');
            return false;
        }
        buttonSingle.closest('.file-upload').find('.overlay').css({'display':'block'});

    },
    onComplete: function(file, response){
        setTimeout(function(){
            buttonSingle.closest('.file-upload').find('.overlay').css({'display':'none'});

            response = JSON.parse(response);
            $('.' + buttonSingle.data('name')).html('<img src="/upload/products/base/' + response.file + '" style="max-height: 150px;">');
        }, 1000);
    }
});

new AjaxUpload(buttonMulti, {
    action: adminpath + '/' + buttonMulti.data('url') + "?upload=1",
    data: {name: buttonMulti.data('name')},
    name: buttonMulti.data('name'),
    onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
            alert('Ошибка! Разрешены только картинки');
            return false;
        }
        buttonMulti.closest('.file-upload').find('.overlay').css({'display':'block'});

    },
    onComplete: function(file, response){
        setTimeout(function(){
            buttonMulti.closest('.file-upload').find('.overlay').css({'display':'none'});

            response = JSON.parse(response);
            $('.' + buttonMulti.data('name')).append('<img src="/upload/products/gallery/' + response.file + '" style="max-height: 150px;">');
        }, 1000);
    }
});
// =================  upload product images  ================= //


// =================  reset product filters  ================= //
$('#reset-filter').click(function(){
    $('#product-filters-tabs input[type=radio]').iCheck('uncheck');
    $('#product-filters-tabs input[type=radio]').prop("checked", false);
    return false;
});

// =================  remove product filters  ================= //

// =================  delete item  ================= //
$('.delete').click(function(){
    var res = confirm('Подтвердите действие');
    if(!res) return false;
});

// =================  active menu item  ================= //
var locationUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;

$('.sidebar-menu a').each(function(){
    var linkUrl = $(this).attr('href');
    if(locationUrl === linkUrl){
        $(this).parent().addClass('active');
        $(this).closest('.treeview').addClass('active');
    }
});
// =================  active menu item  ================= //

// =================  form select beauty  ================= //
$('.select2').select2();

$(".select2.related-products").select2({
    placeholder: "Начните вводить наименование товара",
    //minimumInputLength: 2,
    cache: true,
    ajax: {
        url: adminpath + "/product/related-product",
        delay: 250,
        dataType: 'json',
        data: function (params) {
            return {
                q: params.term,
                page: params.page
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        }
    }
});


// =================  ckEditor init  ================= //
// Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
//CKEDITOR.replace('editorProduct');
$('#editorProduct').ckeditor();


//iCheck for checkbox and radio inputs
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass   : 'iradio_minimal-blue'
});
//Red color scheme for iCheck
$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
    radioClass   : 'iradio_minimal-red'
});
//Flat red color scheme for iCheck
$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
});
