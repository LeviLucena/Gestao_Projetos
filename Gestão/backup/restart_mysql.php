<?php
// Reiniciar o MySQL (exemplo para sistemas Linux)
$output = shell_exec('sudo service mysql restart');
echo "<pre>$output</pre>";
?>