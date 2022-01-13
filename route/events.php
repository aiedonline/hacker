<?php


$function = new ReflectionFunction($data['action']);
echo $function->invoke($data);

?>