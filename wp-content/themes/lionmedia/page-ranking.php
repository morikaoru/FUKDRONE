<?php /* Template Name:ランキングTPL */?>
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
	  
	  <?php
	  if (get_option('fit_function_rankingPage')){
		  $number = get_option('fit_function_rankingPage');
	  }else{
		  $number = '10';
	  }
	  $args = array(
	  		'meta_key'=> 'post_views_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'posts_per_page' => $number,
	  );
	  $my_query = new WP_Query( $args );?>
        <ol class="rankingPage">
        <?php
        while ( $my_query->have_posts() ) : $my_query->the_post();
		$cat_meta = get_option("cat_meta_data");
	    $cat = get_the_category();
	    $cat_id   = $cat[0]->cat_ID;
		$cat_name = $cat[0]->cat_name;
		$cat_link = get_category_link($cat_id);
		?>
	      <li class="rankingPage__item">
            <div class="eyecatch eyecatch-ranking">
              <a href="<?php the_permalink(); ?>">
			    <?php if(has_post_thumbnail()) {the_post_thumbnail('thumbnail');} else {echo '<img src="'.get_template_directory_uri().'/img/img_no_thumbnail.gif" alt="NO IMAGE"/>';}?>
              </a>
            </div>
            <div class="rankingPage__contents">
              <h2 class="heading heading-archive hc<?php if (isset($cat_meta[$cat_id])) { echo esc_html($cat_meta[$cat_id]);} ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <ul class="dateList dateList-archive">
                <li class="dateList__item icon-calendar"><?php the_time('Y.m.d'); ?></li>
                <li class="dateList__item icon-folder"><a class="hc<?php if (isset($cat_meta[$cat_id])) { echo esc_html($cat_meta[$cat_id]);} ?>" href="<?php echo $cat_link; ?>" rel="category"><?php echo $cat_name; ?></a></li>
				<?php if(has_tag()==true) :  ?>
                <li class="dateList__item icon-tag"><?php
		        if (get_option('fit_theme_tagNumber')){
			        $number = get_option('fit_theme_tagNumber');
		        }else{
			        $number = '5';
		        }
                $posttags = get_the_tags();
		        $count = '0';
		        foreach($posttags as $tag) {
			        $count++;
			        if ($count > $number) break; 
			        echo '<a href="'. get_tag_link($tag->term_id) .'" rel="tag">'. $tag->name ."</a><span>, </span>";
		        }
		        ?></li>
                <?php endif; ?>
              </ul>
              <p class="archiveItem__text u-none-sp"><?php echo get_the_excerpt(); ?></p>
              
            </div>
          </li>
	    <?php endwhile; wp_reset_postdata(); ?>
        </ol>
      
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
