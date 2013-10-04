<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
	'mongo' => array(
		// This group is used when no instance name has been provided.
		'default' => array(
			'hostname'   => '10.10.91.11',
			'port' => 27017,
			'database'   => 'qa',
			'username'   => 'qa',
			'password'   => '123456',
		),
	),
);
