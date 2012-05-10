<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function cartalog_delete_plugin() {
	require_once('cartalog.php');
	$cartalog->uninstall();
}

cartalog_delete_plugin();

?>