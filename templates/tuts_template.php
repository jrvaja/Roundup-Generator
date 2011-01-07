<?php $template = "

<li class='clear'>
	<div>
		$post_image
	</div>
	<h4><a href='$page'>" . trim($title) . "</a></h4>
	<p>$intro_paragraph</p>
	<p><a href='$page'>Visit Article</a></p>
</li>

";

return $template;