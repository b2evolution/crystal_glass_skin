<?php 
/**
 * This is the main template. It displays the blog.
 *
 * However this file is not meant to be called directly.
 * It is meant to be called automagically by b2evolution.
 * To display a blog, you should call a stub file instead, for example:
 * /blogs/index.php or /blogs/blog_b.php
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/license.html}
 * @copyright (c)2003-2006 by Francois PLANQUE - {@link http://fplanque.net/}
 *
 * @package evoskins
 * @subpackage crystal_glass
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

skin_content_header(); // Sets charset!
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php locale_lang() ?>" lang="<?php locale_lang() ?>">
<head>
<?php skin_content_meta(); /* Charset for static pages */ ?>
<title>
<?php 
$Blog->disp('name', 'htmlhead');
request_title( ' - ', '', ' - ', 'htmlhead' );
?>
</title>
<?php skin_base_tag(); /* Base URL for this skin. You need this to fix relative links! */ ?>
<meta name="description" content="<?php $Blog->disp( 'shortdesc', 'htmlattr' ); ?>" />
<meta name="keywords" content="<?php $Blog->disp( 'keywords', 'htmlattr' ); ?>" />
<meta name="generator" content="b2evolution <?php echo $app_version ?>" /> <!-- Please leave this for stats -->
<link rel="alternate" type="text/xml" title="RSS 2.0" href="<?php $Blog->disp( 'rss2_url', 'raw' ) ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom" href="<?php $Blog->disp( 'atom_url', 'raw' ) ?>" />
<?php $Plugins->trigger_event( 'SkinBeginHtmlHead' ); ?>
<link rel="stylesheet" type="text/css" media="print" href="print.css" />
<link rel="stylesheet" type="text/css" media="screen" href="stylesheet.css" />
<?php 
$Blog->disp( 'blog_css', 'raw');
$Blog->disp( 'user_css', 'raw');
// comments_popup_script(); // Uncomment to include javascript to open pop up windows
?>
</head>

<body>
<?php if( $disp != 'single' ) {
	echo '<div id="wrapper">';
	} else {
	echo '<div id="wrapperWide">';
	} ?>

<div id="header">
	<h1><a href="<?php $Blog->disp( 'blogurl', 'raw' ) ?>" title="<?php $Blog->disp( 'shortdesc', 'htmlbody' ); ?>"><?php $Blog->disp( 'name', 'htmlbody' ) ?></a></h1>
	<div class="description"><?php $Blog->disp( 'tagline', 'htmlbody' ) ?></div>
</div>

<div id="BlogList">

<?php 
/* -------------------------- BLOG LIST INCLUDED HERE --------------------------
 * customize this with settings in skins/skinname/_bloglist.php or 
 * copy skins/_bloglist.php to skins/skinname and really customize it
 */
require( dirname(__FILE__).'/_bloglist.php' );
// ----------------------------- END OF BLOG LIST --------------------------- ?>

</div>

<hr />

<?php // ------------------------ START OF MAIN AREA ------------------------ ?>


<?php if( $disp != 'single' ) {
	echo '<div id="bPosts">';
	} else {
	echo '<div id="bPostsWide">';
	} ?>


<?php // ------------------- MESSAGES GENERATED FROM ACTIONS -------------------
if( empty( $preview ) ) $Messages->disp();
// ------------------------------- END OF MESSAGES -------------------------- ?>


<?php // -------------------- TITLE FOR THE CURRENT REQUEST --------------------
if( $disp != 'single' ) {
	request_title( '<h2 class="pagetitle">', '</h2>' );
	}
// ----------------------------- END OF REQUEST TITLE ----------------------- ?>

<p>
<?php 
if( $disp == 'posts' ) {
	$Blog->disp( 'longdesc', 'htmlbody' );
	} ?>
</p>

