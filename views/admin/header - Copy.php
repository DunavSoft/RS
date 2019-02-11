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
	/*$_css->addFile('css/login');*/
	$_css->addFile('css/main');
	echo $_css->crunch(true);

	$_js = new JSCrunch();
	$_js->addFile('js/jquery-3.3.1.min');
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
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <i class="fa fa-bars"></i> </button>
			
			<div class="menu-logo">
				<div class="navbar-brand">
					<span class="navbar-logo">
						<img src="<?php echo $logo; ?>" alt="<?php echo lang('site_name');?>" class=" align-middle" />
					</span>
				</div>
			</div>
		
			<?php // if($this->access == "Admin"):?>
				<div class="collapse navbar-collapse nav-right" id="navbarSupportedContent">
					<ul class="navbar-nav">
						<a href="<?php echo site_url();?>" title="Homepage">
							<li class="nav-item"><i class="fa fa-home"></i> <?php echo lang('home');?></li>
						</a>
						<a href="<?php echo site_url('admin/companies');?>" title="">
							<li class="nav-item<?php if($this->active_menu == 'companies'):?> active<?php endif;?>"> <i class="fa fa-list"></i> <?php echo lang('companies');?></li>
						</a>
						<a href="<?php echo site_url('admin/objects');?>" title="">
							<li class="nav-item<?php if($this->active_menu == 'objects'):?> active<?php endif;?>"> <i class="fa fa-list"></i> <?php echo lang('objects');?></li>
						</a>
						
						<a href="<?php echo site_url('admin/templates'); ?>" title="<?php echo lang('templates');?>">
							<li class="nav-item<?php if($this->active_menu == 'templates'):?> active<?php endif;?>"> <i class="fa fa-file-alt"></i> <?php echo lang('templates');?>
							</li>
						</a>
						
						<?php $active_submenu = '';?>
						<?php if($this->active_menu == 'object_types') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'objects_attr') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'companies_attr') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'variables') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'series') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'admin') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'logs') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'display_indicators') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'doc_settings') $active_submenu = ' active';?>
						<?php if($this->active_menu == 'emailsettings') $active_submenu = ' active';?>

							<li class="dropdown nav-item<?php echo $active_submenu;?>" > 
								<a class="dropdown-toggle" href="#" data-toggle="dropdown-menu-t" aria-expanded="false">
									<i class="fa fa-cog"></i> <?php echo lang('settings');?>
								</a>
							
								<ul class="dropdown-menu" id="dropdown-menu-t">
								   
									<?php // if($this->access == "Admin"):?>
										<a href="<?php echo site_url($this->config->item('admin_folder').'/object_types'); ?>">
											<li class="nav-item-dropdown<?php if($this->active_menu == 'object_types'):?> active<?php endif;?>"><i class="fa fa-list"></i> <?php echo lang('groups');?> <?php echo lang('object');?></li>
										</a>
										<a href="<?php echo site_url($this->config->item('admin_folder').'/objects_attr'); ?>">
											<li class="nav-item-dropdown<?php if($this->active_menu == 'objects_attr'):?> active<?php endif;?>"><i class="fa fa-list"></i> <?php echo lang('attributes');?> <?php echo lang('object');?></li>
										</a>
										<a href="<?php echo site_url($this->config->item('admin_folder').'/companies_attr'); ?>">
											<li class="nav-item-dropdown<?php if($this->active_menu == 'companies_attr'):?> active<?php endif;?>"><i class="fa fa-list"></i> <?php echo lang('attributes');?> <?php echo lang('company');?> </li>
										</a>
										<a href="<?php echo site_url($this->config->item('admin_folder').'/variables'); ?>">
											<li class="nav-item-dropdown<?php if($this->active_menu == 'variables'):?> active<?php endif;?>"><i class="fa fa-list"></i> <?php echo lang('variables');?></li>
										</a>
										<a href="<?php echo site_url($this->config->item('admin_folder').'/series'); ?>">
											<li class="nav-item-dropdown<?php if($this->active_menu == 'series'):?> active<?php endif;?>"><?php echo lang('series');?></li>
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
										<?php endif;?>
										<a href="<?php echo site_url($this->config->item('admin_folder').'/display_indicators'); ?>">
											<li class="nav-item-dropdown<?php if($this->active_menu == 'display_indicators'):?> active<?php endif;?>"> <?php echo lang('display_indicators');?></li>
										</a>
										<a href="<?php echo site_url($this->config->item('admin_folder').'/doc_settings'); ?>">
											<li class="nav-item-dropdown<?php if($this->active_menu == 'doc_settings'):?> active<?php endif;?>"> <?php echo lang('doc_settings');?></li>
										</a>
										<?php // endif;?>
								</ul>
							</li>
						</a>
					</ul>
				</div>
				<?php // endif;?>
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
