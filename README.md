Joshua Project API
------------------

An API for connecting to the [Joshua Project](https://joshuaproject.net) data.  It uses the [PHP Slim Framework](http://slimframework.com).

Development
-----------
This repository is following the branching technique described in [this blog post](http://nvie.com/posts/a-successful-git-branching-model/), and the semantic version set out on the [Semantic Versioning Website](http://semver.org/).

This tool uses [Composer](http://getcomposer.org/) to install the project specific libraries.  We also use [Swagger](https://developers.helloreverb.com/swagger/) annotations for generating the front facing API documentation.

Questions or problems? Please post them on the [issue tracker](https://github.com/MissionalDigerati/vts_cakephp_plugin/issues). You can contribute changes by forking the project and submitting a pull request.

Deployment
----------

To deploy this code, simply follow these steps:

1. Clone the code to your server.
2. Copy the file `.env.example` to `.env`.
3. Open the file, and set the appropriate settings.
4. Install [Composer](https://getcomposer.org/) on your server.
5. Install all the required librarues with `composer install`.

PHP Settings
------------
In your `php.ini` file you will need to set the following values equal to or greater than the value provided:

1. max_execution_time = 90

Sample Code
-----------

Sample code for PHP, Ruby, Javascript and Python can be found in the following [Github Repo](https://github.com/MissionalDigerati/joshua_project_api_sample_code).

Update on JP Server
-------------------

To update the submodule, use the following command: `git submodule update --recursive --remote`.

Google Analytics 4
------------------

To set up the Google Analytics, you need to do the following in Google Analytics:

1. Add a data stream: Admin > Data Streams > Add a Stream
2. Get the Measurement ID
3. To get the secret: Admin > Data Streams > Click the appropriate stream
    - Click Measurement Protocol API Secrets
    - Create
    - Copy the key
4. Add the event: Admin > Events > Create Event api_requests
5. Add custom definitions:
    - Admin > Custom Definitions
    - Create 3 Custom Dimensions: endpoint, format, version

Add your settings to the .env file, and in 24 hours you should start receiving data.

License
-------
This script is created by Missional Digerati and is under the [GNU General Public License v3](http://www.gnu.org/licenses/gpl-3.0-standalone.html).
