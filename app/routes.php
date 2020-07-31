<?php

$route[] = ['/','HomeController@index'];
$route[] = ['/login', 'AuthController@index'];
$route[] = ['/register', 'AuthController@register'];
$route[] = ['/logout', 'AuthController@logout'];
return $route;

?>
