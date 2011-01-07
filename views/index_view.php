<!DOCTYPE html>

<html lang="en">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   
   <title>Envato Marketplace Round-up Generator for Tuts+</title>
   
	<!--[if lte IE 7]>
	    <script>
	        // Come on Envato peeps; you know better than to use this old browser... :)
	        location = 'http://chrome.google.com';
	    </script>
	<![endif]-->
      
	<link rel="stylesheet" href="css/style.min.css" />
    <link rel="shortcut icon" href="./favicon.ico">
</head>
<body>

<div>
    <h1>
        <span>Marketplace Round-up Generator</span> 
        <a href="#" title="To use this tool, paste in links -- each separated by a comma -- into the textarea." class="trigger">*</a> 
    </h1>
    
    <form method="post" action="">
    
        <select name="site_select" id="site_select">
            <option value="Marketplaces"> Marketplaces </option>
            <option value="Tuts"> Tuts+ </option>
        </select>
        
        <textarea name="assets" id="assets" required autofocus><?php if ( isset($_POST['assets']) ) echo $_POST['assets']; ?></textarea>
        <input type="submit" value="Submit" id="submit" name="submit" />
        <?php 
        if ( isset($error) && $error !== '' ) {
            echo "<p class='result'> $error </p>";
        }
        ?>
        
        <?php 
        if ( isset($link) ) {
            echo "<p class='result'> $link </p>";
        }
    ?>
    </form>
</div>

<div class="overlay trigger"> 
    <p>
        To use this tool, paste in links -- each separated by a comma -- into the textarea. Once you click submit, the script will compile the formatted roundup for you. 
        If you'd prefer a Tuts+ network roundup instead, you can choose that from the dropdown menu. 
    </p>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="js/site.min.js"></script>

</body>
</html>