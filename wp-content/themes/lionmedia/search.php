<?php get_header(); ?>
<?php fit_breadcrumb(); ?>

  <!-- l-wrapper -->
  <div class="l-wrapper ">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_archiveLayout') == 'value2' ):?> l-main-single
    <?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">
	
      <header class="archiveTitle">
        <h1 class="heading heading-first"><?php echo fit_archive_title(); ?> <?php echo $wp_query->found_posts; ?>件</h1>
		<?php fit_sub_pagination(); ?>
      </header>
	  
      
	  <?php if (have_posts()) : ?>
        <div class="archive">
	    <?php while (have_posts()) : the_post(); ?>
	      <?php get_template_part('loop'); ?>
	    <?php endwhile; ?>
        </div>
	  <?php else : ?>
      <div class="archive">
        <div class="archiveList">
          <p class="archiveList__text archiveList__text-center">投稿が1件も見つかりませんでした。</p>
        </div>
      </div>
	  <?php endif; ?>
	  
	  <?php fit_posts_pagination(); ?>
      
      
    </main>
    <!-- /l-main -->
    
    
	<?php if ( get_option('fit_theme_archiveLayout') != 'value2' ):?>
    <!-- l-sidebar -->
      <?php get_sidebar(); ?>
    <!-- /l-sidebar -->
	<?php endif; ?>
    
    
  </div>
  <!-- /l-wrapper -->


<?php get_footer(); ?>