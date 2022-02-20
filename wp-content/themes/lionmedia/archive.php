<?php get_header(); ?>
<?php
if (is_category() && !is_paged() && get_option('fit_function_categoryTop') == 'value2' ):
$cat_meta = get_option("cat_meta_data");
$cat_id = get_query_var('cat');
?>
  <div class="categoryDescription bgc<?php if (isset($cat_meta[$cat_id])) { echo esc_html($cat_meta[$cat_id]);} ?>">
    <div class="container">
      <div class="categoryDescription__explain">
        <h1 class="categoryDescription__heading u-txtShdw">
		  <span class="categoryDescription__sub">CATEGORY</span>
		  <?php single_cat_title(); ?>
        </h1>
        <?php if (!is_paged() && term_description() && get_option('fit_theme_term') != 'value1' ) : ?>
        <p class="categoryDescription__text u-txtShdw"><?php echo category_description(); ?></p>
        <?php endif; ?>
      </div>
      <ul class="categoryDescription__post">
      <?php
	  $args = array(
	  		'meta_key'=> 'post_views_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'posts_per_page' => '1',
			'cat' => $cat_id,
	  );
	  $my_query = new WP_Query( $args );
	  while ( $my_query->have_posts() ) : $my_query->the_post();
	  ?>
        <li class="categoryDescription__item">
          <span class="categoryDescription__ribbon">Pickup</span>
          <?php if(has_post_thumbnail()) {the_post_thumbnail('icatch');} else {echo '<img src="'.get_template_directory_uri().'/img/img_no.gif" alt="NO IMAGE"/>';}?>
          <a class="categoryDescription__link" href="<?php the_permalink(); ?>">
            <h3 class="categoryDescription__title u-txtShdw"><?php the_title(); ?></h3>
          </a>
        </li>
      <?php endwhile; wp_reset_postdata(); ?>
      </ul>
    </div>
  </div>
<?php endif; ?>
<?php fit_breadcrumb(); ?>
   
  <!-- l-wrapper -->
  <div class="l-wrapper">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_archiveLayout') == 'value2' ):?> l-main-single
    <?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">
	  
      <?php if (!is_category() || get_option('fit_function_categoryTop') != 'value2' || get_option('fit_function_categoryTop') == 'value2' && is_paged()  ): ?>
      <header class="archiveTitle">
        <h1 class="heading heading-first"><?php echo fit_archive_title(); ?></h1>
		<?php fit_sub_pagination(); ?>
      </header>
      <?php endif; ?>
      
      <?php if (!is_paged() && term_description() && get_option('fit_theme_term') != 'value1' ) : ?>
        <?php if (!is_category() || get_option('fit_function_categoryTop') != 'value2' ): ?>
        <div class="archiveDescription">
          <?php echo term_description(); ?>
        </div>
        <?php endif; ?>
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
			  <div class="archiveItem archiveItem-infeed
			  <?php if(get_option('fit_theme_articleLayout') == 'value2'): ?> archiveItem-wide<?php endif; ?>
			  <?php if(get_option('fit_theme_articleLayout') == 'value3'): ?> archiveItem-wideSp<?php endif; ?>"><?php echo get_option('fit_ad_infeed'); ?></div>
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















