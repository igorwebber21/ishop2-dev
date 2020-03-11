
$('#currency').change(function(){

    var currency =  $(this).val();
    window.location = 'currency/change?curr=' + currency;
    console.log();
});