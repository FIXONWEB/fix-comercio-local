<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'parse_request', 'fix161174_parse_request');
function fix161174_parse_request( &$wp ) {
	if($wp->request == 'administrativo/comercio-local/upload_planilha'){
		fix161174_access();
		get_header();
		echo do_shortcode('[fix161174_head]');
		?>
		<style type="text/css" media="screen">
			.fix161174_meio {
				display: grid;
				grid-template-columns: 1fr 1fr 1fr;
				padding: 30px 0px; 
			}
		</style>
		<div class="fix161174_meio">
			<div></div>
			<div>
				<?php echo do_shortcode('[fix161174_form_upload_planilha]') ?>	
			</div>
			<div></div>
		</div>
			

		<?php
		get_footer();
		exit;

	}
	if($wp->request == 'administrativo/comercio-local'){
		get_header();
		fix161174_access();
		echo do_shortcode('[fix161174_head]');
		?>


		<?php
		get_footer();
		exit;
	}
	if($wp->request == 'administrativo/upload-planilha-comercio-local'){
		get_header();
		?>
		<div style="min-height: 300px;padding: 50px 0px;">
			<div style="text-align: center;margin:0px;padding:0px;line-height: 1;">Uploads</div>
			<h2 style="text-align: center;margin:0px;padding:0px;line-height: 1;">Planilha Excell</h2>
			<div style="text-align: center;margin:0px;padding:0px;line-height: 1;text-transform: uppercase;"><strong>Listagem dos registros de comercio local</strong></div>

			<div style="display: grid;grid-template-columns: 1fr 1fr 1fr;">
				<div></div>
				<div style="padding: 20px;text-align: center;">
					 
				</div>
				<div></div>
			</div>
			
		</div>
		<?php
		echo do_shortcode('[fix161340_mnu_lateral]');
		get_footer();
		exit;
	}

	if($wp->request == 'fix161174_exec_upload_planilha'){
		$vai = 0;
		if(current_user_can('administrator')) $vai = 1;
		if(current_user_can('role-administrativo')) $vai = 1;

		if(!$vai) {	return '<!--não disponivel-->';exit;}

		fix161174_exec_upload_planilha();
	
		exit;
	}
	if($wp->request == 'fix161174_recebe_form_upload_planilha'){
		// die('---');

		// $vai = 0;
		// if(current_user_can('administrator')) $vai = 1;
		// if(current_user_can('role-administrativo')) $vai = 1;
		// if(!$vai) {	return '--não disponivel--';exit;}
		if( !is_user_logged_in() ) {
			ob_start();
			?>
			<div style="text-align: center">
				<a href="<?php echo site_url()?>/login/ " title="">Login requerido.</a>
			</div>
			<?php 
			// return ob_get_clean();
			echo ob_get_clean();
			exit;
		}
		$vai = 0;
		if(current_user_can('administrator')) $vai = 1;
		if(current_user_can('role-administrativo')) $vai = 1;
		
		if(!$vai) {	
			ob_start();
			?>
			<div style="text-align: center">
				Permissão insuficiente.
			</div>
			<?php 
			// return ob_get_clean();
			echo ob_get_clean();
			exit;
		}
		if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload') {
			if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK){
				$fileTmpPath 	= $_FILES['uploadedFile']['tmp_name'];
				$fileName 		= $_FILES['uploadedFile']['name'];
				$fileSize 		= $_FILES['uploadedFile']['size'];
				$fileType 		= $_FILES['uploadedFile']['type'];
				$fileNameCmps 	= explode(".", $fileName);
				$fileExtension 	= strtolower(end($fileNameCmps));

				$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
				$allowedfileExtensions = array('xlsx');

				if (in_array($fileExtension, $allowedfileExtensions)){
					$dest_path = plugin_dir_path( __FILE__ )."planilhas/listagem_comercio_local.xlsx";
					// die($dest_path);
					///var/www/clients/client26/web26/web/wp-content/plugins/fix-comercio-local/planilhas/listagem_comercio_local.xlsx
					if(move_uploaded_file($fileTmpPath, $dest_path)) {
						$message ='File is successfully uploaded.';
						wp_redirect( site_url()."/fix161174_exec_upload_planilha" );
						exit;
					} else {
						ob_start();
						?>
						<div style="text-align: center;color: red">
							Arquivo não pode ser movido.
						</div>
						<?php 
						echo ob_get_clean();
						exit;
					}
				} else {
					ob_start();
					?>
					<div style="text-align: center;color: red">
						Extensão nao permitida.
					</div>
					<?php 
					// return ob_get_clean();
					echo ob_get_clean();
					exit;
				}

			} else { // upçpad file
				ob_start();
				?>
				<div style="text-align: center;color: red">
					Upload falhou.
				</div>
				<?php 
				// return ob_get_clean();
				echo ob_get_clean();
				exit;
			}
		} else { //is uploadBtn
			ob_start();
			?>
			<div style="text-align: center;color: red">
				Origem do form inválido.
			</div>
			<?php 
			// return ob_get_clean();
			echo ob_get_clean();
			exit;
		}
		exit;
	}
}
