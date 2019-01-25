( function( $ ) {



// Custom Dropdown

$('select').vDrop({allowMultiple: false});



// Datepicker

$('#date_picker').dateTimePicker({

	
	format: 'MM/dd/yyyy'

});


$('#date_picker4').dateTimePicker({

	format: 'MM/dd/yyyy'

});
$('#date_picker3').dateTimePicker({

	format: 'MM/dd/yyyy'	

});

$('#date_picker2').dateTimePicker({

	format: 'MM/dd/yyyy'

});

	

// Custom File Upload

$('#file-upload').change(function() {

    var filepath = this.value;

    var m = filepath.match(/([^\/\\]+)$/);

    var filename = m[1];

    $('#filename').html(filename);

});	

	
$('#file-uploads').change(function() {

    var filepath = this.value;

    var m = filepath.match(/([^\/\\]+)$/);

    var filename = m[1];

    $('#filenames').html(filename);

});	
		

} )( jQuery );	

	

// Current Date

( function( $ ) {

	

var date = new Date();



var day = date.getDate();

var month = date.getMonth() + 1;

var year = date.getFullYear();



if (month < 10) month = "0" + month;

if (day < 10) day = "0" + day;



var today = month + "/" + day + "/" + year;



document.getElementById('theDate').value = today;	

	

} )( jQuery );