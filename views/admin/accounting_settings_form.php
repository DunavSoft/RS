<?php include('header.php'); ?>

<?php echo form_open( base_url( $this->config->item('admin_folder') . '/accounting_settings/form/'.$system_id . '/' . $id) , 'id="product_form"'); ?>

<div class="row">
	<div class="col-lg-9">
	<h1><a href="<?php echo site_url($this->config->item('admin_folder').'/accounting_settings');?>"><i class="fa fa-arrow-left"></i></a> <?php echo $page_title; ?></h1>
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

<div class="row">
	<div class="col-lg-10">
	<h3><?php echo lang('to_system');?>: <u><?php echo $systems_array[$system_id];?></u></h3>
	</div>

	<div class="col-lg-2">
		
	</div>
</div>

<div class="clear"></div>
<hr/>

<input type="hidden" name="system_id" value="<?php echo $system_id;?>" />
<input type="hidden" name="id" value="<?php echo $id;?>" />

	<div class="form-group row">
	  <label for="vat_no" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('vat_no');?>:  <span class="danger">*</span></label>

	  <div class="col-lg-10 col-sm-8">
		<?php
		$data	= array('id'=>'vat_no', 'name'=>'vat_no', 'value'=>set_value('vat_no', $vat_no), 'class'=>'form-control', 'required'=>'required');
		echo form_input($data);
		?>
	  </div>
	</div>
	
	<div class="form-group row">
	  <label for="company_name" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('name');?>:  <span class="danger">*</span></label>

	  <div class="col-lg-10 col-sm-8">
		<?php
		$data	= array('id'=>'company_name', 'name'=>'company_name', 'value'=>set_value('company_name', $company_name), 'class'=>'form-control', 'required'=>'required');
		echo form_input($data);
		?>
	  </div>
	</div>
	
	<div class="form-group row">
		<label for="document_type" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('document_type');?>: <span class="danger">*</span></label>
		<div class="col-lg-10 col-sm-8 input-group">
			<?php
			$select_array = array();
			$select_array[''] = lang('select');
		
			foreach ($documents_types_array as $element) 
			{
				//$select_array[$element->DocumentTypeID] = mb_convert_encoding($element->Name, "utf-8", "Windows-1251");
				$select_array[$element->DocumentTypeID] = $element->Name;
			}
		
			echo form_dropdown('document_type', $select_array, set_value('document_type', $document_type), 'required="required" class="form-control custom-select" ');
			?>
			
			<div class="input-group-append">
				<a class="btn btn-outline-secondary" href="<?php echo base_url( $this->config->item('admin_folder') . '/objects/form');?>"><?php echo lang('add_new');?></a>
			</div>
		</div>
	</div>
	
	<div class="form-group row">
	  <label for="conditions" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('conditions');?>:</label>

	  <div class="col-lg-10 col-sm-8">
		<?php
		$data	= array('id'=>'conditions', 'name'=>'conditions', 'value'=>set_value('conditions', $conditions), 'class'=>'form-control');
			echo form_input($data);
		?>
	  </div>
	</div>
	
	<div class="form-group row">
	  <label for="debit" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('debit');?>:</label>

	  <div class="col-lg-10 col-sm-8">
		<?php
		$data	= array('id'=>'debit', 'name'=>'debit', 'value'=>set_value('debit', $debit), 'class'=>'form-control');
		echo form_input($data);
		?>
	  </div>
	</div>
	
	<div class="form-group row">
	  <label for="credit" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('credit');?>:</label>

	  <div class="col-lg-10 col-sm-8">
		<?php
		$data	= array('id'=>'credit', 'name'=>'credit', 'value'=>set_value('credit', $credit), 'class'=>'form-control');
		echo form_input($data);
		?>
	  </div>
	</div>
	
	<div class="form-group row">
		<label for="vat_term" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('vat_term');?>: <span class="danger">*</span></label>
		<div class="col-lg-10 col-sm-8 input-group">
			<?php
			$select_array = array();
			$select_array[''] = lang('select');
		
			foreach ($vat_term_array as $element) 
			{
				//$select_array[$element->VatTermID] = mb_convert_encoding($element->Description, "utf-8", "Windows-1251");
				$select_array[$element->VatTermID] = $element->Description;
			}
		
			echo form_dropdown('vat_term', $select_array, set_value('vat_term', $vat_term), 'required="required" class="form-control custom-select" ');
			?>
			
			<div class="input-group-append">
				<a class="btn btn-outline-secondary" href="<?php echo base_url( $this->config->item('admin_folder') . '/objects/form');?>"><?php echo lang('add_new');?></a>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<label for="vies_id" class="col-lg-2 col-sm-4 col-form-label text-left text-sm-right"><?php echo lang('vies');?>: <span class="danger">*</span></label>
		<div class="col-lg-10 col-sm-8 input-group">
			<?php
			$select_array = array();
			$select_array[''] = lang('select');
		
			foreach ($vies_array as $element) 
			{
				//$select_array[$element->ViesID] = mb_convert_encoding($element->Description, "utf-8", "Windows-1251");
				$select_array[$element->ViesID] = $element->Description;
			}
		
			echo form_dropdown('vies_id', $select_array, set_value('vies_id', $vies_id), 'required="required" class="form-control custom-select" ');
			?>
			
			<div class="input-group-append">
				<a class="btn btn-outline-secondary" href="<?php echo base_url( $this->config->item('admin_folder') . '/objects/form');?>"><?php echo lang('add_new');?></a>
			</div>
		</div>
	</div>

<div class="text-center margin_t_b_10">
  <div class="btn-group" role="group">
      <input class="btn btn-primary" name="submit" type="submit" value="<?php echo lang('save');?>" />
      <input class="btn btn-secondary" name="submit_save_and_return" type="submit" value="<?php echo lang('save_return');?>" />
  </div>
</div>
	
</form>

<?php include('footer.php'); ?>