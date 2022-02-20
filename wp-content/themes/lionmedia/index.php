<?php get_header(); ?>

  <?php
  if (is_home() && !is_paged() && get_option('fit_function_pickup') == 'value2') :
	  $args = array(
	      'numberposts' => '3',
		  'post_type'   => 'post',
	      'post_status' => 'publish'
	  );
	  $new_meta = wp_get_recent_posts($args);
	  if ( get_option('fit_function_pickup_id01') ) {
		  $post_id01 = get_option('fit_function_pickup_id01');
	  }else{
		  $post_id01 = $new_meta[0]['ID'];
	  }
	  if ( get_option('fit_function_pickup_id02') ) {
		  $post_id02 = get_option('fit_function_pickup_id02');
	  }else{
		  $post_id02 = $new_meta[1]['ID'];
	  }
	  if ( get_option('fit_function_pickup_id03') ) {
		  $post_id03 = get_option('fit_function_pickup_id03');
	  }else{
		  $post_id03 = $new_meta[2]['ID'];
	  }
	  	  
	  $cat_meta = get_option('cat_meta_data');
	  $cat_01 = get_the_category($post_id01);
	  $cat_02 = get_the_category($post_id02); 
	  $cat_03 = get_the_category($post_id03); 
	  $cat_id01 = $cat_01[0]->term_id ;
	  $cat_id02 = $cat_02[0]->term_id ;
	  $cat_id03 = $cat_03[0]->term_id ;
  ?>
  <div class="key<?php if(get_option('fit_function_pickupSp')): ?> u-none-sp<?php endif; ?>">
    <ul class="key__list">
      <li class="key__item key__item-first">
        <span class="key__cat bgc<?php if (isset($cat_meta[$cat_id01])) { echo esc_html($cat_meta[$cat_id01]);} ?> u-txtShdw"><?php the_category(' ' ,'' ,$post_id01); ?></span>
        <a class="key__link" href="<?php echo get_permalink($post_id01); ?>">
        <h2 class="key__title u-txtShdw"><?php echo get_post($post_id01)->post_title; ?></h2>
        </a>
      </li>
      <li class="key__item key__item-second">
        <span class="key__cat bgc<?php if (isset($cat_meta[$cat_id02])) { echo esc_html($cat_meta[$cat_id02]);} ?> u-txtShdw"><?php the_category(' ' ,'' ,$post_id02); ?></span>
        <a class="key__link" href="<?php echo get_permalink($post_id02); ?>">
        <h2 class="key__title u-txtShdw"><?php echo get_post($post_id02)->post_title; ?></h2>
        </a>
      </li>
      <li class="key__item key__item-third">
        <span class="key__cat bgc<?php if (isset($cat_meta[$cat_id03])) { echo esc_html($cat_meta[$cat_id03]);} ?> u-txtShdw"><?php the_category(' ' ,'' ,$post_id03); ?></span>
        <a class="key__link" href="<?php echo get_permalink($post_id03); ?>">
        <h2 class="key__title u-txtShdw"><?php echo get_post($post_id03)->post_title; ?></h2>
        </a>
      </li>
    </ul>
  </div>
  <?php endif; ?>
  
  <!-- l-wrapper -->
  <div class="l-wrapper">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_archiveLayout') == 'value2' ):?> l-main-single
    <?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">

	  <?php if ( is_active_sidebar( 'top' ) && is_home() && !is_paged() ) : ?>
        <?php dynamic_sidebar( 'top' ); ?>
	  <?php endif; ?>
	  
	  <?php if (is_home() && is_paged()) :?>
      <header class="archiveTitle">
        <h2 class="heading heading-first"><?php show_page_number(''); ?>/<?php max_show_page_number(''); ?>ページ</h2>
        <?php fit_sub_pagination(); ?>
      </header>
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
    
    </main >
    <!-- /l-main -->
    

	<?php if ( get_option('fit_theme_archiveLayout') != 'value2' ):?>
    <!-- l-sidebar -->
      <?php get_sidebar(); ?>
    <!-- /l-sidebar -->
	<?php endif; ?>

    
  </div>
  <!-- /l-wrapper -->
  
  <?php if (is_home() && !is_paged() && get_option('fit_function_ranking') == 'value2') :?>
  <div class="rankingBox<?php if (get_option('fit_function_category') != 'value2') :?> u-mb-0<?php endif; ?>">
    <div class="container">
      <h2 class="heading heading-primary">
	    <?php if (get_option('fit_function_ranking_title')) :?><?php echo get_option('fit_function_ranking_title') ?><?php else : ?>Overall Ranking<?php endif; ?>
      </h2>
      <?php
	  if (get_option('fit_function_ranking_number')){
		  $number = get_option('fit_function_ranking_number');
	  }else{
		  $number = '5';
	  }
	  $args = array(
	  		'meta_key'=> 'post_views_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'posts_per_page' => $number,
	  );
	  $my_query = new WP_Query( $args );?>
      <div class="rankingBox__over">
      <ol class="rankingBox__list">
	  <?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
        <?php
		$cat_meta = get_option('cat_meta_data');
		$cat = get_the_category();
		$cat_id   = $cat[0]->cat_ID;
		?>
        <li class="rankingBox__item">
          <div class="rankingBox__img">
            <a href="<?php the_permalink(); ?>">
			  <?php if(has_post_thumbnail()) {the_post_thumbnail('icatch');} else {echo '<img src="'.get_template_directory_uri().'/img/img_no.gif" alt="NO IMAGE"/>';}?>
            </a>
          </div>
          <h3 class="rankingBox__title bgc<?php if (isset($cat_meta[$cat_id])) { echo esc_html($cat_meta[$cat_id]);} ?> u-txtShdw"><a class="rankingBox__titleLink" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </li>
      <?php endwhile; wp_reset_postdata(); ?>
      </ol>
      </div>
      
      <?php if (get_option('fit_function_rankingPage_id')): ?>
      <div class="btn btn-right btn-mt20">
        <a class="btn__link" href="<?php echo get_permalink( get_option('fit_function_rankingPage_id') ); ?>">ランキング一覧へ</a>
      </div>
	  <?php endif; ?>
      
    </div>
  </div>
  <?php endif; ?>
  
  <?php if (is_home() && !is_paged() && get_option('fit_function_category') == 'value2') :?>
  <div class="categoryBox">
    <div class="container">
      <h2 class="heading heading-primary">
        <?php if (get_option('fit_function_category_title')) :?><?php echo get_option('fit_function_category_title') ?><?php else : ?>Category New Article<?php endif; ?>
      </h2>
      <ul class="categoryBox__list">
      <?php
	  $exclusion = get_option('fit_function_category_exclusion');
      $cat_meta = get_option('cat_meta_data');
      $get_cat = get_categories('exclude='.$exclusion.'');
      foreach($get_cat as $val) {
		  $category_list_id[$val->name]= $val->cat_ID;
	  }
      ?>
	  <?php foreach($category_list_id as $key => $val): ?>
  
        <li class="categoryBox__item">
          <h3 class="categoryBox__title c<?php if (isset($cat_meta[$val])) { echo esc_html($cat_meta[$val]);} ?>"><a class="categoryBox__titleLink" href="<?php echo esc_url(get_category_link($val)); ?>"><?php echo $key; ?></a></h3>
      
	      <?php
          $cat_post = get_posts('category='.$val.'&numberposts=1');
	      foreach($cat_post as $post):
	      ?>
          <div class="eyecatch eyecatch-archive">
            <a href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()) {the_post_thumbnail('icatch');} else {echo '<img src="'.get_template_directory_uri().'/img/img_no.gif" alt="NO IMAGE"/>';}?></a>
          </div>
      
          <?php if (get_option('fit_post_time') != 'value2' || has_tag() == true ) :?>
          <ul class="dateList dateList-archive">
            <?php if (get_option('fit_post_time') != 'value2' ) :	?>
            <li class="dateList__item icon-calendar"><?php the_time('Y.m.d'); ?></li>
            <?php endif; ?>
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
          <?php endif; ?>
          
          <h2 class="heading heading-archive ">
            <a class="hc<?php if (isset($cat_meta[$val])) { echo esc_html($cat_meta[$val]);} ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <?php endforeach; ?>
        </li>
  
      <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>


<?php get_footer(); ?>