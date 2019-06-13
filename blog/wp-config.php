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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'blog_lexel');

/** MySQL database username */
define('DB_USER', 'blog_lexel');

/** MySQL database password */
define('DB_PASSWORD', 'gHXfTjMBQ68K');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ru)c>?R^/{OQ#[<B)]L;1QSBK}|LxIPGrP%IX?|L$,Op; vgj0>g6~<vAs8LXrF>');
define('SECURE_AUTH_KEY',  'kxZUMWO2nHsEr6iI?.By{KJ{Uqxh/P)4KHb=LEC_;J#0eO4)Pg^$}kqHI|Ye:S`<');
define('LOGGED_IN_KEY',    'uRlxnm{[3m$GDFq.L?ONO?#L8R-AmI_[:>oxP]|[YF&/rykI)u:}!a=BD |YA=_f');
define('NONCE_KEY',        'p,2dH7I>+:s4*> @Q*/x(0r$6|wNu@2LmEo>$*$Cn5`{/Q&!YJc]fBuDV,DYHU^h');
define('AUTH_SALT',        'JtR)7Ki{h)fs$IFU!PO}A#1H=kkzU+v=XHbf$Xd0//M:4l?L:(7@_a00r[KE4N-,');
define('SECURE_AUTH_SALT', 'lhxC`!+3zb-A0V~l0ZWK0LfWXOkpuOGU&W*&Nn$}gNQ+GRq;Gb6?46sqP,f]dfW}');
define('LOGGED_IN_SALT',   'Q?Oya ?@%+gnt$AB4%a|*N541y,Ir+Sd}n]E|<KZH;rmeC|fEcgc-FE#Z^]fc997');
define('NONCE_SALT',       '={Pb[^cG]d@4oI7m)Ghi.ylWU}*zJ_)S45RdO,b,/mMZA+_d|XY.),PdYB U/r#W');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'lxl_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
