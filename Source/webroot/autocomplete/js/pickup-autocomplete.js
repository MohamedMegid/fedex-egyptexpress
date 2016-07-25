$(function(){
var shippers = (function () {
    var shippers = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': "http://localhost/mm/egyptexpress/Source/en/shipment/getshipersnames",
        'dataType': "json",
        'success': function (data) {
			if(data){
				shippers = data;
			}
        }
    });
    return shippers;
})(); 
  
  // setup autocomplete function pulling from shippers[] array
  $('#shiper_autocomplete').autocomplete({
    lookup: shippers,
    onSelect: function (suggestion) {
	  //var returend values from json
	  var cperson = suggestion.cperson;
	  var address1 = suggestion.address1;
	  var address2 = suggestion.address2;
	  var tel = suggestion.tel;
	  var fax = suggestion.fax;
	  var mobile = suggestion.mobile;
	  var city = suggestion.city;

	  //set input values
	  document.getElementById("shipper_cperson").value = cperson;
	  document.getElementById("shipper_address1").value = address1;
	  document.getElementById("shipper_address2").value = address2;
	  document.getElementById("shipper_tel").value = tel;
	  document.getElementById("shipper_fax").value = fax;
	  document.getElementById("shipper_mobile").value = mobile;
	  document.getElementById("shipper_city").value = city;
    }
  });
  

});

$(function(){
var shippers = (function () {
    var shippers = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': "http://localhost/mm/egyptexpress/Source/en/shipment/getconsigneesnames",
        'dataType': "json",
        'success': function (data) {
			if(data){
				shippers = data;
			}
        }
    });
    return shippers;
})(); 
  
  // setup autocomplete function pulling from shippers[] array
  $('#consignee_autocomplete').autocomplete({
    lookup: shippers,
    onSelect: function (suggestion) {
	  //var returend values from json
	  var cperson = suggestion.cperson;
	  var address1 = suggestion.address1;
	  var address2 = suggestion.address2;
	  var tel = suggestion.tel;
	  var fax = suggestion.fax;
	  var mobile = suggestion.mobile;
	  var city = suggestion.city;

	  //set input values
	  document.getElementById("consignee_cperson").value = cperson;
	  document.getElementById("consignee_address1").value = address1;
	  document.getElementById("consignee_address2").value = address2;
	  document.getElementById("consignee_tel").value = tel;
	  document.getElementById("consignee_fax").value = fax;
	  document.getElementById("consignee_mobile").value = mobile;
	  document.getElementById("consignee_city").value = city;
    }
  });
  

});