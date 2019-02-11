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
			     	<span aria-hidden="true"><?php echo lang('close_symbol');?></span>
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
<a class="btn btn-primary pull-right" href="<?php echo site_url($this->config->item('admin_folder').'/emailsettings/form'); ?>"><?php echo lang('new');?></a>
<div class="clear"></div>
<hr/>

<div class="table-responsive">
	<table class="table table-hover" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th><?php echo lang('default_settings');?></th>
				<th><?php echo lang('name');?></th>
				<th style="text-align:right;"><?php echo lang('edit_copy_delete');?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(count($elements) == 0):?>
			<tr>
				<td colspan="2" align="center">
					<?php echo lang('nothing_to_show');?>
				</td>
			</tr>
		<?php endif;?>
		<?php foreach ($elements as $element):?>
			<tr>
				<td><?php if($element->default_settings == 1) echo lang('yes'); ?></td>
				<td><?php echo $element->name; ?></td>
			
				<td style="text-align:right;">
					<div class="btn-group" role="group" aria-label="Basic example">
						<a class='btn btn-secondary' href="<?php echo site_url($this->config->item('admin_folder').'/emailsettings/form/'.$element->id);?>">
							<i class="fa fa-edit" alt="<?php echo lang('edit');?>" title="<?php echo lang('edit');?>"></i>
						</a>
						<a class='btn btn-secondary' href="<?php echo site_url($this->config->item('admin_folder').'/emailsettings/form/'.$element->id . '/1'); ?>">
							<i class="fa fa-copy" alt="<?php echo lang('copy');?>" title="<?php echo lang('copy');?>"></i>
						</a>
						<a class='btn btn-danger' id="b_id<?php echo $element->id;?>" href="<?php echo site_url($this->config->item('admin_folder').'/emailsettings/delete/'.$element->id); ?>" data-toggle="modal" data-target="#ModalConfirm" onclick="return areyousure(<?php echo $element->id;?>);">
							<i class="fa fa-trash-alt" alt="<?php echo lang('delete');?>" title="<?php echo lang('delete');?>"></i>
						</a>
					</div>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
<?php include('footer.php'); ?>
