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
define('DB_NAME', 'rahul1');

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
define('AUTH_KEY',         'arc=xt=~:Z:{1-.Dk/t}P8l}TBCnL?T]q0~tJXDT@^9v+LQFz:| ZvuzQtu:zZHA');
define('SECURE_AUTH_KEY',  '!1K(DT{3/g&S@Sd;N1#9sR8YA%BoOT?^:@>hr0c`BkY(SrYRK`Q5 h@o+.0xf@+m');
define('LOGGED_IN_KEY',    '~QMf^9,ORP2ok:`{^c YfjK1*~R#ErGh>Y()@l_,@kP5!=L>%ge[*YGo0@k@ibKl');
define('NONCE_KEY',        'W`0Pz+4eeH)o]6`z5~4K%Yku.vX$pr=zXxOxOG9tzzfecR.>ll,Dcd%te&!nrQmE');
define('AUTH_SALT',        'p $6>1U0KzH<ni%]a#z?zmz(eZM?<Y>{Jk)?OJ?P4|R@Q52QE#ud4U)B;Uj8M7H~');
define('SECURE_AUTH_SALT', ' <[~w~RqTk&M+O4;Gu`Av>|mz!q.5B^[4moQlJZ;@tf)N7I^k)1_S5x$aY}n5[/w');
define('LOGGED_IN_SALT',   'Q)F,N_?u)tU(?wCCa?6OS^[9p4T6S&=VDJDl8A/,YPVK?D1E3?,X#h`rM.=K&`.a');
define('NONCE_SALT',       'I4#u?Kk,w({S6`bm8SPFubJjrY;o@]1L>W1S:]F>3,hG(w-gaS>{w%Ms_[^mV.8+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
