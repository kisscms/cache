<?php

/**
 * Cache
 * Persistant cache for KISSCMS
 * Created by Makis Tracend ( @tracend )
 *
 */

if ( !class_exists("Cache") ){

class Cache extends Model {

	private $timeout = 10000; // update only once in 10 seconds

	function __construct( $options=array() ) {
		// update timeout
		if( array_key_exists("timeout", $options) ) $this->timeout = $options['timeout'];
		// merge more options...

		// create db file
		$this->pkname = 'key';
		$this->tablename = 'cache'; //sqlite_master
		// schema
		$this->rs['id'] = NULL;
		$this->rs['key'] = '';
		$this->rs['value'] = '';
		$this->rs['updated'] = 0;

		parent::__construct('cache.sqlite', $this->pkname, $this->tablename); //primary key = id; tablename = sqlite_master
		// condition the creation of the table...
		$this->create_table('cache', "id INTEGER PRIMARY KEY ASC, key, value, updated");

		return $this;
	}

	// set the contents of an iteam
	public function setItem($key, $data){
		$value = json_encode_escaped($data);
		$this->set('key', $key);
		$this->set('value', $value);
		$this->create();
		//
	}

	// get the contents of an item
	public function getItem($key){
		$this->retrieve($key);
		return json_decode($this->get('value'), true);
	}

	public function removeItem($key){
		$this->set('key', $key);
		$this->delete(); // shouldn't this be read()?
		return;
	}

	// check if an item exists
	public function checkItem($id){

	}

	// get the last updated time
	public function valid($key, $timestamp=false ){
		// compare against a date (fallback to now)
		if( !$timestamp ) $timestamp = (string) $_SERVER['REQUEST_TIME'];
		$this->retrieve($key);
		return $this->get("updated") != 0 && ( (int) $this->get("updated") < $timestamp - $this->timeout );
	}


	// CRUD
	function create(){
		$timestamp = (string) $_SERVER['REQUEST_TIME'];
		// #5 include microseconds when calculating REQUEST_TIME in PHP < 5.4
		if( strlen($timestamp) == 10 ) $timestamp .= "000";
		$this->rs['updated'] = $timestamp;

		return parent::create();
	}

	function update(){
		$timestamp = (string) $_SERVER['REQUEST_TIME'];
		// #5 include microseconds when calculating REQUEST_TIME in PHP < 5.4
		if( strlen($timestamp) == 10 ) $timestamp .= "000";
		$this->rs['updated'] = $timestamp;

		return parent::update();
	}
}

}
