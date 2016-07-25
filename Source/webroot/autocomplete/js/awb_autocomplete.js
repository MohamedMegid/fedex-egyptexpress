$(function(){
var awb_auto = (function () {
    var awb_auto = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': "http://localhost/mm/egyptexpress/Source/en/shipment/getawpauto",
        'dataType': "json",
        'success': function (data) {
			if(data){
				awb_auto = data;
			}
        }
    });
    return awb_auto;
})(); 
  
  // setup autocomplete function pulling from awb_auto[] array
  $('#recipient_autocomplete').autocomplete({
    lookup: awb_auto,
    onSelect: function (suggestion) {
	  //var returend values from json
	  var address1 = suggestion.address1;
	  var address2 = suggestion.address2;
	  var phone = suggestion.phone;
	  var city = suggestion.city;

	  //set input values
	  document.getElementById("recipient_address1").value = address1;
	  document.getElementById("recipient_address2").value = address2;
	  document.getElementById("recipient_phone").value = phone;
	  document.getElementById("recipient_city").value = city;
    }
  });
  

});