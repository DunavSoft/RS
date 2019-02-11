<?php include('header.php'); ?>

<div class="clear"></div>

<h1 class="text-center"><a href="<?php echo site_url($this->config->item('admin_folder').'/admin');?>"><i class="fa fa-arrow-left"></i></a> <?php echo $page_title; ?></h1>
<div class="clear"></div>
<hr/>

<?php echo form_open( site_url($this->config->item('admin_folder').'/admin/form/'.$id) , 'id="element_form"'); ?>

<div class="form-group row">
  <label for="firstname" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('firstname');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'firstname', 'name'=>'firstname', 'value'=>set_value('firstname', $firstname), 'class'=>'form-control', 'required'=>'required');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="lastname" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('lastname');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'lastname', 'name'=>'lastname', 'value'=>set_value('lastname', $lastname), 'class'=>'form-control');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="email" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('email');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
    $data	= array('id'=>'email', 'name'=>'email', 'value'=>set_value('email', $email), 'class'=>'form-control', 'required'=>'required', 'autocomplete'=>'off');
    echo form_input($data);
    ?>
  </div>
</div>

<div class="form-group row">
	<label for="access" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('access');?></label>
	<div class="col-lg-10 col-sm-8 input-group">
	<?php
		$select_array = array();
		$select_array[''] = lang('select');
	  
		$select_array['Admin'] 	= lang('administrator');
		$select_array['User'] 	= lang('user');
		
		echo form_dropdown('access', $select_array, set_value('access',$access), 'class="form-control custom-select" id="access" required="required" ');
	?>
	</div>
</div>

<div class="form-group row">
  <label for="password" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('password');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
	if(!$id)
		$data	= array('id'=>'password', 'name'=>'password', 'value'=>'', 'class'=>'form-control', 'required'=>'required');
	else
		$data	= array('id'=>'password', 'name'=>'password', 'value'=>'', 'class'=>'form-control');
    echo form_password($data);
    ?>
  </div>
</div>

<div class="form-group row">
  <label for="password" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('confirm_password');?></label>

  <div class="col-lg-10 col-sm-8">
    <?php
	if(!$id)
		$data	= array('id'=>'confirm', 'name'=>'confirm', 'value'=>'', 'class'=>'form-control', 'required'=>'required');
	else
		$data	= array('id'=>'confirm', 'name'=>'confirm', 'value'=>'', 'class'=>'form-control');
    echo form_password($data);
    ?>
  </div>
</div>


<div class="text-center margin_t_b_10">
  <div class="btn-group" role="group">
      <input class="btn btn-primary" name="submit" type="submit" value="<?php echo lang('save');?>" />
      <input class="btn btn-secondary" name="submit_save_and_return" type="submit" value="<?php echo lang('save_return');?>" />
  </div>
</div>

<?php echo form_close();?>

<?php include('footer.php'); ?> 