<?php // ------------------------ START OF POSTS LOOP --------------------------
if( isset($MainList) ) $MainList->display_if_empty(); // Display message if no post
if( isset($MainList) ) while( $Item = & $MainList->get_item() ) {
	// $MainList->date_if_changed(); // display post date if it changed
	locale_temp_switch( $Item->locale ); // Temporarily switch to post locale
	$Item->anchor(); // Anchor for permalinks to refer to
	?>

<div class="bPost" lang="<?php $Item->lang() ?>">

<h2><?php $Item->title(); ?></h2>

<div class="bSmallHead">
	<?php $Item->issue_date('F jS, Y'); ?>
	&nbsp;
	(<?php $Item->views();
	echo ' '; ?>)
	</div>

<div class="bText">
	<?php 
	$Item->content();
	link_pages();
	?>
	</div>

<div class="bSmallPrint">
	<?php $Item->msgform_link( $Blog->get('msgformurl') ); ?> 
	<a href="<?php $Item->permanent_url() ?>" title="<?php echo T_('Permanent link to full entry') ?>" class="permalink_right"><img src="img/chain_link.gif" alt="<?php echo T_('Permalink') ?>" width="22" height="22" border="0" class="middle" /></a>
	<?php $Item->feedback_link( 'feedbacks' ) // Link to comments & trackbacks & pingbacks ?>
	<?php $Item->edit_link( ' &bull; ' ) // Link to backoffice for editing ?>
	<?php $Item->trackback_rdf() // trackback autodiscovery information ?>
	</div>

<?php // -------- START OF INCLUDE FOR COMMENTS, TRACKBACKS, PINGBACKS ---------
$disp_comments = 1;      // Display the comments if requested
$disp_comment_form = 1;  // Display the comments form if comments requested
$disp_trackbacks = 1;    // Display the trackbacks if requested
$disp_trackback_url = 1; // Display the trackbal URL if trackbacks requested
$disp_pingbacks = 1;     // Display the pingbacks if requested * pingbacks are going away!
// now get the feedbacks
require( dirname(__FILE__).'/_feedback.php' );
// ------------- END OF INCLUDE FOR COMMENTS, TRACKBACKS, PINGBACKS ------------

locale_restore_previous();	// Restore previous locale (Blog locale) ?>

</div>

<?php } // --------------------- END OF POSTS LOOP -------------------------- ?> 

<p class="center"><strong>
<?php 
// navigation links for multi-post pages
posts_nav_link();
// navigation links for single-post pages
previous_post( '%' ); // uses post title for %
next_post( '%' ); // uses post title for %
?>
</strong></p>

<?php // -------- START OF INCLUDES FOR LAST COMMENTS, MY PROFILE, ETC. --------
$current_skin_includes_path = dirname(__FILE__).'/';
// Call the dispatcher:
require $skins_path.'_dispatch.inc.php';
// -------------- END OF INCLUDES FOR LAST COMMENTS, MY PROFILE, ETC. ------- ?>

</div>

<?php // --------------------- START OF SIDEBAR (RIGHT)  -------------------- ?>

