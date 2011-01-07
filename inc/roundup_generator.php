<?php

require_once('config.php');
require_once('functions.php');


/** 
*  Roundup Generator
*
*  Scrapes the necessary content from the Envato marketplaces and the Tuts+ network
*  and then returns that data, preformated as HTML, for use in WordPress.
* 
*  @author Jeffrey Way (http://jeffrey-way.com) 
*  @version 0.9
*  @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*/ 

class RoundUp_Generator extends Controller {

   /** 
   *  Slice out the needed assets from the Envato marketplaces. 
   *  @param string $contents The entire contents of the scraped page
   *  @param string $page URL to the page
   *  @return array 
   */
   
   function __construct()
   {
   		# If needed, add any future site names that should be added to the list of allowed sites. 
   		//$this->allowed_sites[] = 'appHaven';
   		parent::__construct();
   }
   
	protected function marketplaces($contents, $page) // the returned page for each item
	{
		// What's the marketplace name? 
		preg_match('/(?<=:\/\/)([^\.]+)/i', $page, $sitename);
	
		// We have to use slightly different regexes, dependent upon the site (in some cases)
		switch ( $sitename[1] ) {
			case 'graphicriver' :
				// Get the item page image.
				preg_match('/(<img.+GraphicRiver\sItem[^>]+>)/i', $contents, $image);
				break;
				
			case 'audiojungle' :
				// Get the link to the preview audio clip
				preg_match('/previewConfigs[\s\S]+preview_url:\s[\'"]?([^\'"]+)/i', $contents, $audio);

			default : // AD, TF, CC, T+M
				// Get the item page image.
				preg_match('/(?<=col-s-content">)[\s\S]+?(<img\s[^>]+>)/i', $contents, $image);
		}

		// Get the title
		preg_match('/class="page-title">(.+)</i', $contents, $title);

		// Get the intro paragraph
		preg_match('/class="item-header">[\s\S]+?<p>([\s\S]+?)(?=((<\/p>)|(<br)))/i', $contents, $text);

		$ret = array(
			'title' => !empty($title[1]) ? $title[1] : null, 
			'image' => !empty($image[1]) ? $image[1] : null,
			'text'  => !empty($text[1]) ? $text[1] : null
		);
		
		if ( isset($audio) ) $ret['audio'] = $audio[1];
		
		return $ret;
	}
	
	
   /** 
   *  Slice out the needed assets from the Tuts+ network sites. 
   *  @param string $contents The entire contents of the scraped page
   *  @param string $page URL to the page
   *  @return array 
   */	
	protected function tuts($contents, $page)
	{
		// post image, title, intro_paragraph
		preg_match('/class="post_image[\s\S]+?(<img[^>]+>)[\s\S]+?post_title[^a-z]+([^<]+)[\s\S]+?class="post[\s\S]+?<p>([\s\S]+?)<\/p>/i', $contents, $matches);
		
		$this->before_template = '<ul class="webroundup">';
		$this->after_template = '</ul>';
		
		return array(
			'post_image' => $matches[1],
			'title' => $matches[2],
			'intro_paragraph' => $matches[3]
		);
	}

}

new RoundUp_Generator();