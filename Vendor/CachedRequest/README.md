Cached Request
--------------

These classes are designed to help minimize the load of making cURL requests.  Each request has reference keyword associated with it, and a cache file is created.  This cached file will be fed back if you use the appropriate reference.  This library includes a caching system, and a cURL utility to help in your development.

How to Use:

- First you will want to initialize the class:

`$cachedResult = new CachedResult();`

- Then to make a GET request:

`$cachedRequest->get(__url__, __fields as an array__, __reference__)`

- Or to make a POST request:

`$cachedRequest->post(__url__, __fields as an array__, __reference__)`

- If you want to clear the cache, you can do the following:

`$cachedRequest->clearCache();`

- You can also clear the cache for a specific file.  Just use the reference that you sent with the request.

`$cachedRequest->clearCachedFileByReference(reference);`

Testing
-------

All the code has been tested using [PHPUnit](www.phpunit.de).  You can run the tests by calling:

`phpunit tests/`

Development
-----------

Questions or problems? Please post them on the [issue tracker](). You can contribute changes by forking the project and submitting a pull request.

This script is created by Johnathan Pulos and is under the [GNU General Public License v3](http://www.gnu.org/licenses/gpl-3.0-standalone.html).