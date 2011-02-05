<?php
// this file fires up pew
// #0# prepare and set the include path

// change to root app directory
chdir('../');

set_include_path(get_include_path()
	. PATH_SEPARATOR . dirname( __FILE__ ) . '/classes'
);


// #1# register an autoloader which is capable to load pew classes
spl_autoload_register(function ($class) {
	
	// remove leading pew\ namespace
	// TODO is here a better way to do this?
	if ('pew\\' == substr($class, 0, 4)) $class = substr($class, 4);

	if (!ereg('.php', $class)) $file = str_replace('\\', '/', $class). '.php';

	if (!require_once($file)) {
		// echo '<pre>';
		// print_r(debug_backtrace());
		// echo '</pre>';
		throw new \Exception('required File not found: ' .  $file);
	}
});

// #2# load the dependency injector and bind some interfaces/classes/singeltons
$injector = new pew\Injector();
$injector->bind('pew\Config', 'pew\Config', true);
$injector->bind('pew\Context', 'pew\Context', true);


// #3# if a custom config class exists, bind it to pew/Config
if (is_file('config/Config.php')) 
	$injector->bind('pew\Config', 'config\Config');

// #4# load and run the dispatcher
$dispatcher = $injector->getInstance('pew\Dispatcher');
$dispatcher->run();

?>