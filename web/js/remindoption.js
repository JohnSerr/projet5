/* On récupère la checkbox dans le DOM */

let checkremind = $('checkbox');


function remind() {	

	if(checkremind == false) {

		$('p5_todolistbundle_todolist_remind').css("display", "none");

	} else if (checkremind == true) {

		$('p5_todolistbundle_todolist_remind').css("display", "block");

	}

}