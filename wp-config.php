<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link http://wpdocs.osdn.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'LAA1361917-eabht3');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'LAA1361917');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', 'w2h1F29m');

/** MySQL のホスト名 */
define('DB_HOST', 'mysql139.phy.lolipop.lan');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '4twpf_P-D~I..G6bEz<tBbpuw_)T&"wKv{f2A-=Utj1.Sd{ZBx0C#}M4I"53ev:L');
define('SECURE_AUTH_KEY', 'I!)3F^:)p{TCkRd7Y:[p4VkM*b,}.Q(c8$K&5KjKzFujNT@$tTU%n<<%&Ud|MUlA');
define('LOGGED_IN_KEY', 'l>2s*tKYihRdln]9(IJq4PUy}v~nTS+mj>N.#jg(,6;Dy8`9|GsJ5R7Vcn1plCno');
define('NONCE_KEY', 'WM8]]>6<.8=?34VR"QX5^@I1{LYQT6^,7r93_o*mqVxAAQhq7lx}9>Mv=M/^^m`C');
define('AUTH_SALT', 'iNdeF,nxb2G8[a|;{T.%@LAEh(3GKZBD/*8/g55+Tduc9A}]0g-Gq:>-?9:w61>p');
define('SECURE_AUTH_SALT', 'h;qE<RtU~rUnBm2"aYS@b(*#{oJVmFiO3MCH(A&kLXE~Q}:[r?EN,yWT1q1Hsq^#');
define('LOGGED_IN_SALT', '~24)k_/1mg!!f{CkSz]ykp~![t{AKaF4#<<F!OX,TuB8T~O}9xN_S1~l|T/29d_C');
define('NONCE_SALT', '~(/0_#DX~i,Uvw/~c1|ol2#4RWlwX!aIY,@6HEe[E7Ue~6?6[uop.{L+uEa5K}hO');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wp20211029140655_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数については Codex をご覧ください。
 *
 * @link http://wpdocs.osdn.jp/WordPress%E3%81%A7%E3%81%AE%E3%83%87%E3%83%90%E3%83%83%E3%82%B0
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
  define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
