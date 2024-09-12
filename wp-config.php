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

define( 'DB_NAME', 'mapeditorarhamso_map-editor' );



/** MySQL database username */

define( 'DB_USER', 'mapeditorarhamso_map-editor' );



/** MySQL database password */

define( 'DB_PASSWORD', '&r1.F?IBl*2(' );



/** MySQL hostname */

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

define( 'AUTH_KEY',         '}XWE-~_*4&Xvl4sCr,~#%NFsDFARx (W>[q781v9zzbX9+0?!BI5ul}X5iOub<Ft' );

define( 'SECURE_AUTH_KEY',  'iJ$CGp$turbyT*<&,y&waq^go*MYt~#A;/i~Ib%?y}~T0+s2.&Rz}-(X}Vyk&{-r' );

define( 'LOGGED_IN_KEY',    '(p&^aTAJDC3Ff?6l6K<=-/@ZLuR|d$8*^{|z!UKWC]VFgD]<>q>xm/j,g? gCutn' );

define( 'NONCE_KEY',        '=~63mfw+|0k)jLY{9>nFl[=K}Dy!;wke!a/mSv$c5@}#euU2[A,^b(#3+xLY%?g*' );

define( 'AUTH_SALT',        'Y.XgnJ3d50w(S>4*%&fhxBhm,#pK_;tfRrh$#]+#/~cl*DlqQqC{)SS!WQ  Nt5n' );

define( 'SECURE_AUTH_SALT', '.F;{2C)q7pc35n>45q}r3f#>dwoa2?T#5n,:F)a0O~g%%JU8X#I@4:iP_:#QSp2%' );

define( 'LOGGED_IN_SALT',   'cjXPThEqAnR58c<N2Ry4jWt7w?>u|M%RYkF_B;FCv)cJHXo27Xb_J_<<^uoe2(c5' );

define( 'NONCE_SALT',       '5%qB=qI5cDo@=P=8%KE*c6Yg8A9KRzPY00O/Cqv05:Y+ktRzY7:A%6HLFzTJ5#5`' );



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
//define( 'WPSTRAVA_DEBUG', true );


/* Add any custom values between this line and the "stop editing" line. */







/* That's all, stop editing! Happy publishing. */



/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}



/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

