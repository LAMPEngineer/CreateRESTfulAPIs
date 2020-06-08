<?php
/**
 * autoload rules to load controller, model, view 
 * and config files as needed
 */
spl_autoload_register('apiAutoload');
function apiAutoload($classname)
{
	if(preg_match('/[a-zA-Z]+Controller$/', $classname)){
		include __DIR__ . '/controllers/' . $classname . '.php';
		return true;
	} elseif (preg_match('/[a-zA-Z]+Interface$/', $classname)) {
		include __DIR__ . '/interfaces/' . $classname . '.php';
		return true;
	} elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
		include __DIR__ . '/models/' . $classname . '.php';
		return true;
	} elseif (preg_match('/[a-zA-Z]+View$/', $classname)) {
		include __DIR__ . '/views/' . $classname . '.php';
		return true;
	} elseif (preg_match('/[a-zA-Z]+Config$/', $classname)) {
		include __DIR__ . './../configs/' . $classname . '.php';
	}
}