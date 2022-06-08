<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u170082150_naitaknik_wp' );

/** Database username */
define( 'DB_USER', 'u170082150_naitaknik_wp' );

/** Database password */
define( 'DB_PASSWORD', '$@T!S#07@sk' );

/** Database hostname */
define( 'DB_HOST', 'mysql' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'Hi1E.:/sI!pR<c]+,{*PUcPZ,VD>RjV)OL{S_(z{TE1Z82o=LIoGM*MZLl&=`U7C' );
define( 'SECURE_AUTH_KEY',   '0_H4,>]6ZlMZk0qVN)bw^(Ub(ZixJ^!0t1k..k6{fbEA0LO5*r|IF,R4 ,p}vqV@' );
define( 'LOGGED_IN_KEY',     'Aiu].tf=4e@&>L+XQMwoe)v_|=W0M<>~pef=F^+Z3KPV@[=%=fJ4;U{EtBG<Fe&s' );
define( 'NONCE_KEY',         'N+!e=Ye?s==J8S#X429m>s2?tu/ x&glY5@1>jDvin(K&;O2D.OxkPRe]YHu5|Pm' );
define( 'AUTH_SALT',         'WD?3R:/A@y`d;<O]?`_Wxw,R6!;yOcaG<8[0fN~M.fu18uH=}`.~mg]:-k~tish_' );
define( 'SECURE_AUTH_SALT',  'U5{$Sm5:,^%|)i+?h7ycZl]z?Y*_x+7oO`J?AlU.f.UzUMNwq_6lRR;|P19?HXj~' );
define( 'LOGGED_IN_SALT',    'eZxxIOR0dSJh+Rvp/2N(6D7Uw>!BCSc5SfT9([YrtOXn$W(xBkoT72Eel TSWwS_' );
define( 'NONCE_SALT',        '6hn%U&GCj?*3Ao8K}Y9s?%vQwVt6UQgbsM=nwX1lcc$k)n*JYD`}AA8?QkZL!#mp' );
define( 'WP_CACHE_KEY_SALT', 'F1`~~.VvhWuE)=G`Ld_~!e6Vrre;Vp]7_4xj;]5euNI`56n|RjU(6Im05+ExS[@o' );


/**#@-*/

/**
 * WordPress database table prefix.
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


/* Add any custom values between this line and the "stop editing" line. */



define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
