<!DOCTYPE>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo lang('common_site_title');?> - <?php echo  $page_title; ?></title>
		<link rel="icon" href="<?php echo base_url("images/favicon.ico"); ?>" type="image/x-icon" />
		
<?php
	$_css = new CSSCrunch();
	$_css->addFile('css/fontawesome-all.min');
	$_css->addFile('css/bootstrap.min');
	$_css->addFile('css/data-tables.bootstrap4.min');
	/*$_css->addFile('css/login');*/
	$_css->addFile('css/main');
	echo $_css->crunch();

	$_js = new JSCrunch();
	$_js->addFile('js/jquery-3.3.1.min');
	$_js->addFile('js/popper.min');
	$_js->addFile('js/bootstrap.min');
	
	echo $_js->crunch();
?>

<?php
$image = FCPATH . '/images/logo.png';
$logo = "";
// Read image path, convert to base64 encoding
if(file_exists($image)) {
	$imageData = base64_encode(file_get_contents($image));

	// Format the image SRC:  data:{mime};base64,{data};
	$logo = 'data: '.mime_content_type($image).';base64,'.$imageData;
}

$user_data = $this->session->userdata('admin');
$access = $user_data['access'];
unset($user_data);

?>


</head>
<body>

<div class="row navbar-rs">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<nav class="navbar navbar-expand-lg">
			<div class="menu-logo">
				<div class="navbar-brand">
					<span class="navbar-logo">
						<img src="<?php echo $logo; ?>" alt="<?php echo lang('site_name');?>" class=" align-middle" />
					</span>
				</div>
			</div>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <i class="fa fa-bars"></i> </button>
			
			<?php $administrator = $this->session->userdata('admin');?>
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-left d-none d-sm-block text-white">
                <?php echo $administrator['firstname'] . ' ' . $administrator['lastname'];?> 
				<a class="btn danger" href="<?php echo site_url('login/logout');?>" title="<?php echo lang('logout');?>"><i class="fa fa-power-off fa-2x"></i></a>
			</div>
		
			<?php // if($this->access == "Admin"):?> 
				<div class="collapse navbar-collapse nav-right" id="navbarSupportedContent">
					<ul class="navbar-nav">
						<a href="<?php echo site_url();?>" title="Homepage">
							<li class="nav-item<?php if($this->active_menu == 'homepage'):?> active<?php endif;?>"><i class="fa fa-home"></i> <?php echo lang('home');?></li>
						</a>
						
						<li class="dropdown nav-item<?php if($this->active_menu == 'invoices'):?> active<?php endif;?>" > 
							<a class="dropdown-toggle" href="<?php echo site_url('admin/invoices');?>" data-toggle="dropdown-menu-invoices" aria-expanded="false">
								<i class="fa fa-file"></i> <?php echo lang('invoices');?>
							</a>
						
							<ul class="dropdown-menu" id="dropdown-menu-invoices">
								<a href="<?php echo site_url($this->config->item('admin_folder').'/invoicesspeditor'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_submenu == 'invoicesspeditor'):?> active<?php endif;?>"><i class="fa fa-file"></i> <?php echo lang('invoices');?> Speditor </li>
								</a>
								
								<a href="<?php echo site_url($this->config->item('admin_folder').'/invoicesspeditor/export'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_submenu == 'accounting_settings'):?> active<?php endif;?>"><i class="fa fa-upload"></i> <?php echo lang('export');?> Speditor </li>
								</a>
								
								<a href="<?php echo site_url($this->config->item('admin_folder').'/invoicesexpecta'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_submenu == 'invoicesexpecta'):?> active<?php endif;?>"><i class="fa fa-file"></i> <?php echo lang('invoices');?> Expecta 
								</a>
								
								<a href="<?php echo site_url($this->config->item('admin_folder').'/invoicesexpecta/export'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_submenu == 'export_expecta'):?> active<?php endif;?>"><i class="fa fa-upload"></i> <?php echo lang('export');?> Expecta </li>
								</a>
								
								<a href="<?php echo site_url($this->config->item('admin_folder').'/invoices/exported'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_submenu == 'invoices_exported'):?> active<?php endif;?>"><?php // echo lang('export');?> Exported </li>
								</a>
								
								
								
							</ul>
						</li>
						
						
						<?php $active_submenu = '';?>
						<?php if($this->active_menu == 'accounting_settings') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'admin') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'logs') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'emailsettings') $active_submenu = ' active';?>

						<li class="dropdown nav-item<?php echo $active_submenu;?>" > 
							<a class="dropdown-toggle" href="#" data-toggle="dropdown-menu-t" aria-expanded="false">
								<i class="fa fa-cogs"></i> <?php echo lang('settings');?>
							</a>
						
							<ul class="dropdown-menu" id="dropdown-menu-t">
								<a href="<?php echo site_url($this->config->item('admin_folder').'/accounting_settings'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_menu == 'accounting_settings'):?> active<?php endif;?>"><i class="fa fa-cog"></i> <?php echo lang('accounting_settings');?></li>
								</a>

								<?php if($access == 'Admin'):?>
								<a href="<?php echo site_url($this->config->item('admin_folder').'/admin'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_menu == 'admin'):?> active<?php endif;?>"> <?php echo lang('admins');?></li>
								</a>
								<a href="<?php echo site_url($this->config->item('admin_folder').'/admin/user_rights'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_menu == 'user_rights'):?> active<?php endif;?>"> <?php echo lang('user_rights');?></li>
								</a>

								<a href="<?php echo site_url($this->config->item('admin_folder').'/emailsettings'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_menu == 'emailsettings'):?> active<?php endif;?>"><i class="fa fa-envelope"></i> <?php echo lang('email_settings');?></li>
								</a>
								
								<a href="<?php echo site_url($this->config->item('admin_folder').'/invoicesspeditor/show_table_post'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_submenu == 'show_table_post'):?> active<?php endif;?>">TABLES SPEDITOR  </li>
								</a>
								
								<a href="<?php echo site_url($this->config->item('admin_folder').'/invoicesexpecta/show_table_post'); ?>">
									<li class="nav-item-dropdown<?php if($this->active_submenu == 'show_table_post'):?> active<?php endif;?>">TABLES EXPECTA  </li>
								</a>
								
								<?php endif;?>
								<a href="<?php echo site_url('login/logout');?>">
									<li class="nav-item-dropdown" style="background-color:#dc3545;">
										<i class="fa fa-power-off"></i> <?php echo lang('logout');?>
									</li>
								</a>
							</ul>
						</li>
					</ul>
					
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-right text-white d-xs-block d-block d-sm-none">
						<hr class="text-white">
						<?php echo $administrator['firstname'] . ' ' . $administrator['lastname'];?> 
						<a class="btn danger" href="<?php echo site_url('login/logout');?>" title="<?php echo lang('logout');?>"><i class="fa fa-power-off fa-2x"></i></a>
					</div>
				
				</div>
				
		</nav>
	</div>
</div>

    <div class="row mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="clear"></div>

<?php
	//lets have the flashdata overright "$message" if it exists
	if($this->session->flashdata('message'))
	{
		$message	= $this->session->flashdata('message');
	}
	
	if($this->session->flashdata('error'))
	{
		$error	= $this->session->flashdata('error');
	}
	
	if(function_exists('validation_errors') && validation_errors() != '')
	{
		$error	= validation_errors();
	}
?>

<?php if (!empty($message)): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong><?php echo lang('common_note') ?>:</strong>
		<?php echo $message; ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
	</div>
<?php endif; ?>

<?php if (!empty($error)): ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong><?php echo lang('common_alert') ?>:</strong>
		<?php echo $error; ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
	</div>
<?php endif; ?>
