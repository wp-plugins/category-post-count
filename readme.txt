=== Category Post Count ===
Contributors: karalamalar
Tags: categories,posts_per_page,posts_per_rss,count,category
Requires at least: 3.0
Tested up to: 4.2.4
Stable tag: 4.2.4
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

With this plugin you can set the posts_per_page and posts_per_rss settings for individual categories.

== Description ==

Sometimes you need to customize post counts for individual categories. There is no built-in method for this in WordPress. So this plugin steps in and fills that gap. You can set values for each category as you wish. 

Does not support custom taxonomies yet.

= Future =

* Options Page for various options
* Support for tags
* Support for custom taxonomies
* Support for search results

== Installation ==

You can use automattic install from your extensions page or you can install it manually;

1. Unzip archive, upload `category-post-count` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What is the default value of `posts_per_page` and `posts_per_rss`? =

If you didn't change it after installing WordPress, it has to be 10. It can be changed from Settings > Reading page of your Admin area.

= Does it have to be same values for posts and feeds? =

You can use different values. You can even left it empty to use default value of your blog.

== Screenshots ==

1. When adding a category, you can specify post count for that category.
2. Category listing shows each category's `posts_per_page` and `posts_per_rss` values.

== Changelog ==

[0.1.2] - 2015-08-07

* Added Dutch translations by Taco Verdonschot

[0.1.1] - 2015-08-06

* Changed textdomain variable to hardcoded strings (https://markjaquith.wordpress.com/2011/10/06/translating-wordpress-plugins-and-themes-dont-get-clever/ - Thanks Bjørn)
* Added Norwegian Bokmål translations by Bjørn Johansen (Thanks again)

[0.1.0] - 2015-08-06

* First version