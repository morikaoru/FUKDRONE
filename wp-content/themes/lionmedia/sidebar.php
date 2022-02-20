    <div class="l-sidebar">
	  
	  <?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar' ); ?>
	  <?php endif; ?>
	  
	  <?php if ( is_active_sidebar( 'sidebar-sticky' ) ) : ?>
      <div class="widgetSticky">
        <?php dynamic_sidebar( 'sidebar-sticky' ); ?>
      </div>
	  <?php endif; ?>
    
    </div>

