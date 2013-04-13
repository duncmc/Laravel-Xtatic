<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<?=Xtatic::get('title')?>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<?=Xtatic::head()?>
</head>

<body class="<?=Xtatic::get('slug')?>">

<!-- START Container -->
<div id="layout-container">

<header id="layout-header">
<?=render('xtatic::common.header')?>
</header>

<nav id="layout-primary-nav">
<?=Xtatic::menu('primary-navigation')?>
</nav>

<?=Xtatic::content()?>

<footer id="layout-footer">
<?=render('xtatic::common.footer')?>
</footer>

</div>
<!-- END Container -->

<small id="layout-attribution">&copy; <?=date('Y')?> &middot; <?=Xtatic::get('site_owner')?> &middot; Website by Art &amp; Soul <a href="http://www.artandsoul.co.uk">Web Design Hull</a></small>

<?=Xtatic::tail()?>

</body>
</html>