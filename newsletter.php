<?php  //newsletter.php

require_once "database.php";

function createPost( $headline, $image, $dimensions, $date, $time, $loc, $fb, $rsvp, $description){
	if ($headline == "")
		return false;
		$post = "<div style=\"padding-bottom: 15px;\">
	<section style=\"display: block;\">
		<h2 style=\"font-size: 1.4em;margin: .83em 0;\">
			<img src=\"http://ieee.ucsd.edu/Newsletter/NewsBarStart.png\" align=\"left\" style=\"margin: 0; padding: 0; border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle; height: 1.4em;\"><span style=\"background-color: #76899d; margin:0; padding-right: 1.8em; color: #FFFFFF; line-height: 1.4em; border: 2px solid #76899d;\">$headline</span><!--<img src=\"http://ieee.ucsd.edu/Newsletter/NewsBarEnd.png\" style=\"position: relative; margin: 0; padding: 0; top: -0.2em;border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle; height: 1.4em;\">-->

		</h2>
	</section>

	<p style=\"margin: 1em 0;\">
		<img src=\"$image\" align=\"left\" width=\"$dimensions[0]px\" height=\"$dimensions[1]px\"  style=\"border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle; padding-right: 3px; padding-bottom: 3px;\">
	</p>
	
	<p style=\"margin: 1em 0;\">";
	if ( $date != "" )
		$post .= "<b style=\"font-weight: bold;\">Date:</b> $date <br>";
	if ( $time != "" )
		$post .= "<b style=\"font-weight: bold;\">Time:</b> $time <br>";
	if ( $loc != "" )
		$post .= "<b style=\"font-weight: bold;\">Location:</b> $loc <br>";
	$post .= "$description<br />";						
		
		if ( strpos($fb, "http") > -1 ){				
			$post .= "<b style=\"font-weight: bold;\">Facebook event: </b><a href=\"$fb\" target=\"_blank\">$fb</a> <br>";
		}
		if ( strpos($rsvp, "http") > -1 ){				
			$post .= "<b style=\"font-weight: bold;\">RSVP Link: </b><a href=\"$rsvp\" target=\"_blank\">$rsvp</a> <br>";
		}

	$post.="</p></div>";
	return $post;
}
//$lol = array_keys($_POST);
$headline = array();
if (isset($_POST["headline"]))
		$headline = $_POST["headline"];
if (isset($_POST["image"]))
		$image = $_POST["image"];
if (isset($_POST["dimensions"]))
		$dimensions = $_POST["dimensions"];
if (isset($_POST["date"]))
		$date = $_POST["date"];
if (isset($_POST["time"]))
		$time = $_POST["time"];
if (isset($_POST["location"]))
		$location = $_POST["location"];
if (isset($_POST["fbEvent"]))
		$fbEvent = $_POST["fbEvent"];
if (isset($_POST["rsvpLink"]))
		$rsvpLink = $_POST["rsvpLink"];
if (isset($_POST["description"]))
		$description = $_POST["description"];

for($i = 0; $i < sizeof($headline); $i++){
	$widthHeight = array($dimensions[2*$i], $dimensions[2*$i+1]);
	$post = createPost( $headline[$i], $image[$i], $widthHeight, $date[$i], $time[$i], $location[$i], $fbEvent[$i], $rsvpLink[$i], nl2br($description[$i]));
  if ( $post != false )
		addToDatabase( $headline[$i], $post );
}

if (isset($_POST['postsToRemove'])){
	$postsToRemove = $_POST['postsToRemove'];
	foreach ($postsToRemove as $posts){
		$headline = str_replace("-", " ", $posts);
    if( $posts == "all_Posts" )
      removeAllFromDatabase();
		removeFromDatabase($headline);
	}
}
$editionArr = grabEdition();
$quarterType = $editionArr[1];
$weekNum = $editionArr[0];

