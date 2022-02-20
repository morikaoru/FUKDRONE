<?php
//////////////////////////////////////////////////
//テーマアップデートチェック
//////////////////////////////////////////////////
require 'theme-update-checker.php';
$example_update_checker = new ThemeUpdateChecker(
	//テーマフォルダ名
	'lionblog', 
	
	 //JSONファイルのURL
	'http://fit-jp.com/update/lionblog-info.json'
);




//////////////////////////////////////////////////
//Original sanitize_callback
//////////////////////////////////////////////////
// CheckBox
function fit_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}
// radio/select
function fit_sanitize_select( $input, $setting ) {
	$input = sanitize_key( $input );
    $choices = $setting->manager->get_control($setting->id)->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
// number limit
function fit_sanitize_number_range( $number, $setting ) {
    $number = absint( $number );
    $atts = $setting->manager->get_control( $setting->id )->input_attrs;
    $min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
    $max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
    $step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
    return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}
// uploader
function fit_sanitize_image( $image, $setting ) {
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
    $file = wp_check_filetype( $image, $mimes );
    return ( $file['ext'] ? $image : $setting->default );
}




//////////////////////////////////////////////////
//カスタマイザー各種設定画面
//////////////////////////////////////////////////
function fit_theme_cutomizer( $wp_customize ) {

	// セクション
	$wp_customize->add_section( 'fit_theme_section', array(
		'title'     => '基本設定 [LION用]',
		'priority'  => 1,
		'description' => '
		<style type="text/css">
		.customize-control-title{color:#0073AA;border-top: #BFBFBF 1px dotted;padding-top: 10px;margin-top: 10px;}
		.customize-control select,
		.customize-control input,
		.customize-control textarea {font-size:12px;}
		.customize-control select,
		.customize-control input[type=number]{width: auto !important;}
		.customize-control + .customize-control-checkbox{margin-top: -12px;}
		</style>',
	));

	// 検索対象 セッティング
	$wp_customize->add_setting( 'fit_theme_search', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 検索対象 コントロール
	$wp_customize->add_control( 'fit_theme_search', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_search',
		'label'     => '■検索機能の検索対象',
		'description' => '検索ボックス利用時の検索対象を選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '固定ページと投稿(default)',
			'value2' => '投稿だけ',
			'value3' => '固定ページだけ',
		),
	));
  
	// アーカイブページ抜粋文字数 セッティング
	$wp_customize->add_setting( 'fit_theme_archiveWord', array(
		'default'   => '200',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_number_range',
	));
	// アーカイブページ抜粋文字数 コントロール
	$wp_customize->add_control( 'fit_theme_archiveWord', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_archiveWord',
		'label'     => '■投稿の抜粋文字数',
		'description' => 'アーカイブページの投稿の抜粋文字数を指定<br>(20～500文字以内)',
		'type'      => 'number',
		'input_attrs' => array(
        	'step'     => '1',
        	'min'      => '20',
        	'max'      => '500',
    	),
	));

	// アーカイブページレイアウト セッティング
	$wp_customize->add_setting( 'fit_theme_archiveLayout', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// アーカイブページレイアウト コントロール
	$wp_customize->add_control( 'fit_theme_archiveLayout', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_archiveLayout',
		'label'     => '■レイアウト設定',
		'description' => 'アーカイブページのレイアウトを選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '2カラム(default)',
			'value2' => '1カラム',
		),
	));


	// 投稿ページレイアウト セッティング
	$wp_customize->add_setting( 'fit_theme_postLayout', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 投稿ページレイアウト コントロール
	$wp_customize->add_control( 'fit_theme_postLayout', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_postLayout',
		'description' => '投稿ページのレイアウトを選択<br>',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '2カラム(default)',
			'value2' => '1カラム',
		),
	));
	
	// 固定ページレイアウト セッティング
	$wp_customize->add_setting( 'fit_theme_pageLayout', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 固定ページレイアウト コントロール
	$wp_customize->add_control( 'fit_theme_pageLayout', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_pageLayout',
		'description' => '固定ページのレイアウトを選択<br>',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '2カラム(default)',
			'value2' => '1カラム',
		),
	));

	//1カラム時の横幅 セッティング
	$wp_customize->add_setting( 'fit_theme_singleWidth', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 1カラム時の横幅 コントロール
	$wp_customize->add_control( 'fit_theme_singleWidth', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_singleWidth',
		'description' => '1カラム時のメインカラムの横幅を選択<br>
		(アーカイブ・投稿・固定ページで適用されます)',
		'type'      => 'select',
		'choices'   => array(
			'value2' => '740px',
			'value1' => '820px(default)',
			'value3' => '900px',
			'value4' => '100%',
		),
	));

	// アーカイブページ記事ビューレイアウト セッティング
	$wp_customize->add_setting( 'fit_theme_articleLayout', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// アーカイブページ記事ビューレイアウト コントロール
	$wp_customize->add_control( 'fit_theme_articleLayout', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_articleLayout',
		'label'     => '■記事ビューレイアウト設定',
		'description' => 'アーカイブページの記事ビューレイアウトを選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => 'ワイド(default)',
			'value2' => '画像レフト',
		),
	));


  
	// 最上部ヘッダーPCスマホ表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_theme_headerArea', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 最上部ヘッダーPCスマホ表示/非表示 コントロール
	$wp_customize->add_control( 'fit_theme_headerArea', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_headerArea',
		'label'     => '■ヘッダー最上部の表示設定',
		'description' => 'ヘッダー最上部の表示/非表示を選択<br>(表示しない場合はサイドバーに検索ボックスを設置することを推薦します)',
		'type'      => 'radio',
		'choices'   => array(
			'value1' => 'PC・スマホ：表示',
			'value2' => 'PC・スマホ：非表示',
			'value3' => 'PCのみ：非表示',
			'value4' => 'スマホのみ：非表示',
		),
	));

	// お知らせヘッダースイッチ セッティング
	$wp_customize->add_setting( 'fit_theme_infoHead', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// お知らせヘッダースイッチ コントロール
	$wp_customize->add_control( 'fit_theme_infoHead', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_infoHead',
		'label'     => '■お知らせヘッダーの設定',
		'label'     => 'お知らせヘッダーの表示選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '非表示(default)',
			'value2' => '表示',
		),
	));
	
	// お知らせヘッダー内容 セッティング
	$wp_customize->add_setting( 'fit_theme_infoHeadText', array(
		'default'   => '',
		'type' => 'option',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
	// お知らせヘッダー内容 コントロール
	$wp_customize->add_control( 'fit_theme_infoHeadText', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_infoHeadText',
		'description' => 'お知らせとして表示する文章を入力',
		'type'      => 'text',
	));
	
	// お知らせヘッダーURL セッティング
	$wp_customize->add_setting( 'fit_theme_infoHeadUrl', array(
		'default'   => '',
		'type' => 'option',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
	// お知らせヘッダーURL コントロール
	$wp_customize->add_control( 'fit_theme_infoHeadUrl', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_infoHeadUrl',
		'description' => 'リンク先URLを入力',
		'type'      => 'text',
	));
	
	// お知らせヘッダーカラー セッティング
	$wp_customize->add_setting( 'fit_theme_infoHeadColor', array(
		'default' => '#c53929',
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	// お知らせヘッダーカラー コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_theme_infoHeadColor', array(
		'section' => 'fit_theme_section',
		'settings' =>'fit_theme_infoHeadColor',
		'description' => '背景色を指定',		
	)));

	
	// カテゴリー・タグ説明の表示位置 セッティング
	$wp_customize->add_setting( 'fit_theme_term', array(
		'default'   => 'value3',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// カテゴリー・タグ説明の表示位置 コントロール
	$wp_customize->add_control( 'fit_theme_term', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_term',
		'label'     => '■カテゴリー・タグ説明の表示設定',
		'description' => 'カテゴリー・タグの説明をどこに表示するか選択',
		'type'      => 'radio',
		'choices'   => array(
			'value1' => 'meta descriptionで表示',
			'value2' => 'アーカイブページで表示',
			'value3' => '両方で表示',
		),
	));


	// コピーライト セッティング
	$wp_customize->add_setting( 'fit_theme_copyright', array(
		'default'   => '© Copyright '.date('Y').' <a class="copyright__link" href="'.home_url().'">'.get_bloginfo('name').'</a>.',
		'type' => 'option',
		'sanitize_callback' => 'wp_kses_post',
	));
	// コピーライト コントロール
	$wp_customize->add_control( 'fit_theme_copyright', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_copyright',
		'label'     => '■Copyrightの設定',
		'description' => 'Copyrightの自由入力<br>
		(未入力の場合は【© Copyright '.date('Y').' '.get_bloginfo('name').'.】が表示されます)<br>
		<br>
		【タグ利用可能】',
		'type'      => 'text',
	));
	
	// コピーライトの下 セッティング
	$wp_customize->add_setting( 'fit_theme_copyrightInfo', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// コピーライトの下 コントロール
	$wp_customize->add_control( 'fit_theme_copyrightInfo', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_copyrightInfo',
		'label'     => 'Copyrightの下に表示されるFITおよびWordPressへのリンクを非表示にする',
		'type'      => 'checkbox',
	));


	//ロゴ画像 セッティング
	$wp_customize->add_setting('fit_theme_image_logo', array(
		'type' => 'theme_mod',
		'sanitize_callback' => 'fit_sanitize_image',
	));
 
	//ロゴ画像 コントロール
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fit_theme_image_logo', array(
		'section' => 'fit_theme_section',
		'settings' => 'fit_theme_image_logo',
		'label' => '■ロゴ画像の設定',
		'description' => 'サイトのロゴ画像を登録<br>
		(高画素密度のディスプレイ表示を考え、縦100 × 440px以内の透過PING画像を指定してください)',
	)));


	//メイン画像 セッティング
	$wp_customize->add_setting('fit_theme_image_main', array(
		'type' => 'theme_mod',
		'sanitize_callback' => 'fit_sanitize_image',
	));
 
	//メイン画像 コントロール
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fit_theme_image_main', array(
		'section' => 'fit_theme_section',
		'settings' => 'fit_theme_image_main',
		'label' => '■メイン画像の設定',
		'description' => 'サイトのメイン画像を登録<br>(縦900 × 横1600px以上の画像を推奨しています)',
	)));

	// メイン画像高さ セッティング
	$wp_customize->add_setting( 'fit_theme_image_main-height', array(
		'default'   => '400',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_number_range',
	));
	// メイン画像高さ コントロール
	$wp_customize->add_control( 'fit_theme_image_main-height', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_image_main-height',
		'description' => 'PC表示時のメイン画像の高さを指定',
		'type'      => 'number',
		'input_attrs' => array(
        	'step'     => '1',
        	'min'      => '200',
        	'max'      => '800',
    	),
	));

	// メイン画像高さ(スマホ) セッティング
	$wp_customize->add_setting( 'fit_theme_image_main-heightSp', array(
		'default'   => '200',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_number_range',
	));
	// メイン画像高さ(スマホ) コントロール
	$wp_customize->add_control( 'fit_theme_image_main-heightSp', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_image_main-heightSp',
		'description' => 'スマホ表示時のメイン画像の高さを指定',
		'type'      => 'number',
		'input_attrs' => array(
        	'step'     => '1',
        	'min'      => '100',
        	'max'      => '400',
    	),
	));

	// メイン画像表示位置 セッティング
	$wp_customize->add_setting( 'fit_theme_image_main-position', array(
		'default'   => 'center',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// メイン画像表示位置 コントロール
	$wp_customize->add_control( 'fit_theme_image_main-position', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_image_main-position',
		'description' => 'メイン画像の表示位置を指定',
		'type'      => 'select',
		'choices'   => array(
			'center' => '中央(default)',
			'top' => '上',
			'bottom' => '下',
		),
	));

	// メイン画像内コピー セッティング
	$wp_customize->add_setting( 'fit_theme_image_main-heading', array(
		'type' => 'option',
		'sanitize_callback' => 'wp_kses_post',
	));
	// メイン画像内コピー コントロール
	$wp_customize->add_control( 'fit_theme_image_main-heading', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_image_main-heading',
		'description' => 'メイン画像内に表示するコンテンツを入力<br>
		<br>
		キャッチコピー【タグ利用可能】',
		'type'      => 'text',
	));

	// メイン画像内本文 セッティング
	$wp_customize->add_setting( 'fit_theme_image_main-text', array(
		'type' => 'option',
		'sanitize_callback' => 'wp_kses_post',
	));
	// メイン画像内本文 コントロール
	$wp_customize->add_control( 'fit_theme_image_main-text', array(
		'section'   => 'fit_theme_section',
		'settings'  => 'fit_theme_image_main-text',
		'description' => 'キャッチコピーの下に表示する文章【タグ利用可能】',
		'type'      => 'text',
	));
	
}
add_action( 'customize_register', 'fit_theme_cutomizer' );
 
//セットした画像のURLを取得
function get_fit_image_logo(){ return esc_url(get_theme_mod('fit_theme_image_logo'));}
function get_fit_image_main(){ return esc_url(get_theme_mod('fit_theme_image_main'));}




//////////////////////////////////////////////////
//SEO設定画面
//////////////////////////////////////////////////
function fit_seo_cutomizer( $wp_customize ) {
	
	if ( get_option( 'show_on_front' ) == 'page' ) {
		$fit_seo_section_desc = '固定ページをTOPページに設定している場合、&lt;title&gt;と&lt;description&gt;の編集は、固定ページから行ってください。';
	}else{
		$fit_seo_section_desc = '';
	}
	// セクション
	$wp_customize->add_section( 'fit_seo_section', array(
		'title'     => 'SEO設定 [LION用]',
		'priority'  => 1,
		'description' => $fit_seo_section_desc,
	));
	
	
	if ( get_option( 'show_on_front' ) != 'page' ) {
	// TOPページの<title> セッティング
	$wp_customize->add_setting( 'fit_seo_titleTop', array(
		'default'   => get_bloginfo( 'description' ) .fit_title_separator() .get_bloginfo( 'name' ),
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	));
	// TOPページの<title> コントロール
	$wp_customize->add_control( 'fit_seo_titleTop', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_titleTop',
		'label' => '■TOPページの&lt;title&gt;',
		'description' => 'TOPページの&lt;title&gt;を入力<br>(未入力の場合は「設定」→「一般」の【キャッチフレーズ │ サイトのタイトル】が表示されます)',
		'type'      => 'text',
	));
	// TOPページの<title>に｜サイト名 セッティング
	$wp_customize->add_setting( 'fit_seo_titleTopName', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// TOPページの<title>に｜サイト名 コントロール
	$wp_customize->add_control( 'fit_seo_titleTopName', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_titleTopName',
		'label'     => '「'.fit_title_separator().' '.get_bloginfo( 'name' ).'」を表示する',
		'type'      => 'checkbox',
	));
	
	// TOPページの<meta description> セッティング
	$wp_customize->add_setting( 'fit_seo_descriptionTop', array(
		'default'   => get_bloginfo( 'description' ),
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	));
	// TOPページの<meta description> コントロール
	$wp_customize->add_control( 'fit_seo_descriptionTop', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_descriptionTop',
		'label' => '■TOPページの&lt;meta description&gt;',
		'description' => 'TOPページの&lt;meta  description&gt;を入力',
		'type'      => 'textarea',
	));
	}
	
	// CSS非同期読み込み セッティング
	$wp_customize->add_setting( 'fit_seo_cssLoad', array(
		'default'   => 'value1',
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// CSS非同期読み込み コントロール
	$wp_customize->add_control( 'fit_seo_cssLoad', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_cssLoad',
		'label'     => '■CSS非同期読込設定',
		'description' => 'CSSの非同期読み込みを有効化するか選択<br>
		（CSS非同期読み込みを有効化するとページの読み込み速度が向上する代わりに、一瞬デザインが崩れて見えることがあります。※有効にするとfooterに一行JavaScript記述）<br>
		<br>
		※無効にする場合は下記のチェック項目をすべてOFFにしてください。',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '無効(default)',
			'value2' => '有効',
		),
	));
	
	// メインCSS セッティング
	$wp_customize->add_setting( 'fit_seo_cssLoad-main', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// メインCSS コントロール
	$wp_customize->add_control( 'fit_seo_cssLoad-main', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_cssLoad-main',
		'label'     => 'メインCSS(style.css)を非同期読み込みする',
		'type'      => 'checkbox',
	));
	
	// 投稿・固定ページ用CSS セッティング
	$wp_customize->add_setting( 'fit_seo_cssLoad-content', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// 投稿・固定ページ用CSS コントロール
	$wp_customize->add_control( 'fit_seo_cssLoad-content', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_cssLoad-content',
		'label'     => '投稿・固定ページ用CSS(content.css)を非同期読み込みする',
		'type'      => 'checkbox',
	));
	
	// アイコンフォントCSS セッティング
	$wp_customize->add_setting( 'fit_seo_cssLoad-icon', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// アイコンフォントCSS コントロール
	$wp_customize->add_control( 'fit_seo_cssLoad-icon', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_cssLoad-icon',
		'label'     => 'アイコンフォントCSS(icon.css)を非同期読み込みする',
		'type'      => 'checkbox',
	));
	
	// GoogleフォントCSS セッティング
	$wp_customize->add_setting( 'fit_seo_cssLoad-lato', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// GoogleフォントCSS コントロール
	$wp_customize->add_control( 'fit_seo_cssLoad-lato', array(
		'section'   => 'fit_seo_section',
		'settings'  => 'fit_seo_cssLoad-lato',
		'label'     => 'GoogleフォントCSS(Lato)を非同期読み込みする',
		'type'      => 'checkbox',
	));
	
}
add_action( 'customize_register', 'fit_seo_cutomizer' );




//////////////////////////////////////////////////
//AMP機能設定画面
//////////////////////////////////////////////////
function fit_amp_cutomizer( $wp_customize ) {
	
	// セクション
	$wp_customize->add_section( 'fit_amp_section', array(
		'title'     => 'AMP設定 [LION用]',
		'priority'  => 1,
		'description' => 'AMPとは、GoogleとTwitterで共同開発されているモバイル端末でウェブページを高速表示するためのフレームワーク(AMP HTML)のことです。<br>
		AMPを適用させるには、AMPが定める厳格なマークアップルールに従う必要があります。記述に誤りがあると機能を有効化してもページによってはエラーとなる可能性があります。',
	));
  
	// AMP機能 セッティング
	$wp_customize->add_setting( 'fit_anp_check', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// AMP機能 コントロール
	$wp_customize->add_control( 'fit_anp_check', array(
		'section'   => 'fit_amp_section',
		'settings'  => 'fit_anp_check',
		'label'     => '■AMP機能',
		'description' => 'AMP機能を有効化するか選択<br>
		（有効にした場合、投稿ページのURLの後に「?amp=1」と入力すると、AMP用ページが表示されます。※パーマリンク設定が【基本(?p=123)】の場合は「&amp;amp=1」）',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '無効(default)',
			'value2' => '有効',
		),
	));

	// AMP検索機能 セッティング
	$wp_customize->add_setting( 'fit_anp_search', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// AMP検索機能 コントロール
	$wp_customize->add_control( 'fit_anp_search', array(
		'section'   => 'fit_amp_section',
		'settings'  => 'fit_anp_search',
		'label'     => '■AMP検索機能',
		'description' => 'AMPページで検索機能を有効化するか選択<br>
		（検索機能を有効化させるには、https(暗号化通信)サイトである必要があります。※http(通常通信)サイトではエラーとなるため有効化不可）',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '無効(default)',
			'value2' => '有効',
		),
	));
  
	//AMPロゴ画像 セッティング
	$wp_customize->add_setting('fit_anp_logo', array(
		'type' => 'theme_mod',
		'sanitize_callback' => 'fit_sanitize_image',
	));
	//AMPロゴ画像 コントロール
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fit_anp_logo', array(
		'section' => 'fit_amp_section',
		'settings' => 'fit_anp_logo',
		'label' => '■AMP用ロゴ画像の設定',
		'description' => '縦60 × 600px以内で制作した画像を登録<br>
		(AMP機能を有効にする場合は必ず登録してください。※指定サイズを超えた場合、AMP機能がエラーとなる可能性有り)',
	)));
		
}
add_action( 'customize_register', 'fit_amp_cutomizer' );
 
//セットした画像のURLを取得
function get_fit_amp_logo(){ return esc_url(get_theme_mod('fit_anp_logo'));}




