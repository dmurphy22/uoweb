<?php
include_once 'inc/config.php';

if (!array_key_exists('n', $_REQ))
	return;

$n = preg_replace('/\D/', '', $_REQ['n']);
if (!$n)
	return;

include_once 'inc/mongo.php';

$c = $md->cliloc;
$data = $c->find([_id => $n],['_id' => false])['text'];

header('Vary: Accept-Encoding');
echo $data;
?>