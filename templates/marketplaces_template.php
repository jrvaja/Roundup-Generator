<?php $template = "


<hr />
<h2> <span> $i.</span> <a href='$page'>$title</a> </h2>

<div class='tutorial_image'> 
	<a href='$page'> $image </a>";
		
	if ( !empty($audio) ) {
		$template .= "<div> <a href='$audio'>Listen to a Preview</a> </div>";
	}
$template .= "
</div>

<blockquote>
	<p> $text </p>
</blockquote>";


return $template;