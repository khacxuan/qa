<?php
return array(
	'_root_'  => 'user/login',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
	'user/tag/(:alnum)' => 'user/tag/index/$1',
	'user/detail/(:alnum)' => 'user/detail/index/$1',
);