<?php

$instance_name = $_GET['name'];

$shell_out = shell_exec('sh start_instance.sh ' . $instance_name);
echo $shell_out;

?>