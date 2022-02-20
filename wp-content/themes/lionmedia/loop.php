      <?php
	  if(get_the_category()){
		  $cat_meta = get_option("cat_meta_data");
		  $cat = get_the_category();
		  $cat_id   = $cat[0]->cat_ID;
	  }
	  ?>
      <article class="archiveItem
	  <?php if(get_option('fit_theme_articleLayout') == 'value2'): ?> archiveItem-wide<?php endif; ?>
      <?php if(get_option('fit_theme_articleLayout') == 'value3'): ?> archiveItem-wideSp<?php endif; ?>">
        
        <div class="eyecatch eyecatch-archive">
          <?php if(is_sticky()):?>
            <span class="eyecatch__ribbon">Pickup</span>
          <?php endif;?>
          <span class="eyecatch__cat bgc<?php if (isset($cat_meta[$cat_id])) { echo esc_html($cat_meta[$cat_id]);} ?> u-txtShdw"><?php the_category(' ');?></span>
          <a href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()) {the_post_thumbnail('icatch');} else {echo '<img src="'.get_template_directory_uri().'/img/img_no.gif" alt="NO IMAGE"/>';}?></a>
        </div>
        
        <?php if (get_post_type($post->ID) == 'post') :
		if (get_option('fit_post_time') != 'value2' || has_tag() == true ) :	?>
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
        <?php endif; endif; ?>

        <h2 class="heading heading-archive">
          <a class=" hc<?php if (isset($cat_meta[$cat_id])) { echo esc_html($cat_meta[$cat_id]);} ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        
        <p class="archiveItem__text">
		  <?php echo get_the_excerpt(); ?>
        </p>
        
        <div class="btn btn-right">
          <a class="btn__link" href="<?php the_permalink(); ?>">続きを読む</a>
        </div>
      
      </article>