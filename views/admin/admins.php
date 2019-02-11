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
<a class="btn btn-primary pull-right" href="<?php echo site_url($this->config->item('admin_folder').'/admin/form'); ?>"><?php echo lang('new');?></a>
<div class="clear"></div>
<hr/>

<div class="table-responsive">
	<table class="table table-hover" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="zd_cell_left"><?php echo lang('firstname');?></th>
				<th><?php echo lang('lastname');?></th>
				<th><?php echo lang('email');?></th>
				<th><?php echo lang('access');?></th>
				<th class="zd_cell_right"><?php echo lang('edit_copy_delete');?></th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($admins as $admin):?>
			<tr>
				<td><?php echo $admin->firstname; ?></td>
				<td><?php echo $admin->lastname; ?></td>
				<td><?php echo $admin->email;?></td>
				<td><?php echo $admin->access; ?></td>
				<td style="text-align:right;">
					<div class="btn-group" role="group" aria-label="Basic example">
						<a class='btn btn-secondary' href="<?php echo site_url($this->config->item('admin_folder').'/admin/form/'.$admin->id);?>">
							<i class="fa fa-edit" alt="<?php echo lang('edit');?>" title="<?php echo lang('edit');?>"></i>
						</a>
						<?php
						$current_admin	= $this->session->userdata('admin');
						if ($current_admin['id'] != $admin->id): ?>
						<a class='btn btn-danger' id="b_id<?php echo $admin->id;?>" href="<?php echo site_url($this->config->item('admin_folder').'/admin/delete/'.$admin->id); ?>" data-toggle="modal" data-target="#ModalConfirm" onclick="return areyousure(<?php echo $admin->id;?>);">
							<i class="fa fa-trash-alt" alt="<?php echo lang('delete');?>" title="<?php echo lang('delete');?>"></i>
						</a>
						<?php endif; ?>
					</div>
				</td>
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php include('footer.php'); ?>
