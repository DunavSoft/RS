<?php include('header.php'); ?>
<script type="text/javascript">
var a;
function areyousure(id)
{
	if(id != 0)
		a = $('#b_id' + id).attr('href');
	
	if(id == 0)
		$(location).attr('href', a);

}
</script>

<?php if($this->active_menu == 'invoicesspeditor'):?>
	<?php echo $this->config->item('speditor_tables');?>
<?php endif;?>

<?php if($this->active_menu == 'invoicesexpecta'):?>
	<?php echo $this->config->item('expecta_tables');?>
<?php endif;?>


<?php echo form_open( base_url($this->config->item('admin_folder') . '/'.$this->active_menu.'/show_table_post') , 'id="form_search"'); ?>
<input type="hidden" name="export_action" value="1"/>

<h1 class="pull-left"><?php echo $page_title;?></h1>
<div class="clear"></div>
<label class="searchInfo">Table: </label>
<input type="text" name="table_name" value="<?php echo $table_name;?>" class="form-control input-sm col-3" >

<label class="searchInfo">WHERE: </label>
<input type="text" name="where_txt" value="<?php echo $where_txt;?>" class="form-control input-sm col-2" > 
<input type="text" name="where_sign" value="<?php echo $where_sign;?>" class="form-control input-sm col-1" > 
<input type="text" name="where_val" value="<?php echo $where_val;?>" class="form-control input-sm col-2" >

<label class="searchInfo">ORDER BY: </label>
<input type="text" name="order_by" value="<?php echo $order_by;?>" class="form-control input-sm col-2" > , 
<input type="text" name="asc_desc" value="<?php echo $asc_desc;?>" class="form-control input-sm col-2" >

<label class="searchInfo">LIMIT: </label>
<input type="text" name="limit" value="<?php echo $limit;?>" class="form-control input-sm col-1" > , 

<a class="btn btn-primary pull-right" href="#" onclick="$('#form_search').submit();" >SEARCH</a>

<div class="clear"></div>
<hr/>

<section class="section-table cid-r4YXQsOj6U">
	<div class="row search">
		<div class="dataTables_filter">
			<label class="searchInfo"><?php echo lang('search');?>:</label>
			<input class="form-control input-sm" disabled="">
		</div>
	</div>

	<div class="scroll">
	  <table class="table table-hover isSearch" cellspacing="0" style="width:100%;">
		<thead>
			<tr class="table-heads">
				
				<?php if(isset($elements_exp[0])) 
					foreach ($elements_exp[0] as $key=>$value):?>
				<th class="head-item text-center"><?php echo $key;?></th>
				<?php endforeach;?>
		
			</tr>
		</thead>
		<tbody>
	<?php foreach ($elements_exp as $element):?>
		<tr id="tableRow<?php echo $element->ID;?>">
		<?php foreach ($element as $key=>$value):?>
			<td data-toggle="tooltip" title="<?php echo $key;?>"><?php echo $value;?></td>
		<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
</div>

	<div class="table-info-container">
	  <div class="row info">
		<div class="col-md-6">
		  <div class="dataTables_info mbr-fonts-style display-7">
			<span class="infoBefore"><?php echo lang('showing');?></span>
			<span class="inactive infoRows"></span>
			<span class="infoAfter"><?php echo lang('entries');?></span>
			<span class="infoFilteredBefore">(<?php echo lang('filtered_from');?></span>
			<span class="inactive infoRows"></span>
			<span class="infoFilteredAfter"> <?php echo lang('total_entries');?>)</span>
		  </div>
		</div>
		<div class="col-md-6"></div>
	  </div>
	</div>

</section>
<?php echo form_close(); ?>

<script>
$("document").ready(function() { 
	$("#first_th").trigger('click'); 
	$("#first_th").trigger('click'); 
});

$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});
</script>


<?php include('footer.php'); ?>
