<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'babycakes');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '!T`crvSTpk-walMTgo=Qg{l+>!3*XRo~/hl$T>a|BjyB/3)gn1$.HWc[;I]|>(oI');
define('SECURE_AUTH_KEY',  'TK1tO,S+hb&2,Dz*S*[[i7bT@5l4iPB|[>1T.za_:B#)T1-J+J&5^`m^w@+qzF`-');
define('LOGGED_IN_KEY',    'PF;mR0xNKgxCd>zJ+SE${9fmcAp})~0y UIV<ocW|^I14e.,2SE$)H]YNQ{0Z54=');
define('NONCE_KEY',        'H-ZXcB*tB.xiN7tNM6t7V3;Sq<Lb#B6Oh>GQc=pVVq0sc~dEwv:X_IqCH-h^hl1<');
define('AUTH_SALT',        ']WW)@,8t4,@qI+&qUWYqqdhg{_GsTj 0;QWnIQ[n}~d$F]s#B]dxffKsgKm88[gL');
define('SECURE_AUTH_SALT', 'QX-6b>!Gtq/ZQouE<8|{__X$#28C)Tx7X},5n8?8twcB#GQPj&bPl1}4lyY#%= U');
define('LOGGED_IN_SALT',   'SSo2xD^-+iyjZV|{Q{w:Hg-@C1mR}g2v#:{FR#>ug+#:kK.{>^y7>|d>-%%DbVns');
define('NONCE_SALT',       'Wm~q)F^Kd.-+N(xz^U#q8HW6Qq[mw{ydh|TI_mB;4/.m JO-TA&}n}vb[y,;bzy+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
