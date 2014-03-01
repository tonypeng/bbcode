<?php
/*
 * BBCode.php - sample.php
 * Copyright (c) 2010-2014 Tony "untitled" Peng.  All rights reserved.
 * <http://www.tonypeng.com/>
 *
 * This file is part of the BBCode project <http://git.io/UVM12g>
 * and is licensed under the MIT license <http://git.io/oT_TKg>.
 */

require_once('../bbcode.php');
?>
<html>
<head>
    <title>bbcode.php</title>
    <link href="http://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800" rel="stylesheet" type="text/css">
    <link type="text/css" rel="stylesheet" href="style.css">
</head>
<body>
<a href="https://github.com/unt1tl3d/bbcode"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://github-camo.global.ssl.fastly.net/652c5b9acfaddf3a9c326fa6bde407b87f7be0f4/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6f72616e67655f6666373630302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png"></a>
<div style="text-align: center;">
<br />
<br />
<strong><span style="font-size: 20px;">BBCode.php Sample</span></strong>
<br />
<?php
if(!isset($_POST['submit']))
{
?>
<form method="post" action="">
    <textarea cols="65" rows="15" style="margin-left:auto; margin-right:auto; font-family: 'Dosis', sans-serif; font-size: 16px; border: 1px solid #bebebe; border-radius: 5px;" name="bbcode">no markup [b]bold [i]italics[/i][/b] [b][i]mismatched tags don't parse[/b][/i] <a href="javascript:alert('injected');">no html/XSS injection</a>
    </textarea>
    <br />
    <input type="submit" name="submit" value="Parse It! (ALT+P)" accesskey="p" style="border: 1px solid #bebebe; background-color: #eee; border-radius: 5px; font-family: 'Dosis', sans-serif; font-size: 15px; cursor: pointer;" />
</form>
<br />
<br />
<div class="revisionBox">
    <strong>Revision History</strong><br />
    <br />
    <span style="text-decoration: underline;">8.28.10</span><br />Started on parser.  Parser can detect unfinished <strong>first-level</strong> bbcode.  Recursive checking (child bbcode) is not implemented yet.<br /><br />
    <span style="text-decoration: underline;">8.29.10</span><br />Recursive parsing just about done.  There may still be bugs thoughl more testing needs to be done.  Next up: Tag fixing when invalid tags are detected.<br /><br />
    <span style="text-decoration: underline;">8.31.10</span><br />Parser is pretty much done.  (Most) Bugs have been fixed.<br /><br />
    <span style="text-decoration: underline;">2.28.14</span><br />3 and a half years later!  Fixed a long-standing bug in which text with no mark-up in child text is ignored.  Moved to OOPHP and did some housekeeping.
</div>
<?php
}
else
{
    $text = $_POST['bbcode'];

    $bbcodeParser = new BBCode();

    require_once('bbcode_handlers.php'); // load our handlers

    $bbcodeParser->addTagHandler('b', 'bbcode_parse_bold');
    $bbcodeParser->addTagHandler('i', 'bbcode_parse_italics');
    $bbcodeParser->addTagHandler('u', 'bbcode_parse_underline');

    $syntaxTree = $bbcodeParser->parse($text, true);
    $output = $bbcodeParser->treeToText($syntaxTree);
?>
    <br />
    The input:<br />
    <div style="background-color: #EEE; width: 600px; max-height: 400px; overflow: auto; margin-left: auto; margin-right: auto; padding: 5px; text-align: left; border: 1px solid #000; border-radius: 5px;">
        <pre style="margin: 0;"><?php echo $text; ?></pre>
    </div>
    <br />
    Produced the syntax tree:<br />
    <div style="background-color: #EEE; width: 600px; max-height: 400px; height: 400px; overflow: auto; margin-left: auto; margin-right: auto; padding: 5px; text-align: left; border: 1px solid #000; border-radius: 5px;">
        <pre style="margin: 0;"><?php print_r($syntaxTree); ?></pre>
    </div>
    <br />
    Which results in the output:<br />
    <div style="background-color: #EEE; width: 600px; margin-left: auto; margin-right: auto; padding: 5px; text-align: left; border: 1px solid #000; border-radius: 5px;">
        <span>
        <?php echo $output; ?>
        </span>
    </div>
    <br />
    <a href="javascript: history.go(-1);" style="text-decoration: none; color: #ff9f49;">Go back</a>
<?php
}
?>
<br />
<br />
<span>&copy; 2010-2014 <a style="text-decoration: none; color: #2d89ef;" href="http://www.tonypeng.com/">Tony Peng</a></span>
</div>
</body>
</html>