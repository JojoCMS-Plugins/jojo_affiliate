$(document).ready(function(){
  $('#affiliate_commission').change(function(){
    var amount = $(this).val();
    if ((!isNaN(amount)) && (amount <= parseInt($('#commission_rate').html()))) {
        var other = parseInt($('#commission_rate').html()) - amount;
        $('#customer_commission').val(other);
    }
  });
  
  $('#customer_commission').change(function(){
    var amount = $(this).val();
    if ((!isNaN(amount)) && (amount <= parseInt($('#commission_rate').html()))) {
        var other = parseInt($('#commission_rate').html()) - amount;
        $('#affiliate_commission').val(other);
    }
  });
});