
$('.select2').select2();



$('.delete').click(function(){
    var res = confirm('Подтвердите действие');
    if(!res) return false;
});


var locationUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;

$('.sidebar-menu a').each(function(){

    var linkUrl = $(this).attr('href');

    if(locationUrl === linkUrl){
        $(this).parent().addClass('active');
        $(this).closest('.treeview').addClass('active');
    }
});