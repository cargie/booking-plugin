/*
 * This is a sample JavaScript file used by {{ name }}
 *
 * You can delete this file if you want
 */
function datespickermonth(input){
	var input_class = $(input).attr("class").split(' ').pop()
	$("."+input_class).prop("checked", input.checked)
}
function datespickeryear(input){
	
}