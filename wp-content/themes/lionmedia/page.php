<?php get_header(); ?>
<?php fit_breadcrumb(); ?>

  <!-- l-wrapper -->
  <div class="l-wrapper ">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_pageLayout') == 'value2' ):?> l-main-single
    <?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">
      
      <article> 
      <h1 class="heading heading-page"><?php the_title(); ?></h1>
	  
	  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <section class="content content-page">
	    <?php the_content(); ?>
      </section>
	  <?php endwhile; endif; ?>
      
      </article> 
 
      
    </main>
    <!-- /l-main -->
    
	<?php if ( get_option('fit_theme_pageLayout') != 'value2' ):?>
    <!-- l-sidebar -->
      <?php get_sidebar(); ?>
    <!-- /l-sidebar -->
	<?php endif; ?>
    
  </div>
  <!-- /l-wrapper -->


<?php get_footer(); ?>