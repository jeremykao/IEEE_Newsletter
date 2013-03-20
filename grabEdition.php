<?php  //grabEdition.php

require "database.php";

$editionArr = array(2);

$row = grabEdition();
$editionArr["weekNum"] = $row[0];
$editionArr["quarterType"] = $row[1];
echo json_encode($editionArr);

?>
