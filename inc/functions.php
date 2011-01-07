<?php

class Controller {

	public $marketplace_name;
	public $selected_template;
	public $before_template;
	public $after_template;
	protected $allowed_sites = array('graphicriver', 'themeforest', 'videohive', 'activeden', 'tutsplus', 'codecanyon', '3docean', 'audiojungle', 'photodune');

	function __construct()
	{
		$data = array();

		// Has the form been submitted, and assets were added?
		if ( isset($_POST['submit']) ) {
		
			$this->selected_template = $_POST['site_select'];
		
			if ( strlen($_POST['assets']) > 0 ) {
				$data = $this->init();
			} else {
				$data = array("error" => "Don't forget to paste in some links, fool!");
			}
		}

		$this->loadView('views/index_view.php', $data);
	}


	protected function init()
	{
		// Take the list of links, and turn it into an array
		$assets = explode( ',', trim($_POST['assets']) ); 

		// Filters through the inks, and create the necessary markup.
		$data = $this->compileAssets($assets);
		
		// Write the data to a text file.
		$data = $this->writeToFile( $data, 'roundupTemplate.txt' );

		return $data;
	}
	

	protected function compileAssets($assets)
	{
		$fullRoundup = '';
		$i = 0;
		$error = '';
			
		// For each link...
		foreach( $assets as $asset ) {
			
			$snippet = $this->createMarkup( trim($asset), ++$i );
				
			// If something was returned, update the roundup data.
			if ( $snippet !== false ) {
				$fullRoundup .= $snippet . "\r\n";
			}
				
			// Otherwise, error:
			else {
				$error = 'Please note that some or all of your links were not able to be accessed. ';
				--$i;
			}
		}
		
		// Add any wrapping markup that might have been specified...
		$fullRoundup = $this->before_template . $fullRoundup . $this->after_template;

		return array(
			'fullRoundup' => $fullRoundup,
			'error'		  => $error
		);
	}


	protected function createMarkup( $page, $i )
	{
	
		// If the link isn't an Envato-approved site, let's get outta here. 
		if ( !$this->is_envato_site( $page ) ) { return false; }
	
		// If you couldn't load the page, get outta here. An error will be thrown.
		if ( (@$contents = file_get_contents($page)) === false ) {
			return false;
		}
		
		// Replace pretty text stuff with defaults. 
		$pretty = array('–', '”', '“', "’");
		$replacements = array('-'. '"', '"', '&rsquo;');
		$contents = str_replace($pretty, $replacements, $contents);
		
		// Calls the necessary function from index.php, which determines what info
		// to extract from the page. 
		extract( $this->prepareRegExprs($contents, $page) );

		// Apply the template for each roundup item.
		$roundupItem = include('templates/' . strtolower($this->selected_template) . '_template.php');
		
		// Return the formatted snippet for that particular link.
		return $roundupItem;
	}
	
	
	protected function is_envato_site( $page )
	{
		$result = false; 
		foreach( $this->allowed_sites as $site_name ) {
			if ( stristr($page, $site_name) === false ) {
				continue;
			}
			$result = true;
		}
		
		return $result;
	}
	

	protected function prepareRegExprs($contents, $page)
	{
		// Determine which template was chosen
		return ( call_user_func( array($this, $this->selected_template), $contents, $page ) );
	}
	
	
	public function writeToFile( $data, $fileName )
	{
		// If the returned roundup is not empty, paste into txt file.
		if ( strlen($data['fullRoundup']) > 0 ) {
			$fp = fopen($fileName, "w");
			fwrite($fp, $data['fullRoundup']);
			fclose($fp);
				
			$data['link'] = "<a href='$fileName' target='_blank'>Paste the contents of this link into the 'New Post' panel in WordPress.</a>";
		}
		return $data;
	}


	public function loadView($file, $data = '')
	{
		if (is_array($data) ) {
			extract($data);
		}
		require_once($file);
	}

}
