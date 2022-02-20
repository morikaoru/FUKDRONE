<?php get_header(); ?>
<?php fit_breadcrumb(); ?>

  <!-- l-wrapper -->
  <div class="l-wrapper ">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_archiveLayout') == 'value2' ):?> l-main-single
    <?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">
	  
      <header class="archiveTitle">
        <h1 class="heading heading-first"><?php echo fit_archive_title(); ?></h1>
      </header>

      <div class="archive">
        <div class="archiveList">
          <p class="archiveList__text archiveList__text-center">お探しのページはありませんでした。</p>
        </div>
      </div>
      
      
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