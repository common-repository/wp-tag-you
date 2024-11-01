=== WP Tag You ===
Contributors: the-ank
Donate link: http://paypal.me/ankurvishwakarma786
Tags: comments,users,mention in comments,mention users in comments, facebook type tagging feature , tag users in comments , mention user by @ keyword, email notifications to tagged users .  
Requires at least: 4.0
Requires PHP: 5.2.4
Tested up to: 4.9.5 
Stable tag: 1.1
License: GPLv2 or later

Allows website users to tag(mention) each other like facebook using "@" keyword while posting comments on a WP Post/Pages/Custom Posts. 

== Description ==

Allows website users to tag(mention) each other like facebook using "@" keyword while posting comments on a WP Post/Pages/Custom Posts.

This plugin contains following features - 

#Frontend - 

1. It works for default comments section provided by wordpress to each post , page or custom post .

2. People can tag more then one users in single comment .

3. Each tagged user will get an email notification where email body contains comment link , conversation string and notification body .

4. To change the default email format , you can edit wp-content\plugins\wp-tag-you\templates\default_mail_format\default_mail_format.php

6. Suggestion box shows "display name" of user and avatar that will help to find correct one easily .

7. Plugin contains hooks for development point of view that would help to customize it.

#Backend - 

1. Allows admin to turn on/off the Tag "@" feature.
2. Admin can select who can use this feature logged in / non logged in / logged in + non logged in .
3. Admin can select which user roles can use this feature.

== Installation ==

This section describes how to install the plugin and get it working.

Guide -
1. Extract the zip file.
2. Upload `wp-tag-you` directory to the `/wp-content/plugins/` directory ,
3. Activate the plugin by the 'Plugins' menu from wp-admin area .


== Frequently Asked Questions ==

= How to tag other users of site while commenting on posts ? =

After you get logged-in in comment box type '@' with some words of user's name ex- @jh and auto suggestion will list all the related users of site. 

= Does this plugin support auto suggestion while typing user name ? =

Yes, this plugin contains an auto suggestion box and display user hint while typing .

= Can multiple users be tagged? =

Yes, the plugin works whether you tag one or multiple users.

= Do I need to know the user's username , email ? =

No, this plugin has its own auto suggestion box So when people types a name of existing user ex : @jhon than it will show a list with user jhon if jhon is a registered user .

= Can I change the default mail format ? =

Yes , you can change it from default mail format file in plugin from here -  wp-content\plugins\wp-tag-you\templates\default_mail_format\default_mail_format.php

= Does this plugin contains any notifier for tagged users ? =

Yes , once user / users would be tagged in a comment , suddenly an email will send to them with conversation message and comment link .

= Does this plugin contains hooks ? =

Yes , either you would be able to change body of mail , subject and you can apply action just after you save comment .

== Screenshots ==
1. Comment box with tag"@" users.
2. Settings Area.

== Changelog ==
= 1.0 =
Initial Version released 

= 1.1 (2018-12-18) =

* Fixed Minor Bugs
* Added settings in wp-admin area for admin to enable/disable tagging feature, enable feature for loggedin , non logged in or both and allow tagging by user roles.  
