<?php
// Reiniciar o Nginx (exemplo para sistemas Linux)
$output = shell_exec('sudo service nginx restart');
echo "<pre>$output</pre>";
?>