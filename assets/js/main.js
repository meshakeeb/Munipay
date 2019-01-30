( function( $ ) {

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
