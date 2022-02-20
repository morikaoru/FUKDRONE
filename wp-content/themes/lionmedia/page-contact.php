<?php /* Template Name: お問い合わせTPL */?>
<?php
if(isset($_POST['submitted'])) {
	
	//項目チェック
	if(isset($_POST['checking'])) {
		$captchaError = true;
	} else {
		
		//名前の入力なし
		if(trim($_POST['contactName']) === '') {
			$nameError = '名前が入力されていません';
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}
		
		//メールアドレスの間違い
		if(trim($_POST['email']) === '') {
			$emailError = 'メールアドレスが入力されていません';
			$hasError = true;
		} else if (!preg_match('|^[0-9a-z_./?-]+@([0-9a-z-]+.)+[0-9a-z-]+$|', trim($_POST['email']))) {
			$emailError = 'メールアドレスが正しくありません';
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
		
		//お問い合わせ内容の入力なし
		if(trim($_POST['comments']) === '') {
			$commentError = 'お問い合わせ内容が入力されていません';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}
		
		//エラーなしの場合、メール送信
		if(!isset($hasError)) {
			mb_language("japanese");
			mb_internal_encoding("UTF-8");
			$emailTo = get_option('admin_email');
			$subject = 'お問い合わせ';
			$body = "
下記の通りお問い合わせを受け付けました。 \r\n
\r\n
-------------------------------------------------\r\n
お名前: $name \r\n
メールアドレス: $email \r\n
お問い合わせ内容: $comments \r\n
-------------------------------------------------
";
			$title = get_bloginfo('name');
			$from = mb_encode_mimeheader("$title"."のお問い合わせ","UTF-8");
			$headers = 'From: '.$from.' <'.$email.'>';
			mb_send_mail($emailTo, $subject, $body, $headers);
			
			
			//自動返信用
			$subject = 'お問い合わせ受付のお知らせ';
			$from = mb_encode_mimeheader("$title","UTF-8");
			$headers2 = 'From: '.$from.' <'.$emailTo.'>';
			$body = "
$name 様 \r\n
$title にお問い合わせありがとうございます。\r\n
改めて担当者よりご連絡をさせていただきますので、\r\n
今しばらくお待ちください。\r\n
\r\n
-------------------------------------------------\r\n
お名前：$name \r\n
メールアドレス：$email \r\n
お問い合わせ内容：$comments \r\n
-------------------------------------------------
";
			mb_send_mail($email, $subject, $body, $headers2);
			$emailSent = true;
		}
	}
} ?>
<?php get_header(); ?>
<?php fit_breadcrumb(); ?>

  <!-- l-wrapper -->
  <div class="l-wrapper ">
	
    <!-- l-main -->
    <main class="l-main<?php if ( get_option('fit_theme_pageLayout') == 'value2' ):?> l-main-single
    <?php if ( get_option('fit_theme_singleWidth') == 'value2' ):?> l-main-w740<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value3' ):?> l-main-w900<?php endif; ?>
    <?php if ( get_option('fit_theme_singleWidth') == 'value4' ):?> l-main-w100<?php endif; ?>
    <?php endif; ?>">

      <article>
	  
	  <?php if(isset($emailSent) && $emailSent == true) { ?>
      <h1 class="heading heading-page"><?=$name;?>様、<br>お問い合わせありがとうございます。</h1>
      <section class="content content-page">
        <p>この度はお問い合わせいただきありがとうございます。<br>
        メールを確認次第、担当者よりご連絡をさせていただきます。<p>
      </section>
	  <?php } else { ?>
	  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <h1 class="heading heading-page"><?php the_title(); ?></h1>
      <section class="content content-page">
		<?php the_content(); ?>
      </section>
      <form action="<?php the_permalink(); ?>" method="post">
        <table class="contactTable">
          <tr>
            <th class="contactTable__header">お名前<span class="required">必須</span></th>
            <td class="contactTable__data">
            <input type="text" name="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" />
			<?php if(isset($nameError)) { ?><span class="error"><?=$nameError;?></span><?php } ?>
            </td>
          </tr>
          <tr>
            <th class="contactTable__header">メールアドレス<span class="required">必須</span></th>
            <td class="contactTable__data">
            <input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" />
			<?php if(isset($emailError)) { ?><span class="error"><?=$emailError;?></span><?php } ?>
            </td>
          </tr>
          <tr>
            <th class="contactTable__header">お問い合わせ内容<span class="required">必須</span></th>
            <td class="contactTable__data">
            <textarea name="comments" rows="10"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
        	  <?php if(isset($commentError)) { ?><span class="error"><?=$commentError;?></span><?php } ?></td>
          </tr>
        </table>
        <div class="btn btn-center"><input type="hidden" name="submitted" value="true" /><button class="btn__link" type="submit">送信する</button></div>
      </form>
	  <?php endwhile; endif; ?>
	  <?php } ?>
      
      </article>
    
    
    </main>
    <!-- /l-main -->
    
	<?php if ( get_option('fit_theme_pageLayout') != 'value2' ):?>
    <!-- l-sidebar -->
      <?php get_sidebar(); ?>
    <!-- /l-sidebar -->
	<?php endif; ?>
    
  </div>
  <!-- /l-wrapper -->


<?php get_footer(); ?>