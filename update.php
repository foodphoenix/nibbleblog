<?php

/*
 * Nibbleblog -
 * http://www.nibbleblog.com
 * Author Diego Najar

 * All Nibbleblog code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
*/

define('UPDATER_VERSION', '1.1');

require('admin/boot/rules/1-fs_php.bit');
require('admin/boot/rules/99-constants.bit');

// DB
require(PATH_DB . 'nbxml.class.php');

// Helpers
require(PATH_HELPERS . 'html.class.php');
require(PATH_HELPERS . 'date.class.php');

// Language
require( 'languages/en_US.bit' );

// Set timezone
Date::set_timezone('UTC');

// Variables
$blog_domain = getenv('HTTP_HOST');

?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Nibbleblog Updater <?php echo UPDATER_VERSION ?></title>

	<style type="text/css">
		body {
			font-family: arial,sans-serif;
			background-color: #FFF;
			margin: 0;
			padding: 0;
			font-size: 0.875em;
			color: #555;
		}

		#container {
			background: none repeat scroll 0 0 #F9F9F9;
			border: 1px solid #EBEBEB;
			border-radius: 3px 3px 3px 3px;
			margin: 50px auto;
			max-width: 700px;
			padding: 20px 30px;
			width: 60%;
			box-shadow: 0 0 4px rgba(0, 0, 0, 0.05);
		}

		h1 {
			margin: 0 0 20px 0;
			text-align: center;
		}

		h2 {
			color: #6C7479;
		}

		a {
			color: #2361D3;
			cursor: pointer;
			text-decoration: none;
		}

		a:hover {
			text-decoration: underline;
		}

		footer {
			margin: 30px 0;
			border-top: 1px solid #f1f1f1;
			text-align: center;
			font-size: 0.9em;
		}

		#head {
			margin-bottom: 20px;
		}
</style>

</head>
<body>

	<div id="container">

		<header id="head">
			<?php
				echo Html::h1( array('content'=>$_LANG['WELCOME_TO_NIBBLEBLOG']) );
			?>
		</header>

		<noscript>
		<section id="javascript_fail">
			<h2>Javascript</h2>
			<p><?php echo $_LANG['PLEASE_ENABLE_JAVASCRIPT_IN_YOUR_BROWSER'] ?></p>
		</section>
		</noscript>

		<section id="configuration">
			<?php

				function set_if_not($obj, $name, $value)
				{
					if(!$obj->is_set($name))
					{
						$obj->setChild($name, $value);
					}
				}

				// notifications.xml
				if(!file_exists(FILE_XML_NOTIFICATIONS))
				{

					$xml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
					$xml .= '<notifications>';
					$xml .= '</notifications>';
					$obj = new NBXML($xml, 0, FALSE, '', FALSE);
					$obj->asXml( FILE_XML_NOTIFICATIONS );

					echo Html::p( array('class'=>'pass', 'content'=>'File created: '.FILE_XML_NOTIFICATIONS) );
				}

				// config.xml
				$obj = new NBXML(FILE_XML_CONFIG, 0, TRUE, '', FALSE);
				set_if_not($obj,'notification_comments',0);
				set_if_not($obj,'notification_session_fail',0);
				set_if_not($obj,'notification_session_start',0);
				set_if_not($obj,'notification_email_to','');
				set_if_not($obj,'notification_email_from','noreply@'.$blog_domain);
				$obj->asXml( FILE_XML_CONFIG );
				echo Html::p( array('class'=>'pass', 'content'=>'DB updated: '.FILE_XML_CONFIG) );

				// comments.xml
				$obj = new NBXML(FILE_XML_COMMENTS, 0, TRUE, '', FALSE);
				set_if_not($obj,'moderate',1);
				set_if_not($obj,'sanitize',1);
				set_if_not($obj,'monitor_enable',0);
				set_if_not($obj,'monitor_api_key','');
				set_if_not($obj,'monitor_spam_control','0.75');
				set_if_not($obj,'monitor_auto_delete',0);
				$obj->asXml( FILE_XML_COMMENTS );
				echo Html::p( array('class'=>'pass', 'content'=>'DB updated: '.FILE_XML_COMMENTS) );

			?>
		</section>

		<footer>
			<p><a href="http://nibbleblog.com">Nibbleblog <?php echo NIBBLEBLOG_VERSION ?> "<?php echo NIBBLEBLOG_NAME ?>"</a> | Copyright (2009 - 2013) + GPL v3 | Developed by Diego Najar </p>
		</footer>

	</div>

</body>
</html>