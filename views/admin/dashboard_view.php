<?php include('header.php'); ?>

*DASHBOARD*

<?php if($connection_speditor):?>
	<p>Connection to S OK</p>
<?php else:?>
	<p>Warning: Not established connection S!</p>
<?php endif;?>

<?php if($connection_expecta):?>
	<p>Connection to E OK</p>
<?php else:?>
	<p>Warning: Not established connection E!</p>
<?php endif;?>

<?php include('footer.php'); ?>