<?php if( $disp != 'single' ) { ?>

<div id="bSideBar">

<div class="bSideItem">
<p class="center"><strong><?php posts_nav_link( ' | ', '< '.T_('Previous'), T_('Next').' >' ); ?></strong></p>

<?php 
/* ------------------------- CALL THE CALENDAR PLUGIN --------------------------
 * customize your calendar by adding parameters to the plugin's array. see 
 * http://doc.b2evolution.net/v-1-9/plugins/calendar_plugin.html#methodSkinTag 
 * or plugins/_calendar.plugin.php for details
 */
$Plugins->call_by_code( 'evo_Calr', array( // Add parameters below:
	'block_start'=>'',
	'block_end'=>'',
	'title'=>'', // No title.
	) );
// ---------------------------- END OF CALENDAR ----------------------------- ?>

<p class="center"><a href="<?php $Blog->disp( 'arcdirurl', 'raw' ) ?>" title="Visit the complete archives"><?php echo T_('Archives') ?></a></p>

</div>

<br />

<?php 
/* ------------------------- CALL THE ARCHIVES PLUGIN --------------------------
 * customize your archives by adding parameters to the plugin's array. see 
 * http://doc.b2evolution.net/v-1-9/plugins/archives_plugin.html#methodSkinTag 
 * or plugins/_archives.plugin.php for details
 */
#$Plugins->call_by_code( 'evo_Arch', array( // Add parameters below:
#	) );
// ---------------------------- END OF ARCHIVES ----------------------------- ?>


<?php 
/* ------------------------ CALL THE CATEGORIES PLUGIN -------------------------
 * customize your categories by adding parameters to the plugin's array. see 
 * http://doc.b2evolution.net/v-1-9/plugins/categories_plugin.html#methodSkinTag 
 * or plugins/_categories.plugin.php for details
 */
$Plugins->call_by_code( 'evo_Cats', array( // Add parameters below:
	) );
// --------------------------- END OF CATEGORIES ---------------------------- ?>


<?php 
/* -------------------------- LINKBLOG INCLUDED HERE ---------------------------
 * customize this with settings in skins/skinname/_linkblog.php or 
 * copy skins/_linkblog.php to skins/skinname and really customize it
 */
require( dirname(__FILE__).'/_linkblog.php' );
// ---------------------------- END OF LINKBLOG ----------------------------- ?>


<?php 
/* ------------------------- SKINS LIST INCLUDED HERE --------------------------
 * customize this with settings in skins/skinname/_skinslist.php 
 * this is a hack - it is NOT an official part of b2evolution!
 */
if( ! $Blog->get('force_skin') ) {
	require( dirname(__FILE__).'/_skinslist.php');
	} // -------------------------- END OF SKINS LIST ------------------------- ?>


<?php // --------------------- WHO'S ONLINE INCLUDED HERE ----------------------
if( empty($generating_static) && ! $Plugins->trigger_event_first_true('CacheIsCollectingContent') ) { ?>
	<div class="bSideItem">
	<h3 class="sideItemTitle"><?php echo T_('Who\'s Online?') ?></h3>
	<?php $Sessions->display_onliners(); ?>
	</div>
	<?php 
	} // ------------------------- END OF WHO'S ONLINE ------------------------ ?>

<div class="bSideItem">
<h3><?php echo T_('Misc') ?></h3>
<ul>
<?php 
user_login_link( '<li>', '</li>' );
user_register_link( '<li>', '</li>' );
user_admin_link( '<li>', '</li>' );
user_profile_link( '<li>', '</li>' );
user_subs_link( '<li>', '</li>' );
user_logout_link( '<li>', '</li>' );
?>
</ul>
</div>

<?php // --------------------- SEARCH BOX INCLUDED HERE --------------------- ?>
<div class="bSideItem">
<?php form_formstart( $Blog->dget( 'blogurl', 'raw' ), 'search', 'SearchForm' ) ?>
<p><input type="text" name="s" size="30" value="<?php if (htmlspecialchars($s)=='') { echo 'Search'; } else { echo htmlspecialchars($s); } ?>" onclick="if(this.value=='Search') { this.value='';}" class="SearchField" /></p>
<input type="hidden" name="sentence" value="AND" id="sentAND" <?php if( $sentence=='AND' ) echo 'checked="checked" ' ?>/>
</form>
</div>
<?php // ------------------------ END OF SEARCH BOX ------------------------- ?>


<div class="bSideItem">
<h3><?php echo T_('Syndicate this blog') ?> <img src="../../img/xml.gif" alt="<?php echo T_('XML Feeds') ?>" width="36" height="14" class="middle" /></h3>
<ul>
<li>RSS 0.92: <a href="<?php $Blog->disp( 'rss_url', 'raw' ) ?>"><?php echo T_('Posts') ?></a>, <a href="<?php $Blog->disp( 'comments_rss_url', 'raw' ) ?>"><?php echo T_('Comments') ?></a></li>
<li>RSS 1.0: <a href="<?php $Blog->disp( 'rdf_url', 'raw' ) ?>"><?php echo T_('Posts') ?></a>, <a href="<?php $Blog->disp( 'comments_rdf_url', 'raw' ) ?>"><?php echo T_('Comments') ?></a></li>
<li>RSS 2.0: <a href="<?php $Blog->disp( 'rss2_url', 'raw' ) ?>"><?php echo T_('Posts') ?></a>, <a href="<?php $Blog->disp( 'comments_rss2_url', 'raw' ) ?>"><?php echo T_('Comments') ?></a></li>
<li>ATOM 1.0: <a href="<?php $Blog->disp( 'atom_url', 'raw' ) ?>"><?php echo T_('Posts') ?></a>, <a href="<?php $Blog->disp( 'comments_atom_url', 'raw' ) ?>"><?php echo T_('Comments') ?></a></li>
</ul>
<a href="http://fplanque.net/Blog/itTrends/2004/01/10/rss_rdf_and_atom_in_a_nutshell" title="External - English"><?php echo T_('What is RSS?') ?></a>
</div>

<div class="bSideItem">
<p class="center">powered by<br />
<a href="http://b2evolution.net/" title="b2evolution home"><img src="../../rsc/img/b2evolution_button.png" alt="b2evolution" width="80" height="15" border="0" class="middle" /></a>
</p>
<p class="center">
<?php 
// Display additional credits (see /conf/_advanced.php)
display_list( $credit_links, T_('Credits').': ', ' ', '|', ' ', ' ' );
?>
</p>
</div>



</div>

<?php } // end hiding the sidebar on single post pages ?>


<?php // ----------------------- START OF PAGE FOOTER ----------------------- ?>

<div id="pageFooter">
<p class="baseline">
<br />Valid 
<a href="http://validator.w3.org/check/referer" title="Valid XHTML 1.0!">XHTML</a> :: 
<a href="http://jigsaw.w3.org/css-validator/" title="Valid CSS!">CSS</a> :: 
<a href="http://feedvalidator.org/check.cgi?url=<?php $Blog->disp( 'rss2_url', 'raw' ) ?>" title="Valid RSS!">RSS</a> :: 
<a href="http://feedvalidator.org/check.cgi?url=<?php $Blog->disp( 'atom_url', 'raw' ) ?>" title="Valid Atom!">Atom</a> :: <a href="http://b2evolution.net" title="powered by b2evolution">b2evolution</a>
</p>
</div>

</div>

<?php 
$Hit->log(); // log the hit on this page
debug_info(); // output debug info if requested
?>

</body>

</html>
