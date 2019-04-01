<?php defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ERROR | E_PARSE);
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url()?>assets/bootstrap-4.0.0/dist/css/bootstrap.min.css" >
	
		
</head>

<body>

Soy el VHOME

<main>
        
		<?php echo $this->session->userdata('vista_principal');
		if($this->session->userdata('vista_principal') == "inicio"):?>

		<button  type="button" class="btn btn-primary sync" >Sincronizar Gmail</button>
		
		<?php else:$this->load->view("gmail/".$this->session->userdata('vista_principal'));
		echo $this->session->userdata('vista_principal');
		endif;?>



</main>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/tools.js"></script> 

</body>


</html>