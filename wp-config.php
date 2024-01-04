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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'practice' );

/** Database username */
define( 'DB_USER', 'practice' );

/** Database password */
define( 'DB_PASSWORD', 'practice' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         ')E95!kFqONe<XZUe7Vf%_@6k=!&9Qu4JXfNlYkNZMJSTWYpb<YDJ|+ys%sj$eQYf' );
define( 'SECURE_AUTH_KEY',  'VJ0d$ Ms8`%E)KmdjB}Nqn0LwUtZpO;MT*JPgk04ZwRn1puCL;72wE5t-[90z7oK' );
define( 'LOGGED_IN_KEY',    'zqIkZ<0KSD%1Q@,#og%U;Kg^H,DTqP#WL=OxbY|/QlbVL}oJjJ~SI+m89dZR5@{X' );
define( 'NONCE_KEY',        '},Le?<RIw5c8^za:3#I2<=-Eipra{RM+-_SgQ+}X07Jd;LM},PfQG;.,u=DV};&7' );
define( 'AUTH_SALT',        ' H]M?u4>Egi]3>m/$_)JV3Ae^q=Uif`#?F}cdqg>E/-TR/+5=#tRPypbu.@/Kl,}' );
define( 'SECURE_AUTH_SALT', '!ADpXb.*{Vnqlp!~U)z&EB^$J#U>2x/?B3hgJSS}RM1PFoG>r#a!&CtvYb1*n3bR' );
define( 'LOGGED_IN_SALT',   'Y<@Y!f+%4Qdsi3y>LQboa[?HURywk5O]*%(Cr>GLhG|W6wgQzOo)U_#A 6/7`N)Q' );
define( 'NONCE_SALT',       '<3DkKLV[Ia7$s9H&>6<@74)vwKGa4s2mU6t{-dte<YtPS-h&TJ-7ImF^+D31!GCx' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
