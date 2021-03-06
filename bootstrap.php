<?php
use pew\Injector;


// this file fires up pew
// #0# prepare and set the include path

// change to root app directory if we are in webroot/
if (basename(getcwd()) == 'webroot') chdir('../');

set_include_path(get_include_path()
	. PATH_SEPARATOR . dirname( __FILE__ ) . '/classes'
	. PATH_SEPARATOR . getcwd() . '/webroot'
);

// is there a lib folder? if though, add to include path
if (is_dir('lib')) {
	set_include_path(get_include_path()
		. PATH_SEPARATOR . getcwd() . '/lib'
	);
}

$GLOBALS['fileExistsInIncludePathCache'] = array();
function fileExistsInIncludePath($file) {

    if (in_array($file, $GLOBALS['fileExistsInIncludePathCache'])) return true;

    if (class_exists('logger\Logger'))
      logger\Logger::log('fileExistsInIncludePath Cache miss for ' . $file);
    
    $ps = explode(":", ini_get('include_path'));
    foreach($ps as $path) {
        if(file_exists($path.'/'.$file)) {
          $GLOBALS['fileExistsInIncludePathCache'][] = $file;
          return true;
        }
    }
    if(file_exists($file)) {
      $GLOBALS['fileExistsInIncludePathCache'][] = $file;
      return true;
    }
    return false;
}


// #1# register an autoloader which is capable to load pew classes
spl_autoload_register(function($class) {
    
    // remove leading pew\ namespace
   	// TODO is here a better way to do this?
   	if ('pew\\' == substr($class, 0, 4)) $class = substr($class, 4);

   	if (substr($class, -4, 4) != '.php') $file = str_replace('\\', '/', $class). '.php';

   	//if (fileExistsInIncludePath($file))
    try {
      include_once($file);  
    } catch (Exception $e) {

    }
   	// TODO fix that... problems on case sensitiv filesystems
   	//@include_once(lcfirst($file));
});

// #2# load the dependency injector and bind some interfaces/classes/singeltons
$injector = new Injector();
// not needed?
$injector->bind('pew\Injector', 'pew\Injector', true);


// #3# load the setup
if (is_file('config/Config.php'))
	$injector->bind('pew\Config', 'config\Config');
else $injector->bind('pew\Config', 'pew\Config', true);

if (is_file('config/Setup.php'))
	$injector->bind('pew\Setup', 'config\Setup');

$setup = $injector->getInstance('pew\Setup');


// #4# load autorun classes
$config = $injector->getInstance('pew\Config');
if (is_array($config->autoloadClasses))
	foreach($config->autoloadClasses AS $clazz) {
		$injector->getInstance($clazz);
	}

// #5# load and run the dispatcher
$dispatcher = $injector->getInstance('pew\Dispatcher');
//$dispatcher->run();

// #6# give the renderer the possibility to make some output
$renderer = $injector->getInstance('pew\Renderer');
//$renderer->prepare($dispatcher->getClass(), $dispatcher->getMethod(), $dispatcher->getRender());
// call this when you are rdy
// $renderer->run();

?>