$editionChanged = false;
if (isset($_POST["quarterType"])){
	$quarterType = $_POST["quarterType"];
	$editionChanged = true;
}
if (isset($_POST["weekNum"])){
	$weekNum = $_POST["weekNum"];
	$editionChanged = true;
}
if ($editionChanged)
	updateEdition($weekNum, $quarterType);

$quarterType = ucwords($quarterType);
$year = date("Y");
$editionStr = "Week $weekNum - $quarterType Quarter $year";

?>
<!DOCTYPE html>
	<html>		
		<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>UCSD IEEE Weekly Newsletter</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
    </head>
    <body style="margin: 0;font-size: 1em;line-height: 1.4;font: 16px/26px Helvetica, Helvetica Neue, Arial;">
				<div id="navBar">
				</div>
        <div class="header-container" style="border-bottom: 20px solid #006699; padding-top: 1.4em;background: #8DBFD8;">
            <header class="wrapper clearfix" style="display: block;width: 90%;margin: 0 5%;">
                <h1 class="title" style="font-size: 2em;margin: .67em 0;color: white; padding: .2em .4em 0em .4em;">UCSD IEEE Weekly Newsletter</h1>
                <nav style="display: block;">
                    <!--<ul style="margin: 0;padding: 0;list-style: none;list-style-image: none;">-->
                        <span width="80%" align="center" style="margin-left: .7em;"><a href="http://ieee.ucsd.edu/news/item.php?id=159" target="_blank" style="font-size: .8em; display: inline; padding: .9em .8em; margin-bottom: 10px;text-align: center;text-decoration: none;font-weight: bold;color: white;background: #006699;">Join a Project</a>
                        <a href="http://ieee.ucsd.edu/getinvolved/" target="_blank" style="font-size: .8em; display: inline; padding: .9em .8em; margin-bottom: 10px;text-align: center;text-decoration: none;font-weight: bold;color: white;background: #006699;">Get Involved</a></li>
                        <a href="http://ieee.ucsd.edu/photos/" target="_blank" style="font-size: .8em; display: inline; margin-bottom: 10px; padding: .9em .8em; text-align: center;text-decoration: none;font-weight: bold;color: white;background: #006699;">Photos</a></span>
                    <!--</ul>-->
                </nav>
            </header>
        </div>

        <div class="main-container">
            <div class="main wrapper clearfix" style="width: 90%;margin: 0 5%;padding: 30px 0;">

<?php
$allPosts = grabContents();
echo $allPosts;
?>
            </div> <!-- #main -->
        </div> <!-- #main-container -->

						<div id="contact-container" align="center"><span style="display:block; width:80%; font-size: 1.4em; text-decoration: underline; margin-bottom: .2em;">Contact Us:</span><a href="mailto:ieee.ucsd@gmail.com" target="_blank"><img src="http://ieee.ucsd.edu/Newsletter/Mail.png" border="0" style="border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle;"></a> <a href="http://ieee.ucsd.edu/" target="_blank"><img src="http://ieee.ucsd.edu/Newsletter/Net.png" border="0" style="border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle;"></a> <a href="http://www.facebook.com/group.php?gid=2202794509&amp;ref=ts" target="_blank"><img src="http://ieee.ucsd.edu/Newsletter/Face.png" border="0" style="border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle;"></a>
						</div>

        <div class="footer-container" style="border-top: 20px solid #006699;background: #8DBFD8;">
            <footer class="wrapper" style="display: block;width: 90%;margin: 0 5%;color: white;padding: 20px 0px;">
                <h3 style="font-size: 1.17em;margin: 1em 0; margin-bottom: 0; display: inline; padding-left: .6em;"><?php echo "$editionStr"; ?></h3>
								<h4 style="margin:0; padding: 0;"><a href="https://mailman.ucsd.edu/mailman/listinfo/ieee-ucsd-l" target="_blank" style="text-decoration: none; padding-left: .6em;">Click here to Unsubscribe from future newletters.</a></h4>
            </footer>
        </div>

	</body>
</html>


