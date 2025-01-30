<?php

$baglanti = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$sorgu = new MongoDB\Driver\Query([]);
$sonuc = $baglanti->executeQuery('kafedb.admin', $sorgu);

print_r($sonuc->toArray());

?>
