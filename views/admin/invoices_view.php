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

<?php foreach ($elements as $element):?>


<div class="modal fade" id="ModalDetails<?php echo $element->id;?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document" style="min-width: 98%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo lang('invoice_number');?> <?php echo $element->NUMBER;?> / <?php $d_array = explode('-', $element->IDATE); echo $d_array[2];?>.<?php echo $d_array[1];?>.<?php echo $d_array[0];?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><?php echo lang('close_symbol');?></span>
                </button>
            </div>
            <div class="modal-body p-4">
				<div class="row">
					<div class="col-sm-1 col-lg-1">
						<b><?php echo lang('number');?></b>
					</div>
					<div class="col-sm-7 col-lg-7">
						<b><?php echo lang('description');?></b>
					</div>
					<div class="col-sm-1 col-lg-1">
						<b><?php echo lang('sku');?></b>
					</div>
					<div class="col-sm-1 col-lg-1">
						<b><?php echo lang('quantity');?></b>
					</div>
					<div class="col-sm-1 col-lg-1">
						<b><?php echo lang('single_price');?></b>
					</div>
					<div class="col-sm-1 col-lg-1">
						<b><?php echo lang('value');?></b>
					</div>
				</div>
				<hr>
				<?php foreach($element->invoice_details as $inv_detail):?>
				<div class="row border_top_row">
					<div class="col-sm-1 col-lg-1">
						<?php echo $inv_detail->ROWNUM;?>
					</div>
					<div class="col-sm-7 col-lg-7">
						<?php echo $inv_detail->DETAIL_TEXT;?>
					</div>
					<div class="col-sm-1 col-lg-1">
						<?php echo $inv_detail->DETAIL_MEAS;?>
					</div>
					<div class="col-sm-1 col-lg-1">
						<?php echo $inv_detail->DETAIL_QTY;?>
					</div>
					<div class="col-sm-1 col-lg-1 text-right">
						<?php echo $inv_detail->PRICE_CURR;?> <?php echo lang($element->CURRENCY);?>
					</div>
					<div class="col-sm-1 col-lg-1 text-right">
						<?php echo ($inv_detail->PRICE_CURR * $inv_detail->DETAIL_QTY);?> <?php echo lang($element->CURRENCY);?>
					</div>
					
				</div>
				<hr>
				<?php endforeach;?>
				
				<div class="row">
					<div class="col-sm-6 col-lg-6">
						<p><?php echo lang('customer');?>: <b><?php echo $element->BULSTAT;?></b> | <?php echo $element->COMPANY;?></p>
						<p><?php echo lang('client_ref');?>: <b><?php echo $element->CLIENT_REF;?></b></p>
						<p><?php echo lang('bank');?>: <b><?php echo  $element->BANK;?></b></p>
						<p><?php echo lang('IBAN');?>: <b><?php echo  $element->ACCOUNT;?></b></p>
						<p><?php echo lang('BIC');?>: <b><?php echo  $element->CODE;?></b></p>
					</div>
					<div class="col-sm-6 col-lg-6 text-right">
						<p><?php echo lang('dan_osn');?>: <?php echo  $element->TOTAL;?> <?php echo lang('BGN');?></p>
						<p><?php echo lang('vat');?>: <?php echo $element->TOTAL_VAT;?> <?php echo lang('BGN');?></p>
						<p><b><?php echo lang('total_to_pay');?>: <?php echo  $element->PAYSUM;?> <?php echo lang('BGN');?></b></p>
					</div>
				</div>
				
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal"><?php echo lang('close');?></button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php echo form_open( base_url($this->config->item('admin_folder') . '/'.$this->active_menu.'/export') , 'id="export_form"'); ?>
<input type="hidden" name="export_action" value="1"/>

<h1 class="pull-left"><?php echo $page_title;?></h1>
<?php if($is_export):?>
<a class="btn btn-primary pull-right" href="#" onclick="$('#export_form').submit();" ><i class='fa fa-upload'></i> <?php echo lang('export');?></a>
<?php endif;?>
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
				<?php if($is_export):?>
				<th class="text-left"><input type="checkbox" id="checkAll" data-toggle="tooltip" title="<?php echo lang('select_all');?>" checked /></th>
				<?php endif;?>
				<th id="first_th" class="head-item text-center">
					<?php echo lang('number');?>
				</th>
				<th class="head-item text-center"><?php echo lang('date');?></th>
				<th class="head-item text-center"><?php echo lang('company');?></th>
				<th class="head-item text-center"><?php echo lang('vat_no');?></th>
				<th class="head-item text-center"><?php echo lang('total');?></th>
				<th class="head-item text-center"><?php echo lang('edit_copy_delete');?></th>
			</tr>
		</thead>
		<?php $last_insert_id = 0; if($this->session->flashdata('last_insert_id')) $last_insert_id = $this->session->flashdata('last_insert_id');?>
		<tbody>
	<?php foreach ($elements as $element):?>
		<tr id="tableRow<?php echo $element->id;?>">
				<?php if($is_export):?>
				<td><input type="checkbox" name="invoices_for_export[]" value="<?php echo $element->id;?>" title="<?php echo lang('export');?>" checked /></td>
			<?php endif;?>
			<td <?php if($last_insert_id == $element->id):?>class="btn-secondary"<?php endif;?>>
				<?php echo $element->NUMBER;?>
			</td>
			<td data-toggle="tooltip" title="<?php echo lang('date');?>"><?php echo $element->IDATE;?></td>
			<td data-toggle="tooltip" title="<?php echo lang('company');?>"><?php echo $element->COMPANY;?></td>
			<td><?php echo $element->BULSTAT; ?></td>
			<td class="text-right"><?php echo number_format($element->PAYSUM, 2, '.', ' ');?></td>
			<td style="text-align:right;">
				<div class="btn-group" role="group" aria-label="Basic example">
					<a class='btn btn-secondary' id="preview_id<?php echo $element->id;?>" href="#" data-toggle="modal" data-target="#ModalDetails<?php echo $element->id;?>" >
						<i class="fa fa-search" alt="<?php echo lang('preview');?>" title="<?php echo lang('preview');?>"></i>
					</a>
					<?php if($this->active_menu != 'invoices'):?>
					<a class='btn btn-secondary' href="<?php echo site_url($this->config->item('admin_folder').'/'.$this->active_menu.'/sync/'.$element->id); ?>" >
						<i class="fa fa-sync" alt="<?php echo lang('sync');?>" title="<?php echo lang('sync');?>"></i>
					</a>
					<a class='btn btn-danger' id="b_id<?php echo $element->id;?>" href="<?php echo site_url($this->config->item('admin_folder').'/'.$this->active_menu.'/delete/'.$element->id); ?>" data-toggle="modal" data-target="#ModalConfirm" onclick="return areyousure(<?php echo $element->id;?>);">
						<i class="fa fa-trash-alt" alt="<?php echo lang('delete');?>" title="<?php echo lang('delete');?>"></i>
					</a>
					<?php endif;?>
					
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
