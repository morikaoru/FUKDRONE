<?php get_header(); ?>
    
  <!-- l-wrapper -->
  <div class="l-wrapper ">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_archiveLayout') == 'value2' ):?> l-main-single
    <?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">
	
	  <?php fit_breadcrumb(); ?> 
      <header class="archiveTitle">
        <h1 class="heading heading-first"><?php echo fit_archive_title(); ?></h1>
		<?php fit_sub_pagination(); ?>
      </header>
      
      <?php if (!is_paged() && term_description() && get_option('fit_theme_term') != 'value1' ) : ?>
      <div class="archiveDescription">
        <?php echo term_description(); ?>
      </div>
      <?php endif; ?>

	  <?php if (have_posts()) : $count = 1; ?>
        <div class="archive">
	    <?php while (have_posts()) : the_post(); ?>
	      <?php get_template_part('loop');?>

		  <?php
          $conditions = get_option('fit_ad_infeed');
		  if(get_option('fit_ad_infeed1p')){
			  $conditions = get_option('fit_ad_infeed') && !is_paged();
		  }
		  ?>
		  <?php if($conditions): ?>
		    <?php
            $number = '1';
		    if(get_option('fit_ad_infeedNumber')){
			    $number = get_option('fit_ad_infeedNumber');
		    }
		    ?>
		    <?php if($count == $number): ?>
			  <div class="archiveList archiveList-infeed"><?php echo get_option('fit_ad_infeed'); ?></div>
		    <?php endif; ?>
		    <?php $count = $count + 1; ?>
	    
		  <?php endif; ?>
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