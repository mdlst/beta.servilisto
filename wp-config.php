<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mdlst' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '>adCl9W#-^rWQgmjlVa{ES7%N-nD.8n3{gqz|^wOoMXrR[~GySRRqb>T,74_Lib.' );
define( 'SECURE_AUTH_KEY',  'K:roR[S+.b)Np*59:g!s>OMUY$CI=[3,vNw!wR}fle2;B}w)4Qitcq)lB1 ?ff3;' );
define( 'LOGGED_IN_KEY',    'jojx+CFMWoCCR%kQJ@~Y_:`$>lxoV*_7k2nQ7>=,y*$g^#cSS0ymR=IbkN$L~+C*' );
define( 'NONCE_KEY',        'T]qCM1Vzy<K97LvmA$&VAZ~qT(6Z:|n~NC/:/T++,{J1&&q8pw:8eTd7rH$9Z vt' );
define( 'AUTH_SALT',        '5R`1#kh44LX]c% i;|wPm~_Z3BNd!!N:h).}OXzDt%]*bF#z:# >NE;9CwCz1Kkv' );
define( 'SECURE_AUTH_SALT', ']IbP&F?=/e}~&8zJqzl]Oz(2.7{tZ4cU=dY%j+()(vlSr H$<}[6w,_he[yO.aV^' );
define( 'LOGGED_IN_SALT',   '2UM/:QJEHVbp~F-7Ma?}WG{xHyswHdA:89C9g;~Ci9ywRzdjtc]_O&q.RHsi.lx;' );
define( 'NONCE_SALT',       'Jo$]b@{8JTyr|4q;BLj}q@i(y|Ai%( &xMic,SNf!P5hYKy/$zXt#4xgE^UMr$2M' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
