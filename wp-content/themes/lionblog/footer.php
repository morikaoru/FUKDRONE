<?php
//AMPチェック(機能有効 &singlePage & ampParameter=1)
$myAmp = false;
if(get_option('fit_anp_check') == 'value2' && is_single() && @$_GET['amp'] === '1'){
    $myAmp = true;
}
?>
  <!--l-footer-->
  <footer class="l-footer">
    <div class="container">
      <div class="pagetop u-txtShdw"><a class="pagetop__link" href="#top">Back to top</a></div>

      <?php $opt = get_option('fit_social'); ?>
        <?php if (isset($opt['FBFollowF']) && $opt['FBFollowF'] == '1' || isset($opt['twitterFollowF']) && $opt['twitterFollowF'] == '1' || isset($opt['instaFollowF']) && $opt['instaFollowF'] == '1' || isset($opt['googleFollowF']) && $opt['googleFollowF'] == '1' || isset($opt['rssFollowF']) && $opt['rssFollowF'] == '1'):	?>
          <ul class="socialEffect">
		  <?php if ( isset($opt['FBFollowF']) && $opt['FBFollowF'] == '1' && $opt['FBPage'] != '' ):?>
            <li class="socialEffect__icon"><a class="socialEffect__link icon-facebook" href="https://www.facebook.com/<?php echo $opt['FBPage']; ?>"></a></li>
		  <?php endif; if ( isset($opt['twitterFollowF']) && $opt['twitterFollowF'] == '1' && $opt['twitterId'] != '' ) : ?>
            <li class="socialEffect__icon"><a class="socialEffect__link icon-twitter" href="https://twitter.com/<?php echo $opt['twitterId']; ?>"></a></li>
		  <?php endif; if ( isset($opt['instaFollowF']) && $opt['instaFollowF'] == '1' && $opt['insta'] != '' ) : ?>
            <li class="socialEffect__icon"><a class="socialEffect__link icon-instagram" href="http://instagram.com/<?php echo $opt['insta']; ?>"></a></li>
		  <?php endif; if ( isset($opt['googleFollowF']) && $opt['googleFollowF'] == '1' && $opt['googleUrl'] != '' ) : ?>
            <li class="socialEffect__icon"><a class="socialEffect__link icon-google" href="https://plus.google.com/+<?php echo $opt['googleUrl']; ?>"></a></li>
          <?php endif; if ( isset($opt['rssFollowF']) && $opt['rssFollowF'] == '1'): ?>
            <?php $optRssUrl = $opt['rssUrl']; if (!empty($optRssUrl)) : ?>
              <li class="socialEffect__icon"><a class="socialEffect__link icon-rss" href="<?php echo $opt['rssUrl']; ?>"></a></li>
            <?php else : ?>
              <li class="socialEffect__icon"><a class="socialEffect__link icon-rss" href="<?php bloginfo('rss2_url'); ?>"></a></li>
			<?php endif; ?>
		  <?php endif; ?>
          </ul>
        <?php endif; ?>

         
      <nav class="footerNavi">
	  <?php if ( has_nav_menu( 'footer_menu' ) ) : //メニューセットあり ?>
	  <?php wp_nav_menu(array(
	      'theme_location' => 'footer_menu',
		  'depth' => 1,
		  'items_wrap' => '<ul class="footerNavi__list u-txtShdw">%3$s</ul>',
		  'container' => false,
	  ));?>
	  <?php else : //メニューセットなし ?>
	    <ul class="footerNavi__list u-txtShdw"><?php wp_list_pages ('title_li=&depth=1'); ?></ul>
      <?php endif; ?>
      </nav>

      <div class="copyright">
      <?php if (get_option('fit_theme_copyright')): ?>
        <?php echo get_option('fit_theme_copyright'); ?>
      <?php else : ?>
        © Copyright <?php echo date( 'Y' ); ?> <a class="copyright__link" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>.
      <?php endif; ?>
      
	    <span class="copyright__info<?php if (get_option('fit_theme_copyrightInfo')): ?> u-none<?php endif; ?>">
		  <?php bloginfo( 'name' ); ?> by <a class="copyright__link" href="http://fit-jp.com/" target="_blank">FIT-Web Create</a>. Powered by <a class="copyright__link" href="https://wordpress.org/" target="_blank">WordPress</a>.
        </span>
      
      </div>
      

    </div>     
  </footer>
  <!-- /l-footer -->

  <?php if(!$myAmp): ?>
    <?php wp_footer(); ?>
  <?php endif; ?>

<?php if (get_option('fit_advanced_foot')): ?>
<?php echo get_option('fit_advanced_foot'); ?>
<?php endif; ?>

</body>
</html>