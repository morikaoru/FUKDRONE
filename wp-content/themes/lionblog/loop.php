      <?php
      $a_lay = get_option('fit_theme_articleLayout');
      $a_eye = get_option('fit_post_eyecatch');
      ?>
      <article class="archiveList<?php if($a_lay == 'value2' && !is_sticky()){echo ' archiveList-leftImg'; if ( $a_eye == 'value2' ) {echo 'No'; }} ?>">
      
        <h2 class="heading heading-archive<?php if($a_lay == 'value2' && !is_sticky()){echo ' heading-leftImg'; if ( $a_eye == 'value2' ) {echo 'No'; }} ?>">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        
		<?php if ( $a_eye != 'value2' ) :	?>
          <div class="eyecatch<?php if($a_lay == 'value2' && !is_sticky()){echo ' eyecatch-leftImg'; if ( $a_eye == 'value2' ) {echo 'No'; }} ?>">
            <?php if(is_sticky()):?>
              <span class="eyecatch__ribbon">Pickup</span>
            <?php endif;?>
            <?php if ( $a_lay != 'value2' || is_sticky() ) : ?>
              <span class="eyecatch__cat<?php if($a_lay == 'value2' && !is_sticky()){echo ' eyecatch__cat-leftImg'; if ( $a_eye == 'value2' ) {echo 'No'; }} ?> u-txtShdw"><?php the_category(' ');?></span>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()) {the_post_thumbnail('icatch');} else {echo '<img src="'.get_template_directory_uri().'/img/img_no.gif" alt="NO IMAGE"/>';}?></a>
          </div>
        <?php endif; ?>
        
        <?php if (get_post_type($post->ID) == 'post') :
		if (get_option('fit_post_time') != 'value2' || has_tag() == true || $a_lay == 'value2' && !is_sticky() ) :	?>
        <ul class="dateList<?php if($a_lay == 'value2' && !is_sticky()){echo ' dateList-leftImg'; if ( $a_eye == 'value2' ) {echo 'No'; }} ?>">
          <?php if (get_option('fit_post_time') != 'value2' ) :	?>
            <li class="dateList__item icon-calendar"><?php the_time('Y.m.d'); ?></li>
          <?php endif; ?>
          <?php if ( $a_eye == 'value2' || $a_lay == 'value2' && !is_sticky()) : ?>
            <li class="dateList__item icon-folder"><?php the_category(' ');?></li>
          <?php endif; ?>
          <?php if(has_tag()==true) : ?>
            <li class="dateList__item icon-tag"><?php the_tags(''); ?></li>
          <?php endif; ?>
        </ul>
        <?php endif; endif; ?>
        
        <p class="archiveList__text<?php if($a_lay == 'value2' && !is_sticky()){echo ' archiveList__text-leftImg'; if ( $a_eye == 'value2' ) {echo 'No'; }} ?>">
		  <?php echo get_the_excerpt(); ?>
        </p>
        
        <div class="btn btn-right<?php if($a_lay == 'value2' && !is_sticky()){echo ' btn-leftImg'; if ( $a_eye == 'value2' ) {echo 'No'; }} ?>">
          <a class="btn__link" href="<?php the_permalink(); ?>">続きを読む</a>
        </div>
      
      </article>
      
      
       