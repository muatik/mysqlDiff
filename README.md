mysqlDiff - mysql database differences
=========
This is a tool that displays schema differences between two versions of same mysql databases.

Example
-----------

### Database `test`

Table structure for table `posts`

    CREATE TABLE IF NOT EXISTS `posts` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `content` text NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

Table structure for table `users`

    CREATE TABLE IF NOT EXISTS `users` (
    `id` int(10) unsigned NOT NULL,
    `email` varchar(50) NOT NULL,
    `age` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;


### Database: `test2`

Table structure for table `logs`

    CREATE TABLE IF NOT EXISTS `logs` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `log` text COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

Table structure for table `users`

    CREATE TABLE IF NOT EXISTS `users` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `age` smallint(5) unsigned DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

Sample output:

![screenshot1](https://raw.github.com/muatik/mysqlDiff/master/screen1.png "example 1")
