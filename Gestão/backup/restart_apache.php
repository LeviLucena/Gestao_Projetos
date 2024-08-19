<?php
// Reiniciar o Apache (exemplo para sistemas Linux)
$output = shell_exec('sudo service apache2 restart');
echo "<pre>$output</pre>";
?>