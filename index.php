<?php
header("Content-type: text/xml");
?>
<?xml version="1.0" encoding="UTF-8"?>

<!--
<!DOCTYPE impress SYSTEM "impress.dtd">
-->

<?xml-stylesheet href="impress.xsl" type="text/xsl"?>

<impress numbered="yes">

<!--example with small distance between slides-->
<increment x="1000" y="1000" angle="45" length="4" />
<!--
'x' is the horizontal distance between slides
'y' is the vertical distance between slides
'angle' is the angle between two slides
'lenth' is the loop length
-->


<title>
Events
</title>

<style>
.step {
	width: 1000px;
	font-size: x-large;
}
</style>


<?php
require_once("zapcallib/zapcallib.php");

$url = "https://calendar.google.com/calendar/ical/fachschaft.huwi%40uni-bamberg.de/private-0d89752042a2759a972a611cdacd2f0d/basic.ics";
$content = file_get_contents($url);
$icalobj = new ZCiCal($content);

foreach($icalobj->tree->child as $node)
{
	if($node->getName() == "VEVENT")
	{
		?>
			
			<?php
			
			$summary = $node->data['SUMMARY']->getValues();
			$description = $node->data['DESCRIPTION']->getValues();
			$dtstart = $node->data['DTSTART']->getValues();
			$date = strtotime($dtstart);
			$datestring = date("l, d. F, G:i", $date);
			$location = $node->data['LOCATION']->getValues();
			
			preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $description, $match);
			
			if (count($match[0]) > 0)
			{
				$img = $match[0][0];
				$description = str_replace($img,"",$description);
			}else{
				unset($img);
			}
			
			// skip if date is too old
			if (date("Y-m-d", $date) < date("Y-m-d"))
			{
				continue;
			}
			
			echo "<step>";
			
			echo "<h1>".htmlspecialchars($summary, ENT_XML1, 'UTF-8')."</h1>";
			echo "<h2>".htmlspecialchars($datestring, ENT_XML1, 'UTF-8')." / ".htmlspecialchars($location, ENT_XML1, 'UTF-8')."</h2>";

			echo "<div class='description'>".nl2br(htmlspecialchars($description, ENT_XML1, 'UTF-8'))."</div>";
			
			if (isset($img))
			{
				echo "<div class='image'><img class='image' src='".htmlspecialchars($img, ENT_XML1, 'UTF-8')."' /></div>";
			}
			
			
			
			echo "</step>";

	}
}

//https://calendar.google.com/calendar/ical/marc.kohaupt%40gmail.com/private-7477960ca56bed7b2cb545d565dac028/basic.ics

?>


</impress>
