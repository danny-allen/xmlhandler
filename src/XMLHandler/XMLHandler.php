<?php

namespace XML;
use Exception;
use DOMDocument;

/**
 * XMLHandler
 *
 * Adds methods useful to DOMDocument objects.
 *
 * Note: If possible - extending the DOMDocument would be better? Also the name XMLHandler, should it be
 * DOMHandler?
 */
class XMLHandler {

	/**
	 * __construct
	 *
	 * Saves the DOMDocument for use in the class.
	 * 
	 * @param DOMDocument $dom Must be a DOMDocument object.
	 */
	public function __construct(DOMDocument $dom) {
		$this->dom = $dom;
	}


	/**
	 * load
	 *
	 * Loads data to be used in the class.
	 *
	 * This data is not drectly used here, but classes instantiating this class make
	 * use of this, so to use it in their own methods.
	 * 
	 * Note: Since it does not have any affect on this class, perhaps it's best placed in the
	 * classes inhriting this one?
	 * 
	 * @param  array 	$data 	The data to load for use.
	 */
	public function load($data) {
		$this->_data = $data;
	}


	/**
	 * createChildElements
	 *
	 * Loops through child items from the elements array and adds them to the parent element.
	 * 
	 * @param  object  	$parent   	Node object from the DOMDocument.
	 * @param  array   	$elements 	The array of elements to append to the parent.
	 * 
	 * @return object 				Return this to maintain chainability.
	 */
	function createChildElements($parent, array $elements) {

		//make sure this is a node
		$this->validateNode($parent);
		
		//loop the elements
		foreach ($elements as $name => $value) {

			//we're going a level deeper as we likely have some XML nodes with the same name!
			if(is_array($value)){
				$this->createChildElements($parent, $value);
				continue;
			}

			//check its a string
			if(is_string($value)){
				$element = $this->dom->createElement($name, $value);

			//check its a DOMelement
			}else if($this->validateNode($value)){
				$element = $value;

			}else {
				throw new \Exception(__CLASS__."::".__FUNCTION__.' - Well, that was not a valid node value for '.$name.'.');
			}

			//check the element exists
			if(isset($element)){
				$parent->appendChild($element);
			}
		}

		//for chainability
		return $this;
	}


	/**
	 * validateNode
	 *
	 * Determines whether the value passed is an object and of the type DOMElement.
	 * 
	 * @param  object  				$node        	Must be a DOMElement.
	 * @param  boolean 				$allow_false 	Optional, but will allow a false value to be returned instead of an exception
	 * 
	 * @return boolean/exception 	               	Optional allow_false can change the data type.
	 */
	function validateNode($node, $allow_false = false) {

		//make sure this is a node
		if(gettype($node) == "object" && (get_class($node) == "DOMElement" || get_class($node) == "DOMDocument")) return true;

		//might be okay with a non node value
		if($allow_false) return false;

		//throw validation error!
		throw new \Exception(__CLASS__."::".__FUNCTION__." - You are trying to add elements to something that is not a node.");
	}


	/**
	 * setAttributes 
	 *
	 * Loops through attributes array and adds them to the node element.
	 * 
	 * @param object 	$node 			Node object from the DOMDocument.
	 * @param array  	$attributes 	The array of attributes to add to the node.
	 */
	function setAttributes($node, array $attributes) {

		//make sure this is a node
		$this->validateNode($node);

		//loop through and set the attributes
		foreach($attributes as $name => $value) {
			$node->setAttribute($name, $value);
		}

		//for chainability
		return $this;
	}
}