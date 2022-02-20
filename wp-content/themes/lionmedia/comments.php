      <?php if( comments_open() ): ?>
      <aside class="comments">
        <?php if( have_comments() ): ?>
        <h2 class="heading heading-primary">コメント<span>（<?php comments_number('0','1','%'); ?>件）</span></h2>
        <ul class="comments__list">
		  <?php wp_list_comments('avatar_size=40'); ?>
        </ul>
        <?php endif; ?>
		
		<?php if(get_comment_pages_count() > 1): ?>  
        <div class="pager pager-comments">
		  <?php paginate_comments_links( array(
		  'prev_text' => 'PREV',
		  'next_text' => 'NEXT',
		  'mid_size'  => 0,
		  )); ?>
        </div>
        <?php endif; ?>
		
		<?php $args = array(
		'title_reply' => 'コメントを書く',
		'label_submit' => __( 'コメントを送信' , 'default' ),
		'title_reply_before' => '<h2 class="heading heading-primary">',
		'title_reply_after' => '</h2>',
		);
		comment_form( $args ); ?>
      </aside>
      <?php endif; ?>
