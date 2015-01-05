<?php

include 'lib/Atom.php';



$atom = new Atom ($request->path[0], '#', 'today');

$atom->feed(array('author'=>array('name'=>'CS Web Services Atom Publisher'), 
	'link'=>array('rel'=>'self', 
		'type'=>'application/atom+xml', 
		'href'=>$_SERVER['REQUEST_URI']) 
	));

foreach ($atom_array as $key => $value) {
	$atom->entry($value['title'], 
	'#', 
	$value['date'], 
	array('link'=>array('rel'=>'alternate', 
		'href'=>'#'), 
	'content'=>$value['content']));

}





$atom->display();


?>