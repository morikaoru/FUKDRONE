<?php
//AMPチェック(機能有効 &singlePage & ampParameter=1)
$myAmp = false;
if(get_option('fit_anp_check') == 'value2' && is_single() && @$_GET['amp'] === '1'){
    $myAmp = true;
}
?>
<!DOCTYPE html>
<?php if($myAmp): // AMPページ ?>
<html amp>
<head>
<meta charset="utf-8">
<?php fit_amp_head(); ?>
<?php else: // 通常ページ ?>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<meta charset="<?php bloginfo('charset'); ?>">
<?php wp_head(); ?>
<?php endif; // AMP分岐終了 ?>
<?php fit_seo();?>
<?php fit_ogp();?>

<?php if(!$myAmp && get_option('fit_access_gaid')): // 通常ページanalytics ?>
<?php include_once("analyticstracking.php"); ?>
<?php endif; ?>

<?php if (get_option('fit_advanced_head')): ?>
<?php echo get_option('fit_advanced_head'); ?>
<?php endif; ?>

</head>
<body<?php fit_body_class(); ?>>
<?php if($myAmp && get_option('fit_access_ampgaid')): // AMPページanalytics ?>
<amp-analytics type="googleanalytics" id="amp-analytics">
<script type="application/json">
{
  "vars": {
    "account": "<?php echo get_option('fit_access_ampgaid');	?>"
  },
  "triggers": {
    "trackPageview": {
      "on": "visible",
      "request": "pageview"
    }
  }
}
</script>
</amp-analytics>
<?php endif; ?>

  <?php if(get_option('fit_theme_infoHead') == 'value2'): ?>
  <div class="infoHead">
    <?php if(get_option('fit_theme_infoHeadUrl')): ?><a class="infoHead__link" href="<?php echo get_option('fit_theme_infoHeadUrl') ?>"><?php endif; ?>
      <?php if(get_option('fit_theme_infoHeadText')): ?><?php echo get_option('fit_theme_infoHeadText') ?><?php endif; ?>
    <?php if(get_option('fit_theme_infoHeadUrl')): ?></a><?php endif; ?>
  </div>
  <?php endif; ?>
  
  <!--l-header-->
  <header class="l-header">
    
    <!--l-hMain-->
    <div class="l-hMain">
      <div class="container">
      
        <div class="siteTitle<?php if(get_option('fit_ad_header') == ''): ?> siteTitle-noneAd<?php endif; ?>">
	    <?php 
	    if (get_fit_image_logo()):
			$logo = get_fit_image_logo();
			$image_id = fit_get_image_id($logo);
			$image = wp_get_attachment_image_src( $image_id, 'full' );
			$src = $image[0]; //url
			$width = $image[1]; //横幅
			$height = $image[2]; //高さ
	    ?>
          <?php if (is_home()) : ?><h1<?php else : ?><p<?php endif; ?> class="siteTitle__logo"><a class="siteTitle__link" href="<?php echo home_url() ?>">
          <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo $src;?>" alt="<?php bloginfo('name') ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" ><?php if($myAmp){echo '</amp-img>';}?>	
          </a><?php if (is_home()) : ?></h1><?php else : ?></p><?php endif; ?>
	    <?php else : ?>
          <?php if (is_home()) : ?><h1<?php else : ?><p<?php endif; ?> class="siteTitle__big u-txtShdw"><a class="siteTitle__link" href="<?php echo home_url() ?>"><?php bloginfo('name') ?></a><?php if (is_home()) : ?></h1><?php else : ?></p><?php endif; ?>
          <?php if (is_home()) : ?><h2<?php else : ?><p<?php endif; ?> class="siteTitle__small"><?php bloginfo('description') ?><?php if (is_home()) : ?></h2><?php else : ?></p><?php endif; ?>
	    <?php endif; ?>
        </div>
      

	    <?php if (!$myAmp && get_option('fit_ad_header') ) : ?>
          <div class="adHeader<?php if (get_option('fit_ad_headerCheck') ) : ?> u-none-sp<?php endif; ?>">
	        <?php echo get_option('fit_ad_header'); echo "\n"; ?>
          </div>
	    <?php endif; ?>
      
      
        <nav class="globalNavi">
        <input class="globalNavi__toggle" id="globalNavi__toggle" type="checkbox" value="none">
        <label class="globalNavi__switch" for="globalNavi__toggle"></label>
	    <?php
        if ( has_nav_menu( 'header_menu' ) ) : //メニューセットあり
	        wp_nav_menu(array(
		        'theme_location' => 'header_menu',
			    'items_wrap' => '<ul class="globalNavi__list u-txtShdw">%3$s</ul>',
			    'container' => false,
	        )
        ); echo "\n";?>
	    <?php else : //メニューセットなし ?>
	      <ul class="globalNavi__list u-txtShdw">
		    <?php wp_list_pages ('title_li='); echo "\n"; ?>
          </ul>
	    <?php endif; ?>
        </nav>
       
      </div>
    </div>
    <!-- /l-hMain -->
    
    
    <!-- l-hExtra -->
	<?php $exclass = get_option('fit_theme_headerArea'); ?>
	<?php  if ( $exclass != 'value2' ) : ?>
    <div class="l-hExtra<?php if ( $exclass == 'value3' ) : ?> u-none-pc<?php endif; ?><?php if ( $exclass == 'value4' ) : ?> u-none-sp<?php endif; ?>">
      <div class="container">
        
        <div class="marquee">
          <div class="marquee__title">NEW ARTICLE</div>
          <div class="marquee__item">
		  <?php $args = array('posts_per_page' => 1,'ignore_sticky_posts' => 1); $my_query = new WP_Query( $args );?>
		  <?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
            <a class="marquee__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		  <?php endwhile; ?>
		  <?php wp_reset_postdata(); ?>
          </div>
        </div>

        <div class="socialSearch">
        <?php if(get_option('fit_anp_search') == 'value2' || !$myAmp): ?>
          <?php get_search_form() ?>
        <?php endif; ?>
        
	    <?php $opt = get_option('fit_social'); ?>
        <?php if (isset($opt['FBFollowH']) && $opt['FBFollowH'] == '1' || isset($opt['twitterFollowH']) && $opt['twitterFollowH'] == '1' || isset($opt['instaFollowH']) && $opt['instaFollowH'] == '1' || isset($opt['googleFollowH']) && $opt['googleFollowH'] == '1' || isset($opt['rssFollowH']) && $opt['rssFollowH'] == '1'):	?>
          <ul class="socialSearch__list">
		  <?php if ( isset($opt['FBFollowH']) && $opt['FBFollowH'] == '1' && $opt['FBPage'] != '' ):?>
            <li class="socialSearch__item"><a class="socialSearch__link icon-facebook" href="https://www.facebook.com/<?php echo $opt['FBPage']; ?>"></a></li>
		  <?php endif; if ( isset($opt['twitterFollowH']) && $opt['twitterFollowH'] == '1' && $opt['twitterId'] != '' ) : ?>
            <li class="socialSearch__item"><a class="socialSearch__link icon-twitter" href="https://twitter.com/<?php echo $opt['twitterId']; ?>"></a></li>
		  <?php endif; if ( isset($opt['instaFollowH']) && $opt['instaFollowH'] == '1' && $opt['insta'] != '' ) : ?>
            <li class="socialSearch__item"><a class="socialSearch__link icon-instagram" href="http://instagram.com/<?php echo $opt['insta']; ?>"></a></li>
		  <?php endif; if ( isset($opt['googleFollowH']) && $opt['googleFollowH'] == '1' && $opt['googleUrl'] != '' ) : ?>
            <li class="socialSearch__item"><a class="socialSearch__link icon-google" href="https://plus.google.com/+<?php echo $opt['googleUrl']; ?>"></a></li>
          <?php endif; if ( isset($opt['rssFollowH']) && $opt['rssFollowH'] == '1'): ?>
            <?php $optRssUrl = $opt['rssUrl']; if (!empty($optRssUrl)) : ?>
              <li class="socialSearch__item"><a class="socialSearch__link icon-rss" href="<?php echo $opt['rssUrl']; ?>"></a></li>
            <?php else : ?>
              <li class="socialSearch__item"><a class="socialSearch__link icon-rss" href="<?php bloginfo('rss2_url'); ?>"></a></li>
			<?php endif; ?>
		  <?php endif; ?>
          </ul>
        <?php endif; ?>
        </div>
     
      </div>
    </div>
    <?php endif; ?>
    <!-- /l-hExtra -->
    
  </header>
  <!--/l-header-->
  
