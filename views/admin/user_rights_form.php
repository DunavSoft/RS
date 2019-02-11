<?php include('header.php'); ?>

<div class="clear"></div>

<h1 class="text-center"><a href="<?php echo site_url($this->config->item('admin_folder').'/admin');?>"><i class="fa fa-arrow-left"></i></a> <?php echo $page_title; ?></h1>
<div class="clear"></div>
<hr/>

<?php echo form_open( site_url($this->config->item('admin_folder').'/admin/user_rights') , 'id="element_form"'); ?>
<input type="hidden" name="hidden_element" value="1" />

<table class="table table-hover">
	<thead>
		<tr>
			<th class="text-center"><?php echo lang('template');?></th>
			
			<?php foreach($users as $user):?>
				<th class="text-center"><?php echo $user->firstname;?> <?php echo $user->lastname;?></th>
			<?php endforeach;?>
		</tr>
	</thead>
	<tbody>
	<?php if(count($templates) == 0):?><tr><td class="text-center" colspan="3"><?php echo lang('nothing_to_show');?></td></tr><?php endif;?>
	<?php foreach ($templates as $template):?>
		<tr>
			<td><?php echo $template->num;?> | <?php echo $template->name; ?></td>
			
			<?php foreach($users as $user):?>
			<td class="text-center">
			<?php $r_id = ''; if(isset($user_rights_array_id[$template->num][$user->id])) $r_id = $user_rights_array_id[$template->num][$user->id];?>
			<?php $r_rights = 0; if(isset($user_rights_array[$template->num][$user->id])) $r_rights = $user_rights_array[$template->num][$user->id];?>
			
				<input type="checkbox" value="1" <?php if($r_rights == 1):?>checked<?php endif;?> name="rights[<?php echo $template->num;?>-<?php echo $user->id;?>-<?php echo $r_id;?>]" />
				
				<input type="hidden" value="1" <?php if($r_rights == 1):?>checked<?php endif;?> name="rights_field[<?php echo $template->num;?>-<?php echo $user->id;?>-<?php echo $r_id;?>]"
			</td>
			<?php endforeach;?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>



<div class="text-center margin_t_b_10">
  <div class="btn-group" role="group">
      <input class="btn btn-primary" name="submit" type="submit" value="<?php echo lang('save');?>" />
      <input class="btn btn-secondary" name="submit_save_and_return" type="submit" value="<?php echo lang('save_return');?>" />
  </div>
</div>

<?php echo form_close();?>

<?php include('footer.php'); ?> 
