<?php get_header(); ?>
<?php 
	global $wp;
// 	echo $wp->request;
	if($wp->request == "locations/mississauga"){
		wp_redirect(get_site_url());
	}
?>
<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php _e( 'page not found', 'html5blank' ); ?>
		</h1>
	</div>
</div>

<div class="main-container">
	<div class="wrapper">
		<main role="main" class="fullwidth">
			<article id="post-404">
				<h2>
					<a href="<?php echo home_url(); ?>">
						<?php _e( 'Return home?', 'html5blank' ); ?>
					</a>
				</h2>
			</article>
		</main>
	</div>
</div>

<?php get_footer(); ?>
