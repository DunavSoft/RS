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

<div class="modal fade" id="ModalConfirm" tabindex="-1" role="dialog" aria-hidden="true">
  	<div class="modal-dialog" role="document">
	    <div class="modal-content">
		    <div class="modal-header">
			    <h5 class="modal-title"><?php echo lang('modal_title_delete');?></h5>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			     	<span aria-hidden="true">×</span>
			    </button>
		    </div>
		    <div class="modal-body">
		      	<?php echo lang('are_you_sure');?>
	      	</div>
	      	<div class="modal-footer">
	        	<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php echo lang('close');?></button>
	        	<button class="btn btn-danger" type="button" onclick="areyousure(0);"><?php echo lang('yes');?></button>
	      	</div>
	    </div>
  	</div>
</div>

<h1 class="pull-left"><?php echo $page_title;?></h1>
<span class="pull-right"> 
<?php $systems_array = array('0' => lang('select'));?>
<?php $systems_array = array_merge($systems_array, $this->config->item('systems'));?>

<label for="system_id"><?php echo lang('new');?> <?php echo lang('accounting_settings');?> <?php echo lang('to_system');?>: </label>
<?php echo form_dropdown('system_id', $systems_array, set_value('system_id',$system_id), 'class="custom-select form-control" id="system_id" onchange="redirectTemplate(); return false;"'); ?>
</span>
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
			<th id="first_th" class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('system');?>"><?php echo lang('system');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('vat_no');?>"><?php echo lang('vat_no');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('name');?>"><?php echo lang('name');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('document_type');?>"><?php echo lang('document_type');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('conditions');?>"><?php echo lang('conditions');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('debit');?>"><?php echo lang('debit');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('credit');?>"><?php echo lang('credit');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('vat_term');?>"><?php echo lang('vat_term');?></th>
			<th class="head-item text-center" data-toggle="tooltip" title="<?php echo lang('sort_by');?><?php echo lang('vies');?>"><?php echo lang('vies');?></th>
			
			<th class="head-item text-center admin_action_col"><?php echo lang('edit_copy_delete');?></th>
		  </tr>
		</thead>
		<tbody>
		
		<?php $last_insert_id = 0; if($this->session->flashdata('last_insert_id')) $last_insert_id = $this->session->flashdata('last_insert_id');?>

		<?php if(count($elements) == 0):?><tr><td class="text-center" colspan="9"><?php echo lang('nothing_to_show');?></td></tr><?php endif;?>
		<?php foreach ($elements as $element):?>
			<tr>
				<td class="body-item <?php if($last_insert_id == $element->id):?>btn-secondary<?php endif;?>" data-toggle="tooltip" title="<?php echo lang('to_system');?>">
					<?php echo $systems_array[$element->system_id];?>
				</td>
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('vat_no');?>"><?php echo $element->vat_no; ?></td>
				
				
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('name');?>"><?php echo $element->company_name; ?></td>
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('document_type');?>"><?php echo $documents_types_array[$element->document_type]; ?></td>
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('conditions');?>"><?php echo $element->conditions; ?></td>
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('debit');?>"><?php echo $element->debit; ?></td>
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('credit');?>"><?php echo $element->credit; ?></td>
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('vat_term');?>"><small><?php echo $vat_term_array[$element->vat_term]; ?></small></td>
				<td class="body-item" data-toggle="tooltip" title="<?php echo lang('vies');?>"><small><?php echo $vies_array[$element->vies_id]; ?></small></td>
				
				<td class="text-right">
					<div class="btn-group buttons_list" role="group">
						<a class="btn btn-secondary" href="<?php echo site_url($this->config->item('admin_folder').'/accounting_settings/form/'.$element->system_id . '/' . $element->id); ?>">
							<i class="fa fa-edit" data-toggle="tooltip" title="<?php echo lang('edit');?>"></i>
						</a>
						<a class="btn btn-secondary" href="<?php echo site_url($this->config->item('admin_folder').'/accounting_settings/form/'.$element->system_id . '/' . $element->id . '/1'); ?>">
							<i class="fa fa-copy" data-toggle="tooltip" title="<?php echo lang('copy');?>"></i>
						</a>
						<a class="btn btn-danger" id="b_id<?php echo $element->id;?>" href="<?php echo site_url($this->config->item('admin_folder').'/accounting_settings/delete/'.$element->id); ?>" data-toggle="modal" data-target="#ModalConfirm" onclick="areyousure(<?php echo $element->id;?>);" >
							<i class="fa fa-trash-alt" data-toggle="tooltip" title="<?php echo lang('delete');?>"></i>
						</a>
					</div>
				</td>
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


<script>
$("document").ready(function() { 
	$("#first_th").trigger('click'); 
	$("#first_th").trigger('click'); 
});
</script>

<script type="text/javascript">
function redirectTemplate() {
	var system_id = '';
	system_id = $( "#system_id" ).val();

	window.location.replace("<?php echo base_url($this->config->item('admin_folder') . '/accounting_settings/form/'); ?>"+system_id+'/');
}
</script>

<?php include('footer.php'); ?>