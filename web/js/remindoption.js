/* On met le selecteur de date invisible par défaut */

$('#p5_todolistbundle_todolist_dateofend').parent().css("display", "none");

/* On récupère la checkbox dans le DOM */

let checkremind = $('#p5_todolistbundle_todolist_remind');

/* On implante un listener sur la checkbox */

checkremind.change( function () {
	if (checkremind.is(':checked')) {
		$('#p5_todolistbundle_todolist_dateofend').parent().css("display", "block");
	} else {
		$('#p5_todolistbundle_todolist_dateofend').parent().css("display", "none");	
	}
});

