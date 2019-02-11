<?php
	$_js = new JSCrunch();
	$_js->addFile('js/jquery.data-tables.min');

	// https://datatables.net/examples/styling/bootstrap4.html
	//pagination
	
	echo $_js->crunch();
?>

<?php if( isset($elements) && count($elements) > 2):?>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<?php endif;?>

	</div></div>
	<?php if(isset($this->connected) && !$this->connected):?><span class="danger"> <?php echo lang('no_expecta_connection');?> </span><?php endif;?>
	<footer class="footer navbar-fixed-bottom">2018 &copy; <span class="pull-right"><?php if(isset($elapsed_time)) echo $elapsed_time;?></span></footer>	</body>
</html>