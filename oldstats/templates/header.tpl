<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <link href="{$page.baseURL}favicon.ico" rel="shortcut icon">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{if $page.refresh}
  <meta http-equiv="Refresh" content="120">
{/if}
{if $page.theme != 'none'}
  <link rel="stylesheet" type="text/css" href="{$page.baseURL}css/{$page.theme}.css">
  <link rel="stylesheet" type="text/css" media="print" href="{$page.baseURL}css/print.css">
{/if}
  <title>{$page.servername}{if $page.title} - {$page.title}{/if}</title>
</head>
<body>

<div id="banner">

<div id="menubar">
<a{if $page.area == 'home'} class="active"{/if} href="{$page.baseURL}">Home</a> <a{if $page.area == 'players'} class="active"{/if} href="{$page.baseURL}currentplayers.php">Players</a> <a{if $page.area == 'servers'} class="active"{/if} href="{$page.baseURL}serverlist.php">Servers</a> <a{if $page.area == 'help'} class="active"{/if} href="{$page.baseURL}help.php">Help</a>
</div>

<a name="top"></a>
</div>

<div id="main">

<!--<div id="languages">
Languages: <a href="{$page.baseURL}?language=en"><img src="{$page.baseURL}flags/f0-us.gif" alt="en"></a> | <a href="{$page.baseURL}?language=it"><img src="{$page.baseURL}flags/f0-it.gif" alt="it"></a>
</div>-->

<h1>{$page.servername}{if $page.title} - {$page.title}{/if}</h1>

<div id="menu">
<div class="{if $page.area == 'home'}menusectionactive{else}menusectionhome{/if}"><a href="{$page.baseURL}">Home</a></div>

<div class="{if $page.area == 'players'}menusectionactive{else}menusection{/if}">
Players
<ul>
<li><a href="{$page.baseURL}currentplayers.php">Current Players</a></li>
<li><a href="{$page.baseURL}playerstats.php">Player Stats</a></li>
<li><a href="{$page.baseURL}playersearch.php">Player Search</a></li>
</ul>
</div>

<div class="{if $page.area == 'servers'}menusectionactive{else}menusection{/if}">
Servers
<ul>
<li><a href="{$page.baseURL}serverlist.php">Server List</a></li>
</ul>
</div>

<div class="{if $page.area == 'help'}menusectionactive{else}menusection{/if}"><a href="{$page.baseURL}help.php">Help</a></div>

<div class="menusection">
Other Links
<ul>
<li><a href="http://bzflag.org/">BZFlag.org</a></li>
<li><a href="/bb/">Forums</a></li>
<li><a href="/league/">CTF League</a></li>
<li><a href="http://my.bzflag.org/w">BZFlag Wiki</a></li>
<li><a href="http://my.bzflag.org/w/Download">Download</a></li>
<li><a href="http://my.bzflag.org/w/Other_Links">Other Links</a></li>
<li><em><a href="{$page.baseURL}link.php">Link to us!</a></em></li>
</ul>
</div>

<div class="menusection">
<form action="{$page.baseURL}setoptions.php" method="post">
<p>Options</p>

<p><select name="theme" style="width: 120px;">
{foreach from=$themes item=themename key=themeid}
<option value="{$themeid}"{if $themeid == $page.theme} selected="selected"{/if}>{$themename}{if $themeid == $page.theme}*{/if}</option>
{/foreach}
</select></p>

<p><input type="checkbox" name="refresh" value="yes"{if $page.refresh} checked="checked"{/if}>
Auto refresh?</p>

<p>
<input type="submit" value="Save">
</p>
</form>
</div>


<div class="menusection">

<div class="center">
<p><a href="{$page.baseURL}rssgenerator.php"><img src="images/xml.gif" alt="RSS Feed"></a></p>
<p><a href="{$page.baseURL}link.php"><img src="images/link_{if $theme == 'bluetangerine'}bronze{elseif $theme == 'industrial'}silver{else}blue{/if}.png" alt="got stats?"></a></p>
<p><a href="http://validator.w3.org/check?uri=referer"><img src="images/valid-html401.png" alt="Valid HTML 4.01 Strict" height="31" width="88"></a></p>
</div>
</div>

</div>

<div id="content">
