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
		$this->pkname = 'id';
		$this->tablename = 'cache'; //sqlite_master
		// schema
		$this->rs['id'] = 0;
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
		// reset id
		$this->set('id', 0);
		// find an existing key if available
		$this->retrieve_one("key=?", $key);
		$this->set('key', $key);
		$value = json_encode_escaped($data);
		$this->set('value', $value);
		return ( !( $this->get('id') ) ) ? $this->create() : $this->update();
		//
	}

	// get the contents of an item
	public function getItem($key){
		// reset id
		$this->set('id', 0);
		$this->retrieve_one("key=?", $key);
		return ( !( $this->get('id') ) ) ? null : json_decode($this->get('value'), true);
	}

	public function removeItem($key){
		// reset id
		$this->set('id', 0);
		$this->retrieve_one("key=?", $key);
		if( !( $this->get('id') ) ) $this->delete(); // shouldn't this be read()?
		return;
	}

	// check if an item exists
	public function checkItem($id){

	}

	// get the last updated time
	public function valid($key, $timestamp=false ){
		// first make sure the item exists
		$this->set('id', 0);
		$this->retrieve_one("key=?", $key);
		if( !( $this->get('id') ) ) return false;
		// compare against a date (fallback to now)
		if( !$timestamp ) $timestamp = (string) $_SERVER['REQUEST_TIME'];
		if( strlen($timestamp) == 10 ) $timestamp .= "000";
		return $this->get("updated") != 0 && ( (int) $timestamp < (int) $this->get("updated") + $this->timeout );
	}


	// CRUD
	function create(){
		$timestamp = (string) $_SERVER['REQUEST_TIME'];
		// #5 include microseconds when calculating REQUEST_TIME in PHP < 5.4
		if( strlen($timestamp) == 10 ) $timestamp .= "000";
		$this->rs['updated'] = $timestamp;
		// continue as usual...
		return parent::create();
	}

	function update(){
		$timestamp = (string) $_SERVER['REQUEST_TIME'];
		// #5 include microseconds when calculating REQUEST_TIME in PHP < 5.4
		if( strlen($timestamp) == 10 ) $timestamp .= "000";
		$this->rs['updated'] = $timestamp;
		// continue as usual...
		return parent::update();
	}
}

}
