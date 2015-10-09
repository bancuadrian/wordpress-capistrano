<?php


require "vendor/autoload.php";

Dotenv::load(__DIR__);

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $_ENV['DB_NAME']);

/** MySQL database username */
define('DB_USER', $_ENV['DB_USER']);

/** MySQL database password */
define('DB_PASSWORD', $_ENV['DB_PASS']);

/** MySQL hostname */
define('DB_HOST', $_ENV['DB_HOST']);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_HOME',$_ENV['SITE_URL']);
define('WP_SITEURL',$_ENV['SITE_URL']);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'pf!MVUTLY034t~m&zW19^%hFi^3KA;UH:W_8Gz._Ptn:=3Jq:|#dlFN#r;:4q7`.');
define('SECURE_AUTH_KEY',  'XcR<&upy`F?qx?ULt?NM/@TVc:NcIh$_y^YHk3@iSQFVV^,%j;LmIQ6s2xWDABw|');
define('LOGGED_IN_KEY',    'I(98b_DG?-px^lmDh,|N?:s&;:W)X{(]`UdaEe)i?1+&TRyi@]Xrtj/J[eUQS$qm');
define('NONCE_KEY',        '^FwcfGHhn:QXb.bq.0{Vum6#kasl!Kt.TZamF_!T<)!!Q (|usIHP}ehd+TKF2@6');
define('AUTH_SALT',        ' ta4EnL+dF]kBH;,P(X>FqQcjvrp[flh?!&v&9/S_J>`K4<VEGAHN)clZn!r`<+n');
define('SECURE_AUTH_SALT', '<pS>#._XR#0rY-ZbOMe=q!U-mmC7Czy#ELiv o-`LN7c6Tz`CqMu?i`2GYN}C;cp');
define('LOGGED_IN_SALT',   'BJZy1nRm?K hE%a<:4]4GpeNS)|A>Na[[|bsEGw0?2MMaHXpmFAHF0Ob$D999!@:');
define('NONCE_SALT',       'Aq/r$tVx::h[9E8I|G?HAh4PvzeqVR8~vRlE+r(?P`wcDBqzlhFdxS&Ueqix2o/;');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

define('WP_HTTP_BLOCK_EXTERNAL', false);

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if($_ENV['ENVIRONMENT'] == 'local') define('WP_DEBUG', true);
if($_ENV['ENVIRONMENT'] == 'prod') define('WP_DEBUG', false);
//print "<pre>";print_r($_ENV);die();
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
