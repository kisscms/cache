## KISSCMS: Cache

Persistent cache plugin for KISSCMS, using a dedicated SQLite database. 

Use this when you need caching that spans accross users and possibly extends beyond session persistance...


## Install 

Download and place the archive in your plugins folder


## Usage

A sample usage of the class. You should only need to initiate the class once per run...
```
$cache = new Cache();

// add an item
$key = "mykey";
$value = "a value can be a string or array";
$cache->setItem( $key, $value );

// get item
$cache->getItem( $key );

```

## Options

When initiating the class you can pass an array of options. Thhose are: 

* timeout - set the time difference where the contents of the cache gets invalidated. Set in milliseconds. Default: 10000 (10 seconds).


## Methods

The interface has been modelled after regular Web Storage APIs (localStorage, sessionStorage)

### setItem()

Saved a single key-values to the database.  

### getItem()

Retrieves an item with the specific key. Returns null if nothing found.

### removeItem()

Removes the entry with the specific key. 


## Showcase

A few of the sites using this open source:

[![Commoncode](http://appicon.makesit.es/commoncode.net)](http://commoncode.net)
[![Gistalks](http://appicon.makesit.es/gistalks.com)](http://gistalks.com)


##Credits

Initiated by Makis Tracend ( [@tracend](http://github.com/tracend) )

Distributed through [Makesites.org](http://makesites.org)

Released under the [MIT license](http://makesites.org/licenses/MIT)


