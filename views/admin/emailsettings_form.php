<?php include('header.php'); ?>

<script type="text/javascript">
var a;
function areyousure(id)
{
	if(id == 1)
	{
		$(a).remove();
	}else
	{
		a = '#' + id.attr('rel');
	}
	
	$('#ModalConfirm').modal('toggle');
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
	        	<button class="btn btn-danger" type="button" onclick="areyousure(1);"><?php echo lang('yes');?></button>
	      	</div>
	    </div>
  	</div>
</div>

<div class="clear"></div>

<?php echo form_open( site_url($this->config->item('admin_folder').'/emailsettings/form/'.$id) , 'id="element_form"'); ?>

<div class="row">
	<div class="col-lg-9">
	<h1><a href="<?php echo site_url($this->config->item('admin_folder').'/emailsettings');?>"><i class="fa fa-arrow-left"></i></a> <?php echo $page_title; ?></h1>
	</div>

	<div class="col-lg-3 text-right">
	  <div class="btn-group" role="group">
		  <input class="btn btn-primary" name="submit" type="submit" value="<?php echo lang('save');?>" />
		  <input class="btn btn-secondary" name="submit_save_and_return" type="submit" value="<?php echo lang('save_return');?>" />
	  </div>
	</div>
</div>

<div class="clear"></div>
<hr/>

<div class="form-group row">
  <label for="name_main" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('name');?> <span class="danger">*</span></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'name_main', 'name'=>'name', 'value'=>set_value('name', $name), 'class'=>'form-control', 'required'=>'required');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
	<label for="protocol" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('protocol');?> <span class="danger">*</span></label>
	<div class="col-lg-10 col-sm-8">
		<?php
			$select_array = array();
			$select_array['smtp'] = 'smtp';
			$select_array['sendmail'] = 'sendmail';
		  
			echo form_dropdown('protocol', $select_array, set_value('protocol',$protocol), 'class="form-control custom-select" id="protocol" required');
		?>
	</div>
</div>

<div class="form-group row">
  <label for="smtp_host" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('smtp_host');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'smtp_host', 'name'=>'smtp_host', 'value'=>set_value('smtp_host', $smtp_host), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="smtp_port" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('smtp_port');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'smtp_port', 'name'=>'smtp_port', 'value'=>set_value('smtp_port', $smtp_port), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="smtp_timeout" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('smtp_timeout');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'smtp_timeout', 'name'=>'smtp_timeout', 'value'=>set_value('smtp_timeout', $smtp_timeout), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="smtp_user" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('smtp_user');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'smtp_user', 'name'=>'smtp_user', 'value'=>set_value('smtp_user', $smtp_user), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="smtp_pass" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('smtp_pass');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'name_main', 'name'=>'smtp_pass', 'value'=>set_value('smtp_pass', $smtp_pass), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="smtp_crypto" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('smtp_crypto');?></span></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'smtp_crypto', 'name'=>'smtp_crypto', 'value'=>set_value('smtp_crypto', $smtp_crypto), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>


<div class="form-group row">
  <label for="default_company_email" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('default_company_email');?></span></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'default_company_email', 'name'=>'default_company_email', 'value'=>set_value('default_company_email', $default_company_email), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="default_company_email_name" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('default_company_email_name');?></span></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'default_company_email_name', 'name'=>'default_company_email_name', 'value'=>set_value('default_company_email_name', $default_company_email_name), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>


<div class="form-group row">
	<label for="default_settings" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('default_settings');?></label>
	<div class="col-lg-10 col-sm-8">
		<?php
			$select_array = array();
			$select_array['0'] = lang('no');
			$select_array['1'] = lang('yes');
		  
			echo form_dropdown('default_settings', $select_array, set_value('default_settings', $default_settings), 'class="form-control custom-select" id="default_settings"');
		?>
	</div>
</div>

<div class="clear"></div>


<div class="text-center margin_t_b_10">
  <div class="btn-group" role="group">
      <input class="btn btn-primary f_submit" name="submit" type="submit" value="<?php echo lang('save');?>" />
      <input class="btn btn-secondary f_submit" name="submit_save_and_return" type="submit" value="<?php echo lang('save_return');?>" />
  </div>
</div>

</form>
<hr/>

<?php echo form_open( site_url($this->config->item('admin_folder').'/emailsettings/send/'.$id) , 'id="test_form"'); ?>

<div class="row">
	<div class="col-lg-9">
	<h1 class="text-center"><?php echo lang('test_email'); ?></h1>
	</div>
</div>

<div class="form-group row">
  <label for="test_test_email" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('test_test_email');?></span></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'test_test_email', 'name'=>'test_test_email', 'value'=>set_value('test_test_email', $test_test_email), 'class'=>'form-control', 'required'=>'required');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="test_subject" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('test_subject');?></span></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'test_subject', 'name'=>'test_subject', 'value'=>set_value('test_subject', $test_subject), 'class'=>'form-control', 'required'=>'required');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="test_body" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('test_body');?></span></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'test_body', 'name'=>'test_body', 'value'=>set_value('test_body', $test_body), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="clear"></div>


<div class="text-center margin_t_b_10">
  <div class="btn-group" role="group">
      <input class="btn btn-primary f_submit" name="submit" type="submit" value="<?php echo lang('test_email');?>" />
  </div>
</div>

</form>

<hr/>

<?php if(count($logs) > 0):?>
<div id="accordion">
	<a href="#" class="collapsed" data-toggle="collapse" data-target="#collapse_logs" aria-expanded="false" aria-controls="collapse_logs" data-parent="#accordion">
		<div class="text-center card-header"><h5><?php echo lang('view_logs');?> <i class="fa fa-plus add_attribute_button hover_pointer"></i></h5></div>
	</a>

	<div id="collapse_logs" class="collapse collapsed" aria-labelledby="headingThree">
		<table class="table table-hover">
			<thead>
				<tr>
					<th><?php echo lang('date');?></th>
					<th><?php echo lang('action');?></th>
					<th><?php echo lang('user');?></th>
					<th><?php echo lang('email');?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($logs as $log):?>
				<tr>
					<td><?php echo date("d.m.Y H:i", $log->date);?></td>
					<td><?php echo lang($log->action);?></td>
					<td><?php echo $log->firstname;?> <?php echo $log->lastname;?></td>
					<td><?php echo $log->email;?></td>
				<?php endforeach;?>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php endif;?>

<?php include('footer.php'); ?> 