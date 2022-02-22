<?php
return [
	'/user/:uid$' => ['profile/info', '*'],  
	'/book/:bid' => ['gbook/index', '*'],  	
	'/page/:pn$' => ['single/page', '*'],  
];