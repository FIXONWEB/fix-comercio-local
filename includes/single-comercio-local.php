<?php
global $post;

$comercio_categoria_all = get_terms( 'comercio-categoria' );

$comercio_categoria = wp_get_post_terms( $post->ID, 'comercio-categoria');
$comercio_categoria_value = "";
foreach ($comercio_categoria as $key => $comercio_categorias) {
	$comercio_categoria_value .= $comercio_categorias->name;
}
$fix161174_endereco = get_post_meta( $post->ID, 'fix161174_endereco', true );
$fix161174_telefone = get_post_meta( $post->ID, 'fix161174_telefone', true );






get_header();
?>
<style type="text/css" media="screen">
	.fix161174 {
		display: grid;
		grid-template-columns: 1fr 2fr 1fr;
	}
</style>

<div style="text-align: center;">
	<div style="font-size: 16px;"><strong>Comércio local</strong></div>
	<div>Detalhe do cadastro</div>
</div>
<div class="fix161174">
	<div><!-- coluna 1/3 - INI -->
		Todas as Categorias
		<?php
			$comercio_categoria_all = get_terms( 'comercio-categoria' );
			foreach ($comercio_categoria_all as $key => $comercio_categoria_alls) {
				?>
					<a href="<?php echo site_url() ?>/listagem-comercio-local/?<?php echo $comercio_categoria_alls->taxonomy ?>=<?php echo $comercio_categoria_alls->slug ?>" title="">
						<span style="border-radius: 5px;-moz-border-radius: 5px;border: 1px solid gray;padding: 0px 12px;margin: 10px; "><?php echo $comercio_categoria_alls->name ?> </span>
					</a>
				<?php
			}
		?>
	</div><!-- coluna 1/3 - END-->






	<div>
		

		<div style="text-align: center;">
			
			<div style="height: 40px;"></div>
			<h1 style="line-height: 1;padding: 0;margin: 0;"><?php echo $post->post_title ?></h1>
			<strong><?php echo $comercio_categoria_value ?></strong>
			<div style="height: 20px;"></div>
			
			<div>
				<div style="font-style: italic;font-size: 14px;">Endereço:</div>
				<div style="font-size: 16px;"><strong><?php echo $fix161174_endereco ?></strong></div>
			</div>
			<div>
				<div style="font-style: italic;font-size: 14px;">Telefone:</div>
				<div style="font-size: 16px;"><strong><?php echo $fix161174_telefone ?></strong></div>	
			</div>
			
		</div>



	</div>
	<div>
		Outros comercios da categoria xxxx
	</div>
</div>
<?php
get_footer();