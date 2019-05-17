<style>
.back-container:before {
    content: ' ';
    display: block;
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    opacity: 0.7;
    background-image: url('imagenes/backgroundISTCRE.jpg');
    background-repeat: no-repeat;
    background-position: 50% 50%;
    -ms-background-size: cover;
    -o-background-size: cover;
    -moz-background-size: cover;
    -webkit-background-size: cover;
    background-size: cover;
}
</style>
<?php
$this->load->view("general/cabecera");
?>
<?=form_open('general/ingreso/login/' , array("id" => "f_login"))?>
<div class="back-container">
<div class="container" >
	<div class="row">
		<div class="col-md-offset-4 col-md-4 col-sm-12">
			<br><br><br><br><br><br>
			<div class="well well-lg">
				<br>
				<div style="margin:0 auto;">
			<a name="istcre" href="https://cruzrojainstituto.edu.ec" title="Instituto Superior Tecnológico Cruz Roja Ecuatoriana" class="img-responsive" style="margin:0 auto;" target="_blank" >
				<img style="margin:0 auto;" class="img-responsive" src="<?=base_url()?>imagenes/istcre.png"/>
			</a>
		</div>
			<h3 class="text-center"><b>ASISTENCIA C1</b></h3>
			
		<hr>
		<font color="#4C9FF7"><h4 class="text-center"><b>INGRESO AL SISTEMA</b></h4></font>
		<?php if($this->session->flashdata('message')) : ?>
					<div class="caja-error">
					<div  class="mensaje"><?php echo $this->session->flashdata('message')?></div>
					</div>

		<?php
			else:?>
					<?php if(!empty($message)) : ?>

							<div style="width:200px;padding-left: 30px;" class="ui-state-error"><?php echo $message; ?></div>

			<?php
					endif;
			endif; ?>
		<input type="text" placeholder="Nombre de Usuario" id="user_name" class="form-control" name="user_name">
		<input type="password" placeholder="Contraseña de Usuario" class="form-control" id="user_pass" name="user_pass" >
		<hr>
			<div class="text-center">
				<input name="login" class="btn btn-lg btn-success" type="submit" name="submit" value="INGRESAR">
				<input name="login" class="btn btn-lg btn-danger" type="reset" name="reset" value="CANCELAR">
			</div>
		</div>
	</div>
</div>
</div>
</div>
<?php
  $this->load->view('general/pie');
?>
<script type="text/javascript">
  $(function() {
     $("#ingreso").button();
     setTimeout(function(){$("#user_name").focus();}, 0);
  });
</script>
