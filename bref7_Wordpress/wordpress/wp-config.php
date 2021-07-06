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
define( 'DB_NAME', 'y-c_wordpress_bref7_1' );

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
define( 'AUTH_KEY',         'Fk=VnCEV!tijGwe#&AE99+)w%42blPK$r `s}m7Ha(81G2`!5-?KQ5IHBz:1%s~x' );
define( 'SECURE_AUTH_KEY',  '!Z,mfuey+f$yg*$5xoob9[u[liHv6s^55T>7(;~d~8?wM^I[S-,u&kN`=3F0[>vV' );
define( 'LOGGED_IN_KEY',    '5KfA[3Z9+Y+IbWN%w5|PbhEO,zy3)g/mluD]KP(6VL@Y`N9BzIcJ).yyMEW<i4yr' );
define( 'NONCE_KEY',        'hv?nWjZZe2bC,>$s;<&0!SUJM&5X~Ia+C/N*RcPw{wI7kE#{(!q<=+,x-4UCWOO1' );
define( 'AUTH_SALT',        '75@8a98AF=*PgxSC:XpG4tA&t.Dwgx@TTrsPag;iLfh6jm!QH?<l8I*?KEmy5lyV' );
define( 'SECURE_AUTH_SALT', '|Wl7E,|zA5:*y,,R@lnwR86LuTufO[hBbaZ|:-r=h~4&F#u5FccZa>!(-4=xnZmc' );
define( 'LOGGED_IN_SALT',   'Bx_*80asw^46J(N,(>qWNaS|qmD(BS4j&7^K7O;YNemVA ]6_pvK?;%?hTNSU$$[' );
define( 'NONCE_SALT',       ' 26xC<ii4>.oqEX7U%<M10=({i{_#D+.3{4^eb!fvq@>UB$;tKJ8By!x6K~oJX!y' );

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
