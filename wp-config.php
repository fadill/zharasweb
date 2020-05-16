<?php
define('WP_CACHE', true);






define('DISABLE_WP_CRON', false);

/** Serve static content from cookieless domain */
//define("WP_CONTENT_URL", "http://wp-content.zharasonline.com");
define("COOKIE_DOMAIN", "zharasonline.com");
//define("WP_PLUGIN_URL", "http://wp-content.zharasonline.com/plugins");

/* Set the address instead of having the server checking it, this will reduce server queries. */
define('WP_HOME', 'https://zharasonline.com');
define('WP_SITEURL', 'https://zharasonline.com');

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
define('DB_NAME', 'u4917047_zharasonline_db');

/** MySQL database username */
define('DB_USER', 'u4917047_zharasonline_user');

/** MySQL database password */
define('DB_PASSWORD', 'aMeZynaNyG');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '2ygzdTJBjKhRVmOH1v727srTeQ6GxWGPAs0oZlYyU7kYhDRypnbUOEK978pvXEte');
define('SECURE_AUTH_KEY',  'K2wsQVAbTGSMnlKwcI4EII3DaCVPTFE6wGDwlsCtcPAlYpFvbgaCyH4nqqWmFjcp');
define('LOGGED_IN_KEY',    'nRD4P88wEWEDcIRdocNkTYQp4OGnAhRisMZsLGIeoLAdT92l6yC52J6UQoaXi02l');
define('NONCE_KEY',        'ReqQO7nqSNccGR2d0bsqTWaNftRChoCfIQvRylF2eN8oeii9nIU2oxEFOFhLMqH9');
define('AUTH_SALT',        'o1jKWbBL0MN7hn5FrfMEazhAGmQEwPtnWMU7BMIXr3hmHzjgSkhTq5XKgGH4rtPQ');
define('SECURE_AUTH_SALT', 'itUDvGgRab4gRwG40J3MyYc7yR7fTBMVFUn88Zgwx5VxYzEJJbBUi2uVY8FaJ60N');
define('LOGGED_IN_SALT',   'Py9Rxs45TUNkSTaWXMlHsiEEUmAKltBFC0hwvwXvJdrTWdQTQ0onnhYbHOxJeuTF');
define('NONCE_SALT',       'cQDz7jvKsetXeFL8dNjD5e7ERXeKCL2cMujezRVARV3PwJqB94JwcDLX9xkFDyfg');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ee3p_';

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
require_once(ABSPATH . 'wp-settings.php');/** Reduce Post Revisions, Drafts, Spam,  */define( 'WP_POST_REVISIONS', 3 );/** Empty trash after 10 days **/
define ('EMPTY_TRASH_DAYS', 10);

/** Disable Editing in Dashboard */
define('DISALLOW_FILE_EDIT', true);