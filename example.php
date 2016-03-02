<?php
	
	require_once('vendor/autoload.php');

	use DannyAllen\XMLHandler;

	//set xml headers
	header("Content-type: text/xml");

	//make the XML object
	$dom = new DOMDocument('1.0', 'utf-8');

	//make XML handler
	$xml = new XMLHandler($dom);

	//create an element
	$test = $xml->dom->createElement('test');

	//create child elements of test
	$xml->createChildElements($test, array(
		'first' 	=> '1st',
		'second'	=> '2nd',
		'third'		=> '3rd'
	));	

	//add the test node to the dom object
	$xml->createChildElements($dom, array(
		'test' => $test
	));

	//output
	echo $xml->dom->saveXML();
