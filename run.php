<?php
require "./class.php";
echo ">> REFF : "; $reff = trim(fgets(STDIN));
echo ">> PROXIES : "; $proxies = trim(fgets(STDIN));
$run = new Execute;
$run->reff = $reff;
$run->proxies = $proxies;
$run->exe();
?>