//////////////////////////////////////////////////
//広告設定画面
//////////////////////////////////////////////////
function fit_ad_cutomizer( $wp_customize ) {

	// セクション
	$wp_customize->add_section( 'fit_ad_section', array(
		'title'     => '広告設定 [LION用]',
		'priority'  => 1,
		'description' => '記事の上、記事の下、またはサイドバーに広告を設置したい場合は、ウィジェットをご利用ください。<br>
		※広告表示タグが登録できない場合は、WAFの設定を確認してください',
	));
  
	// ヘッダーAD セッティング
	$wp_customize->add_setting( 'fit_ad_header', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// ヘッダーAD コントロール
	$wp_customize->add_control( 'fit_ad_header', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_header',
		'label'     => '■ヘッダー用広告',
		'description' => 'AdSense等の広告表示タグを入力<br>
		(レスポンシブ広告推薦)',
		'type'      => 'textarea',
	));

	// ヘッダーADスマホ表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_ad_headerCheck', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// ヘッダーADスマホ表示/非表示 コントロール
	$wp_customize->add_control( 'fit_ad_headerCheck', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_headerCheck',
		'label'     => 'ヘッダー用広告をスマホで非表示にする',
		'type'      => 'checkbox',
	));

	// 記事内広告 セッティング
	$wp_customize->add_setting( 'fit_ad_post', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// 記事内広告 コントロール
	$wp_customize->add_control( 'fit_ad_post', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_post',
		'label'     => '■記事内広告',
		'description' => 'AdSense等の広告表示タグを入力<br>
		(記事に[adsense]と記入すると、投稿内にAdSense等の広告を表示できます)',
		'type'      => 'textarea',
	));
	
	// アーカイブインフィード広告 セッティング
	$wp_customize->add_setting( 'fit_ad_infeed', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// アーカイブインフィード広告 コントロール
	$wp_customize->add_control( 'fit_ad_infeed', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_infeed',
		'label'     => '■アーカイブ用インフィード広告',
		'description' => 'AdSense等のインフィード広告表示タグを入力',
		'type'      => 'textarea',
	));
	
	// アーカイブインフィード広告表示順位 セッティング
	$wp_customize->add_setting( 'fit_ad_infeedNumber', array(
		'default'   => '1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_number_range',
	));
	// アーカイブインフィード広告表示順位 コントロール
	$wp_customize->add_control( 'fit_ad_infeedNumber', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_infeedNumber',
		'description' => '何番目に広告を表示するか指定',
		'type'      => 'number',
		'input_attrs' => array(
        	'step'     => '1',
        	'min'      => '1',
    	),
	));

	// アーカイブインフィード広告表示制限 セッティング
	$wp_customize->add_setting( 'fit_ad_infeed1p', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// アーカイブインフィード広告表示制限 コントロール
	$wp_customize->add_control( 'fit_ad_infeed1p', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_infeed1p',
		'label'     => '1ページ目のみ表示する',
		'type'      => 'checkbox',
	));

	// 記事下用ダブル広告(左) セッティング
	$wp_customize->add_setting( 'fit_ad_doubleLeft', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// 記事下用ダブル広告(左) コントロール
	$wp_customize->add_control( 'fit_ad_doubleLeft', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_doubleLeft',
		'label'     => '■記事下用ダブル広告',
		'description' => '左に表示する広告',
		'type'      => 'textarea',
	));
	// 記事下用ダブル広告(右) セッティング
	$wp_customize->add_setting( 'fit_ad_doubleRight', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// 記事下用ダブル広告(右) コントロール
	$wp_customize->add_control( 'fit_ad_doubleRight', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_doubleRight',
		'description' => '右に表示する広告(スマホ非表示)',
		'type'      => 'textarea',
	));


	// AMP用記事上広告 セッティング
	$wp_customize->add_setting( 'fit_ad_postTop', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// AMP用記事上広告 コントロール
	$wp_customize->add_control( 'fit_ad_postTop', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_postTop',
		'label'     => '■AMP用広告',
		'description' => 'AMPページで表示する広告タグを入力<br>
		入力例：<br>
		&lt;amp-ad<br>
		width="300"<br>
		height="250"<br>
		type="adsense"<br>
		data-ad-client="ca-pub-0000000000"<br>
		data-ad-slot="0000000000"&gt;<br>
		&lt;/amp-ad&gt;<br>
		<br>
		記事本文の上に表示する広告',
		'type'      => 'textarea',
	));
  
	// AMP用記事下広告 セッティング
	$wp_customize->add_setting( 'fit_ad_postBottom', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// AMP用記事下広告 コントロール
	$wp_customize->add_control( 'fit_ad_postBottom', array(
		'section'   => 'fit_ad_section',
		'settings'  => 'fit_ad_postBottom',
		'description' => '記事本文の下に表示する広告',
		'type'      => 'textarea',
	));
	  
}
add_action( 'customize_register', 'fit_ad_cutomizer' );



  
//////////////////////////////////////////////////
//投稿ページ各種設定画面
//////////////////////////////////////////////////
function fit_post_cutomizer( $wp_customize ) {

	// セクション
	$wp_customize->add_section( 'fit_post_section', array(
		'title'     => '投稿ページ設定 [LION用]',
		'priority'  => 1,
	));
  
	// アイキャッチ画像の表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_eyecatch', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// アイキャッチ画像の表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_eyecatch', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_eyecatch',
		'label'     => '■アイキャッチ画像の表示/非表示',
		'description' => '投稿・アーカイブページにアイキャッチ画像を表示するか選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));
	
	// 投稿日の表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_time', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 投稿日の表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_time', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_time',
		'label'     => '■投稿日の表示/非表示',
		'description' => '投稿・アーカイブページに投稿日を表示するか選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));
  
	// 投稿者情報の表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_poster', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 投稿者情報の表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_poster', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_poster',
		'label'     => '■投稿者情報の表示/非表示',
		'description' => '投稿ページに投稿者情報を表示するか選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));
	
	// 目次の表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_outline', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 目次の表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_outline', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_outline',
		'label'     => '■目次の表示/非表示',
		'description' => '投稿ページに目次を表示するか選択<br>
		(記事内の最初のhタグの手前に自動で挿入されます。※[outline]ショートコードで好きな位置に表示可能)',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));
	
	// 目次を表示するための最小見出し数 セッティング
	$wp_customize->add_setting( 'fit_post_outline_number', array(
		'default'   => '1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_number_range',
	));
	// 目次を表示するための最小見出し数 コントロール
	$wp_customize->add_control( 'fit_post_outline_number', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_outline_number',
		'description' => '目次を表示するための最小見出し数を指定',
		'type'      => 'number',
		'input_attrs' => array(
        	'step'     => '1',
        	'min'      => '1',
        	'max'      => '50',
    	),
	));

	// 目次パネルデフォルト設定 セッティング
	$wp_customize->add_setting('fit_post_outline_close', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	// 目次パネルデフォルト設定 コントロール
	$wp_customize->add_control('fit_post_outline_close', array( 
        'section' => 'fit_post_section', 
        'settings' => 'fit_post_outline_close', 
        'label'     => '目次パネルをデフォルトで閉じておく',
        'type'      => 'checkbox',
    ));
	
	
	// 前次記事の表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_prevNext', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 前次記事の表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_prevNext', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_prevNext',
		'label'     => '■前次記事の表示/非表示',
		'description' => '投稿ページに前次記事を表示するか選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));

	// 前次記事のカテゴリ限定機能 セッティング
	$wp_customize->add_setting( 'fit_post_prevNextCategory', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
	));
	// 前次記事のカテゴリ限定機能 コントロール
	$wp_customize->add_control( 'fit_post_prevNextCategory', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_prevNextCategory',
		'label'     => '同一カテゴリの前次記事のみを表示する',
		'type'      => 'checkbox',
	));

	// 関連記事の表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_related', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 関連記事の表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_related', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_related',
		'label'     => '■関連記事の表示/非表示',
		'description' => '投稿ページに関連記事を表示するか選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));

	// 関連記事の表示最大数 セッティング
	$wp_customize->add_setting( 'fit_post_relatedNumber', array(
		'default'   => '3',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_number_range',
	));
	// 関連記事の表示最大数 コントロール
	$wp_customize->add_control( 'fit_post_relatedNumber', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_relatedNumber',
		'description' => '関連記事を表示する時の最大数を指定',
		'type'      => 'number',
		'input_attrs' => array(
        	'step'     => '1',
        	'min'      => '1',
        	'max'      => '10',
    	),
	));

	// 上部シェアボタンの表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_shareTop', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 上部シェアボタンの表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_shareTop', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_shareTop',
		'label'     => '■シェアボタンの表示/非表示',
		'description' => '投稿ページの上部にシェアボタンを表示するか選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));

	// 下部シェアボタンの表示/非表示 セッティング
	$wp_customize->add_setting( 'fit_post_shareBottom', array(
		'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
	));
	// 下部シェアボタンの表示/非表示 コントロール
	$wp_customize->add_control( 'fit_post_shareBottom', array(
		'section'   => 'fit_post_section',
		'settings'  => 'fit_post_shareBottom',
		'description' => '投稿ページの下部にシェアボタンを表示するか選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示する(default)',
			'value2' => '表示しない',
		),
	));

	//Facebookセッティング
	$wp_customize->add_setting('fit_post_share[facebook]', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	//Facebookコントロール
	$wp_customize->add_control('fit_post_share_facebook', array( 
        'section' => 'fit_post_section', 
        'settings' => 'fit_post_share[facebook]', 
        'label'     => 'Facebookボタンを表示する',
        'type'      => 'checkbox',
    ));
	
	//Twitterセッティング
	$wp_customize->add_setting('fit_post_share[twitter]', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	//Twitterコントロール
	$wp_customize->add_control('fit_post_share_twitter', array( 
        'section' => 'fit_post_section', 
        'settings' => 'fit_post_share[twitter]', 
        'label'     => 'Twitterボタンを表示する',
        'type'      => 'checkbox',
    ));

	//Google+セッティング
	$wp_customize->add_setting('fit_post_share[google]', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	//Google+コントロール
	$wp_customize->add_control('fit_post_share_google', array( 
        'section' => 'fit_post_section', 
        'settings' => 'fit_post_share[google]', 
        'label'     => 'Google+ボタンを表示する',
        'type'      => 'checkbox',
    ));

	//はてぶセッティング
	$wp_customize->add_setting('fit_post_share[hatebu]', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	//はてぶコントロール
	$wp_customize->add_control('fit_post_share_hatebu', array( 
        'section' => 'fit_post_section', 
        'settings' => 'fit_post_share[hatebu]', 
        'label'     => 'はてぶボタンを表示する',
        'type'      => 'checkbox',
    ));

	//Pocketセッティング
	$wp_customize->add_setting('fit_post_share[pocket]', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	//Pocketコントロール
	$wp_customize->add_control('fit_post_share_pocket', array( 
        'section' => 'fit_post_section', 
        'settings' => 'fit_post_share[pocket]', 
        'label'     => 'Pocketボタンを表示する',
        'type'      => 'checkbox',
    ));
	
	//LINEセッティング
	$wp_customize->add_setting('fit_post_share[line]', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	//LINEコントロール
	$wp_customize->add_control('fit_post_share_line', array( 
        'section' => 'fit_post_section', 
        'settings' => 'fit_post_share[line]', 
        'label'     => 'LINEボタンを表示する',
        'type'      => 'checkbox',
    ));

}
add_action( 'customize_register', 'fit_post_cutomizer' );




//////////////////////////////////////////////////
//SNS・OGP設定画面
//////////////////////////////////////////////////
function fit_social_cutomizer( $wp_customize ) {
  
    // セクション
    $wp_customize->add_section( 'fit_social_section', array(
        'title'     => 'SNS・OGP設定 [LION用]',
        'priority'  => 1,
    ));

    //OGP画像 セッティング
    $wp_customize->add_setting('fit_social_image_ogp', array(
        'type' => 'theme_mod',
		'transport' => 'postMessage',
		'sanitize_callback' => 'fit_sanitize_image',
    ));
 
    //OGP画像 コントロール
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fit_social_image_ogp', array(
        'section' => 'fit_social_section',
        'settings' => 'fit_social_image_ogp',
        'label' => '■[OGP]画像の設定',
        'description' => '投稿にアイキャッチ画像が登録されていない時に表示する画像<br>
		（縦600 × 横1200px以上の画像を登録してください）',
    )));
    
    // FacebookAPPID セッティング
    $wp_customize->add_setting( 'fit_social_FBAppId', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // FacebookAPPID コントロール
    $wp_customize->add_control( 'fit_social_FBAppId', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social_FBAppId',
        'label'     => '■[OGP]FacebookのAPPID',
        'description' => 'FacebookのApp IDを記入',
        'type'      => 'text',
    ));

    // FacebookAdmins セッティング
    $wp_customize->add_setting( 'fit_social_FBAdmins', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // FacebookAdmins コントロール
    $wp_customize->add_control( 'fit_social_FBAdmins', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social_FBAdmins',
        'label'     => '■[OGP]FacebookのユーザーID',
        'description' => 'FacebookのユーザーIDを記入<br>
		(App IDを利用している場合は未入力で構いません)',
        'type'      => 'text',
    ));

    // TwitterCard セッティング
    $wp_customize->add_setting( 'fit_social_TwitterCard', array(
        'default'   => 'summary',
		'type' => 'option',
        'transport' => 'postMessage',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
    // TwitterCard コントロール
    $wp_customize->add_control( 'fit_social_TwitterCard', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social_TwitterCard',
        'label'     => '■[OGP]Twitter Cardの種類を選択',
        'description' => 'Twitterで記事がシェアされた時のカードデザインを選択',
        'type'      => 'select',
        'choices'   => array(
            'summary' => 'Summaryカード(default)',
            'summary_large_image' => 'Summary with Large Imageカード',
        ),
    ));

    // Facebookページユーザー名 セッティング
    $wp_customize->add_setting( 'fit_social[FBPage]', array(
		'type' => 'option',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // Facebookページユーザー名 コントロール
    $wp_customize->add_control( 'fit_social_FBPage', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[FBPage]',
        'label'     => '■[FOLLOW]Facebookページのユーザー名',
        'description' => 'FacebookページのURLが「https://www.facebook.com/examples/」の場合、「examples」だけを入力',
        'type'      => 'text',
    ));

    // Facebookフォローアイコンの表示H セッティング
    $wp_customize->add_setting( 'fit_social[FBFollowH]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Facebookフォローアイコンの表示H コントロール
    $wp_customize->add_control( 'fit_social_FBFollowH', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[FBFollowH]',
        'label' => 'Headerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
    // Facebookフォローアイコンの表示F セッティング
    $wp_customize->add_setting( 'fit_social[FBFollowF]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Facebookフォローアイコンの表示F コントロール
    $wp_customize->add_control( 'fit_social_FBFollowF', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[FBFollowF]',
        'label' => 'Footerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));	

    // Instagramページユーザー名 セッティング
    $wp_customize->add_setting( 'fit_social[insta]', array(
		'type' => 'option',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // Instagramページユーザー名 コントロール
    $wp_customize->add_control( 'fit_social_insta', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[insta]',
        'label'     => '■[FOLLOW]Instagramページのユーザー名',
        'description' => 'InstagramページのURLが「http://instagram.com/examples」の場合、「examples」だけを入力',
        'type'      => 'text',
    ));

    // Instagramフォローアイコンの表示H セッティング
    $wp_customize->add_setting( 'fit_social[instaFollowH]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Instagramフォローアイコンの表示H コントロール
    $wp_customize->add_control( 'fit_social_instaFollowH', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[instaFollowH]',
        'label' => 'Headerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
    // Instagramフォローアイコンの表示F セッティング
    $wp_customize->add_setting( 'fit_social[instaFollowF]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Instagramフォローアイコンの表示F コントロール
    $wp_customize->add_control( 'fit_social_instaFollowF', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[instaFollowF]',
        'label' => 'Footerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
		  
    // TwitterID セッティング
    $wp_customize->add_setting( 'fit_social[twitterId]', array(
		'type' => 'option',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // TwitterID コントロール
    $wp_customize->add_control( 'fit_social_twitterId', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[twitterId]',
        'label'     => '■[FOLLOW]TwitterのID(@以降)',
        'description' => 'TwitterのマイページのURLが「https://twitter.com/examples」の場合、「examples」だけを入力',
        'type'      => 'text',
    ));

    // Twitterフォローアイコンの表示H セッティング
    $wp_customize->add_setting( 'fit_social[twitterFollowH]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Twitterフォローアイコンの表示H コントロール
    $wp_customize->add_control( 'fit_social_twitterFollowH', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[twitterFollowH]',
        'label' => 'Headerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
    // Twitterフォローアイコンの表示F セッティング
    $wp_customize->add_setting( 'fit_social[twitterFollowF]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Twitterフォローアイコンの表示F コントロール
    $wp_customize->add_control( 'fit_social_twitterFollowF', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[twitterFollowF]',
        'label' => 'Footerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));

    // Google+ページカスタムURL セッティング
    $wp_customize->add_setting( 'fit_social[googleUrl]', array(
		'type' => 'option',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // Google+ページカスタムURL コントロール
    $wp_customize->add_control( 'fit_social_googleUrl', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[googleUrl]',
        'label'     => '■[FOLLOW]Google+ページのURL(+以降)',
        'description' => 'Google+ページのURLが「https://plus.google.com/+Examples」の場合、「Examples」だけを入力',
        'type'      => 'text',
    ));

    // Google+フォローアイコンの表示H セッティング
    $wp_customize->add_setting( 'fit_social[googleFollowH]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Google+フォローアイコンの表示H コントロール
    $wp_customize->add_control( 'fit_social_googleFollowH', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[googleFollowH]',
        'label' => 'Headerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
    // Google+フォローアイコンの表示F セッティング
    $wp_customize->add_setting( 'fit_social[googleFollowF]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // Google+フォローアイコンの表示F コントロール
    $wp_customize->add_control( 'fit_social_googleFollowF', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[googleFollowF]',
        'label' => 'Footerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
	
	// RSSページURL セッティング
    $wp_customize->add_setting( 'fit_social[rssUrl]', array(
		'type' => 'option',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // RSSページURL コントロール
    $wp_customize->add_control( 'fit_social_rssUrl', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[rssUrl]',
        'label'     => '■[FOLLOW]RSSページのURL',
        'description' => '未入力の場合は[bloginfo(rss2_url)]を表示。',
        'type'      => 'text',
    ));

    // RSSフォローアイコンの表示H セッティング
    $wp_customize->add_setting( 'fit_social[rssFollowH]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // RSSフォローアイコンの表示H コントロール
    $wp_customize->add_control( 'fit_social_rssFollowH', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[rssFollowH]',
        'label' => 'Headerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
    // RSSフォローアイコンの表示F セッティング
    $wp_customize->add_setting( 'fit_social[rssFollowF]', array(
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
    // RSSフォローアイコンの表示F コントロール
    $wp_customize->add_control( 'fit_social_rssFollowF', array(
        'section'   => 'fit_social_section',
        'settings'  => 'fit_social[rssFollowF]',
        'label' => 'Footerでフォローアイコンを表示',
        'type'      => 'checkbox',
    ));
  
}
add_action( 'customize_register', 'fit_social_cutomizer' );
 
//セットした画像のURLを取得
function get_fit_image_ogp() { return esc_url(get_theme_mod('fit_social_image_ogp'));}




//////////////////////////////////////////////////
//アクセス解析設定画面
//////////////////////////////////////////////////
function fit_access_cutomizer( $wp_customize ) {

    // セクション
    $wp_customize->add_section( 'fit_access_section', array(
        'title'     => 'アクセス解析設定 [LION用]',
        'priority'  => 1,
    ));
  
    // Google AnalyticsのトラッキングID セッティング
    $wp_customize->add_setting( 'fit_access_gaid', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // Google AnalyticsのトラッキングID コントロール
    $wp_customize->add_control( 'fit_access_gaid', array(
        'section'   => 'fit_access_section',
        'settings'  => 'fit_access_gaid',
        'label'     => '■Google AnalyticsのトラッキングID',
        'description' => 'Google AnalyticsのトラッキングIDを入力<br>入力例：UA-11111111-1',
        'type'      => 'text',
    ));

    // Google AnalyticsのAMP用トラッキングID セッティング
    $wp_customize->add_setting( 'fit_access_ampgaid', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // Google AnalyticsのAMP用トラッキングID コントロール
    $wp_customize->add_control( 'fit_access_ampgaid', array(
        'section'   => 'fit_access_section',
        'settings'  => 'fit_access_ampgaid',
        'label'     => '■AMP用のGoogle AnalyticsのトラッキングID',
        'description' => 'AMP用のGoogle AnalyticsのトラッキングIDを入力<br>
		(AMP用のトラッキングIDは上記で入力したものと同じでも構いませんが、別々に設定することが推奨されています)<br>
		入力例：UA-22222222-2',
        'type'      => 'text',
    ));
  
    // Google Search Consoleの認証ID セッティング
    $wp_customize->add_setting( 'fit_access_gscid', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // Google Search Consoleの認証ID コントロール
    $wp_customize->add_control( 'fit_access_gscid', array(
        'section'   => 'fit_access_section',
        'settings'  => 'fit_access_gscid',
        'label'     => '■Google Search Consoleの認証ID',
        'description' => 'Google Search Consoleの認証IDを入力<br>(&lt;meta name="google-site-verification" content="**********" /&gt;の「**********」だけを入力してください)',
        'type'      => 'text',
    ));

}
add_action( 'customize_register', 'fit_access_cutomizer' );





//////////////////////////////////////////////////
//CTA設定画面
//////////////////////////////////////////////////
function fit_cta_cutomizer( $wp_customize ) {

    // セクション
    $wp_customize->add_section( 'fit_cta_section', array(
        'title'     => 'CTA設定 [LION用]',
        'priority'  => 1,
    ));
  
    // 記事下CTAボックス表示/非表示 セッティング
    $wp_customize->add_setting( 'fit_cta_postBox', array(
        'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
    // 記事下CTAボックス表示/非表示 コントロール
    $wp_customize->add_control( 'fit_cta_postBox', array(
        'section'   => 'fit_cta_section',
		'settings'  => 'fit_cta_postBox',
		'label'     => '■記事下CTAボックス設定',
		'description' => '記事下CTAボックスの表示/非表示を選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '表示しない(default)',
			'value2' => '表示する',
		),
    ));
	
	// 記事下CTAボックスのタイトル セッティング
    $wp_customize->add_setting( 'fit_cta_postTitle', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 記事下CTAボックスのタイトル コントロール
    $wp_customize->add_control( 'fit_cta_postTitle', array(
        'section'   => 'fit_cta_section',
        'settings'  => 'fit_cta_postTitle',
        'description' => '記事下CTAボックスのタイトルを入力',
        'type'      => 'text',
    ));

	// 記事下CTAボックスの本文 セッティング
    $wp_customize->add_setting( 'fit_cta_postContents', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_kses_post',
    ));
    // 記事下CTAボックスの本文 コントロール
    $wp_customize->add_control( 'fit_cta_postContents', array(
        'section'   => 'fit_cta_section',
        'settings'  => 'fit_cta_postContents',
        'description' => '記事下CTAボックスの本文を入力<br>
		【タグ利用可能】',
        'type'      => 'textarea',
    ));

    // 記事下CTAボックスのボタン設定 セッティング
    $wp_customize->add_setting( 'fit_cta_postBtn', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 記事下CTAボックスのボタン設定 コントロール
    $wp_customize->add_control( 'fit_cta_postBtn', array(
        'section'   => 'fit_cta_section',
        'settings'  => 'fit_cta_postBtn',
        'description' => 'ボタン上に表示するリンクテキストを入力',
        'type'      => 'text',
    ));

    // 記事下CTAボックスのリンク設定 セッティング
    $wp_customize->add_setting( 'fit_cta_postUrl', array(
        'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 記事下CTAボックスのリンク設定 コントロール
    $wp_customize->add_control( 'fit_cta_postUrl', array(
        'section'   => 'fit_cta_section',
        'settings'  => 'fit_cta_postUrl',
        'description' => 'リンク先URLを入力',
        'type'      => 'text',
    ));
	
	//記事下CTAボックスの画像 セッティング
	$wp_customize->add_setting('fit_cta_postImg', array(
		'type' => 'theme_mod',
		'sanitize_callback' => 'fit_sanitize_image',
	));
 
	//記事下CTAボックスの画像 コントロール
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fit_cta_postImg', array(
		'section' => 'fit_cta_section',
		'settings' => 'fit_cta_postImg',
		'description' => '記事下CTAボックスの画像を選択',
	)));
	
	// 記事下CTAボックスの画像の表示位置(PC) セッティング
    $wp_customize->add_setting( 'fit_cta_postImgPc', array(
        'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
    // 記事下CTAボックスの画像の表示位置(PC) コントロール
    $wp_customize->add_control( 'fit_cta_postImgPc', array(
        'section'   => 'fit_cta_section',
		'settings'  => 'fit_cta_postImgPc',
		'description' => 'PC表示時の画像の表示位置を選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '右(default)',
			'value2' => '中央',
			'value3' => '左',
		),
    ));
	
	// 記事下CTAボックスの画像の表示位置(スマホ) セッティング
    $wp_customize->add_setting( 'fit_cta_postImgSp', array(
        'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
    // 記事下CTAボックスの画像の表示位置(スマホ) コントロール
    $wp_customize->add_control( 'fit_cta_postImgSp', array(
        'section'   => 'fit_cta_section',
		'settings'  => 'fit_cta_postImgSp',
		'description' => 'スマホ表示時の画像の表示位置を選択',
		'type'      => 'select',
		'choices'   => array(
			'value1' => '右(default)',
			'value2' => '中央',
			'value3' => '左',
		),
    ));


}
add_action( 'customize_register', 'fit_cta_cutomizer' );

//セットした画像のURLを取得
function get_fit_cta_postImg(){ return esc_url(get_theme_mod('fit_cta_postImg'));}




//////////////////////////////////////////////////
//高度な設定画面
//////////////////////////////////////////////////
function fit_advanced_cutomizer( $wp_customize ) {

	// セクション
	$wp_customize->add_section( 'fit_advanced_section', array(
		'title'     => '高度な設定 [LION用]',
		'priority'  => 1,
	));
  
	// ヘッダー自由入力エリア セッティング
	$wp_customize->add_setting( 'fit_advanced_head', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// ヘッダー自由入力エリア コントロール
	$wp_customize->add_control( 'fit_advanced_head', array(
		'section'   => 'fit_advanced_section',
		'settings'  => 'fit_advanced_head',
		'label'     => '■&lt;/head&gt;直上の自由入力エリア',
		'description' => '&lt;head&gt;～&lt;/head&gt;内用の自由入力エリア(CSSなどの読み込みに最適)',
		'type'      => 'textarea',
	));

	// フッター自由入力エリア セッティング
	$wp_customize->add_setting( 'fit_advanced_foot', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// フッター自由入力エリア コントロール
	$wp_customize->add_control( 'fit_advanced_foot', array(
		'section'   => 'fit_advanced_section',
		'settings'  => 'fit_advanced_foot',
		'label'     => '■&lt;/body&gt;直上の自由入力エリア',
		'description' => '&lt;body&gt;～&lt;/body&gt;内用の自由入力エリア(JavaScriptなどの読み込みに最適)',
		'type'      => 'textarea',
	));
	
	
	
	
	// カテゴリーアーカイブの前半タイトル セッティング
    $wp_customize->add_setting( 'fit_advanced_archive[category]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // カテゴリーアーカイブの前半タイトル コントロール
    $wp_customize->add_control( 'fit_advanced_archive_category', array(
        'section'   => 'fit_advanced_section',
        'settings'  => 'fit_advanced_archive[category]',
		'label'     => '■アーカイブページの&lt;title&gt;設定',
        'description' => 'アーカイブページのタイトルの前半部分を必要に応じてカスタマイズできます。<br>
		【入力例】［カテゴリー：］<br>
		【出力結果例】［カテゴリー：未分類］<br>
		<br>
		カテゴリーアーカイブの前半タイトルを入力',
        'type'      => 'text',
    ));
	
	// タグアーカイブの前半タイトル セッティング
    $wp_customize->add_setting( 'fit_advanced_archive[tag]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // タグアーカイブの前半タイトル コントロール
    $wp_customize->add_control( 'fit_advanced_archive_tag', array(
        'section'   => 'fit_advanced_section',
        'settings'  => 'fit_advanced_archive[tag]',
        'description' => 'タグアーカイブの前半タイトルを入力',
        'type'      => 'text',
    ));
	
	// 投稿者アーカイブの前半タイトル セッティング
    $wp_customize->add_setting( 'fit_advanced_archive[author]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 投稿者アーカイブの前半タイトル コントロール
    $wp_customize->add_control( 'fit_advanced_archive_author', array(
        'section'   => 'fit_advanced_section',
        'settings'  => 'fit_advanced_archive[author]',
        'description' => '投稿者アーカイブの前半タイトルを入力',
        'type'      => 'text',
    ));

	// 年別アーカイブの前半タイトル セッティング
    $wp_customize->add_setting( 'fit_advanced_archive[year]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 年別アーカイブの前半タイトル コントロール
    $wp_customize->add_control( 'fit_advanced_archive_year', array(
        'section'   => 'fit_advanced_section',
        'settings'  => 'fit_advanced_archive[year]',
        'description' => '年別アーカイブの前半タイトルを入力',
        'type'      => 'text',
    ));

	// 月別アーカイブの前半タイトル セッティング
    $wp_customize->add_setting( 'fit_advanced_archive[month]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 月別アーカイブの前半タイトル コントロール
    $wp_customize->add_control( 'fit_advanced_archive_month', array(
        'section'   => 'fit_advanced_section',
        'settings'  => 'fit_advanced_archive[month]',
        'description' => '月別アーカイブの前半タイトルを入力',
        'type'      => 'text',
    ));

	// 日別アーカイブの前半タイトル セッティング
    $wp_customize->add_setting( 'fit_advanced_archive[day]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 日別アーカイブの前半タイトル コントロール
    $wp_customize->add_control( 'fit_advanced_archive_day', array(
        'section'   => 'fit_advanced_section',
        'settings'  => 'fit_advanced_archive[day]',
        'description' => '日別アーカイブの前半タイトルを入力',
        'type'      => 'text',
    ));

	// 検索アーカイブの前半タイトル セッティング
    $wp_customize->add_setting( 'fit_advanced_archive[search]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    // 検索アーカイブの前半タイトル コントロール
    $wp_customize->add_control( 'fit_advanced_archive_search', array(
        'section'   => 'fit_advanced_section',
        'settings'  => 'fit_advanced_archive[search]',
        'description' => '検索アーカイブの前半タイトルを入力',
        'type'      => 'text',
    ));


}
add_action( 'customize_register', 'fit_advanced_cutomizer' );




//////////////////////////////////////////////////
//投稿スキン設定画面
//////////////////////////////////////////////////
function fit_hskin_cutomizer($wp_customize){
    //セクション
	$wp_customize->add_section( 'fit_hskin_section', array( 
        'title' => '投稿スキン設定 [LION用]', 
        'priority' => 1, 
    ));
	
	if ( get_option('fit_skin_theme') == 'value1' ) {$defaultColor = get_theme_mod('fit_skin_pick');
	}elseif( get_option('fit_skin_theme') == 'value2' ) {$defaultColor = '#0E88BE';
	}elseif( get_option('fit_skin_theme') == 'value3' ) {$defaultColor = '#2f8e56';
	}elseif( get_option('fit_skin_theme') == 'value4' ) {$defaultColor = '#e85e00';
	}elseif( get_option('fit_skin_theme') == 'value5' ) {$defaultColor = '#d32374';
	}elseif( get_option('fit_skin_theme') == 'value6' ) {$defaultColor = '#B92C2C';
	}elseif( get_option('fit_skin_theme') == 'value7' ) {$defaultColor = '#534970';
	}elseif( get_option('fit_skin_theme') == 'value8' ) {$defaultColor = '#0e3d69';
	}else{$defaultColor = '#63acb7';}

	//見出し2のカラーA　セッティング
	$wp_customize->add_setting('fit_hskin_h2ColorA', array( 
        'default' => $defaultColor,
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し2のカラーA コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h2ColorA', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h2ColorA',
		'label'     => '■見出し2のスタイルを選択',
		'description' => 'カラーA',		
	)));
	
	//見出し2のカラーB　セッティング
	$wp_customize->add_setting('fit_hskin_h2ColorB', array( 
        'default' => '#191919',
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し2のカラーB コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h2ColorB', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h2ColorB',
		'description' => 'カラーB',		
	)));
	
	//見出し2のスタイル　セッティング
	$wp_customize->add_setting('fit_hskin_h2Style', array( 
        'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
	//見出し2のスタイル　コントロール
	$wp_customize->add_control( 'fit_hskin_h2Style', array( 
        'section' => 'fit_hskin_section', 
        'settings' => 'fit_hskin_h2Style', 
        'description'     => '※ここで指定したカラーの反映場所は、下記の「スタイル選択」のセレクトボックス内に記載されています<br>
		<br>		
		スタイルを選択',
        'type'      => 'select',
        'choices'   => array(			
            'value1' => '01.先頭大［カラーA：先頭文字　B：文字］(default)',
            'value2' => '02.内側影［カラーA：背景　B：文字］',
            'value3' => '03.リボン風［カラーA：背景　B：文字］',
            'value4' => '04.箱型［カラーA：背景　B：文字］',
            'value5' => '05.マーカー風［カラーA：下線　B：文字］',
            'value6' => '06.吹き出し風［カラーA：背景　B：文字］',
            'value7' => '07.グラデダーク［カラーA：上　B：下］',
            'value8' => '08.グラデライト［カラーA：上線　B：文字］',
            'value9' => '09.ボックス［カラーA：線　B：文字］',
            'value10' => '10.左線［カラーA：左線　B：文字］',
            'value11' => '11.左線+背景［カラーA：左線　B：背景］',
			'value12' => '12.下線［カラーA：下線　B：文字］',
            'value13' => '13.左下線［カラーA：左線　B：文字］',
            'value14' => '14.内側線［カラーA：背景　B：文字］',
            'value15' => '15.はみ出す線［カラーA：線　B：文字］',
            'value16' => '16.文字下色線［カラーA：下線　B：文字］',
			'value17' => '00.オリジナル見出しを作成',
        ),
    ));

	// 見出し2のCSS セッティング
	$wp_customize->add_setting( 'fit_hskin_h2Css', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// 見出し2のCSS コントロール
	$wp_customize->add_control( 'fit_hskin_h2Css', array(
		'section'   => 'fit_hskin_section',
		'settings'  => 'fit_hskin_h2Css',
		'description' => 'オリジナル見出しのCSSを入力<br>
		※「00.オリジナル見出しを作成」を指定した場合は、下記のフォームor子テーマのCSSファイルにスタイルを記述してください。',
		'type'      => 'textarea',
	));



	//見出し3のカラーA　セッティング
	$wp_customize->add_setting('fit_hskin_h3ColorA', array( 
        'default' => $defaultColor,
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し3のカラーA コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h3ColorA', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h3ColorA',
		'label'     => '■見出し3のスタイルを選択',
		'description' => 'カラーA',		
	)));
	
	//見出し3のカラーB　セッティング
	$wp_customize->add_setting('fit_hskin_h3ColorB', array( 
        'default' => '#191919',
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し3のカラーB コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h3ColorB', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h3ColorB',
		'description' => 'カラーB',		
	)));
	
	//見出し3のスタイル　セッティング
	$wp_customize->add_setting('fit_hskin_h3Style', array( 
        'default'   => 'value9',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
	//見出し3のスタイル　コントロール
	$wp_customize->add_control( 'fit_hskin_h3Style', array( 
        'section' => 'fit_hskin_section', 
        'settings' => 'fit_hskin_h3Style', 
        'description'     => '※ここで指定したカラーの反映場所は、下記の「スタイル選択」のセレクトボックス内に記載されています<br>
		<br>		
		スタイルを選択',
        'type'      => 'select',
        'choices'   => array(			
            'value1' => '01.先頭大［カラーA：先頭文字　B：文字］',
            'value2' => '02.内側影［カラーA：背景　B：文字］',
            'value3' => '03.リボン風［カラーA：背景　B：文字］',
            'value4' => '04.箱型［カラーA：背景　B：文字］',
            'value5' => '05.マーカー風［カラーA：下線　B：文字］',
            'value6' => '06.吹き出し風［カラーA：背景　B：文字］',
            'value7' => '07.グラデダーク［カラーA：上　B：下］',
            'value8' => '08.グラデライト［カラーA：上線　B：文字］',
            'value9' => '09.ボックス［カラーA：線　B：文字］(default)',
            'value10' => '10.左線［カラーA：左線　B：文字］',
            'value11' => '11.左線+背景［カラーA：左線　B：背景］',
			'value12' => '12.下線［カラーA：下線　B：文字］',
            'value13' => '13.左下線［カラーA：左線　B：文字］',
            'value14' => '14.内側線［カラーA：背景　B：文字］',
            'value15' => '15.はみ出す線［カラーA：線　B：文字］',
            'value16' => '16.文字下色線［カラーA：下線　B：文字］',
			'value17' => '00.オリジナル見出しを作成',
        ),
    ));

	// 見出し3のCSS セッティング
	$wp_customize->add_setting( 'fit_hskin_h3Css', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// 見出し3のCSS コントロール
	$wp_customize->add_control( 'fit_hskin_h3Css', array(
		'section'   => 'fit_hskin_section',
		'settings'  => 'fit_hskin_h3Css',
		'description' => 'オリジナル見出しのCSSを入力<br>
		※「00.オリジナル見出しを作成」を指定した場合は、下記のフォームor子テーマのCSSファイルにスタイルを記述してください。',
		'type'      => 'textarea',
	));


	//見出し4のカラーA　セッティング
	$wp_customize->add_setting('fit_hskin_h4ColorA', array( 
        'default' => $defaultColor,
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し4のカラーA コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h4ColorA', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h4ColorA',
		'label'     => '■見出し4のスタイルを選択',
		'description' => 'カラーA',		
	)));
	
	//見出し4のカラーB　セッティング
	$wp_customize->add_setting('fit_hskin_h4ColorB', array( 
        'default' => '#191919',
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し4のカラーB コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h4ColorB', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h4ColorB',
		'description' => 'カラーB',		
	)));
	
	//見出し4のスタイル　セッティング
	$wp_customize->add_setting('fit_hskin_h4Style', array( 
        'default'   => 'value17',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
	//見出し4のスタイル　コントロール
	$wp_customize->add_control( 'fit_hskin_h4Style', array( 
        'section' => 'fit_hskin_section', 
        'settings' => 'fit_hskin_h4Style', 
        'description'     => '※ここで指定したカラーの反映場所は、下記の「スタイル選択」のセレクトボックス内に記載されています<br>
		<br>		
		スタイルを選択',
        'type'      => 'select',
        'choices'   => array(			
            'value1' => '01.先頭大［カラーA：先頭文字　B：文字］',
            'value2' => '02.内側影［カラーA：背景　B：文字］',
            'value3' => '03.リボン風［カラーA：背景　B：文字］',
            'value4' => '04.箱型［カラーA：背景　B：文字］',
            'value5' => '05.マーカー風［カラーA：下線　B：文字］',
            'value6' => '06.吹き出し風［カラーA：背景　B：文字］',
            'value7' => '07.グラデダーク［カラーA：上　B：下］',
            'value8' => '08.グラデライト［カラーA：上線　B：文字］',
            'value9' => '09.ボックス［カラーA：線　B：文字］',
            'value10' => '10.左線［カラーA：左線　B：文字］',
            'value11' => '11.左線+背景［カラーA：左線　B：背景］',
			'value12' => '12.下線［カラーA：下線　B：文字］',
            'value13' => '13.左下線［カラーA：左線　B：文字］',
            'value14' => '14.内側線［カラーA：背景　B：文字］',
            'value15' => '15.はみ出す線［カラーA：線　B：文字］',
            'value16' => '16.文字下色線［カラーA：下線　B：文字］',
			'value17' => '00.オリジナル見出しを作成(default)',
        ),
    ));

	// 見出し4のCSS セッティング
	$wp_customize->add_setting( 'fit_hskin_h4Css', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// 見出し4のCSS コントロール
	$wp_customize->add_control( 'fit_hskin_h4Css', array(
		'section'   => 'fit_hskin_section',
		'settings'  => 'fit_hskin_h4Css',
		'description' => 'オリジナル見出しのCSSを入力<br>
		※「00.オリジナル見出しを作成」を指定した場合は、下記のフォームor子テーマのCSSファイルにスタイルを記述してください。',
		'type'      => 'textarea',
	));


	//見出し5のカラーA　セッティング
	$wp_customize->add_setting('fit_hskin_h5ColorA', array( 
        'default' => $defaultColor,
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し5のカラーA コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h5ColorA', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h5ColorA',
		'label'     => '■見出し5のスタイルを選択',
		'description' => 'カラーA',		
	)));
	
	//見出し5のカラーB　セッティング
	$wp_customize->add_setting('fit_hskin_h5ColorB', array( 
        'default' => '#191919',
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
    ));
	// 見出し5のカラーB コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_hskin_h5ColorB', array(
		'section' => 'fit_hskin_section',
		'settings' =>'fit_hskin_h5ColorB',
		'description' => 'カラーB',		
	)));
	
	//見出し5のスタイル　セッティング
	$wp_customize->add_setting('fit_hskin_h5Style', array( 
        'default'   => 'value17',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
	//見出し5のスタイル　コントロール
	$wp_customize->add_control( 'fit_hskin_h5Style', array( 
        'section' => 'fit_hskin_section', 
        'settings' => 'fit_hskin_h5Style', 
        'description'     => '※ここで指定したカラーの反映場所は、下記の「スタイル選択」のセレクトボックス内に記載されています<br>
		<br>		
		スタイルを選択',
        'type'      => 'select',
        'choices'   => array(			
            'value1' => '01.先頭大［カラーA：先頭文字　B：文字］',
            'value2' => '02.内側影［カラーA：背景　B：文字］',
            'value3' => '03.リボン風［カラーA：背景　B：文字］',
            'value4' => '04.箱型［カラーA：背景　B：文字］',
            'value5' => '05.マーカー風［カラーA：下線　B：文字］',
            'value6' => '06.吹き出し風［カラーA：背景　B：文字］',
            'value7' => '07.グラデダーク［カラーA：上　B：下］',
            'value8' => '08.グラデライト［カラーA：上線　B：文字］',
            'value9' => '09.ボックス［カラーA：線　B：文字］',
            'value10' => '10.左線［カラーA：左線　B：文字］',
            'value11' => '11.左線+背景［カラーA：左線　B：背景］',
			'value12' => '12.下線［カラーA：下線　B：文字］',
            'value13' => '13.左下線［カラーA：左線　B：文字］',
            'value14' => '14.内側線［カラーA：背景　B：文字］',
            'value15' => '15.はみ出す線［カラーA：線　B：文字］',
            'value16' => '16.文字下色線［カラーA：下線　B：文字］',
			'value17' => '00.オリジナル見出しを作成(default)',
        ),
    ));

	// 見出し5のCSS セッティング
	$wp_customize->add_setting( 'fit_hskin_h5Css', array(
		'type' => 'option',
		'sanitize_callback' => '',
	));
	// 見出し5のCSS コントロール
	$wp_customize->add_control( 'fit_hskin_h5Css', array(
		'section'   => 'fit_hskin_section',
		'settings'  => 'fit_hskin_h5Css',
		'description' => 'オリジナル見出しのCSSを入力<br>
		※「00.オリジナル見出しを作成」を指定した場合は、下記のフォームor子テーマのCSSファイルにスタイルを記述してください。',
		'type'      => 'textarea',
	));
	
    


}
add_action( 'customize_register', 'fit_hskin_cutomizer' );
	
	
	
	
//////////////////////////////////////////////////
//デザインスキン設定画面
//////////////////////////////////////////////////
function fit_skin_cutomizer($wp_customize){
    //セクション
	$wp_customize->add_section( 'fit_skin_section', array( 
        'title' => 'デザインスキン設定 [LION用]', 
        'priority' => 1, 
    ));

	//ベースデザインセッティング
	$wp_customize->add_setting('fit_skin_base', array( 
        'default'   => 'value1',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));
	//ベースデザインコントロール
	$wp_customize->add_control( 'fit_skin_base', array( 
        'section' => 'fit_skin_section', 
        'settings' => 'fit_skin_base', 
        'label'     => '■ベースデザインを選択',
        'type'      => 'select',
        'choices'   => array(
            'value1' => 'DARK(default)',
            'value2' => 'LIGHT',
			'value3' => 'CONTRAST',
			'value4' => 'SEPARATE',
        ),
    ));
	
	//グラデーションオプション　セッティング
	$wp_customize->add_setting('fit_skin_optionRich', array( 
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_checkbox',
    ));
	//グラデーションオプション　コントロール
	$wp_customize->add_control( 'fit_skin_optionRich', array( 
        'section' => 'fit_skin_section', 
        'settings' => 'fit_skin_optionRich', 
        'label'     => 'ほんのりリッチなデザインに仕上げる',
        'type'      => 'select',
        'type'      => 'checkbox',
    ));

	
    
	//テーマカラーセッティング
	$wp_customize->add_setting('fit_skin_theme', array( 
		'default'   => '',
		'type' => 'option',
		'sanitize_callback' => 'fit_sanitize_select',
    ));    
	//テーマカラーコントロール
	$wp_customize->add_control( 'fit_skin_theme', array( 
        'section' => 'fit_skin_section', 
        'settings' => 'fit_skin_theme', 
        'label'     => '■メインカラーを選択',
        'type'      => 'radio',
        'choices'   => array(
            'value2' => 'SKY',
            'value3' => 'GREEN',
            'value4' => 'ORANGE',
			'value5' => 'PINK',
			'value6' => 'RED',
			'value7' => 'PURPLE',
			'value8' => 'NAVY',
			'value1' => 'Color Pickerからメインカラーを選択する',
        ),
    ));

	// カラーピッカー セッティング
	$wp_customize->add_setting( 'fit_skin_pick', array(
		'default' => '#63acb7',
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	// カラーピッカー コントロール
	$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'fit_skin_pick', array(
		'section' => 'fit_skin_section',
		'settings' =>'fit_skin_pick',
		'description' => 'Color Picker',		
	)));

}
add_action( 'customize_register', 'fit_skin_cutomizer' );




//////////////////////////////////////////////////
//オリジナルBODYクラスを作成
//////////////////////////////////////////////////
function fit_body_class(){
	$fit_skin_base = NULL;
	if ( get_option('fit_skin_base') == 'value1') {
		$fit_skin_base = 't-dark';
	} else if ( get_option('fit_skin_base') == 'value2' ) {
		$fit_skin_base = 't-light';
	} else if ( get_option('fit_skin_base') == 'value3' ) {
		$fit_skin_base = 't-contrast';
	} else if ( get_option('fit_skin_base') == 'value4' ) {
		$fit_skin_base = 't-separate';
	}else{
		$fit_skin_base = 't-dark';
	}
	
	$fit_skin_theme = NULL;
	if ( get_option('fit_skin_theme') == 'value1' && get_theme_mod('fit_skin_pick') != '#63acb7') {
		$fit_skin_theme = ' t-color';
	} else if ( get_option('fit_skin_theme') == 'value2' ) {
		$fit_skin_theme = ' t-sky';
	} else if ( get_option('fit_skin_theme') == 'value3' ) {
		$fit_skin_theme = ' t-green';
	} else if ( get_option('fit_skin_theme') == 'value4' ) {
		$fit_skin_theme = ' t-orange';
	} else if ( get_option('fit_skin_theme') == 'value5' ) {
		$fit_skin_theme = ' t-pink';
	} else if ( get_option('fit_skin_theme') == 'value6' ) {
		$fit_skin_theme = ' t-red';
	} else if ( get_option('fit_skin_theme') == 'value7' ) {
		$fit_skin_theme = ' t-purple';
	} else if ( get_option('fit_skin_theme') == 'value8' ) {
		$fit_skin_theme = ' t-navy';
	}
	
	$fit_skin_option = NULL;
	if ( get_option('fit_skin_optionRich')) {
		$fit_skin_option = ' t-rich';
	}

	echo ' class="'.$fit_skin_base.''.$fit_skin_theme.''.$fit_skin_option.'"';
}






//////////////////////////////////////////////////
//wp_headにオリジナル項目追加
//////////////////////////////////////////////////
function fit_head() {
	if ( get_option('fit_seo_cssLoad') == "value2" && get_option('fit_seo_cssLoad-main')) {
		echo '<link class="css-async" rel href="'.get_stylesheet_uri().'">'."\n";
	}else{
		echo '<link rel="stylesheet" href="'.get_stylesheet_uri().'">'."\n";
	}
	$stylesheet_directory_path = get_stylesheet_directory();
	$check_css_file = $stylesheet_directory_path."/css/content.css";
	if (is_singular() && file_exists($check_css_file)){
		if ( get_option('fit_seo_cssLoad') == "value2" && get_option('fit_seo_cssLoad-content')) {
			echo '<link class="css-async" rel href="'.get_stylesheet_directory_uri().'/css/content.css">'."\n";
		}else{
			echo '<link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/css/content.css">'."\n";
		}
	}
	if ( get_option('fit_seo_cssLoad') == "value2" && get_option('fit_seo_cssLoad-icon')) {
		echo '<link class="css-async" rel href="'.get_template_directory_uri().'/css/icon.css">'."\n";
	}else{
		echo '<link rel="stylesheet" href="'.get_template_directory_uri().'/css/icon.css">'."\n";
	}
	if ( get_option('fit_seo_cssLoad') == "value2" && get_option('fit_seo_cssLoad-lato')) {
		echo '<link class="css-async" rel href="https://fonts.googleapis.com/css?family=Lato:400,700,900">'."\n";
	}else{
		echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,700,900">'."\n";
	}
	if (is_home() && !is_paged() && get_option('fit_access_gscid') ) {
	  echo '<meta name="google-site-verification" content="'.get_option('fit_access_gscid').'" />'."\n";
	};
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">'."\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">'."\n";
	if (is_single() && get_option('fit_anp_check') == 'value2' ) {
		if (get_option( 'permalink_structure' ) == ''){
			echo '<link rel="amphtml" href="'.get_permalink().'&amp=1">'."\n";
		}else{
			echo '<link rel="amphtml" href="'.get_permalink().'?amp=1">'."\n";
		}
	}
	echo '<link rel="dns-prefetch" href="//www.google.com">'."\n";
	echo '<link rel="dns-prefetch" href="//www.google-analytics.com">'."\n";
	echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">'."\n";
	echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">'."\n";
	echo '<link rel="dns-prefetch" href="//pagead2.googlesyndication.com">'."\n";
	echo '<link rel="dns-prefetch" href="//googleads.g.doubleclick.net">'."\n";
	echo '<link rel="dns-prefetch" href="//www.gstatic.com">'."\n";	
	
	if (is_single()){
		wp_enqueue_script("comment-reply");
	}

	echo '<style>';

	if ( get_option('fit_theme_infoHead') == 'value2' && get_theme_mod('fit_theme_infoHeadColor') != '#c53929') {
		$infoHeadColor = esc_attr( get_theme_mod( 'fit_theme_infoHeadColor' ));
		echo '
.infoHead{background-color:'.$infoHeadColor.';}'."\n";
	}
	
	if (is_home() && !is_paged() && get_fit_image_main()) {
		if (get_option('fit_theme_image_main-height')){
			$height = get_option('fit_theme_image_main-height');
		}else{
			$height = '400';
		}
		if (get_option('fit_theme_image_main-heightSp')){
			$heightSp = get_option('fit_theme_image_main-heightSp');
		}else{
			$heightSp = '200';
		}
		echo '
.keyVisual{
	height:'.$height.'px;
	background:url('.get_fit_image_main().') no-repeat center '.get_option('fit_theme_image_main-position').';
	background-size: cover;
}
@media only screen and (max-width: 767px){.keyVisual{height:'.$heightSp.'px;}}'."\n";
	}
	
	if ( get_option('fit_skin_theme') == 'value1' && get_theme_mod('fit_skin_pick') != '#63acb7') {
		$primaryColor = esc_attr( get_theme_mod( 'fit_skin_pick' ));
		echo '
/*User Custom Color SP*/
.t-color .globalNavi__switch{background-color:'.$primaryColor.';}

/*User Custom Color SP/PC*/
.t-color .dateList__item a:hover,
.t-color .footerNavi__list li a:hover,
.t-color .copyright__link:hover,
.t-color .heading.heading-first:first-letter,
.t-color .heading a:hover,
.t-color .btn__link,
.t-color .widget .tag-cloud-link,
.t-color .comment-respond .submit,
.t-color .comments__list .comment-reply-link,
.t-color .widget a:hover,
.t-color .widget ul li .rsswidget,
.t-color .content a,
.t-color .related__title,
.t-color .ctaPost__btn:hover,
.t-color .prevNext__title{color:'.$primaryColor.';}
.t-color .globalNavi__list,
.t-color .eyecatch__cat a,
.t-color .pagetop,
.t-color .archiveTitle::before,
.t-color .heading.heading-secondary::before,
.t-color .btn__link:hover,
.t-color .widget .tag-cloud-link:hover,
.t-color .comment-respond .submit:hover,
.t-color .comments__list .comment-reply-link:hover,
.t-color .widget::before,
.t-color .widget .calendar_wrap tbody a:hover,
.t-color .comments__list .comment-meta,
.t-color .ctaPost__btn,
.t-color .prevNext__pop{background-color:'.$primaryColor.';}
.t-color .archiveList,
.t-color .heading.heading-widget,
.t-color .btn__link,
.t-color .widget .tag-cloud-link,
.t-color .comment-respond .submit,
.t-color .comments__list .comment-reply-link,
.t-color .content a:hover,
.t-color .ctaPost__btn,
.t-color.t-light .l-hMain::before{border-color:'.$primaryColor.';}';
	}
	
	if (is_singular()) {		
		
		// 見出し2のスタイル
		$colorA = '#63acb7';
		if (get_theme_mod('fit_hskin_h2ColorA') != '') {
			$colorA = esc_attr( get_theme_mod( 'fit_hskin_h2ColorA' ));
		}
		$colorB = '#191919';
		if (get_theme_mod('fit_hskin_h2ColorB') != '') {
			$colorB = esc_attr( get_theme_mod( 'fit_hskin_h2ColorB' ));
		}		
		if (get_option('fit_hskin_h2Style') == 'value1' || get_option('fit_hskin_h2Style') == '') {echo '
.content h2{color:'.$colorB.';}
.content h2:first-letter{
	font-size:3.2rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
		}if (get_option('fit_hskin_h2Style') == 'value2') {echo '
.content h2{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
		}if (get_option('fit_hskin_h2Style') == 'value3') {echo '
.content h2{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h2::before,
.content h2::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h2::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h2::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
		}if (get_option('fit_hskin_h2Style') == 'value4') {echo '
.content h2{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h2::before,.content h2::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h2::before{
	border-bottom-color:'.$colorA.';
}
.content h2::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
		}if (get_option('fit_hskin_h2Style') == 'value5') {echo '
.content h2{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
		}if (get_option('fit_hskin_h2Style') == 'value6') {echo '
.content h2{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h2::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
		}if (get_option('fit_hskin_h2Style') == 'value7') {echo '
.content h2{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h2Style') == 'value8') {echo '
.content h2{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}
.content h2::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
		}if (get_option('fit_hskin_h2Style') == 'value9') {echo '
.content h2{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h2Style') == 'value10') {echo '
.content h2{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h2Style') == 'value11') {echo '
.content h2{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h2Style') == 'value12') {echo '
.content h2{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h2Style') == 'value13') {echo '
.content h2{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
		}if (get_option('fit_hskin_h2Style') == 'value14') {echo '
.content h2{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h2Style') == 'value15') {echo '
.content h2{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h2::before,
.content h2::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h2::before{
	left: 10px;
}
.content h2::after{
	right: 10px;
}';
		}if (get_option('fit_hskin_h2Style') == 'value16') {echo '
.content h2{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h2::before,
.content h2::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h2:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h2:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
		}if (get_option('fit_hskin_h2Style') == 'value17' && get_option('fit_hskin_h2Css') != '') {
			echo get_option('fit_hskin_h2Css');
		}
		
		// 見出し3のスタイル
		$colorA = '#63acb7';
		if (get_theme_mod('fit_hskin_h3ColorA') != '') {
			$colorA = esc_attr( get_theme_mod( 'fit_hskin_h3ColorA' ));
		}
		$colorB = '#191919';
		if (get_theme_mod('fit_hskin_h3ColorB') != '') {
			$colorB = esc_attr( get_theme_mod( 'fit_hskin_h3ColorB' ));
		}		
		if (get_option('fit_hskin_h3Style') == 'value1') {echo '
.content h3{color:'.$colorB.';}
.content h3:first-letter{
	font-size:2.8rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
		}if (get_option('fit_hskin_h3Style') == 'value2') {echo '
.content h3{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
		}if (get_option('fit_hskin_h3Style') == 'value3') {echo '
.content h3{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h3::before,
.content h3::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h3::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h3::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
		}if (get_option('fit_hskin_h3Style') == 'value4') {echo '
.content h3{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h3::before,.content h3::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h3::before{
	border-bottom-color:'.$colorA.';
}
.content h3::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
		}if (get_option('fit_hskin_h3Style') == 'value5') {echo '
.content h3{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
		}if (get_option('fit_hskin_h3Style') == 'value6') {echo '
.content h3{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h3::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
		}if (get_option('fit_hskin_h3Style') == 'value7') {echo '
.content h3{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h3Style') == 'value8') {echo '
.content h3{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}
.content h3::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
		}if (get_option('fit_hskin_h3Style') == 'value9' || get_option('fit_hskin_h3Style') == '') {echo '
.content h3{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h3Style') == 'value10') {echo '
.content h3{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h3Style') == 'value11') {echo '
.content h3{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h3Style') == 'value12') {echo '
.content h3{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h3Style') == 'value13') {echo '
.content h3{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
		}if (get_option('fit_hskin_h3Style') == 'value14') {echo '
.content h3{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h3Style') == 'value15') {echo '
.content h3{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h3::before,
.content h3::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h3::before{
	left: 10px;
}
.content h3::after{
	right: 10px;
}';
		}if (get_option('fit_hskin_h3Style') == 'value16') {echo '
.content h3{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h3::before,
.content h3::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h3:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h3:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
		}if (get_option('fit_hskin_h3Style') == 'value17' && get_option('fit_hskin_h3Css') != '') {
			echo get_option('fit_hskin_h3Css');
		}
		
		// 見出し4のスタイル
		$colorA = '#63acb7';
		if (get_theme_mod('fit_hskin_h4ColorA') != '') {
			$colorA = esc_attr( get_theme_mod( 'fit_hskin_h4ColorA' ));
		}
		$colorB = '#191919';
		if (get_theme_mod('fit_hskin_h4ColorB') != '') {
			$colorB = esc_attr( get_theme_mod( 'fit_hskin_h4ColorB' ));
		}
		if (get_option('fit_hskin_h4Style') == 'value1') {echo '
.content h4{color:'.$colorB.';}
.content h4:first-letter{
	font-size:2.4rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
		}if (get_option('fit_hskin_h4Style') == 'value2') {echo '
.content h4{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
		}if (get_option('fit_hskin_h4Style') == 'value3') {echo '
.content h4{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h4::before,
.content h4::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h4::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h4::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
		}if (get_option('fit_hskin_h4Style') == 'value4') {echo '
.content h4{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h4::before,.content h4::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h4::before{
	border-bottom-color:'.$colorA.';
}
.content h4::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
		}if (get_option('fit_hskin_h4Style') == 'value5') {echo '
.content h4{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
		}if (get_option('fit_hskin_h4Style') == 'value6') {echo '
.content h4{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h4::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
		}if (get_option('fit_hskin_h4Style') == 'value7') {echo '
.content h4{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h4Style') == 'value8') {echo '
.content h4{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}
.content h4::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
		}if (get_option('fit_hskin_h4Style') == 'value9') {echo '
.content h4{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h4Style') == 'value10') {echo '
.content h4{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h4Style') == 'value11') {echo '
.content h4{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h4Style') == 'value12') {echo '
.content h4{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h4Style') == 'value13') {echo '
.content h4{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
		}if (get_option('fit_hskin_h4Style') == 'value14') {echo '
.content h4{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h4Style') == 'value15') {echo '
.content h4{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h4::before,
.content h4::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h4::before{
	left: 10px;
}
.content h4::after{
	right: 10px;
}';
		}if (get_option('fit_hskin_h4Style') == 'value16') {echo '
.content h4{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h4::before,
.content h4::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h4:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h4:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
		}if (get_option('fit_hskin_h4Style') == 'value17' && get_option('fit_hskin_h4Css') != '') {
			echo get_option('fit_hskin_h4Css');
		}
		
		// 見出し5のスタイル
		$colorA = '#63acb7';
		if (get_theme_mod('fit_hskin_h5ColorA') != '') {
			$colorA = esc_attr( get_theme_mod( 'fit_hskin_h5ColorA' ));
		}
		$colorB = '#191919';
		if (get_theme_mod('fit_hskin_h5ColorB') != '') {
			$colorB = esc_attr( get_theme_mod( 'fit_hskin_h5ColorB' ));
		}
		if (get_option('fit_hskin_h5Style') == 'value1') {echo '
.content h5{color:'.$colorB.';}
.content h5:first-letter{
	font-size:2rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
		}if (get_option('fit_hskin_h5Style') == 'value2') {echo '
.content h5{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
		}if (get_option('fit_hskin_h5Style') == 'value3') {echo '
.content h5{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h5::before,
.content h5::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h5::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h5::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
		}if (get_option('fit_hskin_h5Style') == 'value4') {echo '
.content h5{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h5::before,.content h5::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h5::before{
	border-bottom-color:'.$colorA.';
}
.content h5::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
		}if (get_option('fit_hskin_h5Style') == 'value5') {echo '
.content h5{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
		}if (get_option('fit_hskin_h5Style') == 'value6') {echo '
.content h5{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h5::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
		}if (get_option('fit_hskin_h5Style') == 'value7') {echo '
.content h5{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h5Style') == 'value8') {echo '
.content h5{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}
.content h5::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
		}if (get_option('fit_hskin_h5Style') == 'value9') {echo '
.content h5{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h5Style') == 'value10') {echo '
.content h5{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h5Style') == 'value11') {echo '
.content h5{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h5Style') == 'value12') {echo '
.content h5{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
		}if (get_option('fit_hskin_h5Style') == 'value13') {echo '
.content h5{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
		}if (get_option('fit_hskin_h5Style') == 'value14') {echo '
.content h5{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
		}if (get_option('fit_hskin_h5Style') == 'value15') {echo '
.content h5{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h5::before,
.content h5::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h5::before{
	left: 10px;
}
.content h5::after{
	right: 10px;
}';
		}if (get_option('fit_hskin_h5Style') == 'value16') {echo '
.content h5{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h5::before,
.content h5::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h5:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h5:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
		}if (get_option('fit_hskin_h5Style') == 'value17' && get_option('fit_hskin_h5Css') != '') {
			echo get_option('fit_hskin_h5Css');
		}
		
		
	}
	echo "\n".'</style>'."\n";

}		
add_action('wp_head', 'fit_head');




//////////////////////////////////////////////////
//wp_head　不要タグの削除
//////////////////////////////////////////////////
remove_action( 'wp_head', 'wp_generator' ); //WordPressのバージョン情報
remove_action( 'wp_head', 'rsd_link' ); //外部アプリケーションから情報を取得するタグ
remove_action( 'wp_head', 'wlwmanifest_link' ); //Windows Live Writer用のタグ
remove_action( 'wp_head', 'index_rel_link' ); //現在の文書に対する「索引」であることを示すタグ
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 ); //「?p=投稿ID」形式のデフォルトパーマリンクタグ

//「link rel=next」等のタグ
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

//フィード関連のタグ
remove_action( 'wp_head', 'feed_links', 2);
remove_action( 'wp_head', 'feed_links_extra', 3);

//絵文字関連タグ
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
remove_action( 'admin_print_scripts', 'print_emoji_detection_script');
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles');
add_filter( 'emoji_svg_url', '__return_false' );

//最近のコメント用インラインスタイルタグ 
function remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'remove_recent_comments_style' );




//////////////////////////////////////////////////
//wp_footer　オリジナル項目追加
//////////////////////////////////////////////////
function fit_footer() {
	if ( get_option('fit_seo_cssLoad') == "value2" ) {
		echo '<script>Array.prototype.forEach.call(document.getElementsByClassName("css-async"), function(e){e.rel = "stylesheet"});</script>'."\n";
	}
}
add_action('wp_footer', 'fit_footer', '999');




//////////////////////////////////////////////////
// fit_original_titleを設定
//////////////////////////////////////////////////
function fit_page_title() {
	$opt = get_option('fit_advanced_archive');
	$title = get_bloginfo( 'name' );
	if ( is_category() ) {
        $title = $opt['category']. single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = $opt['tag']. single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = $opt['author']. get_the_author();
    } elseif ( is_year() ) {
        $title = $opt['year']. get_the_date('Y年') ;
    } elseif ( is_month() ) {
        $title = $opt['month']. get_the_date('Y年n月') ;
    } elseif ( is_day() ) {
        $title = $opt['day']. get_the_date('Y年n月j日') ;
	} elseif ( is_search() ) {
        $title = $opt['search'].'「'.get_search_query().'」の検索結果' ;
    } elseif ( is_404() ) {
        $title = 'Hello! My Name Is 404' ;
    }
	return $title;
}

function fit_archive_title() {
	$opt = get_option('fit_advanced_archive');
	$title = get_bloginfo( 'name' );
	if ( is_category() ) {
        $title = $opt['category']. single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = $opt['tag']. single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = $opt['author']. get_the_author();
    } elseif ( is_year() ) {
        $title = $opt['year']. get_the_date('Y年') ;
    } elseif ( is_month() ) {
        $title = $opt['month']. get_the_date('Y年n月') ;
    } elseif ( is_day() ) {
        $title = $opt['day']. get_the_date('Y年n月j日') ;
	} elseif ( is_search() ) {
        $title = $opt['search'].'「'.get_search_query().'」の検索結果' ;
    } elseif ( is_404() ) {
        $title = 'Hello! My Name Is 404' ;
    }
	return $title;
}




//////////////////////////////////////////////////
//wp_head　<title>タグの設定
//////////////////////////////////////////////////
// wp_headで<title>を出力する
function setup_theme() {
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'setup_theme' );

// <title>の区切り線を｜に変更する
function fit_title_separator(){
    $sep = '│';
    return $sep;
}
add_filter( 'document_title_separator', 'fit_title_separator' );

// <title>の設定
function fit_document_title( $title ) {
	if ( is_home() ) {
		if ( get_option('fit_seo_titleTop') && !get_option('fit_seo_titleTopName') ) {
			$title = get_option('fit_seo_titleTop');
		}elseif ( get_option('fit_seo_titleTop') && get_option('fit_seo_titleTopName') ) {
			$title = get_option('fit_seo_titleTop') .fit_title_separator() .get_bloginfo( 'name' );		
		}else {
			$title = get_bloginfo( 'description' ) .fit_title_separator() .get_bloginfo( 'name' );
		}
	}
	if (is_category() || is_tag() || is_author() || is_year() || is_month() || is_day() || is_search() || is_404() ) {
        $title = fit_page_title() .fit_title_separator() .get_bloginfo( 'name' );
	}
	if (is_singular() && get_post_meta(get_the_ID(), 'title', true) && get_post_meta(get_the_ID(), 'titleName', true) ) {
        $title = get_post_meta(get_the_ID(), 'title', true) .fit_title_separator() .get_bloginfo( 'name' );
    }
	if (is_singular() && get_post_meta(get_the_ID(), 'title', true) && !get_post_meta(get_the_ID(), 'titleName', true) ) {
        $title = get_post_meta(get_the_ID(), 'title', true);
    }
	return $title;
}
add_filter( 'pre_get_document_title', 'fit_document_title' );




//////////////////////////////////////////////////
// SEO専用カスタムフィールド追加
//////////////////////////////////////////////////
function add_seo_fields() {
	add_meta_box( 'seo_setting', 'SEO対策', 'insert_seo_fields', 'post', 'normal', 'high');
	add_meta_box( 'seo_setting', 'SEO対策', 'insert_seo_fields', 'page', 'normal', 'high');
}
add_action('admin_menu', 'add_seo_fields');


// カスタムフィールドの入力フィールド
function insert_seo_fields() {
	global $post;
	$title = get_post_meta($post->ID,'title',true);
	$titleName = get_post_meta($post->ID,'titleName',true);
	if( $titleName == 1 ) {
		$titleName_check = "checked";
	} else {
		$titleName_check = "/";
	}
	
	$description = get_post_meta($post->ID,'description',true);
	
	$noindex = get_post_meta($post->ID,'noindex',true);
	if( $noindex == 1 ) {
		$noindex_check = "checked";
	} else {
		$noindex_check = "/";
	}
	
	$nofollow = get_post_meta($post->ID,'nofollow',true);
	if( $nofollow == 1 ) {
		$nofollow_check = "checked";
	} else {
		$nofollow_check = "/";
	}
	
	$nosnippet = get_post_meta($post->ID,'nosnippet',true);
	if( $nosnippet == 1 ) {
		$nosnippet_check = "checked";
	} else {
		$nosnippet_check = "/";
	}
	
	$noarchive = get_post_meta($post->ID,'noarchive',true);
	if( $noarchive == 1 ) {
		$noarchive_check = "checked";
	} else {
		$noarchive_check = "/";
	}


	echo '
		<div style="margin:20px 0; overflow: hidden; line-height:2;">
		  <div style="float:left;width:120px;">title設定</div>
		  <div style="float:right;width:calc(100% - 140px);">
		    <input type="text" size="50" name="title" id="title" value="'.esc_html($title).'"  /><br>
			<input type="checkbox" name="titleName" value="1" ' . $titleName_check . '>後ろに［'.fit_title_separator().' '.get_bloginfo( 'name' ).'］を表示する<br>
			<span style="color: #7F7F7F;">※未入力時は「記事タイトル '.fit_title_separator().' '.get_bloginfo( 'name' ).'」が表示されます。</span>
		  </div>
		  <div style="clear:both;"></div>
		</div>
	';
	
	echo '
		<div style="margin:20px 0; overflow: hidden; line-height:2;">
		  <div style="float:left;width:120px;">description設定</div>
		  <div style="float:right;width:calc(100% - 140px);">
		    <textarea name="description" id="description" cols="50" rows="4" />'.esc_html($description).'</textarea><br>
			<span>検索結果に表示される説明文です。</span>
		  </div>
		  <div style="clear:both;"></div>
		</div>
	';

	echo '
		<div style="margin:20px 0; overflow: hidden; line-height:2;">
		  <div style="float:left;width:120px;">meta robot設定</div>
		  <div style="float:right;width:calc(100% - 140px);">
		    <input type="checkbox" name="noindex" value="1" ' . $noindex_check . '>:NoIndex　
			<input type="checkbox" name="nofollow" value="1" ' . $nofollow_check . '>:NoFollow　
			<input type="checkbox" name="nosnippet" value="1" ' . $nosnippet_check . '>:NoSnippet　
			<input type="checkbox" name="noarchive" value="1" ' . $noarchive_check . '>:NoArchive
		  </div>
		  <div style="clear:both;"></div>
		</div>
	';
	 
}

// カスタムフィールドの値を保存
function save_custom_fields( $post_id ) {
	if(!empty($_POST['title'])){
		update_post_meta($post_id, 'title', $_POST['title'] );
	}else{
		delete_post_meta($post_id, 'title');
	}
	if(!empty($_POST['titleName'])){
		update_post_meta($post_id, 'titleName', $_POST['titleName'] );
	}else{
		delete_post_meta($post_id, 'titleName');
	}
	if(!empty($_POST['description'])){
		update_post_meta($post_id, 'description', $_POST['description'] );
	}else{
		delete_post_meta($post_id, 'description');
	}
	if(!empty($_POST['noindex'])){
		update_post_meta($post_id, 'noindex', $_POST['noindex'] );
	}else{
		delete_post_meta($post_id, 'noindex');
	}
	if(!empty($_POST['nofollow'])){
		update_post_meta($post_id, 'nofollow', $_POST['nofollow'] );
	}else{
		delete_post_meta($post_id, 'nofollow');
	}
	if(!empty($_POST['nosnippet'])){
		update_post_meta($post_id, 'nosnippet', $_POST['nosnippet'] );
	}else{
		delete_post_meta($post_id, 'nosnippet');
	}
	if(!empty($_POST['noarchive'])){
		update_post_meta($post_id, 'noarchive', $_POST['noarchive'] );
	}else{
		delete_post_meta($post_id, 'noarchive');
	}
}
add_action('save_post', 'save_custom_fields');


//カスタムフィールドで設定したディスクリプションを加工
function custom_description() {
	$description = get_post_meta(get_the_ID(), 'description', true);
	$description = strip_tags(str_replace(array("\r\n", "\r", "\n"), '', $description));//改行削除
	return $description;
}


//ディスクリプション設定
function description_fit() {
	$get_description = NULL;
	
	// TOPページ
	if ( is_home() ) {
		if ( get_option('fit_seo_descriptionTop') ){
			$get_description = get_option('fit_seo_descriptionTop');
		}
	}// 投稿・固定ページ
	elseif ( is_singular() ) { 
		$get_description = custom_description();
	}// カテゴリー・タグページ
	elseif (is_category() || is_tag()) {
		if ( term_description() && get_option('fit_theme_term') != 'value2' ){
			$get_description = term_description();
		}
	}
	return $get_description;
}


// 設定の反映
function fit_seo() {// カスタムフィールドの設定値の読み込み
	$custom = get_post_custom();
	$noindex = @$custom['noindex'][0];
	$nofollow = @$custom['nofollow'][0];
	$nosnippet = @$custom['nosnippet'][0];
	$noarchive = @$custom['noarchive'][0];
	$description = description_fit();

	//noindexとnofollow設定
	if    ( $noindex && !$nofollow && !$nosnippet && !$noarchive ) {echo '<meta name="robots" content="noindex">'."\n";}
	elseif( !$noindex && $nofollow && !$nosnippet && !$noarchive ) {echo '<meta name="robots" content="nofollow">'."\n";}
	elseif( !$noindex && !$nofollow && $nosnippet && !$noarchive ) {echo '<meta name="robots" content="nosnippet">'."\n";}
	elseif( !$noindex && !$nofollow && !$nosnippet && $noarchive ) {echo '<meta name="robots" content="noarchive">'."\n";}
	elseif( $noindex && $nofollow && !$nosnippet && !$noarchive ) {echo '<meta name="robots" content="noindex,nofollow">'."\n";}
	elseif( $noindex && !$nofollow && $nosnippet && !$noarchive ) {echo '<meta name="robots" content="noindex,nosnippet">'."\n";}
	elseif( $noindex && !$nofollow && !$nosnippet && $noarchive ) {echo '<meta name="robots" content="noindex,noarchive">'."\n";}
	elseif( !$noindex && $nofollow && $nosnippet && !$noarchive ) {echo '<meta name="robots" content="nofollow,nosnippet">'."\n";}
	elseif( !$noindex && $nofollow && !$nosnippet && $noarchive ) {echo '<meta name="robots" content="nofollow,noarchive">'."\n";}
	elseif( !$noindex && !$nofollow && $nosnippet && $noarchive ) {echo '<meta name="robots" content="nosnippet,noarchive">'."\n";}
	elseif( $noindex && $nofollow && $nosnippet && !$noarchive ) {echo '<meta name="robots" content="noindex,nofollow,nosnippet">'."\n";}
	elseif( $noindex && $nofollow && !$nosnippet && $noarchive ) {echo '<meta name="robots" content="noindex,nofollow,noarchive">'."\n";}
	elseif( $noindex && !$nofollow && $nosnippet && $noarchive ) {echo '<meta name="robots" content="noindex,nosnippet,noarchive">'."\n";}
	elseif( !$noindex && $nofollow && $nosnippet && $noarchive ) {echo '<meta name="robots" content="nofollow,nosnippet,noarchive">'."\n";}
	elseif( $noindex && $nofollow && $nosnippet && $noarchive ) {echo '<meta name="robots" content="noindex,nofollow,nosnippet,noarchive">'."\n";}

	//ディスクリプション設定
	if (!empty($description)) {
		echo '<meta name="description" content="'.$description.'">'; echo "\n";
	}
}




//////////////////////////////////////////////////
//AMPページ用scriptの選択設定
//////////////////////////////////////////////////
if(get_option('fit_anp_check') == 'value2'){
	function add_amp_fields() {
		//add_meta_box(表示される入力ボックスのHTMLのID, ラベル, 表示する内容を作成する関数名, 投稿タイプ, 表示方法)
		add_meta_box( 'amp_setting', 'AMPページ用Scriptの選択', 'insert_amp_fields', 'post', 'normal');
	}
	add_action('admin_menu', 'add_amp_fields');
 
 
	// カスタムフィールドの入力エリア
	function insert_amp_fields() {
		global $post;
		
		if( get_post_meta($post->ID,'amp_script_twitter',true) == "1" ) {
			$amp_script_twitter_check = "checked";
		}else {
			$amp_script_twitter_check = "";
		}
		if( get_post_meta($post->ID,'amp_script_instagram',true) == "1" ) {
			$amp_script_instagram_check = "checked";
		}else {
			$amp_script_instagram_check = "";
		}
		if( get_post_meta($post->ID,'amp_script_youtube',true) == "1" ) {
			$amp_script_youtube_check = "checked";
		}else {
			$amp_script_youtube_check = "";
		}
		if( get_post_meta($post->ID,'amp_script_iframe',true) == "1" ) {
			$amp_script_iframe_check = "checked";
		}else {
			$amp_script_iframe_check = "";
		}
		
		echo '
			<div style="margin:20px 0; overflow: hidden; line-height:2;">
			  <div style="float:left;width:120px;">AMP用Script設定</div>
			  <div style="float:right;width:calc(100% - 140px);">
			    <input type="checkbox" name="amp_script_twitter" value="1" '.$amp_script_twitter_check.' >:Twitter　
				<input type="checkbox" name="amp_script_instagram" value="1" '.$amp_script_instagram_check.' >:Instagram　
				<input type="checkbox" name="amp_script_youtube" value="1" '.$amp_script_youtube_check.' >:YouTube　
				<input type="checkbox" name="amp_script_iframe" value="1" '.$amp_script_iframe_check.' >:iframe<br>
				<span style="color: #7F7F7F;">※外部メディアコンテンツを記事中に埋め込んでいる場合は必ず必要な項目にチェックを入れてください。</span>
			  </div>
			  <div style="clear:both;"></div>
			</div>
		';

	}
 
 
	// カスタムフィールドの値を保存
	function save_amp_fields( $post_id ) {
		if(!empty($_POST['amp_script_twitter'])){
			update_post_meta($post_id, 'amp_script_twitter', $_POST['amp_script_twitter'] );
		}else{
			delete_post_meta($post_id, 'amp_script_twitter');
		}
	
		if(!empty($_POST['amp_script_instagram'])){
			update_post_meta($post_id, 'amp_script_instagram', $_POST['amp_script_instagram'] );
		}else{
			delete_post_meta($post_id, 'amp_script_instagram');
		}
	
		if(!empty($_POST['amp_script_youtube'])){
			update_post_meta($post_id, 'amp_script_youtube', $_POST['amp_script_youtube'] );
		}else{
			delete_post_meta($post_id, 'amp_script_youtube');
		}
	
		if(!empty($_POST['amp_script_iframe'])){
			update_post_meta($post_id, 'amp_script_iframe', $_POST['amp_script_iframe'] );
		}else{
			delete_post_meta($post_id, 'amp_script_iframe');
		}
	}
	add_action('save_post', 'save_amp_fields');
}




//////////////////////////////////////////////////
//目次の表示/非表示、個別選択設定
//////////////////////////////////////////////////
if ( get_option('fit_post_outline') != 'value2') {
	function add_outline_fields() {
		//add_meta_box(表示される入力ボックスのHTMLのID, ラベル, 表示する内容を作成する関数名, 投稿タイプ, 表示方法)
		add_meta_box( 'outline_setting', '目次の個別非表示設定', 'insert_outline_fields', 'post', 'normal');
	}
	add_action('admin_menu', 'add_outline_fields');
 
 
	// カスタムフィールドの入力エリア
	function insert_outline_fields() {
		global $post;
	
		if( get_post_meta($post->ID,'outline_none',true) == "1" ) {
			$outline_none_check = "checked";
		}else {
			$outline_none_check = "";
		}
	
		echo '
			<div style="margin:20px 0; overflow: hidden; line-height:2;">
		  	<div style="float:left;width:120px;">目次の表示設定</div>
		  	<div style="float:right;width:calc(100% - 140px);">
		    	<input type="checkbox" name="outline_none" value="1" '.$outline_none_check.' >:この投稿では目次を非表示にしますか？
		  	</div>
		  	<div style="clear:both;"></div>
			</div>
		';
	
	}

	// カスタムフィールドの値を保存
	function save_outline_fields( $post_id ) {
		if(!empty($_POST['outline_none'])){
			update_post_meta($post_id, 'outline_none', $_POST['outline_none'] );
		}else{
			delete_post_meta($post_id, 'outline_none');
		}

	}
	add_action('save_post', 'save_outline_fields');
}




//////////////////////////////////////////////////
//OGP設定
//////////////////////////////////////////////////
function fit_ogp(){
	echo '<meta property="og:site_name" content="'.get_bloginfo('name').'" />'."\n";
	if ( is_home() ) {
		echo '<meta property="og:type" content="blog" />'."\n";
	}
	else {
		echo '<meta property="og:type" content="article" />'."\n";
	}

	if (is_singular()){
		echo '<meta property="og:title" content="'.get_the_title().'" />'."\n";
		if(description_fit()){
			echo '<meta property="og:description" content="'.description_fit().'" />'."\n";
		}elseif(have_posts()){while ( have_posts() ) { the_post();
			echo '<meta property="og:description" content="'.mb_substr(get_the_excerpt(), 0, 120).'" />'."\n";
		}}
		echo '<meta property="og:url" content="'.get_the_permalink().'" />'."\n";
	}elseif (is_home()){
		if(get_option('fit_seo_titleTop')){
			echo '<meta property="og:title" content="'.fit_document_title('fit_seo_titleTop').'" />'."\n";
		}else{
			echo '<meta property="og:title" content="'.get_bloginfo('name').'" />'."\n";
		}
		if(get_option('fit_seo_descriptionTop')){
			echo '<meta property="og:description" content="'.get_option('fit_seo_descriptionTop').'" />'."\n";
		}else{
			echo '<meta property="og:description" content="'.get_bloginfo('description').'" />'."\n";
		}
		echo '<meta property="og:url" content="'.get_home_url().'" />'."\n";
	}else {
		echo '<meta property="og:title" content="'.wp_get_document_title().'" />'."\n";
		if (term_description()) {
			echo '<meta property="og:description" content="'.term_description().'" />'."\n";
		}else{
			echo '<meta property="og:description" content="'.get_bloginfo('description').'" />'."\n";
		}
		if(is_year()){
			echo '<meta property="og:url" content="'.get_year_link('').'" />'."\n";
		}elseif(is_month()){
			echo '<meta property="og:url" content="'.get_month_link('', '').'" />'."\n";
		}elseif(is_day()){
			echo '<meta property="og:url" content="'.get_day_link('', '', '').'" />'."\n";
		}elseif(is_author()){
			echo '<meta property="og:url" content="'.get_author_posts_url(get_the_author_meta( 'ID' )).'" />'."\n";
		}elseif(is_search()){
			echo '<meta property="og:url" content="'.get_search_link().'" />'."\n";
		}elseif(is_category()){
			$cat = get_the_category();
			$cat_id = $cat[0]->cat_ID;
			echo '<meta property="og:url" content="'.get_category_link($cat_id).'" />'."\n";
		}elseif(is_tag()){
			$tag = get_the_tags();
			$tag_id = $tag[0]->term_id;
			echo '<meta property="og:url" content="'.get_tag_link($tag_id).'" />'."\n";
		}else{
			echo '<meta property="og:url" content="'.get_home_url().'" />'."\n";
		}
	}

	if (is_singular()){
		if (has_post_thumbnail()){//投稿にサムネイルがある場合
			$image_id = get_post_thumbnail_id();
			$image = wp_get_attachment_image_src( $image_id, 'icatch');
			echo '<meta property="og:image" content="'.$image[0].'" />'."\n";
		}elseif(get_fit_image_ogp()){//投稿にサムネイルが無く、OGP用画像がある場合
			echo '<meta property="og:image" content="'.get_fit_image_ogp().'" />'."\n";
		}else{//何も無い場合
			echo '<meta property="og:image" content="'.get_template_directory_uri().'/img/img_no.gif" />'."\n";
		}
	}
	else {
		if(get_fit_image_ogp()){
			echo '<meta property="og:image" content="'.get_fit_image_ogp().'" />'."\n";
		}elseif(get_fit_image_main()){
			echo '<meta property="og:image" content="'.get_fit_image_main().'" />'."\n";
		}else{
			echo '<meta property="og:image" content="'.get_template_directory_uri().'/img/img_no.gif" />'."\n";
		}
	}

	if ( get_option('fit_social_TwitterCard')) {
		echo '<meta name="twitter:card" content="'.get_option('fit_social_TwitterCard').'" />'."\n";
	}else{
		echo '<meta name="twitter:card" content="summary" />'."\n";
	}
	
	if ( get_option('fit_social_TwitterId')) {
		echo '<meta name="twitter:site" content="@'.get_option('fit_social_TwitterId').'" />'."\n";
	}
	
	if ( get_option('fit_social_FBAppId')) {
		echo '<meta property="fb:app_id" content="'.get_option('fit_social_FBAppId').'" />'."\n";
	}
	
	if ( get_option('fit_social_FBAdmins')) {
		echo '<meta property="fb:admins" content="'.get_option('fit_social_FBAdmins').'" />'."\n";
	}
}




//////////////////////////////////////////////////
//投稿ページにPVカウント用カスタムフィールド追加
//////////////////////////////////////////////////
//アクセス数を取得
function get_post_views($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	
	if($count==''){
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return '0 View';
	}
	return $count.' Views';
}

//アクセス数を保存
function set_post_views($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	
	if($count==''){
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	}else{
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}

//クローラーのアクセス判別
function is_bot() {
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$bot = array(
		"googlebot",
		"msnbot",
		"yahoo"
	);
	
	foreach( $bot as $bot ) {
		if (stripos( $ua, $bot ) !== false){
			return true;
		}
	}
	return false;
}




//////////////////////////////////////////////////
//管理画面の投稿画面にPV数を表示
//////////////////////////////////////////////////
function count_status($post){
	if( $post->post_type == "post" ){
		echo'<div class="postbox" style="margin:20px 0 0 0; padding:12px; ">';
		echo'<span>この記事の閲覧数：</span>';
		echo'<strong> '; echo esc_html( $post->post_views_count ); echo' View </strong>';
		echo'</div>';
	}
}
add_action('edit_form_after_editor', 'count_status');




//////////////////////////////////////////////////
//管理画面の投稿一覧にPV数とサムネイル画像を表示
//////////////////////////////////////////////////
function manage_posts_columns($columns) {
	$columns['post_views_count'] = '閲覧数';
	$columns['thumbnail'] = 'サムネイル';
	return $columns;
}

function add_column($column_name, $post_id) {
	//View数呼び出し
	if ( $column_name == 'post_views_count' ) {
		$stitle = get_post_meta($post_id, 'post_views_count', true);
	}
	//サムネイル呼び出し
	if ( $column_name == 'thumbnail') {
		$thumb = get_the_post_thumbnail($post_id, array(100,100), 'thumbnail');
	}
	//表示する
	if ( isset($stitle) && $stitle ) {
		echo esc_attr($stitle);
	}
	else if ( isset($thumb) && $thumb ) {
		echo $thumb;
	}

}
add_filter( 'manage_posts_columns', 'manage_posts_columns' );
add_action( 'manage_posts_custom_column', 'add_column', 10, 2 );


//閲覧数でソートできるようにする
function column_orderby_custom( $vars ) {
    if ( isset( $vars['orderby'] ) && 'post_views_count' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num'
        ));
    }
    return $vars;
}
add_filter( 'request', 'column_orderby_custom' );
 
function posts_register_sortable( $sortable_column ) {
    $sortable_column['post_views_count'] = 'post_views_count';
    return $sortable_column;
}
add_filter( 'manage_edit-post_sortable_columns', 'posts_register_sortable' );




//////////////////////////////////////////////////
//管理画面の文言を変更
//////////////////////////////////////////////////
function fit_admin_style() {
	$cautionColor = '#0073aa';
	echo '<style>
	.options-media-php .title + p::after{
		content: "※()括弧内の数字はLION BLOG Themeの推薦サイズです。";
		display: block;
		color: '.$cautionColor.';
	}
	.options-media-php label[for="thumbnail_size_w"]::after{
		content: "(160px)";
		color: '.$cautionColor.';
	}
	.options-media-php label[for="thumbnail_size_h"]::after{
		content: "(160px)";
		color: '.$cautionColor.';
	}
	.options-media-php label[for="medium_size_w"]::after{
		content: "(300px)";
		color: '.$cautionColor.';
	}
	.options-media-php label[for="medium_size_h"]::after{
		content: "(300px)";
		color: '.$cautionColor.';
	}
	.post-php a#set-post-thumbnail::after{
		display: block;
		content: "※[縦500 × 横890px]以上の画像";
		color: '.$cautionColor.';
	}
	</style>'."\n";
}
add_action('admin_print_styles', 'fit_admin_style');






//////////////////////////////////////////////////
//検索対象をPOSTに限定
//////////////////////////////////////////////////
function fit_search_filter($search) {

	if ( get_option('fit_theme_search') == 'value2' ) {
		if(is_search()) {
			$search .= " AND post_type = 'post'";
		}
		return $search;
	}elseif ( get_option('fit_theme_search') == 'value3' ) {
		if(is_search()) {
			$search .= " AND post_type = 'page'";
		}
		return $search;
	}else{
		if(is_search()) {
			$search .= " AND (post_type = 'post' OR post_type='page')";
		}
		return $search;
	}
}
add_filter('posts_search', 'fit_search_filter');




//////////////////////////////////////////////////
// コメントの名前文字数制限
//////////////////////////////////////////////////
add_filter('pre_comment_author_name', 'et_comment_author_length');
function et_comment_author_length($author) {
    if (isset($_POST['author'])) {
		if(mb_strlen($_POST['author']) > 10) {
			$author = mb_substr($_POST['author'], 0, 10, 'UTF-8');
		}
	}
    return $author;
}



//////////////////////////////////////////////////
//投稿スラッグを自動的に生成する
//////////////////////////////////////////////////
function auto_post_slug( $slug, $post_ID, $post_status, $post_type ) {
    if ( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
        $slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
    }
    return $slug;
}
add_filter( 'wp_unique_post_slug', 'auto_post_slug', 10, 4 );




//////////////////////////////////////////////////
//ビジュアルエディタ項目カスタマイズ
//////////////////////////////////////////////////
function custom_editor_settings( $initArray ){
	$initArray['block_formats'] = "段落=p; 見出し2=h2; 見出し3=h3; 見出し4=h4; 見出し5=h5; 整形済みテキスト=pre;";
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );


function custom_tiny_mce_style_formats( $settings ) {
  $style_formats = array(
    array(
        'title' => 'BOX：線枠',
        'block' => 'div',
        'classes' => 'borderBox',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：二重線',
        'block' => 'div',
        'classes' => 'border2Box',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：背景',
        'block' => 'div',
        'classes' => 'bgBox',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：ペーパー',
        'block' => 'div',
        'classes' => 'paperBox',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：太文字',
        'block' => 'div',
        'classes' => 'boldBox',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：括弧',
        'block' => 'div',
        'classes' => 'bracketsBox',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：はてな',
        'block' => 'div',
        'classes' => 'questionBox',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：びっくり',
        'block' => 'div',
        'classes' => 'exclamationBox',
        'wrapper' => true,
    ),
    array(
        'title' => 'BOX：ポイント',
        'block' => 'div',
        'classes' => 'pointBox',
        'wrapper' => true,
    ),
	array(
      'title' => 'マーカー：イエロー',
      'inline' => 'span',
      'classes' => 'markerYellow',
      'wrapper' => true,
    ),
	array(
      'title' => 'マーカー：ピンク',
      'inline' => 'span',
      'classes' => 'markerPink',
      'wrapper' => true,
    ),
	array(
      'title' => 'マーカー：ブルー',
      'inline' => 'span',
      'classes' => 'markerBlue',
      'wrapper' => true,
    ),
	array(
      'title' => '注釈',
      'inline' => 'span',
      'classes' => 'asterisk',
      'wrapper' => true,
    ),

  );
  $settings[ 'style_formats' ] = json_encode( $style_formats );
  return $settings;
}
add_filter( 'tiny_mce_before_init', 'custom_tiny_mce_style_formats' );


function add_original_styles_button( $buttons ) {
  array_splice( $buttons, 1, 0, 'styleselect' );
  return $buttons;
}
add_filter( 'mce_buttons', 'add_original_styles_button' );




//////////////////////////////////////////////////
//投稿エディタにクイックタグボタン追加
//////////////////////////////////////////////////
if (!function_exists( 'add_quicktags_to_text_editor' ) ) {
	function add_quicktags_to_text_editor() {
		//スクリプトキューにquicktagsが保存されているかチェック
		if (wp_script_is('quicktags')){?>
		<script>
			QTags.addButton('qt-p','p','<p>','</p>');
			QTags.addButton('qt-h2','h2','<h2>','</h2>');
			QTags.addButton('qt-h3','h3','<h3>','</h3>');
			QTags.addButton('qt-h4','h4','<h4>','</h4>');
			QTags.addButton('qt-h5','h5','<h5>','</h5>');
			QTags.addButton('qt-hr','hr','<hr>');
			QTags.addButton('qt-br','br','<br>');
			QTags.addButton('qt-pre','pre','<pre>','</pre>');
			QTags.addButton('qt-borderBox','BOX：枠線','<div class="borderBox">','</div>');
			QTags.addButton('qt-border2Box','BOX：二重線','<div class="border2Box">','</div>');
			QTags.addButton('qt-bgBox','BOX：背景','<div class="bgBox">','</div>');
			QTags.addButton('qt-paperBox','BOX：ペーパー','<div class="paperBox">','</div>');
			QTags.addButton('qt-boldBox','BOX：太文字','<div class="boldBox">','</div>');
			QTags.addButton('qt-bracketsBox','BOX：括弧','<div class="bracketsBox">','</div>');
			QTags.addButton('qt-questionBox','BOX：はてな','<div class="questionBox">','</div>');
			QTags.addButton('qt-exclamationBox','BOX：びっくり','<div class="exclamationBox">','</div>');
			QTags.addButton('qt-pointBox','BOX：ポイント','<div class="pointBox">','</div>');
			
			QTags.addButton('qt-markerYellow','マーカー：イエロー','<span class="markerYellow">','</span>');
			QTags.addButton('qt-markerPink','マーカー：ピンク','<span class="markerPink">','</span>');
			QTags.addButton('qt-markerBlue','マーカー：ブルー','<span class="markerBlue">','</span>');
			
			QTags.addButton('qt-asterisk','注釈','<span class="asterisk">','</span>');
			
			QTags.addButton('qt-outline','目次','[outline]');
			QTags.addButton('qt-adchord','記事内広告','[adchord]');
			
			</script>
		<?php
        }
	}
}
add_action( 'admin_print_footer_scripts', 'add_quicktags_to_text_editor' );




//////////////////////////////////////////////////
//投稿ビジュアルエディタをテーマCSSに合わせる
//////////////////////////////////////////////////
add_editor_style("style-editor.css");




//////////////////////////////////////////////////
//term_descriptionPタグ削除（カテゴリ・タグのSEO）
//////////////////////////////////////////////////
remove_filter('term_description','wpautop');




//////////////////////////////////////////////////
//投稿エディタで出力される画像srcset無効化
//////////////////////////////////////////////////
add_filter( 'wp_calculate_image_srcset', '__return_false' );




//////////////////////////////////////////////////
//content_width
//////////////////////////////////////////////////
if (!isset($content_width)) $content_width = 1100;




//////////////////////////////////////////////////
//デフォルトコメントフォーム文法エラー修正
//////////////////////////////////////////////////
function custom_comment_form($args) {
	$args['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea></p>';
	return $args;
}
add_filter('comment_form_defaults', 'custom_comment_form');




//////////////////////////////////////////////////
//カスタムメニュー設定
//////////////////////////////////////////////////
register_nav_menus( array(
    'header_menu' => 'ヘッダーメニュー',
    'footer_menu' => 'フッターメニュー'
));




//////////////////////////////////////////////////
//アイキャッチ画像設定
//////////////////////////////////////////////////
if ( get_option('fit_post_eyecatch') != 'value2' ) {
	add_theme_support('post-thumbnails');
}




//////////////////////////////////////////////////
//サムネイル画像追加
//////////////////////////////////////////////////
add_image_size('icatch', 890, 500, true);




//////////////////////////////////////////////////
//excerpt抜粋文字数設定
//////////////////////////////////////////////////
function custom_excerpt_length( $length ) {
	if (get_option('fit_theme_archiveWord')){
		$excerpt = get_option('fit_theme_archiveWord');
	}else{
		$excerpt = 200;
	}
	return $excerpt;
}   
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );




//////////////////////////////////////////////////
//投稿エディタで出力されるキャプションの設定
//////////////////////////////////////////////////
function custom_caption_code($attr, $content = null) {
    if ( ! isset( $attr['caption'] ) ) {
        if ( preg_match( '#((?:<a [^>]+>s*)?<img [^>]+>(?:s*</a>)?)(.*)#is', $content, $matches ) ) {
            $content = $matches[1];
            $attr['caption'] = trim( $matches[2] );
        }
    }

    $output = apply_filters('img_caption_shortcode', '', $attr, $content);
    if ( $output != '' )
        return $output;

    extract(shortcode_atts(array(
        'id'    => '',
        'align' => 'alignnone',
        'width' => '',
        'caption' => ''
    ), $attr, 'caption'));

    if ( 1 > (int) $width || empty($caption) )
        return $content;

    if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

    return '<figure ' . $id . 'class="wp-caption ' . esc_attr($align) . '">' . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
}
add_shortcode('caption', 'custom_caption_code');




//////////////////////////////////////////////////
// YouTube oEmbed DIVで囲む
//////////////////////////////////////////////////
function custom_youtube_oembed($code){
  if(strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false){
    $html = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2", $code);
	
    $html = preg_replace('/ width="\d+"/', '', $html);
    $html = preg_replace('/ height="\d+"/', '', $html);
    $html = '<div class="youtube">' . $html . '</div>';

    return $html;
  }
  return $code;
}

add_filter('embed_handler_html', 'custom_youtube_oembed');
add_filter('embed_oembed_html', 'custom_youtube_oembed');




//////////////////////////////////////////////////
// 不要なページを無効化(404扱い)
//////////////////////////////////////////////////
function custom_handle_404() {
    // 添付ファイルページを無効化
    if ( is_attachment() ) {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        nocache_headers();
    }
}
add_action( 'template_redirect', 'custom_handle_404' );




//////////////////////////////////////////////////
//ウィジェット追加
//////////////////////////////////////////////////
function arphabet_widgets_init() {
	register_sidebar( array(
		'name' => '通常サイドバーエリア',
		'description' => 'サイドバーにコンテンツを表示します。',
		'id' => 'sidebar',
		'before_widget' => '<aside class="widget">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="heading heading-widget">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => '固定サイドバーエリア',
		'description' => '通常のサイドバーエリアの下にコンテンツを表示します。',
		'id' => 'sidebar-sticky',
		'before_widget' => '<aside class="widget">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="heading heading-widget">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => 'TOPページ上部エリア',
		'description' => 'TOPページの上部にコンテンツを表示します。',
		'id' => 'top',
		'before_widget' => '<aside class="widget">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="heading heading-secondary">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => '記事上エリア',
		'description' => '記事の上(投稿の本文の始まり)にコンテンツを表示します。',
		'id' => 'post-top',
		'before_widget' => '<aside class="widget">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="heading heading-secondary">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => '記事下エリア',
		'description' => '記事の下(投稿の本文の終わり)にコンテンツを表示します。',
		'id' => 'post-bottom',
		'before_widget' => '<aside class="widget">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="heading heading-secondary">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => '関連記事内広告エリア',
		'description' => '投稿の下に表示される関連記事内に広告を表示します。広告以外のコンテンツも表示可能。',
		'id' => 'related-ad',
		'before_widget' => '<li class="related__item">',
		'after_widget' => '</li>',
		'before_title' => '',
		'after_title' => '',
	));
}
add_action( 'widgets_init', 'arphabet_widgets_init' );




//////////////////////////////////////////////////
//広告ウィジェットアイテム
//////////////////////////////////////////////////
class AdWidgetItemClass extends WP_Widget {
	function __construct() {
		$widget_option = array('description' => '様々な広告に利用できるテキストエリア');
		parent::__construct( false, $name = '[LION]広告', $widget_option );
	}
 
	// 設定を表示するメソッド
	function widget( $args, $instance ) {
		extract( $args );
 
		echo $before_widget;
		echo '<div class="adWidget">';
		
		// 本文を取得
		$body = $instance[ 'body' ];
		if( $body != '' ) {
			echo $body; 
		}
 
		echo '<h2>Advertisement</h2></div>';
		echo $after_widget;
 
	}
	
	// 設定を保存するメソッド
	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	
	// 設定フォームを出力するメソッド
	function form( $instance ) {
		?>
        <p>
          <label for="<?php echo $this->get_field_id('body'); ?>">広告タグ:</label>
          <textarea class="widefat" rows="8" id="<?php echo $this->get_field_id('body'); ?>" name="<?php echo $this->get_field_name('body'); ?>"><?php echo @$instance['body']; ?></textarea>
		</p>
		<?php
	}
 
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "AdWidgetItemClass" );' ) );




//////////////////////////////////////////////////
//人気記事一覧ウィジェットアイテム
//////////////////////////////////////////////////
class Popular_Posts extends WP_Widget {
	function __construct() {
		$widget_option = array('description' => 'PV数の多い順で記事を表示');
		parent::__construct( false, $name = '[LION]人気記事', $widget_option );
	}
	
	// 設定フォームを出力するメソッド
	function form($instance) {
		$time  = !empty($instance['time']) ? 'checked' : '';
		?>
        <p>
		  <p>
		  <label for="<?php echo $this->get_field_id('title'); ?>">タイトル:</label>
		  <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( @$instance['title'] ); ?>">
		  </p>
		
		  <p>
		  <label for="<?php echo $this->get_field_id('number'); ?>">表示する投稿数:</label>
		  <input class="tiny-text" type="number" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo esc_attr( @$instance['number'] ); ?>" step="1" min="1" max="10" size="3">
          </p>
          
          <p>
          <input class="checkbox" type="checkbox" <?php echo $time; ?> id="<?php echo $this->get_field_id('time'); ?>" name="<?php echo $this->get_field_name('time'); ?>" />
          <label for="<?php echo $this->get_field_id('time'); ?>">投稿日を表示しますか ?</label>
          </p>

        </p>
		<?php
	}
	
	//カスタマイズ欄の入力内容が変更された場合の処理
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = is_numeric($new_instance['number']) ? $new_instance['number'] : 5;
		$instance['time'] = strip_tags($new_instance['time']);

		return $instance;
	}

	
	// 設定を表示するメソッド
	function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		$title = NULL;
		if(!empty($instance['title'])) {
			$title = apply_filters('widget_title', $instance['title'] );
		}
		
		if ($title) {
			echo $before_title . $title . $after_title;
		} else {
			echo '<h2 class="heading heading-widget">RANKING</h2>';
		}
		$number = !empty($instance['number']) ? $instance['number'] : 5;
		

		get_the_ID();
		$args = array(
			'meta_key'=> 'post_views_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'ignore_sticky_posts' => '1',
			'posts_per_page' => $number
		);
		$my_query = new WP_Query( $args );?>
        <ol class="rankListWidget">
<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
          <li class="rankListWidget__item<?php if ( get_option('fit_post_eyecatch') == 'value2' ) :	?> rankListWidget__item-noeye<?php endif; ?>">
            <?php if ( get_option('fit_post_eyecatch') != 'value2' ) :	?>
            <div class="eyecatch eyecatch-widget u-txtShdw">
              <a href="<?php the_permalink(); ?>">
			    <?php if(has_post_thumbnail()) {the_post_thumbnail('icatch');} else {echo '<img src="'.get_template_directory_uri().'/img/img_no.gif" alt="NO IMAGE"/>';}?>
              </a>
            </div>
            <?php endif; ?>
            <h3 class="rankListWidget__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="dateList dateList-widget<?php if ( get_option('fit_post_eyecatch') == 'value2' ) :	?> dateList-noeye<?php endif; ?>">
              <?php if(!empty($instance['time'])) :	?><span class="dateList__item icon-calendar"><?php the_time('Y.m.d'); ?></span><?php endif; ?>
              <span class="dateList__item icon-folder"><?php the_category(' ');?></span>
            </div>
          </li>
<?php endwhile; wp_reset_postdata(); ?>
        </ol>
		<?php
        echo $after_widget;
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Popular_Posts" );' ) );




//////////////////////////////////////////////////
//新着記事ウィジェットアイテムのフォーマット変更
//////////////////////////////////////////////////
Class fit_recent_posts_widget extends wp_widget_recent_posts {
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
                 
        if( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
            $number = 10;
                     
        $r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        if( $r->have_posts() ) :
             
            echo $before_widget;
            if( $title ) echo $before_title . $title . $after_title; ?>
            <ol class="imgListWidget">
              <?php while( $r->have_posts() ) : $r->the_post(); ?>                
              <li class="imgListWidget__item">
                <?php if ( get_option('fit_post_eyecatch') != 'value2' ) :	?>
                  <a class="imgListWidget__borderBox" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><span>
                  <?php if ( has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('thumbnail'); ?>
                  <?php else: ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/img/img_no_thumbnail.gif" alt="NO IMAGE">
                  <?php endif; ?>
                  </span></a>
                <?php endif; ?>
                <h3 class="imgListWidget__title<?php if ( get_option('fit_post_eyecatch') == 'value2' ) :	?> imgListWidget__title-noeye<?php endif; ?>">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  <?php if( !empty( $instance['show_date'] )): ?><span class="post-date"><?php the_time('Y.m.d'); ?></span><?php endif; ?>
                </h3>
              </li>
              <?php endwhile; ?>
            </ol>
            <?php
            echo $after_widget;
         
        wp_reset_postdata();         
        endif;
    }
}
function fit_recent_widget_registration() {
	unregister_widget('wp_widget_recent_posts'); register_widget('fit_recent_posts_widget');
}
add_action('widgets_init', 'fit_recent_widget_registration');




//////////////////////////////////////////////////
//SNSボタンリスト
//////////////////////////////////////////////////	
function fit_share_btn(){
	$options = get_option('fit_post_share');
	if ( $options['facebook'] || $options['twitter'] || $options['google'] || $options['hatebu'] || $options['pocket'] || $options['line'] ) {
		echo '<aside>'."\n";
		echo '<ul class="socialList">'."\n";
		if ( $options['facebook'] ) {
			echo '<li class="socialList__item"><a class="socialList__link icon-facebook" href="http://www.facebook.com/sharer.php?u='. urlencode(get_permalink()) .'&amp;t='. urlencode(the_title("","",0)) .'" target="_blank" title="Facebookで共有"></a></li>';
		}if ( $options['twitter'] ) {
			echo '<li class="socialList__item"><a class="socialList__link icon-twitter" href="http://twitter.com/intent/tweet?text='. urlencode(the_title("","",0)) .'&amp;'. urlencode(get_permalink()) .'&amp;url='. urlencode(get_permalink()) .'" target="_blank" title="Twitterで共有"></a></li>';
		}if ( $options['google'] ) {
			echo '<li class="socialList__item"><a class="socialList__link icon-google" href="https://plus.google.com/share?url='. urlencode(get_permalink()) .'" target="_blank" title="Google+で共有"></a></li>';
    	}if ( $options['hatebu'] ) {
			echo '<li class="socialList__item"><a class="socialList__link icon-hatebu" href="http://b.hatena.ne.jp/add?mode=confirm&amp;url='. urlencode(get_permalink()) .'&amp;title='. urlencode(the_title("","",0)) .'" target="_blank" data-hatena-bookmark-title="'. urlencode(get_permalink()) .'" title="このエントリーをはてなブックマークに追加"></a></li>';
		}if ( $options['pocket'] ) {
			echo '<li class="socialList__item"><a class="socialList__link icon-pocket" href="http://getpocket.com/edit?url='. urlencode(get_permalink()) .'" target="_blank" title="pocketで共有"></a></li>';
		}if ( $options['line'] ) {
			echo '<li class="socialList__item"><a class="socialList__link icon-line" href="http://line.naver.jp/R/msg/text/?'. urlencode(the_title("","",0)) .'%0D%0A'. urlencode(get_permalink()) .'" target="_blank" title="LINEで送る"></a></li>';
		}
    	echo '</ul>'."\n";
		echo '</aside>'."\n";
	}
}




//////////////////////////////////////////////////
//プロフィール項目追加
//////////////////////////////////////////////////
function custom_user_contact( $user_contact ) {
    $user_contact['facebook'] = __( 'Facebook URL', 'text_domain' );
    $user_contact['twitter'] = __( 'Twitter URL', 'text_domain' );
	$user_contact['instagram'] = __( 'Instagram URL', 'text_domain' );
    $user_contact['gplus'] = __( 'Google+ URL', 'text_domain' );
	return $user_contact;
}
add_filter( 'user_contactmethods', 'custom_user_contact' );


function add_user_group_form( $bool ) {
    global $profileuser;
    if ( preg_match( '/^(profile\.php|user-edit\.php)/', basename( $_SERVER['REQUEST_URI'] ) ) ) { ?>
    <tr>
      <th scope="row">役職 / 所属</th>
      <td>
        <input type="text" name="user_group" id="user_group" value="<?php echo esc_html( $profileuser->user_group ); ?>" class="regular-text" />
      </td>
    </tr>
<?php }
    return $bool;
}
add_action( 'show_password_fields', 'add_user_group_form' );


function update_user_group( $user_id, $old_user_data ) {
	if ( isset( $_POST['user_group'] ) && $old_user_data->user_group != $_POST['user_group'] ) {
        $user_group = sanitize_text_field( $_POST['user_group'] );
        $user_group = wp_filter_kses( $user_group );
        $user_group = _wp_specialchars( $user_group );
        update_user_meta( $user_id, 'user_group', $user_group );
    }
}
add_action( 'profile_update', 'update_user_group', 10, 2 );




//////////////////////////////////////////////////
//投稿ページカテゴリー選択を1つのみに変更
//////////////////////////////////////////////////
function limit_category_select() {?>
	<script type="text/javascript">
	jQuery(function($) {
		// 投稿画面のカテゴリー選択を制限
		var categorydiv = $( '#categorydiv input[type=checkbox]' );
		categorydiv.click( function() {
			$(this).parents( '#categorydiv' ).find( 'input[type=checkbox]' ).attr('checked', false);
			$(this).attr( 'checked', true );
		});
		// クイック編集のカテゴリー選択を制限
		var inline_edit_col_center = $( '.inline-edit-col-center input[type=checkbox]' );
		inline_edit_col_center.click( function() {
			$(this).parents( '.inline-edit-col-center' ).find( 'input[type=checkbox]' ).attr( 'checked', false );
			$(this).attr( 'checked', true );
		});
		$( '#categorydiv #category-pop > ul > li:first-child, #categorydiv #category-all > ul > li:first-child, .inline-edit-col-center ul.category-checklist > li:first-child' ).before( '<p style="padding-top:5px;">カテゴリーは1つしか選択できません</p>' );
	});
	</script>
  <?php }
add_action( 'admin_print_footer_scripts', 'limit_category_select' );




//////////////////////////////////////////////////
//イメージURLから画像のIDを取得
//////////////////////////////////////////////////
function fit_get_image_id($image_src){
	global $wpdb;
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	$id = $wpdb->get_var($query);
	return $id;
}


//////////////////////////////////////////////////
//ショートコードで記事中に広告を挿入
//////////////////////////////////////////////////
function shortcode_adsense() {
	$myAmp = false;
	if(get_option('fit_anp_check') == 'value2' && is_single() && @$_GET['amp'] === '1'){
		$myAmp = true;
	}
	if (!$myAmp){
		return '<div class="adPost">'.get_option('fit_ad_post').'<span class="adPost__title">Advertisement</span></div>';
	}else{
		return '';
	}
}
add_shortcode('adchord', 'shortcode_adsense');


//////////////////////////////////////////////////
//オリジナルページネーションを作成
//////////////////////////////////////////////////
function fit_posts_pagination( $args = array() ) {
    $navigation = '';
 
    if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
        $args = wp_parse_args( $args, array(
            'mid_size'           => 0,
            'prev_text'          => 'PREV',
            'next_text'          => 'NEXT',
        ) );
 
        if ( isset( $args['type'] ) && 'array' == $args['type'] ) {
            $args['type'] = 'plain';
        }
 
        $links = paginate_links( $args );
 
        if ( $links ) {
            $template = '<div class="pager">%1$s</div>';
            $navigation = sprintf( $template, $links );
        }
    }
 
    echo $navigation;
}




//////////////////////////////////////////////////
//オリジナルサブページネーションを作成
//////////////////////////////////////////////////
//前後の記事のリンクにclassを追加
function add_prev_posts_link_attr(){
	return 'class="subPager__link"';
}
add_filter('previous_posts_link_attributes', 'add_prev_posts_link_attr');

function add_next_posts_link_attr(){
	return 'class="subPager__link"';
}
add_filter('next_posts_link_attributes', 'add_next_posts_link_attr');



//現在のページ数の取得
function show_page_number() {
    global $wp_query;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $max_page = $wp_query->max_num_pages;
    echo $paged;
}
//総ページ数の取得
function max_show_page_number() {
    global $wp_query;
    $max_page = $wp_query->max_num_pages;
    echo $max_page;
}

//出力用本体
function fit_sub_pagination(){

	$prev_link = get_previous_posts_link('&lt;');
	$next_link = get_next_posts_link('&gt;');
	
	if ( isset( $prev_link ) or isset( $next_link ) ) {
		echo '<div class="subPager">';
		echo '<span class="subPager__text">',show_page_number(''),'/',max_show_page_number(''),'ページ</span>';
		echo '<ul class="subPager__list">';
		if( isset( $prev_link ) ) {
			echo '<li class="subPager__item">',$prev_link,'</li>';
		}
		if( isset( $next_link ) ) {
			echo '<li class="subPager__item">',$next_link,'</li>';
		}
		echo '</ul></div>';
	}
}




//////////////////////////////////////////////////
//オリジナルパンくずリストを作成
//////////////////////////////////////////////////
function fit_breadcrumb( $args = array() ){
	global $post;
	$str ='';
	$defaults = array(
		'class' => "breadcrumb",
		'home' => "HOME",
		'search' => "の検索結果 ",
		'tag' => "",
		'author' => "",
		'notfound' => "Hello! My Name Is 404",
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

		if( !is_home() && !is_admin() ){
			$str.= '<div class="'. $class .'" >';
			$str.= '<div class="container" >';
			$str.= '<ul class="breadcrumb__list">';
			$str.= '<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. home_url() .'/" itemprop="url"><span class="icon-home" itemprop="title">'. $home .'</span></a></li>';
			$my_taxonomy = get_query_var( 'taxonomy' );
			$cpt = get_query_var( 'post_type' );

		if( $my_taxonomy && is_tax( $my_taxonomy ) ) {
			$my_tax = get_queried_object();
			$post_types = get_taxonomy( $my_taxonomy )->object_type;
			$cpt = $post_types[0];
			$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' .get_post_type_archive_link( $cpt ).'" itemprop="url"><span itemprop="title">'. get_post_type_object( $cpt )->label.'</span></a></li>';

		if( $my_tax -> parent != 0 ) {
			$ancestors = array_reverse( get_ancestors( $my_tax -> term_id, $my_tax->taxonomy ) );

			foreach( $ancestors as $ancestor ){
				$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_term_link( $ancestor, $my_tax->taxonomy ) .'" itemprop="url"><span itemprop="title">'. get_term( $ancestor, $my_tax->taxonomy )->name .'</span></a></li>';
			}
		}
			$str.='<li class="breadcrumb__item">'. $my_tax -> name . '</li>';
		}

		elseif( is_category() ) {
			$cat = get_queried_object();
			if( $cat -> parent != 0 ){
				$ancestors = array_reverse( get_ancestors( $cat -> cat_ID, 'category' ));
				foreach( $ancestors as $ancestor ){
					$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link( $ancestor ) .'" itemprop="url"><span itemprop="title">'. get_cat_name( $ancestor ) .'</span></a></li>';
				}
			}
			$str.='<li class="breadcrumb__item">'. $cat -> name . '</li>';
		}

		elseif( is_post_type_archive() ) {
			$cpt = get_query_var( 'post_type' );
			$str.='<li class="breadcrumb__item">'. get_post_type_object( $cpt )->label . '</li>';
		}

		elseif( $cpt && is_singular( $cpt ) ){
			$taxes = get_object_taxonomies( $cpt );
			$mytax = $taxes[0];
			$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' .get_post_type_archive_link( $cpt ).'" itemprop="url"><span itemprop="title">'. get_post_type_object( $cpt )->label.'</span></a></li>';
			$taxes = get_the_terms( $post->ID, $mytax );
			$tax = get_youngest_tax( $taxes, $mytax );

		if( $tax -> parent != 0 ){
			$ancestors = array_reverse( get_ancestors( $tax -> term_id, $mytax ) );
			foreach( $ancestors as $ancestor ){
				$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_term_link( $ancestor, $mytax ).'" itemprop="url"><span itemprop="title">'. get_term( $ancestor, $mytax )->name . '</span></a></li>';
			}
		}
			$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_term_link( $tax, $mytax ).'" itemprop="url"><span itemprop="title">'. $tax -> name . '</span></a></li>';
			$str.= '<li class="breadcrumb__item">'. $post -> post_title .'</li>';
		}

		elseif( is_single() ){
			$categories = get_the_category( $post->ID );
			$cat = get_youngest_cat( $categories );
			if( $cat -> parent != 0 ){
				$ancestors = array_reverse( get_ancestors( $cat -> cat_ID, 'category' ) );
			foreach( $ancestors as $ancestor ){
				$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link( $ancestor ).'" itemprop="url"><span itemprop="title">'. get_cat_name( $ancestor ). '</span></a></li>';
			}
		}
			$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link( $cat -> term_id ). '" itemprop="url"><span itemprop="title">'. $cat-> cat_name . '</span></a></li>';
			$str.= '<li class="breadcrumb__item">'. $post -> post_title .'</li>';
        }

		elseif( is_page() ){
			if( $post -> post_parent != 0 ){
				$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
				foreach( $ancestors as $ancestor ){
					$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_permalink( $ancestor ).'" itemprop="url"><span itemprop="title">'. get_the_title( $ancestor ) .'</span></a></li>';
				}
			}
			$str.= '<li class="breadcrumb__item">'. $post -> post_title .'</li>';
		}

		elseif( is_date() ){
			if( get_query_var( 'day' ) != 0){
				$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_year_link(get_query_var('year')). '" itemprop="url"><span itemprop="title">' . get_query_var( 'year' ). '年</span></a></li>';
				$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_month_link(get_query_var( 'year' ), get_query_var( 'monthnum' ) ). '" itemprop="url"><span itemprop="title">'. get_query_var( 'monthnum' ) .'月</span></a></li>';
				$str.='<li class="breadcrumb__item">'. get_query_var('day'). '日</li>';
		}

		elseif( get_query_var('monthnum' ) != 0){
			$str.='<li class="breadcrumb__item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_year_link( get_query_var('year') ) .'" itemprop="url"><span itemprop="title">'. get_query_var( 'year' ) .'年</span></a></li>';
			$str.='<li class="breadcrumb__item">'. get_query_var( 'monthnum' ). '月</li>';
		}

		else {
			$str.='<li class="breadcrumb__item">'. get_query_var( 'year' ) .'年</li>';
		}
		}

		elseif( is_search() ) {
			$str.='<li class="breadcrumb__item">「'. get_search_query() .'」'. $search .'</li>';
		}

		elseif( is_author() ){
			$str .='<li class="breadcrumb__item">'. $author . get_the_author_meta('display_name', get_query_var( 'author' )).'</li>';
		}

		elseif( is_tag() ){
			$str.='<li class="breadcrumb__item">'. $tag . single_tag_title( '' , false ). '</li>';
		}

		elseif( is_attachment() ){
			$str.= '<li class="breadcrumb__item">'. $post -> post_title .'</li>';
		}

		elseif( is_404() ){
			$str.='<li class="breadcrumb__item">'.$notfound.'</li>';
		}

		else{
			$str.='<li class="breadcrumb__item">'. wp_title( '', true ) .'</li>';
		}

			$str.='</ul>';
			$str.='</div>';
			$str.='</div>';
		}
	echo $str;
}

function get_youngest_cat( $categories ){
	global $post;
	if(count( $categories ) == 1 ){
		$youngest = $categories[0];
	}
	else{
		$count = 0;
		foreach( $categories as $category ){
			$children = get_term_children( $category -> term_id, 'category' );
			if($children){
				if ( $count < count( $children ) ){
					$count = count( $children );
					$lot_children = $children;
					foreach( $lot_children as $child ){
						if( in_category( $child, $post -> ID ) ){
							$youngest = get_category( $child );
						}
					}
				}
			}
			else{
				$youngest = $category;
			}
		}
	}
	return $youngest;
}

function get_youngest_tax( $taxes, $mytaxonomy ){
	global $post;
	if( count( $taxes ) == 1 ){
		$youngest = $taxes[ key( $taxes )];
	}
	else{
		$count = 0;
		foreach( $taxes as $tax ){
			$children = get_term_children( $tax -> term_id, $mytaxonomy );
			if($children){
				if ( $count < count($children) ){
					$count = count($children);
					$lot_children = $children;
					foreach($lot_children as $child){
						if( is_object_in_term( $post -> ID, $mytaxonomy ) ){
							$youngest = get_term($child, $mytaxonomy);
						}
					}
				}
			}
			else{
				$youngest = $tax;
			}
		}
	}
	return $youngest;
}




//////////////////////////////////////////////////
//オリジナル目次を作成
//////////////////////////////////////////////////
function get_outline_info($content) {
	// 目次のHTMLを入れる変数を定義します。
	$outline = '';
	// h1〜h6タグの個数を入れる変数を定義します。
	$counter = 0;
    // 記事内のh1〜h6タグを検索します。(idやclass属性も含むように改良)
    if (preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h\1>/', $content, $matches,  PREG_SET_ORDER)) {
    	   // 記事内で使われているh1〜h6タグの中の、1〜6の中の一番小さな数字を取得します。
    	   // ※以降ソースの中にある、levelという単語は1〜6のことを表します。
        $min_level = min(array_map(function($m) { return $m[1]; }, $matches));
        // スタート時のlevelを決定します。
        // ※このレベルが上がる毎に、<ul></li>タグが追加されていきます。
        $current_level = $min_level - 1;
        // 各レベルの出現数を格納する配列を定義します。
        $sub_levels = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0);
        // 記事内で見つかった、hタグの数だけループします。
        foreach ($matches as $m) {
            $level = $m[1];  // 見つかったhタグのlevelを取得します。
            $text = $m[2];  // 見つかったhタグの、タグの中身を取得します。
            // li, ulタグを閉じる処理です。2ループ目以降に中に入る可能性があります。
            // 例えば、前回処理したのがh3タグで、今回出現したのがh2タグの場合、
            // h3タグ用のulを閉じて、h2タグに備えます。
            while ($current_level > $level) {
                $current_level--;
                $outline .= '</li></ul>';
            }
            // 同じlevelの場合、liタグを閉じ、新しく開きます。
            if ($current_level == $level) {
                $outline .= '</li><li class="outline__item">';
            } else {
                // 同じlevelでない場合は、ul, liタグを追加していきます。
                // 例えば、前回処理したのがh2タグで、今回出現したのがh3タグの場合、
                // h3タグのためにulを追加します。
                while ($current_level < $level) {
                    $current_level++;
                    $outline .= sprintf('<ul class="outline__list outline__list-%s"><li class="outline__item">', $current_level);
                }
                // 見出しのレベルが変わった場合は、現在のレベル以下の出現回数をリセットします。
                for ($idx = $current_level + 0; $idx < count($sub_levels); $idx++) {
                    $sub_levels[$idx] = 0;
                }
            }
            // 各レベルの出現数を格納する配列を更新します。
            $sub_levels[$current_level]++;
            // 現在処理中のhタグの、パスを入れる配列を定義します。
            // 例えば、h2 -> h3 -> h3タグと進んでいる場合は、
            // level_fullpathはarray(1, 2)のようになります。
            // ※level_fullpath[0]の1は、1番目のh2タグの直下に入っていることを表します。
            // ※level_fullpath[1]の2は、2番目のh3を表します。
            $level_fullpath = array();
            for ($idx = $min_level; $idx <= $level; $idx++) {
                $level_fullpath[] = $sub_levels[$idx];
            }
            $target_anchor = 'outline__' . implode('_', $level_fullpath);

            // 目次に、<a href="#outline_1_2">1.2 見出し</a>のような形式で見出しを追加します。
            $outline .= sprintf('<a class="outline__link" href="#%s"><span class="outline__number">%s.</span> %s</a>', $target_anchor, implode('.', $level_fullpath), strip_tags($text));
            // 本文中の見出し本体を、<h3>見出し</h3>を<h3 id="outline_1_2">見出し</h3>
            // のような形式で置き換えます。
            $hid = preg_replace('/<h([1-6])/', '<h\1 id="' .$target_anchor . '"', $m[0]);
            $content = str_replace($m[0], $hid, $content);
			
        }
        // hタグのループが終了後、閉じられていないulタグを閉じていきます。
        while ($current_level >= $min_level) {
            $outline .= '</li></ul>';
            $current_level--;
        }
        // h1〜h6タグの個数
        $counter = count($matches);
    }
    return array('content' => $content, 'outline' => $outline, 'count' => $counter);
}

//目次を作成します。
function add_outline($content) {

    // 目次を表示するために必要な見出しの数
	if(get_option('fit_post_outline_number')){
		$number = get_option('fit_post_outline_number');
	}else{
		$number = 1;
	}
    // 目次関連の情報を取得します。
    $outline_info = get_outline_info($content);
    $content = $outline_info['content'];
    $outline = $outline_info['outline'];
    $count = $outline_info['count'];
	if (get_option('fit_post_outline_close') ) {
		$close = "";
	}else{
		$close = "checked";
	}
    if ($outline != '' && $count >= $number) {
        // 目次を装飾します。
        $decorated_outline = sprintf('
		<div class="outline">
		  <span class="outline__title">目次</span>
		  <input class="outline__toggle" id="outline__toggle" type="checkbox" '.$close.'>
		  <label class="outline__switch" for="outline__toggle"></label>
		  %s
		</div>', $outline);
        // カスタマイザーで目次を非表示にする以外が選択された時＆個別非表示が1以外の時に目次を追加します。
		if ( get_option('fit_post_outline') != 'value2' && get_post_meta(get_the_ID(), 'outline_none', true) != '1' && is_single() ) {
        	$shortcode_outline = '[outline]';
        	if (strpos($content, $shortcode_outline) !== false) {
            	// 記事内にショートコードがある場合、ショートコードを目次で置換します。
            	$content = str_replace($shortcode_outline, $decorated_outline, $content);
        	} else if (preg_match('/<h[1-6].*>/', $content, $matches, PREG_OFFSET_CAPTURE)) {
            	// 最初のhタグの前に目次を追加します。
            	$pos = $matches[0][1];
            	$content = substr($content, 0, $pos) . $decorated_outline . substr($content, $pos);
        	}
		}
    }
	return $content;
}
add_filter('the_content', 'add_outline');



//////////////////////////////////////////////////
//fit_amp_head　オリジナル項目追加
//////////////////////////////////////////////////
function fit_amp_head() {
	if (get_fit_image_logo()) {
		$logo = get_fit_image_logo();
		$image_id = fit_get_image_id($logo);
		$image = wp_get_attachment_image_src( $image_id, 'full' );
		$src = $image[0]; //url
		$width = $image[1]; //横幅
		$height = $image[2]; //高さ
	}
?>
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
<title><?php echo wp_get_document_title(); ?></title>
<link rel="canonical" href="<?php echo get_permalink(); ?>" />
<style amp-custom>
html,body,p,ol,ul,li,dl,dt,dd,blockquote,figure,fieldset,legend,textarea,pre,iframe,hr,h1,h2,h3,h4,h5,h6{margin:0;padding:0;}h1,h2,h3,h4,h5,h6{font-size:100%;}ol,ul,li,dl{list-style-position: inside;}button,input,select,textarea{margin:0;}html{box-sizing:border-box;line-height:1;font-size: 62.5%;}*,*:before,*:after{box-sizing:inherit;}img,embed,iframe,object,audio,video{max-width:100%;}iframe{border:0;}table{border-collapse:collapse;border-spacing:0;}td,th{padding:0;text-align:left;}hr{height: 0;border: 0;}body{width:100%;font-family: "Lato", "游ゴシック体", "Yu Gothic", "YuGothic", "ヒラギノ角ゴシック Pro", "Hiragino Kaku Gothic Pro", "メイリオ", "Meiryo, Osaka", "MS Pゴシック", "MS PGothic", "sans-serif";font-size: 1.4rem;font-weight:500;background:#F2F2F2;color:#191919;}button, input, select, textarea{font-family:inherit;font-weight:inherit;font-size:  inherit;}a{color:inherit;text-decoration:none;}

.l-header{display:flex;flex-flow: column-reverse nowrap;}.l-hExtra{position:relative;width:100%;background:#191919;padding-top:10px;}.l-hExtra::after{content: "";display: block;clear: both;}.l-hMain{width:100%;background:#323232;}.l-hMain::after{content: "";display: block;clear: both;}.l-wrapper{position:relative;display: flex;width:1100px;max-width:95%;    margin: 0 auto;}.l-main{width: 820px;max-width: 100%;padding: 60px 30px;margin: 0 auto;background: #fff;border-left: #e5e5e5 1px solid;border-right: #e5e5e5 1px solid}.l-main.l-main-single-740{width: 740px}.l-main.l-main-single-900{width: 900px} .l-main.l-main-single-100{width: 100%}.l-footer{position:relative;width:100%;background:#191919;}.container{position:relative;width:1100px;max-width:95%;margin: 0 auto;}.container::after{content: "";display: block;clear: both;}.infoHead{text-align:center;background:#c53929}.infoHead__link{display:block;font-weight:700;color:#fff;height:30px;line-height:30px}.infoHead__link:hover{background:hsla(0,0%,100%,.15);transition:.2s}.marquee{float:left;margin-bottom:10px;font-size:1.2rem;width:50%;width : calc(50% - 10px) ;}.marquee::after{content: "";display: block;clear: both;}.marquee__title{font-weight: 900;float:left;width:110px;height:30px;line-height:30px;text-align:center;background:#323232;margin-right:10px;color:#ffffff;}.marquee__item{position:relative;float:left;line-height:30px;color:#BFBFBF;width:calc(100% - 120px);overflow: hidden;}.marquee__link{padding-left:100%;display:inline-block;white-space:nowrap;animation-name:marquee;animation-timing-function:linear;animation-duration:15s;animation-iteration-count:infinite;}.marquee__link:after{content:"";white-space:nowrap;padding-right:50px;}.socialSearch{float:right;width: 50%;display: flex;justify-content: flex-end;align-items: flex-end}.socialSearch__list{list-style:none;display:inline-block;}.socialSearch__list::after{content: "";display: block;clear: both;}.socialSearch__item{float:left;}.socialSearch__link{display:block;width:30px;height:30px;line-height:30px;margin-left:10px;text-align:center;font-size:1.2rem;color:#bfbfbf;border:#323232 1px solid;border-radius:50%;}.socialSearch__link:hover{border:0;color:#ffffff;transition: .2s;}.searchBox{font-size:1.2rem;flex-grow: 1;}.searchBox__form{position:relative;height:30px;border-radius:5px;background:#FFF;}.searchBox__input{position:absolute;top:0;left:0;width:calc(100% - 30px);height: inherit;border: none;padding:0 10px;background:transparent;}.searchBox__submit{position:absolute;top:0;right:0;width:30px;height:inherit;line-height:30px;border: none;cursor:pointer;background:transparent;}.siteTitle{float:left;max-width:220px;padding:40px 0;}.siteTitle.siteTitle-noneAd{max-width: 100%;padding-right:40px;}.siteTitle__big{font-weight: 900;color:#ffffff;font-size:2.8rem;letter-spacing:0.5px;margin-bottom:10px;}.siteTitle__small{font-weight: 400;color:#BFBFBF;font-size:1.2rem;letter-spacing:0.5px;}.siteTitle__link{position:relative;display: inline-block;}.siteTitle__logo .siteTitle__link{width: <?php echo $width;?>px;max-width: 220px;height: 50px; overflow: hidden;}.siteTitle__link::after{content: "";position: absolute;bottom: -5px;left: 0;width: 0;height: 0;border-bottom: 2px solid transparent;}.siteTitle__link:hover::after{width: 100%;border-color: #7F7F7F;transition: .2s;}.globalNavi{position:relative;float:left;width:100%;}.globalNavi ul{list-style:none;}.globalNavi__list{background:#63acb7;border-radius:5px 5px 0 0;}.globalNavi__list::after{content: "";display: block;clear: both;}.globalNavi__list a{display:block;padding:0 20px;height:54px;line-height:54px;}.globalNavi__list a:hover{background:rgba(255,255,255,0.15);transition: .2s;}.globalNavi__list > li{position:relative;float:left;color:#ffffff;border-left: 1px solid rgba(255,255,255,0.15);border-right: 1px solid rgba(0,0,0,0.15);}.globalNavi__list > li:first-child{border-left: none;}.globalNavi__list > li:first-child a{border-radius:5px 0 0 0;}.globalNavi__list > li:last-child::after{content: "";position: absolute;top: 0;right: -2px;bottom: 0;border-left: 1px solid rgba(255,255,255,0.15);}.globalNavi__list > li.page_item_has_children::before,.globalNavi__list > li.menu-item-has-children::before{content: "";position: absolute;top: 50%;right: 20px;width: 5px;height: 5px;margin-top: -5px;border-bottom: solid 1px #ffffff;border-right: solid 1px #ffffff;transform: rotate(45deg);}.globalNavi__list > li.page_item_has_children > a,.globalNavi__list > li.menu-item-has-children > a{padding:0 30px 0 20px;}.globalNavi__list > li .children,.globalNavi__list > li .sub-menu{position: absolute;top: 100%;left: 0;z-index:9999;}.globalNavi__list > li .children > li,.globalNavi__list > li .sub-menu > li{overflow: hidden;width: 300px;height: 0;background-color:#323232;transition: 0.2s;}.globalNavi__list > li:hover .children li,.globalNavi__list > li:hover .sub-menu li{overflow: visible;border-top: 1px solid rgba(0,0,0,0.15);box-shadow:0px 1px 0px 0px rgba(255,255,255,0.15) inset;height:54px;line-height:54px;transition: 0.2s;}.globalNavi__toggle{display: none;}.globalNavi__switch{display: none;}.breadcrumb{margin-bottom:40px;padding:10px;background: #F2F2F2;}.breadcrumb__list{list-style:none;}.breadcrumb__list::after{content: "";display: block;clear: both;}.breadcrumb__item{position:relative;float:left;padding-right:15px;margin-right:15px;font-size:1.2rem;line-height: 1.75;color:#7f7f7f;}.breadcrumb__item .icon-home::before{margin-right: 5px;}.breadcrumb__item::after{content: "";position: absolute;right: 0;top: 50%;margin-top: -3px;width: 5px;height: 5px;border-top: 1px solid #BFBFBF;border-right: 1px solid #BFBFBF;transform: rotate(45deg);}.breadcrumb__item:last-child::after{border: none;}.breadcrumb__link{text-decoration:underline;line-height: 1;}.eyecatch{position:relative;width:100%;height:auto;margin-bottom:20px;overflow:hidden;}.eyecatch.eyecatch-widget{margin-bottom:10px;}.eyecatch img{width:100%;height:auto;vertical-align:bottom;transform: scale(1);transition: ease-in-out .2s;}.eyecatch img:hover{transform: scale(1.2);}.dateList{list-style:none;margin-bottom:10px;}.dateList.dateList-single{margin-bottom:20px;}.dateList__item{display:inline-block;text-align:left;color:#7f7f7f;font-size:1.2rem;margin-right:10px;line-height:1.5;}.dateList__item::before{margin-right:5px;line-height:1;}.dateList__item a:hover{color:#63acb7;transition: .2s;}.pagetop{position:relative;width:180px;height:60px;line-height:70px;margin:0 auto;background:#63acb7;color:#ffffff;text-align:center;border-radius:0 0 5px 5px;}.pagetop::before{content:"";position:absolute;top:15px;left: 50%;margin-left:-3px;width: 6px;height: 6px;border-top: 1px solid #ffffff;border-left: 1px solid #ffffff;transform: rotate(45deg);}.pagetop:hover::before{top:10px;transition: .2s;}.pagetop__link{display:block;height:inherit;}.pagetop__link:hover{background:rgba(0,0,0,0.15);transition: .2s;}.pagetop__link::before{content:"";position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,0);z-index:-1;transition: 0.1s;}.pagetop__link:active::before{background:rgba(255,255,255,0.9);z-index:9999;}.socialEffect{text-align: center;margin:40px auto 0 auto;}.socialEffect__icon{display: inline-block;margin:0 5px;}.socialEffect__link{display: block;width: 60px;height: 60px;line-height:60px;border-radius: 50%;text-align: center;position: relative;z-index: 1;color: #bfbfbf;font-size:1.6rem;background: rgba(255,255,255,0.1);transition: transform ease-out 0.2s, background 0.2s;}.socialEffect__link:after{content: ""; position: absolute;top: 0;left: 0;width: 100%;height: 100%;border-radius: 50%;z-index: -1;transform: scale(0.9);}.socialEffect__link:hover{background: rgba(255,255,255,0.05);transform: scale(0.9);color: #fff;}.socialEffect__link:hover:after{animation: sonar 1s ease-out 75ms;}.footerNavi{padding:40px 0 0 0;}.footerNavi__list{list-style:none;text-align: center;}.footerNavi__list li{display: inline-block;color:#ffffff;font-size:1.6rem;font-weight:700;}.footerNavi__list li a{display:block;padding:0 10px;line-height: 1.5;}.footerNavi__list li a:hover{color:#63acb7;transition: .2s;}.copyright{padding:40px 0;text-align:center;font-size:1.3rem;color:#BFBFBF;letter-spacing: 0.5px;line-height:1.75;}.copyright__info{display:block;}.copyright__link{font-weight:700;text-decoration:underline;color: #ffffff;}.copyright__link:hover{color:#63acb7;transition: .2s;}.heading{display:block;margin-bottom:20px;letter-spacing:0.5px;font-weight:700;}.heading.heading-primary{font-size:3rem;line-height:1.5;}.heading.heading-secondary{position: relative;border-bottom: 2px solid #E5E5E5;font-weight: 900;font-size: 1.8rem;line-height:1.5;padding-bottom:10px;margin-bottom:20px;}.heading.heading-secondary::before{content: "";position: absolute;bottom: -2px;left: 0;z-index: 2;width: 20%;height: 2px;background-color: #63acb7;}.heading a:hover{color:#63acb7;transition: .2s;}.btn{width:100%;}.btn.btn-center{text-align: center;}.btn.btn-right{text-align: right;}.btn__link{position:relative;display: inline-block;padding: 10px 40px;border-radius: 5px;font-size: 1.3rem;border: 1px solid #63acb7;color: #63acb7;background:transparent;cursor:pointer;}.btn__link::before{content:"";position:absolute;top: 50%;right: 10px;margin-top:-3px;width: 6px;height: 6px;border-top: 1px solid;border-right: 1px solid;transform: rotate(45deg);}.btn__link:hover{color:#ffffff;background:#63acb7;transition: .2s;}.btn__link.btn__link-profile{padding: 7px 20px 7px 10px; font-weight:500; line-height:1;}.socialSearch__link.icon-facebook:hover,.socialEffect__link.icon-facebook:hover,.profile__link.icon-facebook:hover{background: #3B5998;}.socialSearch__link.icon-twitter:hover,.socialEffect__link.icon-twitter:hover,.profile__link.icon-twitter:hover{background: #00B0ED;}.socialSearch__link.icon-instagram:hover,.socialEffect__link.icon-instagram:hover,.profile__link.icon-instagram:hover{background: radial-gradient(circle farthest-corner at 32% 106%, rgb(255, 225, 125) 0%, rgb(255, 205, 105) 10%, rgb(250, 145, 55) 28%, rgb(235, 65, 65) 42%, transparent 82%), linear-gradient(135deg, rgb(35, 75, 215) 12%, rgb(195, 60, 190) 58%);}.socialSearch__link.icon-google:hover,.socialEffect__link.icon-google:hover,.profile__link.icon-google:hover{background: #DF4A32;}.socialSearch__link.icon-rss:hover,.socialEffect__link.icon-rss:hover{background: #ff9900;}

.t-light .l-hExtra{background: #F2F2F2;}.t-light .l-hMain{background: #ffffff; position:relative;}.t-light .l-hMain::before{content:"";display:block;position:absolute;bottom:0;left:0;right:0;border-bottom:#63acb7 solid 54px;}.t-light .marquee__title{background: #ffffff; color:#191919;}.t-light .marquee__item{color:#191919;}.t-light .socialSearch__link{color: #191919;border: 1px solid #ffffff;background:#F2F2F2;}.t-light .socialSearch__link:hover{color: #ffffff;border: 0;}.t-light .siteTitle__big{color: #323232;}.t-light .siteTitle__small{color: #7F7F7F;}.t-light .globalNavi{border-right: 1px solid rgba(255,255,255,0.15);}.t-light .globalNavi__list > li:first-child{border-left: 1px solid rgba(255,255,255,0.15);}.t-light .globalNavi__list > li:first-child a{border-radius:0;}.t-light .globalNavi__list > li:first-child::before{content: "";position: absolute;top: 0;left: -2px;bottom: 0;border-right: 1px solid rgba(0,0,0,0.15);}.t-contrast .l-hMain{background: #ffffff;}.t-contrast .l-main::before{border-top:1px solid #E5E5E5;}.t-contrast .siteTitle__big{color: #323232;}.t-contrast .siteTitle__small{color: #7F7F7F;}.t-separate .l-hMain{background: #ffffff;border-bottom: 1px solid #E5E5E5;}.t-separate .globalNavi__list{border-radius: 5px;margin-bottom:40px;}.t-separate .siteTitle__big{color: #323232;}.t-separate .siteTitle__small{color: #7F7F7F;}.t-rich .globalNavi:before,.t-rich .l-footer:before,.t-rich .l-hExtra:before{position:absolute;top:0;left:0;right:0;bottom:0;content:"";background:linear-gradient(0,hsla(0,0%,100%,0),hsla(0,0%,100%,0) 35%,hsla(0,0%,100%,.1))}.t-rich.t-light .l-hExtra:before{background:linear-gradient(180deg,hsla(0,0%,100%,0),hsla(0,0%,100%,0) 35%,rgba(0,0,0,.05))}.t-rich .eyecatch{box-shadow:0 9pt 10px -6px rgba(0,0,0,.25)}}

.u-txtShdw{text-shadow:0px 2px 0px rgba(0,0,0,0.25);}.u-txtShdw-dark{text-shadow:1px 1px 1px rgba(0,0,0,0.75);}.u-none{display:none}.u-none-pc{display:none}.u-none-sp{display: block}

@font-face{font-family: "icomoon";src:  url("<?php echo get_template_directory_uri(); ?>/fonts/icomoon.eot?gizg5m");src:  url("<?php echo get_template_directory_uri(); ?>/fonts/icomoon.eot?gizg5m#iefix") format("embedded-opentype"),url("<?php echo get_template_directory_uri(); ?>/fonts/icomoon.ttf?gizg5m") format("truetype"),url("<?php echo get_template_directory_uri(); ?>/fonts/icomoon.woff?gizg5m") format("woff"),url("<?php echo get_template_directory_uri(); ?>/fonts/icomoon.svg?gizg5m#icomoon") format("svg");font-weight: normal;font-style: normal;}[class^="icon-"], [class*=" icon-"]{font-family: "icomoon";speak: none;font-style: normal;font-weight: normal;font-variant: normal;text-transform: none;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;}.icon-close:before{content:"\e90e";}.icon-menu:before{content:"\e90f";}.icon-instagram:before{content:"\e90d";}.icon-hatebu:before{content:"\e90c";}.icon-quotation:before{content:"\e909";}.icon-line:before{content:"\e90a";}.icon-pocket:before{content:"\e90b";}.icon-calendar:before{content:"\e900";}.icon-facebook:before{content:"\e901";}.icon-folder:before{content:"\e902";}.icon-google:before{content:"\e903";}.icon-home:before{content:"\e904";}.icon-rss:before{content:"\e905";}.icon-search:before{content:"\e906";}.icon-tag:before{content:"\e907";}.icon-twitter:before{content:"\e908";}

@keyframes marquee{from{transform: translate(0%);}to {transform: translate(-100%);}}
@keyframes sonar{0%{opacity: 0.3;}40%{opacity: 0.5;box-shadow: 0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px #191919, 0 0 0 10px rgba(255,255,255,0.5);}100%{box-shadow: 0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px #191919, 0 0 0 10px rgba(255,255,255,0.5);transform: scale(1.2);opacity: 0;}}

.content{position:relative;font-size:1.6rem;line-height:1.75;margin:60px 0}.content:after{content:"";display:block;clear:both}.content.content-page{margin:0}.content a{color:#63acb7}.content a:hover{font-weight:700;border-bottom:#63acb7 1px solid}.content p{margin-top:20px}.content p:after{content:"";display:block;clear:both}.content h2,.content h3,.content h4,.content h5{line-height:1.5;margin-top:40px}.content h2{font-size:2.6rem}.content h3{font-size:2.2rem}.content h4{font-size:1.8rem}.content h5{font-size:1.6rem}.content h2+h2,.content h2+h3,.content h2+h4,.content h2+h5,.content h3+h2,.content h3+h3,.content h3+h4,.content h3+h5,.content h4+h2,.content h4+h3,.content h4+h4,.content h4+h5,.content h5+h2,.content h5+h3,.content h5+h4,.content h5+h5{margin-top:20px}.content .size-full,.content .size-large,.content .size-medium,.content .size-thumbnail{max-width:100%;height:auto}.content .size-large{width: <?php form_option("large_size_w");?>px;}.content .size-medium{width: <?php form_option("medium_size_w");?>px;}.content .size-thumbnail{width: <?php form_option("thumbnail_size_w");?>px;}.content .alignleft{float:left;margin:0 10px 10px 0}.content .aligncenter{display:block;margin:0 auto 10px auto}.content .alignright{float:right;margin:0 0 10px 10px}.content .wp-caption{margin-top:20px}.content .wp-caption a{display:block}.content .wp-caption a:hover{border-bottom:0}.content .wp-caption img{vertical-align:bottom}.content .wp-caption-text{margin-top:10px;text-align:center;font-size:1.4rem}.content ul,.content ol{list-style-type: none;margin-top:20px;}.content ul ul,.content ul ol,.content ol ul,.content ol ol{margin-top:0;}.content ol{counter-reset:number;}.content ul li:before{content:"・";position:absolute;left:0;}.content ol li:before{counter-increment: number;content: counter(number)".";position:absolute;left:0;}.content ul li,.content ol li{position:relative;line-height:1.5;padding: 10px 0 0 25px;font-size:1.4rem;}.content pre{font-family:游ゴシック体,Yu Gothic,YuGothic,ヒラギノ角ゴシック Pro,Hiragino Kaku Gothic Pro,メイリオ,Meiryo\, Osaka,ＭＳ\ Ｐゴシック,MS PGothic,"sans-serif";font-weight:400;font-size:1.4rem;margin-top:20px;padding:20px;background-color:#f2f2f2;border-left:solid 5px #191919;color:#7f7f7f;overflow:auto}.content hr{margin-top:40px;border-top:1px solid #f2f2f2;border-bottom:1px solid #e5e5e5}.content table{margin-top:20px;width:100%;border-top:1px solid #e5e5e5;border-left:1px solid #e5e5e5;font-size:1.4rem}.content table tr:nth-child(2n+1){background:#f2f2f2}.content table th{background:#323232;color:#fff}.content table td,.content table th{padding:10px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5}.content .outline{border:1px dotted #d8d8d8;padding:20px;margin-top:20px;display:inline-block}.content .outline__toggle{display:none}.content .outline__switch:before{content:"開く";cursor:pointer;border:solid 1px #d8d8d8;padding:5px;font-size:1.2rem;margin-left:5px;border-radius:5px}.content .outline__toggle:checked+.outline__switch:before{content:"閉じる"}.content .outline__switch+.outline__list{overflow:hidden;width:0;height:0;margin-top:0;margin-left:-20px;transition:.2s}.content .outline__toggle:checked+.outline__switch+.outline__list{width:auto;height:auto;margin-top:20px;transition:.2s}.content .outline__item:before{content:normal}.content .outline__link{display:inline-block;color:#191919}.content .outline__link:hover{border:0}.content .outline__number{display:inline-block;color:#7f7f7f;background:#f2f2f2;padding:3px 6px;font-weight:400;font-size:1.2rem;margin-right:5px}.content blockquote{position:relative;color:#3f3f3f;margin-top:20px;padding:20px 20px 20px 70px;background-color:#f2f2f2}.content blockquote:before{position:absolute;top:10px;left:20px;font-family:icomoon;content:"\e909";font-size:3rem;color:#d9d9d9}.content .borderBox{border:1px solid #e5e5e5;padding:20px;margin-top:20px}.content .border2Box{border:4px double #e5e5e5;padding:20px;margin-top:20px}.content .bgBox{background:#f2f2f2;padding:20px;margin-top:20px}.content .paperBox{position:relative;padding:20px;margin-top:20px;background-color:#f2f2f2}.content .paperBox:after{content:"";position:absolute;bottom:0;right:0;border-color:#d8d8d8 #fff #fff #d8d8d8;border-style:solid;border-width:0 0 20px 20px}.content .boldBox{border:3px solid #191919;padding:20px;margin-top:20px;font-weight:700}.content .bracketsBox{position:relative;padding:20px;margin-top:20px}.content .bracketsBox:after,.content .bracketsBox:before{display:inline-block;position:absolute;width:30px;height:30px;content:""}.content .bracketsBox:before{top:0;left:0;border-top:solid 1px #191919;border-left:solid 1px #191919}.content .bracketsBox:after{right:0;bottom:0;border-right:solid 1px #191919;border-bottom:solid 1px #191919}.content .exclamationBox,.content .questionBox{position:relative;margin-top:20px;padding:20px 20px 20px 70px}.content .questionBox{background-color:#d9eff7}.content .exclamationBox{background-color:#f6e1df}.content .exclamationBox:before,.content .questionBox:before{position:absolute;top:20px;left:20px;font-size:2rem;font-weight:700;color:#fff;text-align:center;vertical-align:middle;width:30px;height:30px;line-height:30px;border-radius:50%}.content .questionBox:before{content:"?";background:#0096c8}.content .exclamationBox:before{content:"!";background:#c53929}.content .pointBox{position:relative;border:2px solid #c53929;border-radius:5px;padding:20px;margin-top:20px}.content .pointBox:before{content:"POINT";position:absolute;top:-15px;left:15px;font-size:1.6rem;font-weight:700;background-color:#fff;color:#c53929;padding:0 10px}.content .asterisk{display:block;font-size:1.3rem;color:#7f7f7f}.content .markerYellow{background:linear-gradient(transparent 60%,#ffffbc 60%)}.content .markerPink{background:linear-gradient(transparent 60%,#ffdfef 60%)}.content .markerBlue{background:linear-gradient(transparent 60%,#cce5ff 60%)}.content amp-youtube,.content amp-iframe{width: 100%;max-width: 100%;margin:20px auto 0 auto;}.content amp-twitter,.content amp-instagram{width: 500px;max-width: 100%;margin:20px auto 0 auto;}.content amp-instagram,.content amp-iframe{border: 1px solid #e5e6e9;border-radius: 4px;}.content *:first-child{margin-top:0;}

.ampAd{width: 100%;text-align: center;margin: auto;padding: 0 10px;background-color: #F2F2F2;background-image: linear-gradient(to top right, #fff 0%, #fff 25%, transparent 25%, transparent 50%, #fff 50%, #fff 75%, transparent 75%, transparent 100%);background-size: 6px 6px;} .ampAd__text{display: block;font-size: 1.2rem;padding: 10px 0;}.socialList{list-style:none;display: flex;justify-content: flex-end;flex-wrap:wrap;width:100%;margin-bottom:60px;}.socialList__item{flex-grow: 1;height:50px;line-height:50px;min-width:90px;text-align:center;}.socialList__link{display:block;color:#ffffff;}.socialList__link::before{font-size:2.6rem;display:block;transition: ease-in-out .2s;}.socialList__link:hover::before{background:#ffffff;transform: scale(1.2);box-shadow:1px 1px 4px 0px rgba(0,0,0,0.15);}.socialList__link.icon-facebook{background:#3B5998;}.socialList__link.icon-facebook:hover::before{color:#3B5998;}.socialList__link.icon-twitter{background:#00B0ED;}.socialList__link.icon-twitter:hover::before{color:#00B0ED;}.socialList__link.icon-google{background:#DF4A32;}.socialList__link.icon-google:hover::before{color:#DF4A32;}.socialList__link.icon-hatebu{background:#008FDE;}.socialList__link.icon-hatebu:hover::before{color:#008FDE;}.socialList__link.icon-pocket{background:#EB4654;}.socialList__link.icon-pocket:hover::before{color:#EB4654;}.socialList__link.icon-line{background:#00C300;}.socialList__link.icon-line:hover::before{color:#00C300;}.ctaPost{border:#e5e5e5 1px solid;width:100%;margin-bottom:40px}.ctaPost__title{width:100%;background:#efefef;text-align:center;font-size:2.6rem;line-height:1.5;padding:15px}.ctaPost__contents{padding:30px;font-size:1.4rem;line-height:1.75}.ctaPost__contents:after{content:"";display:block;clear:both}.ctaPost__img{display:block;float:right;margin:0 0 30px 30px}.ctaPost__img-pcCenter{float:none;margin:0 auto 30px}.ctaPost__img-pcLeft{float:left;margin:0 30px 30px 0}.ctaPost__btn{position:relative;display:block;clear:both;width:80%;margin:30px auto 0;border-radius:3px;background:#63acb7;border:1px solid #63acb7;text-align:center;color:#fff}.ctaPost__btn:before{content:"";position:absolute;top:50%;right:10px;margin-top:-3px;width:6px;height:6px;border-top:1px solid;border-right:1px solid;transform:rotate(45deg)}.ctaPost__btn:hover{color:#63acb7;background:#fff;transition:.2s}.ctaPost__btn a{display:block;padding:15px 0;line-height:1.5;font-size:1.6rem;font-weight:700}.prevNext{border-top:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;margin:40px 0;list-style-type:none;display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;display:-moz-flex;display:flex;-webkit-box-lines:multiple;-moz-box-lines:multiple;-webkit-flex-wrap:wrap;-moz-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap}.prevNext:after{content:"";display:block;clear:both}.prevNext__item{position:relative;padding:50px 0 20px;float:left}.prevNext__item:after{content:"";display:block;clear:both}.prevNext__item.prevNext__item-prev{width:calc(50% - 1px);padding-right:20px;border-right:1px solid #e5e5e5}.prevNext__item.prevNext__item-next{width:50%;padding-left:20px}.prevNext__pop{position:absolute;top:0;display:inline-block;background:#63acb7;height:30px;line-height:30px;padding:0 10px;color:#fff}.prevNext__item.prevNext__item-prev .prevNext__pop{left:0}.prevNext__item.prevNext__item-next .prevNext__pop{right:0}.prevNext__imgLink{display:block;width:90px;height:90px;overflow:hidden}.prevNext__item.prevNext__item-prev .prevNext__imgLink{float:left}.prevNext__item.prevNext__item-next .prevNext__imgLink{float:right}.prevNext__imgLink img{width:inherit;height:inherit;vertical-align:bottom;transform:scale(1);transition:ease-in-out .2s}.prevNext__imgLink img:hover{transform:scale(1.2)}.prevNext__title{width:calc(100% - 75pt);font-size:1.6rem;font-weight:700;line-height:1.5;margin-bottom:10px;color:#63acb7}.prevNext__item.prevNext__item-prev .prevNext__title{float:right}.prevNext__item.prevNext__item-next .prevNext__title{float:left}.prevNext__title a:hover{text-decoration:underline}.prevNext__title span{display:block;font-size:1.2rem;color:#7f7f7f;font-weight:400}.prevNext__title .icon-calendar:before{margin-right:5px;line-height:1}.prevNext__text{text-align:center;color:#bfbfbf}.prevNext__item.prevNext__item-prev .prevNext__text{padding-left:20px}.prevNext__item.prevNext__item-next .prevNext__text{padding-right:20px}.profile{border-top:1px solid #E5E5E5;margin-top:40px;padding-top:40px;overflow:hidden;}.profile__imgArea{float:left;width:60px;}.profile__imgArea img{border-radius: 50%;}.profile__list{list-style:none;width:60px;}.profile__item{width:30px;height:30px;margin:5px auto 0 auto;}.profile__link{display:block;background:#323232;line-height:30px;border-radius:50%;text-align:center;color:#FFF;font-size:1.2rem}.profile__link:hover{transition: .2s;}.profile__contents{width: calc(100% - 80px);float:right;}.profile__name{font-size:1.8rem;margin-bottom:5px;line-height:1.5;}.profile__group{font-size:1.5rem;line-height:1.5;color:#7F7F7F;margin-bottom:20px;}.profile__description{font-size:1.3rem;line-height:1.75;}.related{border-top: 1px solid #E5E5E5;margin-top: 40px;padding-top: 40px;}.related__list{list-style-type: none;}.related__item{padding-top:20px;}.related__item:first-child{padding-top:0;}.related__item::after{content: "";display: block;clear: both;}.related__imgLink{display:block;float:left;width:90px;height:90px;overflow: hidden;}.related__imgLink img{width:inherit;height:inherit;vertical-align:bottom;transform: scale(1);transition: ease-in-out .2s;}.related__imgLink img:hover{transform: scale(1.2);}.related__title{width:calc(100% - 100px);float:right;font-size:1.6rem;font-weight:700;line-height:1.5;margin-bottom:10px;color:#63acb7;}.related__title a:hover{text-decoration:underline;}.related__title span{display: block;font-size: 1.2rem;color: #7F7F7F;font-weight: normal;}.related__title .icon-calendar::before{margin-right: 5px;line-height: 1;}.related__contents{width:calc(100% - 100px);float:right;font-size:1.3rem;line-height:1.5;}.related__contents.related__contents-max{width:100%;float:none;}

@media only screen and (max-width: 1023px){.l-wrapper{width: 840px;}.container{width: 840px;}.siteTitle{padding:25px 0;}}

@media only screen and (max-width: 767px){body{font-size:1.3rem;}.l-hExtra{padding-bottom:10px;}.l-hMain{padding-bottom:20px;border-bottom:1px solid #E5E5E5;}.l-wrapper{width: 100%;max-width:100%;display:block;}.l-main{width: 100%;padding:40px 10px;background:#ffffff;}.l-main::before{content: normal;}.l-main.l-main-single{width: 100%;padding:40px 10px;}.container{width: 100%;max-width:100%;padding:0 10px;}.infoHead{overflow:hidden}.infoHead__link{padding-left:100%;white-space:nowrap;display:inline-block;animation-name:marquee;animation-timing-function:linear;animation-duration:10s;animation-iteration-count:infinite; font-size:1.2rem}.marquee{float: none;width:calc(100% - 2px);border: 1px solid #323232;}.socialSearch{width:100%;}.socialSearch__link{margin-left:5px;}.siteTitle{float:none;padding:20px 0 0 0;}.siteTitle__big{font-size: 2.4rem;margin-bottom: 5px;}.siteTitle__small{font-size: 1.1rem;}.siteTitle__logo .siteTitle__link{max-width: 176px;height: 40px;}.globalNavi{position:absolute;top:20px;right:10px;width:40px;height:40px;line-height:40px;}.globalNavi__switch{display: block;width: inherit;height:inherit;text-align:center;cursor:pointer;border-radius:50%;background-color: #63acb7;}.globalNavi__switch::before{font-family: "icomoon";content: "\e90f";color:#FFF;font-size:1.6rem;}.globalNavi__list{height:0;overflow: hidden;border-radius:0;}.globalNavi__list::before{display:block;content: "MENU";background: #191919;height: 60px;line-height: 60px;color: #ffffff;font-weight: 900;text-align: center;box-sizing:content-box;padding-top:54px;}.globalNavi__list li{float:none;background:#323232;font-size:1.2rem;border-top: 1px solid rgba(0,0,0,0.15);box-shadow:0px 1px 0px 0px rgba(255,255,255,0.15) inset;border-left:0;border-right: 0;}.globalNavi__list > li.page_item_has_children::before,.globalNavi__list > li.menu-item-has-children::before{top: 26px;}.globalNavi__list > li .children,.globalNavi__list > li .sub-menu{position: static;}.globalNavi__list > li .children > li,.globalNavi__list > li .sub-menu > li{overflow: visible;width:auto;height:54px;line-height:54px;}.globalNavi__list .children a,.globalNavi__list .sub-menu a{padding-left:40px;}.globalNavi__toggle:checked + .globalNavi__switch{position: fixed;top:64px;right:10px;z-index:9999;transition:all 1s;transform: rotate(360deg);}.globalNavi__toggle:checked + .globalNavi__switch::before{content: "\e90e";font-size:1.4rem;}.globalNavi__toggle:checked + .globalNavi__switch + .globalNavi__list{position: fixed;top:0;left:0;right:0;bottom:0;width:100%;height:100%;background:rgba(0,0,0,0.9);overflow: auto;z-index:9899;transition: 1s;}.eyecatch.eyecatch-single{width: auto;margin-left: -10px;margin-right: -10px;}.eyecatch__cat a{padding:10px 20px;font-size:1.2rem;}.breadcrumb{margin-top:-40px;margin-left:-10px;margin-right:-10px;overflow-x: auto;}.breadcrumb__list{display: table;}.breadcrumb__item{display: table-cell;white-space: nowrap;float:none;padding-left:15px;margin-right:0;}.breadcrumb__item:first-child{padding-left:0;}.footerNavi__list li{font-size:1.4rem;}.copyright{font-size:1.2rem;}.heading.heading-primary{font-size:2.6rem;}.heading.heading-secondary{font-size:1.6rem;}.heading span{font-size:1.4rem;}.btn__link{font-size:1.2rem;}.t-light .l-hMain::before{content:normal}.t-light .globalNavi__list > li:first-child{border-left: none;}.t-light .globalNavi__list > li:first-child::before{content: normal;}.t-light .marquee{border-color:#ffffff}.t-separate .globalNavi__list{border-radius:0}.t-rich .globalNavi::before{content:none;}.u-none-sp{display:none}.u-none-pc{display:block}

.content{font-size:1.4rem}.content h2{font-size:2.2rem}.content h3{font-size:1.8rem}.content h4{font-size:1.6rem}.content h5{font-size:1.4rem}.content .wp-caption-text,.content ol li,.content pre,.content ul li{font-size:1.2rem}.content pre{padding:15px}.content table{font-size:1.2rem}.content blockquote{padding:15px 15px 15px 55px}.content blockquote:before{top:5px;left:15px;font-size:2.5rem}.content .exclamationBox,.content .questionBox{padding:15px 15px 15px 55px}.content .exclamationBox:before,.content .questionBox:before{top:15px;left:15px;font-size:1.6rem;width:25px;height:25px;line-height:25px}.socialList{margin-bottom:40px;}.socialList__item{height:40px;line-height:40px;}.socialList__link::before{font-size:2rem;}.ctaPost__title{font-size:2.2rem}.ctaPost__contents{padding:20px}.ctaPost__img{float:right;margin:0 0 20px 20px}.ctaPost__img-spCenter{float:none;margin:0 auto 20px}.ctaPost__img-spLeft{float:left;margin:0 20px 20px 0}.ctaPost__btn{width:100%;margin-top:20px}.ctaPost__btn a{font-size:1.4rem}.prevNext__item.prevNext__item-prev{width:100%;padding-right:0;border-right:0;border-bottom:1px solid #e5e5e5}.prevNext__item.prevNext__item-next{width:100%;padding-left:0}.prevNext__title{font-size:1.4rem}.profile__name{font-size:1.6rem;}.profile__group{font-size:1.4rem;}.profile__description{font-size:1.2rem;}.related__title{font-size:1.4rem;}.related__contents{font-size:1.2rem;}}

<?php
if ( get_option('fit_skin_theme') == 'value1') {$primaryColor = esc_attr(get_theme_mod('fit_skin_pick'));
}elseif( get_option('fit_skin_theme') == 'value2' ) {$primaryColor = '#0E88BE';
}elseif( get_option('fit_skin_theme') == 'value3' ) {$primaryColor = '#2f8e56';
}elseif( get_option('fit_skin_theme') == 'value4' ) {$primaryColor = '#e85e00';
}elseif( get_option('fit_skin_theme') == 'value5' ) {$primaryColor = '#d32374';
}elseif( get_option('fit_skin_theme') == 'value6' ) {$primaryColor = '#B92C2C';
}elseif( get_option('fit_skin_theme') == 'value7' ) {$primaryColor = '#534970';
}elseif( get_option('fit_skin_theme') == 'value8' ) {$primaryColor = '#0e3d69';
}
echo '.globalNavi__switch{background-color:'.$primaryColor.';}.dateList__item a:hover,.footerNavi__list li a:hover,.copyright__link:hover,.heading a:hover,.btn__link,.content a,.related__title,.ctaPost__btn:hover,.prevNext__title{color:'.$primaryColor.';}.globalNavi__list,.pagetop,.heading.heading-secondary::before,.btn__link:hover,.ctaPost__btn,.prevNext__pop{background-color:'.$primaryColor.';}.btn__link,.content a:hover,.t-light .l-hMain::before,.ctaPost__btn{border-color:'.$primaryColor.';}' ."\n";

if ( get_option('fit_theme_infoHead') == 'value2' && get_theme_mod('fit_theme_infoHeadColor') != '#c53929') {
	$infoHeadColor = esc_attr( get_theme_mod( 'fit_theme_infoHeadColor' ));
	echo '
.infoHead{background-color:'.$infoHeadColor.';}'."\n";
}
		
// 見出し2のスタイル
$colorA = '#63acb7';
if (get_theme_mod('fit_hskin_h2ColorA') != '') {
	$colorA = esc_attr( get_theme_mod( 'fit_hskin_h2ColorA' ));
}
$colorB = '#191919';
if (get_theme_mod('fit_hskin_h2ColorB') != '') {
	$colorB = esc_attr( get_theme_mod( 'fit_hskin_h2ColorB' ));
}		
if (get_option('fit_hskin_h2Style') == 'value1' || get_option('fit_hskin_h2Style') == '') {echo '
.content h2{color:'.$colorB.';}
.content h2:first-letter{
	font-size:3.2rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
}if (get_option('fit_hskin_h2Style') == 'value2') {echo '
.content h2{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
}if (get_option('fit_hskin_h2Style') == 'value3') {echo '
.content h2{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h2::before,
.content h2::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h2::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h2::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
}if (get_option('fit_hskin_h2Style') == 'value4') {echo '
.content h2{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h2::before,.content h2::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h2::before{
	border-bottom-color:'.$colorA.';
}
.content h2::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
}if (get_option('fit_hskin_h2Style') == 'value5') {echo '
.content h2{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
}if (get_option('fit_hskin_h2Style') == 'value6') {echo '
.content h2{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h2::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
}if (get_option('fit_hskin_h2Style') == 'value7') {echo '
.content h2{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h2Style') == 'value8') {echo '
.content h2{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}
.content h2::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
}if (get_option('fit_hskin_h2Style') == 'value9') {echo '
.content h2{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h2Style') == 'value10') {echo '
.content h2{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h2Style') == 'value11') {echo '
.content h2{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h2Style') == 'value12') {echo '
.content h2{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h2Style') == 'value13') {echo '
.content h2{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
}if (get_option('fit_hskin_h2Style') == 'value14') {echo '
.content h2{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h2Style') == 'value15') {echo '
.content h2{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h2::before,
.content h2::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h2::before{
	left: 10px;
}
.content h2::after{
	right: 10px;
}';
}if (get_option('fit_hskin_h2Style') == 'value16') {echo '
.content h2{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h2::before,
.content h2::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h2:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h2:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
}if (get_option('fit_hskin_h2Style') == 'value17' && get_option('fit_hskin_h2Css') != '') {
	echo get_option('fit_hskin_h2Css');
}
		
// 見出し3のスタイル
$colorA = '#63acb7';
if (get_theme_mod('fit_hskin_h3ColorA') != '') {
	$colorA = esc_attr( get_theme_mod( 'fit_hskin_h3ColorA' ));
}
$colorB = '#191919';
if (get_theme_mod('fit_hskin_h3ColorB') != '') {
	$colorB = esc_attr( get_theme_mod( 'fit_hskin_h3ColorB' ));
}		
if (get_option('fit_hskin_h3Style') == 'value1') {echo '
.content h3{color:'.$colorB.';}
.content h3:first-letter{
	font-size:2.8rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
}if (get_option('fit_hskin_h3Style') == 'value2') {echo '
.content h3{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
}if (get_option('fit_hskin_h3Style') == 'value3') {echo '
.content h3{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h3::before,
.content h3::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h3::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h3::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
}if (get_option('fit_hskin_h3Style') == 'value4') {echo '
.content h3{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h3::before,.content h3::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h3::before{
	border-bottom-color:'.$colorA.';
}
.content h3::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
}if (get_option('fit_hskin_h3Style') == 'value5') {echo '
.content h3{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
}if (get_option('fit_hskin_h3Style') == 'value6') {echo '
.content h3{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h3::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
}if (get_option('fit_hskin_h3Style') == 'value7') {echo '
.content h3{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h3Style') == 'value8') {echo '
.content h3{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}

.content h3::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
}if (get_option('fit_hskin_h3Style') == 'value9' || get_option('fit_hskin_h3Style') == '') {echo '
.content h3{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h3Style') == 'value10') {echo '
.content h3{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h3Style') == 'value11') {echo '
.content h3{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h3Style') == 'value12') {echo '
.content h3{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h3Style') == 'value13') {echo '
.content h3{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
}if (get_option('fit_hskin_h3Style') == 'value14') {echo '
.content h3{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h3Style') == 'value15') {echo '
.content h3{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h3::before,
.content h3::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h3::before{
	left: 10px;
}
.content h3::after{
	right: 10px;
}';
}if (get_option('fit_hskin_h3Style') == 'value16') {echo '
.content h3{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h3::before,
.content h3::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h3:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h3:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
}if (get_option('fit_hskin_h3Style') == 'value17' && get_option('fit_hskin_h3Css') != '') {
	echo get_option('fit_hskin_h3Css');
}
		
// 見出し4のスタイル
$colorA = '#63acb7';
if (get_theme_mod('fit_hskin_h4ColorA') != '') {
	$colorA = esc_attr( get_theme_mod( 'fit_hskin_h4ColorA' ));
}
$colorB = '#191919';
if (get_theme_mod('fit_hskin_h4ColorB') != '') {
	$colorB = esc_attr( get_theme_mod( 'fit_hskin_h4ColorB' ));
}
if (get_option('fit_hskin_h4Style') == 'value1') {echo '
.content h4{color:'.$colorB.';}
.content h4:first-letter{
	font-size:2.4rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
}if (get_option('fit_hskin_h4Style') == 'value2') {echo '
.content h4{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
}if (get_option('fit_hskin_h4Style') == 'value3') {echo '
.content h4{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h4::before,
.content h4::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h4::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h4::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
}if (get_option('fit_hskin_h4Style') == 'value4') {echo '
.content h4{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h4::before,.content h4::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h4::before{
	border-bottom-color:'.$colorA.';
}
.content h4::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
}if (get_option('fit_hskin_h4Style') == 'value5') {echo '
.content h4{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
}if (get_option('fit_hskin_h4Style') == 'value6') {echo '
.content h4{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h4::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
}if (get_option('fit_hskin_h4Style') == 'value7') {echo '
.content h4{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h4Style') == 'value8') {echo '
.content h4{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}
.content h4::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
}if (get_option('fit_hskin_h4Style') == 'value9') {echo '
.content h4{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h4Style') == 'value10') {echo '
.content h4{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h4Style') == 'value11') {echo '
.content h4{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h4Style') == 'value12') {echo '
.content h4{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h4Style') == 'value13') {echo '
.content h4{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
}if (get_option('fit_hskin_h4Style') == 'value14') {echo '
.content h4{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h4Style') == 'value15') {echo '
.content h4{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h4::before,
.content h4::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h4::before{
	left: 10px;
}
.content h4::after{
	right: 10px;
}';
}if (get_option('fit_hskin_h4Style') == 'value16') {echo '
.content h4{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h4::before,
.content h4::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h4:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h4:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
}if (get_option('fit_hskin_h4Style') == 'value17' && get_option('fit_hskin_h4Css') != '') {
	echo get_option('fit_hskin_h4Css');
}
		
// 見出し5のスタイル
$colorA = '#63acb7';
if (get_theme_mod('fit_hskin_h5ColorA') != '') {
	$colorA = esc_attr( get_theme_mod( 'fit_hskin_h5ColorA' ));
}
$colorB = '#191919';
if (get_theme_mod('fit_hskin_h5ColorB') != '') {
	$colorB = esc_attr( get_theme_mod( 'fit_hskin_h5ColorB' ));
}
if (get_option('fit_hskin_h5Style') == 'value1') {echo '
.content h5{color:'.$colorB.';}
.content h5:first-letter{
	font-size:2rem;
	padding-bottom:5px;
	border-bottom:3px solid;
	color:'.$colorA.';
}';
}if (get_option('fit_hskin_h5Style') == 'value2') {echo '
.content h5{
	padding: 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15) inset;
}';
}if (get_option('fit_hskin_h5Style') == 'value3') {echo '
.content h5{
	position: relative;
	padding:10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
	box-shadow:0 1px 3px rgba(0,0,0,0.25);
}
.content h5::before,
.content h5::after{
	content: "";
	position: absolute;
	top: 100%;
	height: 0;
	width: 0;
	border: 5px solid transparent;
	border-top: 5px solid #1A3654;
}
.content h5::before{
	right: 0;
	border-left: 5px solid #1A3654;
}
.content h5::after{
	left: 0;
	border-right: 5px solid #1A3654;
}';
}if (get_option('fit_hskin_h5Style') == 'value4') {echo '
.content h5{
	position: relative;
	padding: 10px 20px;
	color:'.$colorB.';
	background:'.$colorA.';
}
.content h5::before,.content h5::after{
	content: "";
	position: absolute;
	top: -20px;
	left: 0;
	width: 100%;
	height: 0;
	border: solid 10px transparent;
}
.content h5::before{
	border-bottom-color:'.$colorA.';
}
.content h5::after{
	border-bottom-color: rgba(0,0,0,0.15);;
}';
}if (get_option('fit_hskin_h5Style') == 'value5') {echo '
.content h5{
	color:'.$colorB.';
	background: linear-gradient(transparent 60%, '.$colorA.' 60%);
}';
}if (get_option('fit_hskin_h5Style') == 'value6') {echo '
.content h5{
	position: relative;
	padding:20px;
	color:'.$colorB.';
	background: '.$colorA.';
}
.content h5::after {
	position: absolute;
	content: "";
	top: 100%;
	left: 30px;
	border: 15px solid transparent;
	border-top: 15px solid '.$colorA.';
	width: 0;
	height: 0;
}';
}if (get_option('fit_hskin_h5Style') == 'value7') {echo '
.content h5{
	padding: 20px;
	color:#fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.75);
	background: linear-gradient('.$colorA.' 0%, '.$colorB.' 100%);
	border:1px solid '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h5Style') == 'value8') {echo '
.content h5{
	position: relative;
	padding: 20px 20px 20px 38px;
	border: 1px solid #E5E5E5;
	color:'.$colorB.';
	border-top: 4px solid '.$colorA.';
	background: linear-gradient(#ffffff 0%, #EFEFEF 100%);
	box-shadow: 0 -1px 0 rgba(255, 255, 255, 1) inset;
}
.content h5::after{
	content: "";
	position: absolute;
	top: 50%;
	left: 10px;
	margin-top: -10px;
	width: 18px;
	height: 18px;
	border: 4px solid '.$colorA.';
	border-radius: 100%;
	box-sizing:border-box;
}';
}if (get_option('fit_hskin_h5Style') == 'value9') {echo '
.content h5{
	padding:20px;
	color:'.$colorB.';
	border: 1px solid #E5E5E5;
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h5Style') == 'value10') {echo '
.content h5{
	padding: 10px 20px;
	color:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h5Style') == 'value11') {echo '
.content h5{
	padding: 10px 20px;
	background:'.$colorB.';
	border-left: 5px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h5Style') == 'value12') {echo '
.content h5{
	padding-bottom: 10px;
	color:'.$colorB.';
	border-bottom: 3px solid '.$colorA.';
}';
}if (get_option('fit_hskin_h5Style') == 'value13') {echo '
.content h5{
	padding:10px 20px;
	color:'.$colorB.';
	border-left:8px solid '.$colorA.';
	border-bottom:1px solid #E5E5E5;
}';
}if (get_option('fit_hskin_h5Style') == 'value14') {echo '
.content h5{
	padding:20px;
	color:'.$colorB.';
	border:1px solid '.$colorA.';
	background: '.$colorA.';
	box-shadow:inset 1px 1px 0 rgba(255,255,255,0.25);
}';
}if (get_option('fit_hskin_h5Style') == 'value15') {echo '
.content h5{
	position: relative;
	padding: 20px;
	text-align:center;
	color:'.$colorB.';
	border-top: solid 1px '.$colorA.';
	border-bottom: solid 1px '.$colorA.';
}
.content h5::before,
.content h5::after{
	content: "";
	position: absolute;
	top: -10px;
	width: 1px;
	height: calc(100% + 20px);
	background-color: '.$colorA.';
}
.content h5::before{
	left: 10px;
}
.content h5::after{
	right: 10px;
}';
}if (get_option('fit_hskin_h5Style') == 'value16') {echo '
.content h5{
	position: relative;
	overflow: hidden;
	padding-bottom: 5px;
	color:'.$colorB.';
}
.content h5::before,
.content h5::after{
	content: "";
	position: absolute;
	bottom: 0;
}
.content h5:before{
	border-bottom: 3px solid '.$colorA.';
	width: 100%;
}
.content h5:after{
	border-bottom: 3px solid #E5E5E5;
	width: 100%;
}';
}if (get_option('fit_hskin_h5Style') == 'value17' && get_option('fit_hskin_h5Css') != '') {
	echo get_option('fit_hskin_h5Css');
}
?>

</style>
<?php
if (get_option('fit_ad_postTop') || get_option('fit_ad_postBottom')){ echo '<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>' ."\n";}
if (get_option('fit_access_ampgaid')){ echo '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>' ."\n";}
if (get_option('fit_anp_search') == 'value2'){ echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>' ."\n";}
if (get_post_meta(get_the_ID(),'amp_script_iframe',true) == "1" ) { echo '<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>' ."\n";}
if (get_post_meta(get_the_ID(),'amp_script_twitter',true) == "1" ) { echo '<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>' ."\n";}
if (get_post_meta(get_the_ID(),'amp_script_instagram',true) == "1" ) { echo '<script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>' ."\n";}
if (get_post_meta(get_the_ID(),'amp_script_youtube',true) == "1" ) { echo '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>' ."\n";}

?>

<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
<script async src="https://cdn.ampproject.org/v0.js"></script>  
<?php
}




//////////////////////////////////////////////////
//投稿本文をAMP用にコンテンツを変換する
//////////////////////////////////////////////////
function my_amp(){
	//AMPチェック
	$my_amp = false;
	if ( empty($_GET['amp']) ) {
		return false;
	}
	// ampパラメータ=1 & シングルページの時
	if(is_single() && $_GET['amp'] === '1'){
		$my_amp = true;
	}
	return $my_amp;
}
function convert_content_amp($the_content){
	// 通常ページではコンテンツを置換しない
	if ( !my_amp() ) {
		return $the_content;
	}else {
		// Twitterをamp-twitterに置換する
		$pattern = '/<blockquote class="twitter-tweet".*?>.+?<a href="https:\/\/twitter\.com\/.*?\/status\/(.*?)">.+?<\/blockquote>/is';
		$append = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		$the_content = preg_replace($pattern, $append, $the_content);
	
		// Instagramをamp-instagramに置換する
		$pattern = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/".+?<\/blockquote>/is';
		$append = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
		$the_content = preg_replace($pattern, $append, $the_content);
		
		// YouTubeをamp-youtubeに置換する
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/is';
		$append = '<amp-youtube layout="responsive" data-videoid="$1" width="480" height="270"></amp-youtube>';
		$the_content = preg_replace($pattern, $append, $the_content);
	
		// iframeをamp-iframeに置換する
		$pattern = '/<iframe/i';
		$append = '<amp-iframe layout="responsive" sandbox="allow-scripts allow-same-origin allow-popups"';
		$the_content = preg_replace($pattern, $append, $the_content);
		$pattern = '/<\/iframe>/i';
		$append = '</amp-iframe>';
		$the_content = preg_replace($pattern, $append, $the_content);
		
		//C2A0文字コード（半角スペース）を通常の半角スペースに置換
		$the_content = str_replace('\xc2\xa0', ' ', $the_content);
	
		//style属性を取り除く
		$the_content = preg_replace('/ +style=["][^"]*?["]/i', '', $the_content);
		$the_content = preg_replace('/ +style=[\'][^\']*?[\']/i', '', $the_content);
		
		//onclick属性を取り除く
		$the_content = preg_replace('/ +onclick=["][^"]*?["]/i', '', $the_content);
		$the_content = preg_replace('/ +onclick=[\'][^\']*?[\']/i', '', $the_content);
	
		//fontタグを取り除く
		$the_content = preg_replace('/<font[^>]+?>/i', '', $the_content);
		$the_content = preg_replace('/<\/font>/i', '', $the_content);
	
		//画像タグをAMP用に置換
		$the_content = preg_replace('/<img (.*?)>/i', '<amp-img layout="responsive" $1></amp-img>', $the_content);
        $the_content = preg_replace('/<img (.*?) \/>/i', '<amp-img layout="responsive" $1></amp-img>', $the_content);
	
		//スクリプトを除去する
		$pattern = '/<script.+?<\/script>/is';
		$append = '';
		$the_content = preg_replace($pattern, $append, $the_content);
	
		return $the_content;
	}
}
add_filter('the_content','convert_content_amp', 999999999);
add_filter('fit_postContents','convert_content_amp', 999999999);



//////////////////////////////////////////////////
//ダッシュボードにオリジナルウィジェットを追加
//////////////////////////////////////////////////
function fit_dashboard_widgets() {
	wp_add_dashboard_widget(
	'fit_theme_options_widget',
	'FITからのお知らせ',
	'fit_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'fit_dashboard_widgets');

function fit_dashboard_widget_function() {
	// ここに表示したい内容を記述する。
	echo '「WPテーマ無料ダウンロード会員」への登録がまだお済でない方は、<a href="http://fit-jp.com/membership-registration/" target="_blank">こちらのページから会員登録</a>を行ってください。会員になるとテーマのバグ修正の報告や、新機能の案内、WordPressの活用法などの情報を受け取ることができます。<hr>
	<a href="http://fit-jp.com/themeinfo/" target="_blank"><img src="http://fit-jp.com/themeinfo/info-img.png" style="max-width: 100%;"></a>';
}
// JPG画像の品質圧縮率の制御
add_filter('jpeg_quality', function($arg){return 100;});

?>