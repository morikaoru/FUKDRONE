<?php
//AMPチェック(機能有効 &singlePage & ampParameter=1)
$myAmp = false;
if(get_option('fit_anp_check') == 'value2' && is_single() && @$_GET['amp'] === '1'){
    $myAmp = true;
}

// icatchサイズの画像内容を取得
$thumbnail_id = get_post_thumbnail_id(); 
$icatch_img = wp_get_attachment_image_src( $thumbnail_id , 'icatch' );
// アイキャッチ画像出力
$src = $icatch_img[0];
$width = $icatch_img[1];
$height = $icatch_img[2];

// AMP用ロゴ画像からサイズを取得
if (get_fit_amp_logo()) {
	$amp_logo = get_fit_amp_logo();
	$amp_image_id = fit_get_image_id($amp_logo);
	$amp_image = wp_get_attachment_image_src( $amp_image_id, 'full' );
	$amp_src = $amp_image[0]; //url
	$amp_width = $amp_image[1]; //横幅
	$amp_height = $amp_image[2]; //高さ
}
// ロゴ画像からサイズを取得
if (get_fit_image_logo()) {
	$logo_logo = get_fit_image_logo();
	$logo_image_id = fit_get_image_id($logo_logo);
	$logo_image = wp_get_attachment_image_src( $logo_image_id, 'full' );
	$logo_src = $logo_image[0]; //url
	$logo_width = $logo_image[1]; //横幅
	$logo_height = $logo_image[2]; //高さ
}
get_header(); ?>
   
  <!-- l-wrapper -->
  <div class="l-wrapper">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_postLayout') == 'value2' ):?> l-main-single
	<?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">
	
	  <?php fit_breadcrumb(); ?>
      
      <article>
      <!-- heading-dateList -->
      <h1 class="heading heading-primary"><?php the_title(); ?></h1>
      
      <ul class="dateList dateList-single">
        <?php if (get_option('fit_post_time') != 'value2' ) :	?><li class="dateList__item icon-calendar"><?php the_time('Y.m.d'); ?></li><?php endif; ?>
        <li class="dateList__item icon-folder"><?php the_category(' ');?></li>
        <?php if(has_tag() == true) : ?><li class="dateList__item icon-tag"><?php the_tags(''); ?></li><?php endif; ?>
      </ul>
      <!-- /heading-dateList -->

     
      
	  <?php if ( get_option('fit_post_shareTop') != 'value2' ):?>
      <!-- 記事上シェアボタン -->
        <?php fit_share_btn(); ?>
	  <!-- /記事上シェアボタン -->
	  <?php endif; ?>

	  <?php if (!$myAmp && is_active_sidebar('post-top')) :?>
      <!-- 記事上エリア[widget] -->
        <?php
          echo '<aside class="widgetPost widgetPost-top">';
		  dynamic_sidebar('post-top');
		  echo '</aside>';
        ?>
      <!-- /記事上エリア[widget] -->
	  <?php endif; ?>
      
      <?php if ($myAmp && get_option('fit_ad_postTop')) :?>
      <!-- AMP用記事上広告エリア -->
      <aside class="ampAd">
        <?php echo get_option('fit_ad_postTop'); ?>
        <span class="ampAd__text">Advertisement</span>
      </aside>
      <!-- /AMP用記事上広告エリア -->
	  <?php endif; ?>
      
      
	  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <section class="content">
	    <?php the_content(); ?>
      </section>
	  <?php endwhile; endif; ?>

      
	  <?php if ( get_option('fit_post_shareBottom') != 'value2' ):?>
      <!-- 記事下シェアボタン -->
        <?php fit_share_btn(); ?>
	  <!-- /記事下シェアボタン -->
	  <?php endif; ?>
      
      
      <?php if(get_option('fit_cta_postBox') == 'value2' ):?>
      <!-- 記事下CTAエリア -->
      <div class="ctaPost">
	    <?php if (get_option('fit_cta_postTitle')) :?>
          <h2 class="ctaPost__title"><?php echo get_option('fit_cta_postTitle'); ?></h2>
        <?php endif; ?>
        <div class="ctaPost__contents">           
          
          <?php if (get_fit_cta_postImg()) :
		      $postImg = get_fit_cta_postImg();
			  $postImg_id = fit_get_image_id($postImg);
			  $postImg_full = wp_get_attachment_image_src( $postImg_id, 'full' );
			  $postImg_src = $postImg_full[0]; //url
			  $postImg_width = $postImg_full[1]; //横幅
			  $postImg_height = $postImg_full[2]; //高さ
		  ?>
            <?php if (get_option('fit_cta_postUrl')) :?><a href="<?php echo get_option('fit_cta_postUrl'); ?>"><?php endif; ?>
			<?php
            if($myAmp){
			    echo '<amp-img';
		    }else{echo '<img';}
		    ?>
            class="ctaPost__img<?php
              if(get_option('fit_cta_postImgPc') == 'value2' ){echo ' ctaPost__img-pcCenter';}
		      if(get_option('fit_cta_postImgPc') == 'value3' ){echo ' ctaPost__img-pcLeft';}
		      if(get_option('fit_cta_postImgSp') == 'value2' ){echo ' ctaPost__img-spCenter';}
		      if(get_option('fit_cta_postImgSp') == 'value3' ){echo ' ctaPost__img-spLeft';}
		      ?>"
            src="<?php echo $postImg_src; ?>" alt="CTA-IMAGE" width="<?php echo $postImg_width; ?>" height="<?php echo $postImg_height; ?>" >
		    <?php
            if($myAmp){
			    echo '</amp-img>';
		    }?>
          <?php endif; ?>
          
          <?php if (get_option('fit_cta_postUrl')) :?></a><?php endif; ?>

          <?php if (get_option('fit_cta_postContents')) :?>
		    <?php echo apply_filters( 'fit_postContents', get_option('fit_cta_postContents') ); ?>
          <?php endif; ?>

          
          <?php if (get_option('fit_cta_postBtn') && get_option('fit_cta_postUrl')) :?>
            <div class="ctaPost__btn"><a href="<?php echo get_option('fit_cta_postUrl'); ?>"><?php echo get_option('fit_cta_postBtn'); ?></a></div>
          <?php endif; ?>
          
        </div>
      </div>
      <!-- /記事下CTAエリア -->
      <?php endif; ?>


	  <?php
	  if(get_option('fit_post_prevNext') != 'value2' ){ //前次記事の表示がONの時
	      if(get_option('fit_post_prevNextCategory')){
		      $prevpost = get_adjacent_post(true, '', true); //同一カテゴリの前の記事
		      $nextpost = get_adjacent_post(true, '', false); //同一カテゴリの次の記事
	      }else {
		      $prevpost = get_adjacent_post(false, '', true); //前の記事
		      $nextpost = get_adjacent_post(false, '', false); //次の記事
	      }
		  if($prevpost || $nextpost){  
	  ?>
      <!-- 前次記事エリア -->
	  <ul class="prevNext">
        <?php if ( $prevpost ) {//前の記事がある時
			// prev_thumbnailサイズの画像内容を取得
			$prev_thumbnail_id = get_post_thumbnail_id($prevpost->ID); 
			$prev_thumb_img = wp_get_attachment_image_src( $prev_thumbnail_id , 'thumbnail' );
			// サムネイル画像出力
			$prev_thumb_src = $prev_thumb_img[0];
			$prev_thumb_width = $prev_thumb_img[1];
			$prev_thumb_height = $prev_thumb_img[2];
		?>
	      <li class="prevNext__item prevNext__item-prev">
            <div class="prevNext__pop">前の記事</div>
	        <a class="prevNext__imgLink" href="<?php echo get_permalink($prevpost->ID); ?>" title="<?php echo get_the_title($prevpost->ID); ?>">
			<?php if(has_post_thumbnail($prevpost->ID)) : ?>
		      <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo $prev_thumb_src; ?>" alt="<?php echo get_the_title($prevpost->ID); ?>" width="<?php echo $prev_thumb_width; ?>" height="<?php echo $prev_thumb_height; ?>" >
			  <?php if($myAmp){echo '</amp-img>';}?>
		    <?php else :?>
              <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo get_template_directory_uri(); ?>/img/img_no_thumbnail.gif" alt="NO IMAGE" width="160" height="160" >
			  <?php if($myAmp){echo '</amp-img>';}?>		
		    <?php endif; ?>
	        </a>
	        <h3 class="prevNext__title">
	          <a href="<?php echo get_permalink($prevpost->ID); ?>"><?php echo get_the_title($prevpost->ID); ?></a>
              <?php if (get_option('fit_post_time') != 'value2' ) :	?><span class="icon-calendar"><?php echo get_the_time('Y.m.d' , $prevpost->ID ); ?></span><?php endif; ?>
	        </h3>
	      </li>
        <?php }else { //前の記事がない時?>
          <li class="prevNext__item prevNext__item-prev">
            <div class="prevNext__pop">前の記事</div>
            <p class="prevNext__text">記事がありません</p>
          </li>
        <?php }?>
        <?php if ( $nextpost ) {//次の記事がある時
			// next_thumbnailサイズの画像内容を取得
			$next_thumbnail_id = get_post_thumbnail_id($nextpost->ID); 
			$next_thumb_img = wp_get_attachment_image_src( $next_thumbnail_id , 'thumbnail' );
			// サムネイル画像出力
			$next_thumb_src = $next_thumb_img[0];
			$next_thumb_width = $next_thumb_img[1];
			$next_thumb_height = $next_thumb_img[2];
		?>
	      <li class="prevNext__item prevNext__item-next">
            <div class="prevNext__pop">次の記事</div>
	        <a class="prevNext__imgLink" href="<?php echo get_permalink($nextpost->ID); ?>" title="<?php echo get_the_title($nextpost->ID); ?>">
			<?php if(has_post_thumbnail($nextpost->ID)) : ?>
		      <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo $next_thumb_src; ?>" alt="<?php echo get_the_title($nextpost->ID); ?>" width="<?php echo $next_thumb_width; ?>" height="<?php echo $next_thumb_height; ?>" >
			  <?php if($myAmp){echo '</amp-img>';}?>
		    <?php else :?>
              <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo get_template_directory_uri(); ?>/img/img_no_thumbnail.gif" alt="NO IMAGE" width="160" height="160" >
			  <?php if($myAmp){echo '</amp-img>';}?>		
		    <?php endif; ?>
	        </a>
	        <h3 class="prevNext__title">
	          <a href="<?php echo get_permalink($nextpost->ID); ?>"><?php echo get_the_title($nextpost->ID); ?></a>
	          <?php if (get_option('fit_post_time') != 'value2' ) :	?><span class="icon-calendar"><?php echo get_the_time('Y.m.d' , $nextpost->ID ); ?></span><?php endif; ?>
	        </h3>
	      </li>
        <?php }else { //次の記事がない時 ?>
          <li class="prevNext__item prevNext__item-next">
            <div class="prevNext__pop">次の記事</div>
            <p class="prevNext__text">記事がありません</p>
          </li>
        <?php }?>
	  </ul>
      <!-- /前次記事エリア -->
	  <?php }} ?>
      
      
	  <?php if (!$myAmp && is_active_sidebar('post-bottom')) :?>
      <!-- 記事下エリア[widget] -->
        <?php
          echo '<aside class="widgetPost widgetPost-bottom">';
		  dynamic_sidebar('post-bottom');
		  echo '</aside>';
        ?>
      <!-- /記事下エリア[widget] -->
	  <?php endif; ?>
      
      <?php if ($myAmp && get_option('fit_ad_postBottom')) :?>
      <!-- AMP用記事下広告エリア -->
      <aside class="ampAd">
        <?php echo get_option('fit_ad_postBottom'); ?>
        <span class="ampAd__text">Advertisement</span>
      </aside>
      <!-- /AMP用記事下広告エリア -->
	  <?php endif; ?>
      
      

      <?php if (!$myAmp):?>
	  <?php if (get_option('fit_ad_doubleLeft') || get_option('fit_ad_doubleRight')):?>
      <!-- ダブルレクタングル広告 -->
	  <aside class="rectangle">
	    <div class="rectangle__item rectangle__item-left">
          <?php echo get_option('fit_ad_doubleLeft'); ?>
	    </div>
	    <div class="rectangle__item rectangle__item-right">
          <?php echo get_option('fit_ad_doubleRight'); ?>
	    </div>
        <h2 class="rectangle__title">Advertisement</h2>
	  </aside>
      <!-- /ダブルレクタングル広告 -->
      <?php endif; ?>
      <?php endif; ?>


	  
	  <?php if ( get_option('fit_post_poster') != 'value2' ) :	?>
      <!-- プロフィール -->
	  <aside class="profile">
	    <div class="profile__imgArea">
	      <?php
 	      $author = get_the_author_meta('ID');
 	      $author_img = get_avatar($author);
 	      $imgtag= '/<img.*?src=(["\'])(.+?)\1.*?>/i';
 	      if(preg_match($imgtag, $author_img, $imgurl)){
 	    	  $author_img = $imgurl[2];
 	      }
		  ?>
		  <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo $author_img; ?>" alt="<?php echo the_author_meta('display_name'); ?>" width="60" height="60" >
		  <?php if($myAmp){echo '</amp-img>';}?>	        

	      <ul class="profile__list">
	  	    <?php
	        if (get_the_author_meta('facebook'))  {
	            echo '<li class="profile__item"><a class="profile__link icon-facebook" href="'. esc_url(get_the_author_meta('facebook')) .'"></a></li>';
	        }if (get_the_author_meta('twitter'))  {
	            echo '<li class="profile__item"><a class="profile__link icon-twitter" href="'. esc_url(get_the_author_meta('twitter')) .'"></a></li>';
	        }if (get_the_author_meta('instagram'))  {
				echo '<li class="profile__item"><a class="profile__link icon-instagram" href="'. esc_url(get_the_author_meta('instagram')) .'"></a></li>';
			}if (get_the_author_meta('gplus'))  {
				echo '<li class="profile__item"><a class="profile__link icon-google" href="'. esc_url(get_the_author_meta('gplus')) .'"></a></li>';
			}
			?>
	      </ul>  
	    </div>
	    <div class="profile__contents">
	      <h2 class="profile__name">Author：<?php the_author_meta('display_name'); ?>
            <span class="btn"><a class="btn__link btn__link-profile" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">投稿一覧</a></span>
          </h2>
	      <?php if (get_the_author_meta('user_group'))  { echo'<h3 class="profile__group">'; echo esc_html(get_the_author_meta('user_group')); echo'</h3>'; } ?>
	      <div class="profile__description"><?php the_author_meta('description'); ?></div>
	    </div>
	  </aside>
      <!-- /プロフィール -->
	  <?php endif; ?>
	  


	  
	  <?php if (get_option('fit_post_related') != 'value2' ) : ?>
      <!-- 関連記事 -->
	  <?php
	  // 総件数
	  if(get_option('fit_post_relatedNumber')){
		  $max_post_num = get_option('fit_post_relatedNumber');
	  }else{
		  $max_post_num = 3;
	  }
	  // 現在の記事にタグが設定されている場合
	  if ( has_tag() ) {
	      // 1.タグ関連の投稿を取得
	      $tags = wp_get_post_tags($post->ID);
	      $tag_ids = array();
	      foreach($tags as $tag):
	          array_push( $tag_ids, $tag -> term_id);
	      endforeach ;
	      $tag_args = array(
	          'post__not_in' => array($post -> ID),
			  'tag__not_in' => $tag_ids,
	          'posts_per_page'=> $max_post_num,
	          'tag__in' => $tag_ids,
	          'orderby' => 'rand',
	      );
	      $rel_posts = get_posts($tag_args);
 	     // 総件数よりタグ関連の投稿が少ない場合は、カテゴリ関連の投稿からも取得する
	      $rel_count = count($rel_posts);
	      if ($max_post_num > $rel_count) {
	  		$categories = get_the_category($post->ID);
	          $category_ID = array();
	          foreach($categories as $category):
	              array_push( $category_ID, $category -> cat_ID);
	          endforeach ;
	          // 取得件数は必要な数のみリクエスト
	          $cat_args = array(
	              'post__not_in' => array($post -> ID),
	              'posts_per_page'=> ($max_post_num - $rel_count),
	              'category__in' => $category_ID,
	              'orderby' => 'rand',
 	         );
 	         $cat_posts = get_posts($cat_args);
	          $rel_posts = array_merge($rel_posts, $cat_posts);
	      }
	  } else { // 現在の記事にタグが設定されていない場合

	      $categories = get_the_category($post->ID);
	      $category_ID = array();
	      foreach($categories as $category):
	          array_push( $category_ID, $category -> cat_ID);
	      endforeach ;
	      // 取得件数は必要な数のみリクエスト
	      $cat_args = array(
	          'post__not_in' => array($post -> ID),
	          'posts_per_page'=> ($max_post_num),
	          'category__in' => $category_ID,
	          'orderby' => 'rand',
	      );
	      $cat_posts = get_posts($cat_args);
 	      $rel_posts = $cat_posts;
	  }

	  echo'<aside class="related">';
	  echo'<h2 class="heading heading-secondary">関連する記事</h2>';

	  // 1件以上あれば
	  if( count($rel_posts) > 0 ){
	  	echo'<ul class="related__list">';
	  	if (!$myAmp && is_active_sidebar( 'related-ad' ) ){
	  		dynamic_sidebar( 'related-ad' );
	  	}
	  	foreach ($rel_posts as $post){setup_postdata($post);
		// thumbnailサイズの画像内容を取得
		$thumbnail_id = get_post_thumbnail_id(); 
		$thumb_img = wp_get_attachment_image_src( $thumbnail_id , 'thumbnail' );
		// サムネイル画像出力
		$thumb_src = $thumb_img[0];
		$thumb_width = $thumb_img[1];
		$thumb_height = $thumb_img[2];
		?>
	      <li class="related__item">
	        <a class="related__imgLink" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php if(has_post_thumbnail()) : ?>
		      <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo $thumb_src; ?>" alt="<?php the_title(); ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" >
			  <?php if($myAmp){echo '</amp-img>';}?>
		    <?php else :?>
              <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo get_template_directory_uri(); ?>/img/img_no_thumbnail.gif" alt="NO IMAGE" width="160" height="160" >
			  <?php if($myAmp){echo '</amp-img>';}?>		
		    <?php endif; ?>
	        </a>
	        <h3 class="related__title">
	          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              <?php if (get_option('fit_post_time') != 'value2' ) :	?><span class="icon-calendar"><?php the_time('Y.m.d'); ?></span><?php endif; ?>
	        </h3>
	        <p class="related__contents"><?php echo mb_substr(get_the_excerpt(), 0, 75); ?>[…]</p>
	      </li>
	  	<?php }
	
	  	echo'</ul>';
	  }else{
	  	echo'<p class="related__contents related__contents-max">関連記事はありませんでした</p>';
	  }
 
	  echo'</aside>';
	  ?>
	  <?php wp_reset_postdata();?>
      <!-- /関連記事 -->
	  <?php endif; ?>
	  


	  
	  <?php if(!$myAmp): ?>
      <!-- コメント -->
        <?php comments_template(); ?>
      <!-- /コメント -->
	  <?php endif; ?>
	  

	  
	  <?php if(!is_user_logged_in() && !is_bot()): ?>
      <!-- PVカウンター -->
        <?php set_post_views(get_the_ID()); ?>
	  <!-- /PVカウンター -->
	  <?php endif; ?>
      </article>
      
      
    </main>
    <!-- /l-main -->

    
	<?php if (!$myAmp && get_option('fit_theme_postLayout') != 'value2' ):?>
    <!-- l-sidebar -->
      <?php get_sidebar(); ?>
    <!-- /l-sidebar -->
	<?php endif; ?>
    
    
  </div>
  <!-- /l-wrapper -->

  <!-- schema -->
  <script type="application/ld+json">
  {
  "@context": "http://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage":{
	  "@type": "WebPage",
	  "@id": "<?php the_permalink(); ?>"
  },
  "headline": "<?php echo get_the_title(); ?>",
  "image": {
	  "@type": "ImageObject",
	  <?php if(has_post_thumbnail()) : ?>"url": "<?php echo $src; ?>",
	  "height": "<?php echo $height; ?>",
	  "width": "<?php echo $width; ?>"
	  <?php else: ?>"url": "<?php echo get_template_directory_uri(); ?>/img/img_no.gif",
	  "height": "890",
	  "width": "500"
	  <?php endif; ?>
  },
  "datePublished": "<?php echo get_the_date(DATE_ISO8601); ?>",
  "dateModified": "<?php if ( get_the_date() != get_the_modified_time() ){ the_modified_date(DATE_ISO8601); } else { echo get_the_date(DATE_ISO8601); } ?>",
  "author": {
	  "@type": "Person",
	  "name": "<?php the_author_meta('display_name'); ?>"
  },
  "publisher": {
	  "@type": "Organization",
	  "name": "<?php bloginfo('name'); ?>",
	  "logo": {
		  "@type": "ImageObject",
		  <?php if($myAmp): // AMP?>
		  "url": "<?php echo get_fit_amp_logo(); ?>",
		  "width": "<?php echo($amp_width); ?>",
		  "height":"<?php echo($amp_height); ?>"
		  <?php else: // 通常?>
		    <?php if(get_fit_image_logo()): ?>
		    "url": "<?php echo get_fit_image_logo(); ?>",
		    "width": "<?php echo($logo_width); ?>",
		    "height":"<?php echo($logo_height);?>"
		    <?php else:?>
		    "url": "<?php echo get_fit_amp_logo(); ?>",
		    "width": "<?php echo($amp_width); ?>",
		    "height":"<?php echo($amp_height);?>"
		    <?php endif; ?>
		  <?php endif; // AMP分岐終了 ?>
	  }
  },
  "description": "<?php echo get_the_excerpt(); ?>"
  }
  </script>
  <!-- /schema -->

<?php get_footer(); ?>