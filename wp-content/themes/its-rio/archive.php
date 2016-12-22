<?php
get_header();
?>
<div class="row">
	<?php
	$destaque_id = 0; 

	$args = array(
		'posts_per_page' => 1,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'post_type' => $post_type,
		);

	if(in_array($postType, ['cursos_ctp', 'varandas_ctp'])){
		$args = array(
			'post_type' => $post_type,
			'meta_key' => 'info_inscfim',
			'meta_value' => date('Y-m-d'),
			'meta_compare' => '>='
			);
	}

	$posts = new WP_Query($args);

	if ($posts->have_posts()) {
		?>
		<div class="column large-12">
			<?php 
			switch ($postType) {
				case 'projetos_ctp':
				$bannerTitle = 'áreas de pesquisa';
				$bannerCards = 'projetos';				
				// $bannerCards = 'outros projetos';				
				break;
				case 'cursos_ctp':
				$bannerTitle = 'inscrições abertas';
				$bannerCards = 'cursos futuros';				
				break;
				case 'publicacoes_ctp':
				$bannerTitle = 'publicações recentes';
				$bannerCards = 'publicações';
				break;
				case 'varandas_ctp':
				$bannerTitle = 'inscrições abertas';
				$bannerCards = 'varandas ITS';				
				break;

				default:
				break;
			}

			if($bannerTitle != ""){
				?>
				<h2 class="list-title">
					<?= $bannerTitle ?>
					<div class="line"></div>
				</h2>
				<?php 
			} ?>

			<div class="main-carousel highlights-carousel">
				<?php
				$postsQtd = 0;
				while ($posts->have_posts()) {
					$posts->the_post();
					$postsQtd++;
					$meta = get_post_meta(get_the_ID());
					$destaque_id = get_the_ID();
					?>
					<div <?php post_class( 'carousel-cell highlights'); echo get_thumbnail_style($destaque_id,'banner'); ?> >
						<div class="color-hover"></div>
						<div class="info">
							<a href="<?= get_post_permalink() ?>">
								<div class="header">
									<div class="left">
										<h2><?= the_title(); ?></h2>
										<?php $label = 'professores'; include('inc/palestrantes.php'); $posts->reset_postdata(); ?>
									</div>
									<div class="right horario">
										<p><b>data</b></p>
										<span class="box">
											<?php
											if($postType == 'cursos_ctp'){
												?>
												inscrições até
												<?= date('d/m',strtotime($meta['info_inscfim'][0]))	 ?>
												| início do curso
												<?= date('d/m',strtotime($meta['info_cursoinicio'][0]))	 ?>
												<?php
											}elseif($postType == 'varandas_ctp')
											echo $meta['info_datahorario'][0];
											?>
										</span>
									</div>
								</div>
							</a>
							<div class="line"></div>
							<div class="column large-12 no-p">
								<?php $cat_classes = 'black'; include('inc/categories.php'); ?>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
	if($postsQtd > 1){
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery('.highlights-carousel').flickity({
					wrapAround: true,
					cellSelector: '.carousel-cell',
					setGallerySize : false
				});
			});
		</script>
		<?php
	}
	?>

	<div class="older-posts">
		<?php 
		if($bannerTitle != ""){
			?>
			<div class="column large-12">
				<h2 class="list-title">
					<?= $bannerCards ?>
					<div class="line"></div>		
				</h2>
			</div>
			<?php
		}
		if(in_array($postType, ['cursos_ctp', 'varandas_ctp'])){
			$args = array(
				'post_type' 	=> $post_type,
				'relation'		=> 'OR',
				'post__not_in'	=> [$destaque_id],
				'meta_query'	=> array(
					['meta_key' => 'info_inscfim',
					'meta_value' => date('Y-m-d'),
					'meta_compare' => '<'
					],
					['meta_key' => 'info_inscfim',
					'meta_value' => '',
					'meta_compare' => '='
					],
					)
				);
		}else{
			$args[] = ['exclude' => $destaque_id];
			$args['posts_per_page'] = '100';
		}
		query_posts($args);

		if (have_posts()) {
			while (have_posts()) {
				the_post();
				include(ROOT .'inc/post-box.php');
			}
		}
		?>
	</div>
</div>
<script>
	'use strict';
	setTimeout(()=>{
		$('.older-posts').masonry({
			columnWidth : '.large-4',
			selector : '.large-4',
			percentPosition: true,
		});
	}, 500);
</script>
<?php get_footer(); ?>
