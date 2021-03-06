<?php
include_once 'inc/config.php';

if (!array_key_exists('id', $_REQ))
	return;

$id = intval($_REQ['id'], 0);

if ($id < 0 || $id > 65535)
	return;

$count = 10;

if (array_key_exists('count', $_REQ)) {
	$count = intval($_REQ['count'], 0);

	if ($count <= 0)
		$count = 10;

	if ($count > 50)
		$count = 50;
}

$key = "itemlist-$id-$count";

include_once 'inc/redis.php';

$itemlist = $rd->get($key);

if (!$itemlist) {
	include_once 'inc/mongo.php';

	$c = $md->itemdata;
	$data = $c->find(['_id' => ['$gte' => $id]], ['png' => false]);
	$data->sort(['_id' => 1]);
	$data->limit($count);

	$itemlist = json_encode(iterator_to_array($data, false));
	$rd->set($key, $itemlist);
}

header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=3600');
header('Vary: Accept-Encoding');
header('Content-Type: application/javascript');
echo $itemlist;
?>
