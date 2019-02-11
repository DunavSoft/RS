<!DOCTYPE>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo lang('login_page');?></title>
		<link rel="icon" href="<?php echo site_url("images/favicon.ico"); ?>" type="image/x-icon" />
		
<?php
	$_css = new CSSCrunch();
	$_css->addFile('css/fontawesome-all.min');
	$_css->addFile('css/bootstrap.min');
	$_css->addFile('css/login');
	echo $_css->crunch();

	$_js = new JSCrunch();
	$_js->addFile('js/jquery-3.3.1.min');
	$_js->addFile('js/bootstrap.min');
	echo $_js->crunch();
?>

<?php
$image = FCPATH . '/images/logo_login.png';
$logo = "";
// Read image path, convert to base64 encoding
if(file_exists($image)) {
	$imageData = base64_encode(file_get_contents($image));

	// Format the image SRC:  data:{mime};base64,{data};
	$logo = 'data: '.mime_content_type($image).';base64,'.$imageData;
}
?>

</head>
<body>

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


<section class="view intro-2">
  <div class="mask rgba-stylish-strong h-100 d-flex justify-content-center align-items-center">
	<div class="container">
		<div class="row">
			<div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-4 text-center">

				<img src="<?php echo $logo; ?>" alt="<?php echo lang('site_name');?>" width="300px" class="mb-4" />
				
				<?php if (!empty($error)): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong><?php echo lang('common_alert') ?>:</strong> <?php echo $error; ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<?php endif; ?>

				<?php if (!empty($message)): ?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong><?php echo lang('common_note') ?>:</strong> <?php echo $message; ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<?php endif; ?>

				<?php echo form_open('login', 'autocomplete="off"') ?>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text input_prepends"><i class="fa fa-envelope"></i></div>
							</div>
							<?php echo  form_input(array('id'=>'email', 'name'=>'email', 'class'=>'form-control', 'placeholder' => lang('email'), 'required'=>'required')); ?>
						</div>
					</div>

					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text input_prepends"><i class="fa fa-key"></i></div>
							</div>
							<?php echo  form_password(array('id'=>'password', 'name'=>'password', 'class'=>'form-control', 'placeholder' => lang('password'), 'required'=>' required')); ?>
						</div>
					</div>

					<div class="form-group text-center">
						<input type="submit" value="Login" name="submit" class="btn btn-lg login_button" />
					</div>

					<input type="hidden" value="<?php echo $redirect; ?>" name="redirect"/>
					<input type="hidden" value="submitted" name="submitted"/>
				</form>
			</div>
		</div>
	</div>
  </div>
</section>

</body>
</html>