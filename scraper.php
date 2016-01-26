<?
// This is a template for a PHP scraper on morph.io (https://morph.io)
// including some code snippets below that you should find helpful
// require 'scraperwiki.php';
// require 'scraperwiki/simple_html_dom.php';
//
// // Read in a page
// $html = scraperwiki::scrape("http://foo.com");
//
// // Find something on the page using css selectors
// $dom = new simple_html_dom();
// $dom->load($html);
// print_r($dom->find("table.list"));
//
// // Write out to the sqlite database using scraperwiki library
// scraperwiki::save_sqlite(array('name'), array('name' => 'susan', 'occupation' => 'software developer'));
//
// // An arbitrary query against the database
// scraperwiki::select("* from data where 'name'='peter'")
// You don't have to do things with the ScraperWiki library.
// You can use whatever libraries you want: https://morph.io/documentation/php
// All that matters is that your final data is written to an SQLite database
// called "data.sqlite" in the current working directory which has at least a table
// called "data".
?>


<?php
require 'scraperwiki.php';

$endtime = time() + (60*60) * 23; //23h 

for ($id = 1; $id <= 5; $id++) {
	if ($endtime <= time())
	{
		exit;
	}
	$i = 1;
	$delay = 250000;
	  if (!validateEntry($id))
	  {
	  print $id;
	  while (!validateEntry($id))
	  {
	    print ".";
	  	$delay = $delay + $i * 250000;
	  	//limit to 5 secs
	  	if ($delay > 5000000) {
	  		$delay = 5000000;
	  	}
	  	if ($i % 20 == 0)
	  	{
	  		$delay = 60000000;
	  	}
	  	if ($i == 61)
	  	{
	  		exit;
	  	}
	    usleep($delay);
	    ripById($id);
	    $i++;
	  }
	  print "!";
	  }
}
function ripById($id){
	$pathToDetails = 'http://www.shborujen.ir/DesktopModules/eFormViewer/eFormViewerEdit.aspx?TabID=4753&Site=DouranPortal&MId=14286&Lang=fa-IR&ItemID=1&fID=1228&keyID=itemid%7C' . $id;
	
	$output = scraperwiki::scrape($pathToDetails);
	$firstnamepattern = '/<input name="eFormEditData1228\$field1421\$controlToValidate_Field72\$Field72_Value".*" value="(.*)".*>/smiU';
	$surnamepattern = '/<input name="eFormEditData1228\$field1415\$controlToValidate_Field73\$Field73_Value.*" value="(.*)".*>/smiU';
	$fathernamepattern = '/<input name="eFormEditData1228\$field1416\$controlToValidate_Field74\$Field74_Value.*value="(.*)".*>/smiU';
	$deathdatepattern = '/<input name="eFormEditData1228\$field1418\$ctl00\$txt.*" value="(.*)".*>/smiU';
	$blockpattern = '/<input name="eFormEditData1228\$field1414\$controlToValidate_Field78\$Field78_Value.*" value="(.*)".*>/smiU';
	$rowpattern = '/<input name="eFormEditData1228\$field1434\$controlToValidate_Field1434\$Field1434_Value.*" value="(.*)".*>/smiU';
	$placepattern = '/<input name="eFormEditData1228\$field1413\$controlToValidate_Field77\$Field77_Value.*" value="(.*)".*>/smiU';
	$gravepattern = '/<input name="eFormEditData1228\$field1439\$controlToValidate_Field1439\$Field1439_Value.*" value="(.*)".*>/smiU';
	
		
        preg_match($firstnamepattern, $output, $temp);
      	$firstname = (isset($temp[1])) ? $temp[1] : '';
        
        preg_match($surnamepattern, $output, $temp);
        $surname = (isset($temp[1])) ? $temp[1] : '';
        
        preg_match($fathernamepattern, $output, $temp);
        $fathername = (isset($temp[1])) ? $temp[1] : '';

        preg_match($deathdatepattern, $output, $temp);
        $deathdate = (isset($temp[1])) ? $temp[1] : '';
        
        preg_match($placepattern, $output, $temp);
        $place = (isset($temp[1])) ? $temp[1] : '';
        
        preg_match($rowpattern, $output, $temp);
        $row = (isset($temp[1])) ? $temp[1] : '';
        
        preg_match($blockpattern, $output, $temp);
        $block = (isset($temp[1])) ? $temp[1] : '';
        
        preg_match($gravepattern, $output, $temp);
        $grave = (isset($temp[1])) ? $temp[1] : '';
        
        
	scraperwiki::save_sqlite(array('data'), 
	                    array(
	                          'id'      => $id,
	                          'firstname' => $firstname,
	                          'surname' => $surname, 
	                          'fathername' => $fathername, 
	                          'birthdate' => $birthdate, 
	                          'deathdate' => $deathdate,
	                          'place' => $place, 
	                          'block' => $block, 
	                          'row' => $row,
	                          'grave' => $grave));
}
function validateEntry($id){
	$result = false;
	// Set total number of rows
	try {
	$recordSet = scraperwiki::select("* from data where id ='". $id . "'");
	if (!empty($recordSet[0]['id'])) {
		if ($recordSet[0]['surname'] != ""){
			$result = true;	
		}
		if ($recordSet[0]['firstname'] != ""){
			$result = true;	
		}
		if ($recordSet[0]['fathername'] != ""){
			$result = true;	
		}
	} 
	} catch (Exception $e) {
	}
	return $result;
}
