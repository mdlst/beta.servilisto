<?php
	/**
	 * this template for payment fail, you can overide this template by child theme
	*/
	global $ad;
	if($ad)
		$permalink = get_permalink(get_page_by_title('Publica tu anuncio'))."?id=".$ad->ID;
	else 
		$permalink	=	 get_permalink(get_page_by_title('Publica tu anuncio'))
?>
<div class="redirect-content fail" >
	<div class="main-center">

		<div class="row">
			<div class="col-md-9 centered align-centered">
				<div class="title">
					<?php _e("Lo sentimos, ha habido un problema a la hora de procesar su pago", ET_DOMAIN); ?>
				</div>
				<br>
				<?php _e("Si lo desea puede volver a intentarlo de nuevo pasado unos instantes", ET_DOMAIN); ?>
				<br>
				<br>
				<br>
			</div>
			<div class="col-md-3 centered">
				<div class="top-btn-post-place">
					<a class='btn btn-post-place' href="<?= $permalink ?>">Volver</a>
				</div>
			</div>
		</div>
	</div>
</div>