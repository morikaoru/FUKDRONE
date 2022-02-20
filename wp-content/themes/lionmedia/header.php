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
<?php if($myAmp && get_option('fit_access_ampgaid')){ // AMPページanalytics ?>
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
<?php } ?>

  <?php if(get_option('fit_theme_infoHead') == 'value2'): ?>
  <div class="infoHead">
    <?php if(get_option('fit_theme_infoHeadUrl')): ?><a class="infoHead__link" href="<?php echo get_option('fit_theme_infoHeadUrl') ?>"><?php endif; ?>
      <?php if(get_option('fit_theme_infoHeadText')): ?><?php echo get_option('fit_theme_infoHeadText') ?><?php endif; ?>
    <?php if(get_option('fit_theme_infoHeadUrl')): ?></a><?php endif; ?>
  </div>
  <?php endif; ?>

  <!--l-header-->
  <header class="l-header">
    <div class="container">
      
      <div class="siteTitle">

      <?php 
	  if (get_fit_image_logo()):
	      $logo = get_fit_image_logo();
		  $image_id = fit_get_image_id($logo);
		  $image = wp_get_attachment_image_src( $image_id, 'full' );
		  $src = $image[0]; //url
		  $width = $image[1]; //横幅
		  $height = $image[2]; //高さ
	  ?>
        <?php if (is_home()) : ?><h1<?php else : ?><p<?php endif; ?> class="siteTitle__logo">
          <a class="siteTitle__link" href="<?php echo home_url() ?>">
            <?php if($myAmp){echo '<amp-img layout="responsive"';}else{echo '<img';} ?> src="<?php echo $src;?>" class="siteTitle__img" alt="<?php bloginfo('name') ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" ><?php if($myAmp){echo '</amp-img>';}?>
          </a>
          <span class="siteTitle__sub"><?php bloginfo('description') ?></span>
		<?php if (is_home()) : ?></h1><?php else : ?></p><?php endif; ?>
	  <?php else : ?>
        <?php if (is_home()) : ?><h1<?php else : ?><p<?php endif; ?> class="siteTitle__name  u-txtShdw">
          <a class="siteTitle__link" href="<?php echo home_url() ?>">
            <span class="siteTitle__main"><?php bloginfo('name') ?></span>
          </a>
          <span class="siteTitle__sub"><?php bloginfo('description') ?></span>
		<?php if (is_home()) : ?></h1><?php else : ?></p><?php endif; ?>
	  <?php endif; ?>
      </div>
      
      <nav class="menuNavi">      
      <?php $opt = get_option('fit_social'); ?>
          <ul class="menuNavi__list">
		  <?php if (isset($opt['FBFollowH']) && $opt['FBFollowH'] == '1' && $opt['FBPage'] != ''):?>
            <li class="menuNavi__item u-none-sp u-txtShdw"><a class="menuNavi__link icon-facebook" href="https://www.facebook.com/<?php echo $opt['FBPage']; ?>"></a></li>
		  <?php endif; if (isset($opt['twitterFollowH']) && $opt['twitterFollowH'] == '1' && $opt['twitterId'] != '') : ?>
            <li class="menuNavi__item u-none-sp u-txtShdw"><a class="menuNavi__link icon-twitter" href="https://twitter.com/<?php echo $opt['twitterId']; ?>"></a></li>
		  <?php endif; if (isset($opt['instaFollowH']) && $opt['instaFollowH'] == '1' && $opt['insta'] != '') : ?>
            <li class="menuNavi__item u-none-sp u-txtShdw"><a class="menuNavi__link icon-instagram" href="http://instagram.com/<?php echo $opt['insta']; ?>"></a></li>
		  <?php endif; if (isset($opt['googleFollowH']) && $opt['googleFollowH'] == '1' && $opt['googleUrl'] != '') : ?>
            <li class="menuNavi__item u-none-sp u-txtShdw"><a class="menuNavi__link icon-google" href="https://plus.google.com/+<?php echo $opt['googleUrl']; ?>"></a></li>       
          <?php endif; if (isset($opt['rssFollowH']) && $opt['rssFollowH'] == '1'): ?>
            <?php $optRssUrl = $opt['rssUrl']; if (!empty($optRssUrl)) : ?>
              <li class="menuNavi__item u-none-sp u-txtShdw"><a class="menuNavi__link icon-rss" href="<?php echo $opt['rssUrl']; ?>"></a></li>
            <?php else : ?>
              <li class="menuNavi__item u-none-sp u-txtShdw"><a class="menuNavi__link icon-rss" href="<?php bloginfo('rss2_url'); ?>"></a></li>
			<?php endif; ?>
		  <?php endif; if(!$myAmp): ?>
            <li class="menuNavi__item u-txtShdw"><span class="menuNavi__link<?php if(get_option('fit_theme_headerMenu') != 'value2') : ?> menuNavi__link-current<?php endif; ?> icon-search" id="menuNavi__search" onclick="toggle__search();"></span></li>
            <li class="menuNavi__item u-txtShdw"><span class="menuNavi__link<?php if(get_option('fit_theme_headerMenu') == 'value2') : ?> menuNavi__link-current<?php endif; ?> icon-menu" id="menuNavi__menu" onclick="toggle__menu();"></span></li>
          <?php endif; ?>
        </ul>
      </nav>
      
    </div>
  </header>
  <!--/l-header-->
  
  <!--l-extra-->
    <?php if(!$myAmp): ?>
    <div class="l-extra<?php if(get_option('fit_theme_headerMenu') == 'value2') : ?>None<?php endif; ?>" id="extra__search">
      <div class="container">
        <div class="searchNavi">
          <?php if (get_option('fit_function_keyword01')||get_option('fit_function_keyword02')||get_option('fit_function_keyword03')||get_option('fit_function_keyword04')||get_option('fit_function_keyword05')):	?>
          <div class="searchNavi__title u-txtShdw">注目キーワード</div>
          <ul class="searchNavi__list">
            <?php if (get_option('fit_function_keyword01')):?>
              <li class="searchNavi__item"><a class="searchNavi__link" href="<?php echo home_url() ?>/?s=<?php echo get_option('fit_function_keyword01'); ?>"><?php echo get_option('fit_function_keyword01'); ?></a></li>
            <?php endif; if (get_option('fit_function_keyword02')):?>
              <li class="searchNavi__item"><a class="searchNavi__link" href="<?php echo home_url() ?>/?s=<?php echo get_option('fit_function_keyword02'); ?>"><?php echo get_option('fit_function_keyword02'); ?></a></li>
            <?php endif; if (get_option('fit_function_keyword03')):?>
              <li class="searchNavi__item"><a class="searchNavi__link" href="<?php echo home_url() ?>/?s=<?php echo get_option('fit_function_keyword03'); ?>"><?php echo get_option('fit_function_keyword03'); ?></a></li>
            <?php endif; if (get_option('fit_function_keyword04')):?>
              <li class="searchNavi__item"><a class="searchNavi__link" href="<?php echo home_url() ?>/?s=<?php echo get_option('fit_function_keyword04'); ?>"><?php echo get_option('fit_function_keyword04'); ?></a></li>
            <?php endif; if (get_option('fit_function_keyword05')):?>
              <li class="searchNavi__item"><a class="searchNavi__link" href="<?php echo home_url() ?>/?s=<?php echo get_option('fit_function_keyword05'); ?>"><?php echo get_option('fit_function_keyword05'); ?></a></li>
			<?php endif; ?>
          </ul>
          <?php endif; ?>
          
          <?php get_search_form() ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    <div class="l-extra<?php if(get_option('fit_theme_headerMenu') != 'value2'): ?>None<?php endif; ?>" id="extra__menu">
      <div class="container container-max">
        <nav class="globalNavi">
          <ul class="globalNavi__list">
          <?php if ( has_nav_menu( 'header_menu' ) ) : //メニューセットあり ?>
	        <?php wp_nav_menu(array(
		          'theme_location' => 'header_menu',
				  'depth' => 1,
			      'items_wrap' => '%3$s',
			      'container' => false,
	          )
            );?>
	      <?php else : //メニューセットなし ?>
		    <?php wp_list_pages('title_li='); ?>
	      <?php endif; ?>
          <?php $opt = get_option('fit_social'); ?>
          <?php if (isset($opt['FBFollowH']) && $opt['FBFollowH'] == '1' || isset($opt['twitterFollowH']) && $opt['twitterFollowH'] == '1' || isset($opt['instaFollowH']) && $opt['instaFollowH'] == '1' || isset($opt['googleFollowH']) && $opt['googleFollowH'] == '1' || isset($opt['rssFollowH']) && $opt['rssFollowH'] == '1'):	?>
          
            <?php if (isset($opt['FBFollowH']) && $opt['FBFollowH'] == '1' && $opt['FBPage'] != ''):?>
              <li class="menu-item u-none-pc"><a class="icon-facebook" href="https://www.facebook.com/<?php echo $opt['FBPage']; ?>"></a></li>
            <?php endif; if (isset($opt['twitterFollowH']) && $opt['twitterFollowH'] == '1' && $opt['twitterId'] != '') : ?>
              <li class="menu-item u-none-pc"><a class="icon-twitter" href="https://twitter.com/<?php echo $opt['twitterId']; ?>"></a></li>
            <?php endif; if ( isset($opt['instaFollowH']) && $opt['instaFollowH'] == '1' && $opt['insta'] != '' ) : ?>
              <li class="menu-item u-none-pc"><a class="icon-instagram" href="http://instagram.com/<?php echo $opt['insta']; ?>"></a></li>
		    <?php endif; if ( isset($opt['googleFollowH']) && $opt['googleFollowH'] == '1' && $opt['googleUrl'] != '' ) : ?>
              <li class="menu-item u-none-pc"><a class="icon-google" href="https://plus.google.com/+<?php echo $opt['googleUrl']; ?>"></a></li>
			<?php endif; if ( isset($opt['rssFollowH']) && $opt['rssFollowH'] == '1'): ?>
              <?php $optRssUrl = $opt['rssUrl']; if (!empty($optRssUrl)) : ?>
                <li class="menu-item u-none-pc"><a class="icon-rss" href="<?php echo $opt['rssUrl']; ?>"></a></li>
              <?php else : ?>
                <li class="menu-item u-none-pc"><a class="icon-rss" href="<?php bloginfo('rss2_url'); ?>"></a></li>
			  <?php endif; ?>
		    <?php endif; ?>
            
          <?php endif; ?>
          </ul>
        </nav>
      </div>
    </div>
  <!--/l-extra-->

