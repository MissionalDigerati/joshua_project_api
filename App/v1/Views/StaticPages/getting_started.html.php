<?php
/**
 * Joshua Project API - An API for accessing Joshua Project Data.
 * 
 * GNU Public License 3.0
 * Copyright (C) 2013  Missional Digerati
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Joshua Project API</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
        <link href="css/styles.css" rel="stylesheet" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Joshua Project API</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/">Home</a></li>
                    <li class="active"><a href="/getting_started">Getting Started</a></li>
                    <li><a href="/docs/v1/sample_code">Sample Code</a></li>
                    <li><a href="/docs/v1/#!/people_groups">Documentation</a></li>
                    <li><a href="http://www.joshuaproject.net/">Joshua Project</a></li>
                    <li><a href="http://www.missionaldigerati.org">Missional Digerati</a></li>
                </ul>
                <div id="get-api-holder">
                    <a href="/" class="btn pull-right btn-info"><span class="glyphicon glyphicon-cog"></span> Get an API Key</a>
                </div>
            </div><!--/.nav-collapse -->
        </div>
        <div class="container">
            <div class="row">
                <div id="sidebar" class="col-md-3">
                    <div class="hidden-print visible-desktop affix" role="complementary">
                        <ul class="nav sidenav">
                            <li class="active">
                                <a href="#overview">Overview</a>
                                <ul class="nav">
                                    <li><a href="#overview-api-keys">API Keys</a></li>
                                    <li><a href="#overview-get-http-request-method">GET HTTP Request Method</a></li>
                                    <li><a href="#overview-url-structure">URL Structure</a></li>
                                    <li><a href="#overview-response">Response</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#getting-started">Getting Started (All Tutorials)</a>
                                <ul class="nav">
                                    <li><a href="#getting-started-starting-code">Starting Code</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#javascript">Javascript (JQuery) Example</a>
                                <ul class="nav">
                                    <li><a href="#javascript-jquery-library">The JQuery Library</a></li>
                                    <li><a href="#javascript-calling-the-api">Calling the API</a></li>
                                    <li><a href="#javascript-handling-the-error">Handling the Error</a></li>
                                    <li><a href="#javascript-creating-the-widget">Creating the Widget</a></li>
                                    <li><a href="#javascript-finishing-touches">Finishing Touches</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#php">PHP Example</a>
                                <ul class="nav">
                                    <li><a href="#php-setup">Setup</a></li>
                                    <li><a href="#php-calling-the-api">Calling the API</a></li>
                                    <li><a href="#php-creating-the-widget">Creating the Widget</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#python">Python Example</a>
                                <ul class="nav">
                                    <li><a href="#python-setup">Setup</a></li>
                                    <li><a href="#python-calling-the-api">Calling the API</a></li>
                                    <li><a href="#python-creating-the-widget">Creating the Widget</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#ruby">Ruby Example</a>
                                <ul class="nav">
                                    <li><a href="#ruby-setup">Setup</a></li>
                                    <li><a href="#ruby-calling-the-api">Calling the API</a></li>
                                    <li><a href="#ruby-creating-the-widget">Creating the Widget</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#reporting-tutorial-errors">Errors In These Tutorials?</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="page-header">
                        <h2>Getting Started</h2>
                    </div>
                    <h3 id="overview">Overview</h3>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The Joshua Project API is a REST based API for retrieving data from the <a href="http://joshuaproject.net" target="_blank" title="Visit the Joshua Project Initiative">Joshua Project Initiative</a>.  If you are not familiar with the term REST,  you can read more at <a href="http://en.wikipedia.org/wiki/Representational_state_transfer" target="_blank">Wikipedia</a>.  All requests for data from the API must contain a valid API Key, and must pass the data with the GET HTTP request method.</p>
                    <h4 id="overview-api-keys">API Keys</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;An API Key is a unique 12 character string that identifies your application as the source of every request.  Here is a sample of an API key: <code>233f76f4c84e</code>.  API keys are free and you are allowed to have multiple API keys. The only requirement is that you verify your email before you get access to your key.  <strong>Before starting this tutorial,  you will need to retrieve an <a href="/">API key</a></strong>.  Here are the steps for retrieving the API Key:<br>
                        <ul>
                            <li>Fill out and submit the form on <a href="/">this page</a>  (<strong>You must fill in all required fields</strong>)</li>
                            <li>Visit the email you provided.  You should receive an email from the Joshua Project Intiative. (<strong>You may need to check your Spam folder</strong>)</li>
                            <li>Click the link in the email to retrieve your API Key</li>
                        </ul>
                    </p>
                    
                    <h4 id="overview-get-http-request-method">GET HTTP Request Method</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Each time you click on a link in your browser, you are sending a GET HTTP request method.  There are several HTTP Request methods including POST, PUT, and DELETE, but GET is one of the most common HTTP request methods.  GET HTTP requests pass the parameters through the URL string.  You may be familiar with URLs like:</p>
                        <pre>http://mysite.com/store_center.html?page=store&task=purchase</pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Let us break this URL down.  In this URL, you are requesting the <code>http://mysite.com/</code> domain name, and you want to see the <code>store_center.html</code> file on that web server.  We are also passing 2 parameters that follow the <strong>?</strong>.  The question mark tells the server that we want to send some parameters.  Each parameter has a key and a value.  Here are the parameters we are sending:</p>
                    <table id="key_value_parameters" class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2" class="active text-center">Parameters</th>
                            </tr>
                            <tr>
                                <th class="text-center">Key</th>
                                <th class="text-center">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>page</td>
                                <td>store</td>
                            </tr>
                            <tr>
                                <td>task</td>
                                <td>purchase</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>The <strong>&</strong> is required to seperate each parameter.  That is pretty much all you will need to do to make a GET request.  We will look at this in more depth below.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For more information on the REST Architecture and the GET HTTP request method,  check out <a href="http://net.tutsplus.com/tutorials/other/a-beginners-introduction-to-http-and-rest/" target="_blank">this tutorial</a> at NetTuts.</p>
                    <h4 id="overview-url-structure">URL Structure</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Below is a typical request for data from the Joshua Project API.   In this example,  we will request the Joshua Project's unreached people group of the day for January 11th.</p>
                    <pre><?php echo $DOMAIN_ADDRESS; ?>/v1/people_groups/daily_unreached.json?api_key=233f76f4c84e&month=01&day=11</pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Here is a breakdown of the URL structure:</p>
                    <pre><?php echo $DOMAIN_ADDRESS; ?>/[api_version_number]/[resource_path].[format]?api_key=[your_api_key]&[other_parameters]</pre>
                    <table id="url_definitions" class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2" class="active text-center">URL Definitions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>api_version_number</td>
                                <td>The letter "v" followed by an integer indicating the version of the API you want to use. (Current version = v1)</td>
                            </tr>
                            <tr>
                                <td>resource_path</td>
                                <td>The path to the data you want to retreived.  You can see available resource paths in <a href="/docs/v1/#!/people_groups">the documentation</a>.</td>
                            </tr>
                            <tr>
                                <td>format</td>
                                <td>The format of the server's response.  (json or xml)</td>
                            </tr>
                            <tr>
                                <td>your_api_key</td>
                                <td>The API Key your retrieved for your application.</td>
                            </tr>
                            <tr>
                                <td>other_parameters</td>
                                <td>A series of parameters (key=value) used to filter your request.  Each parameter needs to be seperated by an <strong>&</strong>.</td>
                            </tr>
                        </tbody>
                    </table>
                    <h4 id="overview-response">Response</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Once you make a request,  the server will send back a response in either JSON or XML based on the format you specified.  Here is the JSON response for the request above:</p>
                    <pre>
[
    {
        "ROG3": "LA",
        "Ctry": "Laos",
        "PeopleID3": "14966",
        "ROP3": "109364",
        "PeopNameInCountry": "So",
        "ROG2": "ASI",
        "Continent": "Asia",
        "RegionCode": "2",
        "RegionName": "Southeast Asia",
        "ISO3": "LAO",
        "LocationInCountry": "They live on both sides of the Laos-Thailand border, especially in northern Savannakhet Province and southern Khammouan Province in Laos. An additional group of So people inhabit 53 villages in north-east Thailand.",
        "PeopleID1": "20",
        "ROP1": "A011",
        "AffinityBloc": "Southeast Asian Peoples",
        "PeopleID2": "239",
        "ROP2": "C0147",
        "PeopleCluster": "Mon-Khmer",
        "PeopNameAcrossCountries": "So",
        "Population": "138000",
        "PopulationPercentUN": "2.15989",
        "Category": "2",
        "ROL3": "sss",
        "PrimaryLanguageName": "So",
        "ROL4": "0",
        "PrimaryLanguageDialect": null,
        "NumberLanguagesSpoken": "1",
        "ROL3OfficialLanguage": "lao",
        "OfficialLang": "Lao",
        "SpeakNationalLang": null,
        "BibleStatus": "2",
        "BibleYear": null,
        "NTYear": null,
        "PortionsYear": "1980-2004",
        "TranslationNeedQuestionable": null,
        "JPScale": "1.2",
        "LeastReached": "Y",
        "LeastReachedBasis": "2",
        "GSEC": "2",
        "Unengaged": "",
        "JF": "",
        "AudioRecordings": "Y",
        "NTOnline": "",
        "GospelRadio": "",
        "RLG3": "2",
        "PrimaryReligion": "Buddhism",
        "RLG4": null,
        "ReligionSubdivision": null,
        "PercentAdherents": "0.2",
        "PercentEvangelical": "0.0500834733247757",
        "PCBuddhism": "90",
        "PCDblyProfessing": "0",
        "PCEthnicReligions": "9.8",
        "PCHinduism": "0",
        "PCIslam": "0",
        "PCNonReligious": "0",
        "PCOtherSmall": "0",
        "PCUnknown": "0",
        "PCAnglican": "0",
        "PCIndependent": "0",
        "PCProtestant": "70",
        "PCOrthodox": "0",
        "PCOtherChristian": "0",
        "PCRomanCatholic": "30",
        "StonyGround": "Y",
        "SecurityLevel": "2",
        "OriginalJPL": null,
        "RaceCode": "AUG03z",
        "IndigenousCode": "Y",
        "LRWebProfile": "Y",
        "LRofTheDayMonth": "1",
        "LRofTheDayDay": "11",
        "LRTop100": "",
        "PhotoAddress": "p14966.jpg",
        "PhotoWidth": "200",
        "PhotoHeight": "249",
        "PhotoAddressExpanded": null,
        "PhotoCredits": "Ray Mason",
        "PhotoCreditURL": null,
        "PhotoCreativeCommons": null,
        "PhotoCopyright": null,
        "PhotoPermission": null,
        "MapAddress": "m14966_la.png",
        "MapAddressExpanded": null,
        "MapCredits": "Bethany World Prayer Center",
        "MapCreditURL": null,
        "MapCopyright": "Y",
        "MapPermission": "Y",
        "ProfileTextExists": "Y",
        "FileAddress": "",
        "FileAddressExpanded": "",
        "FileCredits": "",
        "FileCreditURL": "",
        "FileCopyright": "",
        "FilePermission": "",
        "Top10Ranking": null,
        "RankOverall": "73",
        "RankProgress": "26",
        "RankPopulation": "19",
        "RankLocation": "18",
        "RankMinistryTools": "10",
        "CountOfCountries": "2",
        "CountOfProvinces": "2",
        "EthnolinguisticMap": "http://www.lib.utexas.edu/maps/middle_east_and_asia/indochina_eth_1970.jpg",
        "MapID": "sss-LA",
        "V59Country": null,
        "MegablocPC": "Y",
        "LargeSouthAsianLanguageROL3": null,
        "Longitude": "105.427040000396",
        "Latitude": "17.0162099995918",
        "Window10_40": "Y",
        "PeopleGroupURL": "http://www.joshuaproject.net/people-profile.php?peo3=14966&rog3=LA",
        "PeopleGroupPhotoURL": "http://www.joshuaproject.net/profiles/photos/p14966.jpg",
        "CountryURL": "http://www.joshuaproject.net/countries.php?rog3=LA",
        "JPScaleText": "Unreached",
        "JPScaleImageURL": "http://www.joshuaproject.net/images/scale1.jpg"
    }
]
                    </pre>
                    <p>And here is the XML response:</p>
                    <pre>
&lt;?xml version="1.0" ?&gt;
&lt;api&gt;
  &lt;people_groups&gt;
    &lt;people_group&gt;
      &lt;ROG3&gt;LA&lt;/ROG3&gt;
      &lt;Ctry&gt;Laos&lt;/Ctry&gt;
      &lt;PeopleID3&gt;14966&lt;/PeopleID3&gt;
      &lt;ROP3&gt;109364&lt;/ROP3&gt;
      &lt;PeopNameInCountry&gt;So&lt;/PeopNameInCountry&gt;
      &lt;ROG2&gt;ASI&lt;/ROG2&gt;
      &lt;Continent&gt;Asia&lt;/Continent&gt;
      &lt;RegionCode&gt;2&lt;/RegionCode&gt;
      &lt;RegionName&gt;Southeast Asia&lt;/RegionName&gt;
      &lt;ISO3&gt;LAO&lt;/ISO3&gt;
      &lt;LocationInCountry&gt;
        They live on both sides of the Laos-Thailand border, especially in northern Savannakhet Province and southern Khammouan Province in Laos.  An additional group of So people inhabit 53 villages in north-east Thailand.
      &lt;/LocationInCountry&gt;
      &lt;PeopleID1&gt;20&lt;/PeopleID1&gt;
      &lt;ROP1&gt;A011&lt;/ROP1&gt;
      &lt;AffinityBloc&gt;Southeast Asian Peoples&lt;/AffinityBloc&gt;
      &lt;PeopleID2&gt;239&lt;/PeopleID2&gt;
      &lt;ROP2&gt;C0147&lt;/ROP2&gt;
      &lt;PeopleCluster&gt;Mon-Khmer&lt;/PeopleCluster&gt;
      &lt;PeopNameAcrossCountries&gt;So&lt;/PeopNameAcrossCountries&gt;
      &lt;Population&gt;138000&lt;/Population&gt;
      &lt;PopulationPercentUN&gt;2.15989&lt;/PopulationPercentUN&gt;
      &lt;Category&gt;2&lt;/Category&gt;
      &lt;ROL3&gt;sss&lt;/ROL3&gt;
      &lt;PrimaryLanguageName&gt;So&lt;/PrimaryLanguageName&gt;
      &lt;ROL4&gt;0&lt;/ROL4&gt;
      &lt;PrimaryLanguageDialect/&gt;
      &lt;NumberLanguagesSpoken&gt;1&lt;/NumberLanguagesSpoken&gt;
      &lt;ROL3OfficialLanguage&gt;lao&lt;/ROL3OfficialLanguage&gt;
      &lt;OfficialLang&gt;Lao&lt;/OfficialLang&gt;
      &lt;SpeakNationalLang/&gt;
      &lt;BibleStatus&gt;2&lt;/BibleStatus&gt;
      &lt;BibleYear/&gt;
      &lt;NTYear/&gt;
      &lt;PortionsYear&gt;1980-2004&lt;/PortionsYear&gt;
      &lt;TranslationNeedQuestionable/&gt;
      &lt;JPScale&gt;1.2&lt;/JPScale&gt;
      &lt;LeastReached&gt;Y&lt;/LeastReached&gt;
      &lt;LeastReachedBasis&gt;2&lt;/LeastReachedBasis&gt;
      &lt;GSEC&gt;2&lt;/GSEC&gt;
      &lt;Unengaged/&gt;
      &lt;JF/&gt;
      &lt;AudioRecordings&gt;Y&lt;/AudioRecordings&gt;
      &lt;NTOnline/&gt;
      &lt;GospelRadio/&gt;
      &lt;RLG3&gt;2&lt;/RLG3&gt;
      &lt;PrimaryReligion&gt;Buddhism&lt;/PrimaryReligion&gt;
      &lt;RLG4/&gt;
      &lt;ReligionSubdivision/&gt;
      &lt;PercentAdherents&gt;0.2&lt;/PercentAdherents&gt;
      &lt;PercentEvangelical&gt;0.0500834733247757&lt;/PercentEvangelical&gt;
      &lt;PCBuddhism&gt;90&lt;/PCBuddhism&gt;
      &lt;PCDblyProfessing&gt;0&lt;/PCDblyProfessing&gt;
      &lt;PCEthnicReligions&gt;9.8&lt;/PCEthnicReligions&gt;
      &lt;PCHinduism&gt;0&lt;/PCHinduism&gt;
      &lt;PCIslam&gt;0&lt;/PCIslam&gt;
      &lt;PCNonReligious&gt;0&lt;/PCNonReligious&gt;
      &lt;PCOtherSmall&gt;0&lt;/PCOtherSmall&gt;
      &lt;PCUnknown&gt;0&lt;/PCUnknown&gt;
      &lt;PCAnglican&gt;0&lt;/PCAnglican&gt;
      &lt;PCIndependent&gt;0&lt;/PCIndependent&gt;
      &lt;PCProtestant&gt;70&lt;/PCProtestant&gt;
      &lt;PCOrthodox&gt;0&lt;/PCOrthodox&gt;
      &lt;PCOtherChristian&gt;0&lt;/PCOtherChristian&gt;
      &lt;PCRomanCatholic&gt;30&lt;/PCRomanCatholic&gt;
      &lt;StonyGround&gt;Y&lt;/StonyGround&gt;
      &lt;SecurityLevel&gt;2&lt;/SecurityLevel&gt;
      &lt;OriginalJPL/&gt;
      &lt;RaceCode&gt;AUG03z&lt;/RaceCode&gt;
      &lt;IndigenousCode&gt;Y&lt;/IndigenousCode&gt;
      &lt;LRWebProfile&gt;Y&lt;/LRWebProfile&gt;
      &lt;LRofTheDayMonth&gt;1&lt;/LRofTheDayMonth&gt;
      &lt;LRofTheDayDay&gt;11&lt;/LRofTheDayDay&gt;
      &lt;LRTop100/&gt;
      &lt;PhotoAddress&gt;p14966.jpg&lt;/PhotoAddress&gt;
      &lt;PhotoWidth&gt;200&lt;/PhotoWidth&gt;
      &lt;PhotoHeight&gt;249&lt;/PhotoHeight&gt;
      &lt;PhotoAddressExpanded/&gt;
      &lt;PhotoCredits&gt;Ray Mason&lt;/PhotoCredits&gt;
      &lt;PhotoCreditURL/&gt;
      &lt;PhotoCreativeCommons/&gt;
      &lt;PhotoCopyright/&gt;
      &lt;PhotoPermission/&gt;
      &lt;MapAddress&gt;m14966_la.png&lt;/MapAddress&gt;
      &lt;MapAddressExpanded/&gt;
      &lt;MapCredits&gt;Bethany World Prayer Center&lt;/MapCredits&gt;
      &lt;MapCreditURL/&gt;
      &lt;MapCopyright&gt;Y&lt;/MapCopyright&gt;
      &lt;MapPermission&gt;Y&lt;/MapPermission&gt;
      &lt;ProfileTextExists&gt;Y&lt;/ProfileTextExists&gt;
      &lt;FileAddress/&gt;
      &lt;FileAddressExpanded/&gt;
      &lt;FileCredits/&gt;
      &lt;FileCreditURL/&gt;
      &lt;FileCopyright/&gt;
      &lt;FilePermission/&gt;
      &lt;Top10Ranking/&gt;
      &lt;RankOverall&gt;73&lt;/RankOverall&gt;
      &lt;RankProgress&gt;26&lt;/RankProgress&gt;
      &lt;RankPopulation&gt;19&lt;/RankPopulation&gt;
      &lt;RankLocation&gt;18&lt;/RankLocation&gt;
      &lt;RankMinistryTools&gt;10&lt;/RankMinistryTools&gt;
      &lt;CountOfCountries&gt;2&lt;/CountOfCountries&gt;
      &lt;CountOfProvinces&gt;2&lt;/CountOfProvinces&gt;
      &lt;EthnolinguisticMap&gt;http://www.lib.utexas.edu/maps/middle_east_and_asia/indochina_eth_1970.jpg&lt;/EthnolinguisticMap&gt;
      &lt;MapID&gt;sss-LA&lt;/MapID&gt;
      &lt;V59Country/&gt;
      &lt;MegablocPC&gt;Y&lt;/MegablocPC&gt;
      &lt;LargeSouthAsianLanguageROL3/&gt;
      &lt;Longitude&gt;105.427040000396&lt;/Longitude&gt;
      &lt;Latitude&gt;17.0162099995918&lt;/Latitude&gt;
      &lt;Window10_40&gt;Y&lt;/Window10_40&gt;
      &lt;PeopleGroupURL&gt;http://www.joshuaproject.net/people-profile.php?peo3=14966&amp;rog3=LA&lt;/PeopleGroupURL&gt;
      &lt;PeopleGroupPhotoURL&gt;http://www.joshuaproject.net/profiles/photos/p14966.jpg&lt;/PeopleGroupPhotoURL&gt;
      &lt;CountryURL&gt;http://www.joshuaproject.net/countries.php?rog3=LA&lt;/CountryURL&gt;
      &lt;JPScaleText&gt;Unreached&lt;/JPScaleText&gt;
      &lt;JPScaleImageURL&gt;http://www.joshuaproject.net/images/scale1.jpg&lt;/JPScaleImageURL&gt;
    &lt;/people_group&gt;
  &lt;/people_groups&gt;
&lt;/api&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Once you have a response,  you can use and manipulate the provided data.</p>
                    <h3 id="getting-started">Getting Started (All Tutorials)</h3>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In the following tutorials,  we will walk through four different approaches for creating the Joshua Project's unreached people group of the day widget using the new API.  These tutorials come in a variety of programming languages including Javascript using the JQuery library, PHP, Python and Ruby.  Feel free and choose the programming language that you feel more comfortable programming in.  <strong>Before starting this tutorial,  you will need to retrieve an <a href="/">API key</a></strong>.</p>
                    <h4 id="getting-started-starting-code">Starting Code</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have created zip files containing the starting code for each programming language.  You can download these files from the following links:</p>
                    <p>
                        <ul>
                            <li><a href="files/starting_code/javascript.zip">Javascript Starting Code</a></li>
                            <li><a href="files/starting_code/php.zip">PHP Starting Code</a></li>
                            <li><a href="files/starting_code/python.zip">Python Starting Code</a></li>
                            <li><a href="files/starting_code/ruby.zip">Ruby Starting Code</a></li>
                        </ul>
                    </p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;After downloading and unzipping the directory,  look for the <code>styles.css</code> file.  This file offers the basic design for the widget.  Here is a look at the code:</p>
                    <pre>
.upgotd { 
    color: #000000; 
    font-family: Arial, Helvetica, Verdana, sans-serif; 
}
#jp_widget { 
    background-color: #EEE; 
    border: #CCCCCC 1px dashed; 
    text-align: center; 
    width: 215px; 
    font-size:12px;
}
#jp_widget a { 
    color: #000000 !important; 
    text-decoration: none; 
}
#jp_widget a:hover { 
    color: #0000FF !important; 
    text-decoration: none; 
}
.upgotd-title, .upgotd-footer { 
    padding: 3px; 
    background-color: #BBDDFF;
}
.upgotd-title, .upgotd-title a { 
    font-weight: bold; font-size:13px !important; 
    margin-bottom: 5px;
}
.upgotd-image { 
    text-align: center; 
}
.upgotd-pray { 
    font-style: italic; 
    font-weight: normal; 
    padding: 3px 0px; 
    font-size: 12px;
}
.upgotd-people { 
    font-weight: normal; 
    font-size:12px !important; 
    padding-bottom:4px; 
}
.upgotd-people a { 
    font-weight: bold; 
}
.upgotd-table td { 
    font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; 
    font-size:11px; 
    font-weight: normal; 
    color: #000; 
    line-height: 14px; 
    text-align: left; 
    border: 0px; 
    background-color: #EEE; 
    margin: 0px; 
    padding: 0px 0px 0px 3px; 
}
.upgotd-more, .upgotd-more a { 
    font-size: 10px; 
}
.upgotd-footer, .upgotd-footer a { 
    font-weight: normal ;
    font-size: 11px;  
    margin-top: 3px; 
}
a#progress-scale-image img {
    border: none;
}
.hidden {
    display: none;
}
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You will also find an <code>index</code> file. (The extension varies based on the programming language) This file offers the basic structure of the final widget.  The code looks like this:</p>
                    <pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;
    &lt;head&gt;
        &lt;title&gt;Joshua Project&lt;/title&gt;
        &lt;link rel="stylesheet" type="text/css" href="css/styles.css"&gt;
    &lt;/head&gt;
    &lt;body&gt;
        &lt;div id="jp_widget"&gt;
            &lt;div class="upgotd upgotd-title"&gt;
                &lt;a href="http://www.joshuaproject.net/upgotdfeed.php" class="upgotd-link"&gt;Unreached of the Day&lt;/a&gt;
            &lt;/div&gt;
            &lt;div class="upgotd-image"&gt;
                &lt;a href="#" class="upgotd-link pg-link" id="people-group-image"&gt;
                &lt;/a&gt;
            &lt;/div&gt;
            &lt;div class="upgotd upgotd-pray"&gt;Please pray for the ...&lt;/div&gt;
            &lt;div class="upgotd upgotd-people"&gt;
                &lt;a href="#" class="upgotd-link pg-link pg-name"&gt;&lt;/a&gt; of &lt;a href="#" class="upgotd-link country-link country-name"&gt;&lt;/a&gt;
            &lt;/div&gt;
            &lt;table align="center" class="upgotd-table" cellpadding="0" cellspacing="0"&gt;
                &lt;tbody&gt;&lt;tr&gt;
                    &lt;td width="65"&gt;Population:&lt;/td&gt;
                    &lt;td width="135" class="pg-population"&gt;&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Language:&lt;/td&gt;
                    &lt;td class="pg-language"&gt;&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Religion:&lt;/td&gt;
                    &lt;td class="pg-religion"&gt;&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Evangelical:&lt;/td&gt;
                    &lt;td class="pg-evangelical"&gt;&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Status:&lt;/td&gt;
                    &lt;td&gt;
                        &lt;a href="http://www.joshuaproject.net/definitions.php?term=25" class="upgotd-link pg-scale-text"&gt;&lt;/a&gt; (&lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link pg-scale"&gt;&lt;/a&gt;&nbsp;&lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link" id="progress-scale-image"&gt;&lt;/a&gt;)
                    &lt;/td&gt;
                &lt;/tr&gt;
            &lt;/tbody&gt;&lt;/table&gt;
            &lt;div class="upgotd upgotd-footer"&gt;Add this daily global vision feature to &lt;br&gt;&lt;a href="/upgotdfeed.php" class="upgotd-link"&gt;&lt;b&gt;your website&lt;/b&gt;&lt;/a&gt; or get it &lt;a href="http://www.unreachedoftheday.org/unreached-email.php" class="upgotd-link"&gt;&lt;b&gt;by email&lt;/b&gt;&lt;/a&gt;.&lt;/div&gt;
        &lt;/div&gt;
    &lt;/body&gt;
&lt;/html&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We will use this starting code to launch into developing a widget that pulls in the data from our API.  To get started, please choose your preferred programming language to continue this tutorial:</p>
                    <p>
                        <ul>
                            <li><a href="#javascript">Javascript (JQuery) Example</a></li>
                            <li><a href="#php">PHP Example</a></li>
                            <li><a href="#python">Python Example</a></li>
                            <li><a href="#ruby">Ruby Example</a></li>
                        </ul>
                    </p>
                    <div class="page-header">
                        <h3 id="javascript">Javascript (JQuery) Example</h3>
                    </div>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Before beginning this tutorial,  you should have a good understanding of the <a href="http://www.w3schools.com/js/" target="_blank">Javascript programming language</a>, and the <a href="http://jquery.com/" target="_blank">JQuery library</a>!  You will also need to download the <a href="files/starting_code/javascript.zip">starting code</a>.</p>
                    <h4 id="javascript-jquery-library">The JQuery Library</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JQuery is a Javascript library designed to make scripting in Javascript much more enjoyable and faster.  It helps speed up common tasks like traversing the DOM, animating and manipulating DOM elements, and running Ajax calls.  In order to use the library, we need to include it in the head tag of our <code>index.html</code> file.  So open the <code>index.html</code> file and add the following line between the tags <code>&lt;head&gt;&lt;/head&gt;</code>:</p>
                    <pre>&lt;script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"&gt;&lt;/script&gt;</pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We do not need to download the JQuery library, because we will just use the file on their hosted CDN.</p>
                    <h4 id="javascript-calling-the-api">Calling the API</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Before calling the API,  we can use a common JQuery method <code>.ready()</code> (<a href="http://api.jquery.com/ready/" target="_blank">Jquery Docs</a>) to check if the DOM has loaded before running our Ajax request.  So in the <code>&lt;head&gt;&lt;/head&gt;</code> tag, after the declaring the JQuery library, we need to add the following code:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    <span class="code_highlight">jQuery(document).ready(function($) {    

    });</span>
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We should also declare two variables to hold the API's domain name, and your API key.</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    <span class="code_highlight">var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;</span>
    jQuery(document).ready(function($) {    

    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We are now ready to make the API request using JQuery's <code>.ajax()</code> (<a href="http://api.jquery.com/jQuery.ajax/" target="_blank">Jquery Docs</a>) method.  If you are not familiar with Ajax,  it is an acronym for Asynchronous Javascript and XML.  Wikipedia states:</p>
                    <blockquote cite="http://en.wikipedia.org/wiki/Ajax_(programming)">
                        <p>With Ajax, web applications can send data to, and retrieve data from, a server asynchronously (in the background) without interfering with the display and behavior of the existing page.<br><br><em><a href="http://en.wikipedia.org/wiki/Ajax_(programming)" target="_blank">Wikipedia</a></em></p>
                    </blockquote>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Here is the code to run the Ajax request after the dom has loaded:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        <span class="code_highlight">$.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            /* Code in here runs when the request is completed */
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            /* Code in here runs when the request failed */
        });</span>
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;As you can see in the code above,  we send the following settings to JQuery's <code>.ajax()</code> (<a href="http://api.jquery.com/jQuery.ajax/" target="_blank">Jquery Docs</a>) method:</p>
                    <table id="ajax_options" class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2" class="active text-center">JQuery Ajax Settings</th>
                            </tr>
                            <tr>
                                <th class="text-center">Setting</th>
                                <th class="text-center">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>url</td>
                                <td><strong><?php echo $DOMAIN_ADDRESS; ?>/v1/people_groups/daily_unreached.json</strong> - The URL of the web page to request. (See the <a href="#overview-url-structure">URL Structure</a> section)</td>
                            </tr>
                            <tr>
                                <td>dataType</td>
                                <td><strong>json</strong> - The type of data returned from the web page</td>
                            </tr>
                            <tr>
                                <td>data</td>
                                <td><strong>{api_key: API_KEY}</strong> - The additional paramaters to pass the web page.</td>
                            </tr>
                            <tr>
                                <td>type</td>
                                <td><strong>GET</strong> - The HTTP Request Method</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;When this request is completed,  it will call the empty function we provided for JQuery's <code>.done()</code> callback (<a href="hhttp://api.jquery.com/deferred.done/" target="_blank">Jquery Docs</a>).  If the request fails,  the empty function provided for the <code>.fail()</code> callback (<a href="hhttp://api.jquery.com/deferred.fail/" target="_blank">Jquery Docs</a>) is triggered.</p>
                    <h4 id="javascript-handling-the-error">Handling the Error</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Let us start with handling any errors.  This will help us debug any problems we may run into later.  If the request for the web page is unsuccessful, it will trigger the <code>.fail()</code> callback (<a href="hhttp://api.jquery.com/deferred.fail/" target="_blank">Jquery Docs</a>).  If this happens,  we should let the visitor know.  We will do this by appending an error message at the top of the page.  Here is the updated code:</p>
                                        <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            /* Code in here runs when the request is completed */
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            <span class="code_highlight">var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);</span>
        });
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We start by declaring CSS styles (Red &amp; Bold) for the <code>&lt;p&gt;&lt;/p&gt;</code> tag that will hold the message:</p>
                    <pre>var pTagSettings = {'color': 'red', 'font-weight': 'bold'};</pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We then create the <code>&lt;p&gt;&lt;/p&gt;</code> tag, add the warning message using JQuery's <code>.text()</code> method (<a href="http://api.jquery.com/text/" target="_blank">JQuery Docs</a>), and add the CSS styles using JQuery's <code>.css()</code> method (<a href="http://api.jquery.com/css/" target="_blank">JQuery Docs</a>):</p>
                    <pre>var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);</pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We finally prepend the new <code>&lt;p&gt;&lt;/p&gt;</code> tag to the body of the web page using JQuery's <code>.prepend()</code> method (<a href="http://api.jquery.com/prepend/" target="_blank">JQuery Docs</a>).</p>
                    <pre>$('body').prepend(pTag);</pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That is all we need to do to warn the user when the Ajax fails.</p>
                    <h4 id="javascript-creating-the-widget">Creating the Widget</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that we have handled the errors,  let us deal with the response sent back from the API.  When the Ajax has successfully received a response from the API,  it triggers JQuery's <code>.done()</code> callback (<a href="hhttp://api.jquery.com/deferred.done/" target="_blank">Jquery Docs</a>).  One of the parameters passed to our empty function is <code>data</code>.  This is the API's response.  As you remember, we asked the server for JSON.  So now we need to handle that JSON response.  This code is temporary, but it will help us understand what we are receiving from the API.  In the <code>.done()</code> callback (<a href="hhttp://api.jquery.com/deferred.done/" target="_blank">Jquery Docs</a>) we will prepend the data to the body of our HTML similar to the <code>.fail()</code> callback (<a href="hhttp://api.jquery.com/deferred.fail/" target="_blank">Jquery Docs</a>).  Here is the code:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            <span class="code_highlight">/* JSON.stringify is only supported in IE 8 > */
            $('body').prepend(JSON.stringify(data));</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;When you save and run this code,  you will see something similar to the <a href="#overview-response">response code</a> we showed up above.  If you do not get a response,  then you need to check if you created your <a href="#overview-url-structure">URL structure</a> correctly.  As you can see in the response,  we are receiving a <a href="http://www.w3schools.com/js/js_obj_array.asp" target="_blank" title="What is an Array">Javascript Array</a> of a single JSON object.  So to get the first JSON object, we simply set a variable to the first object of the array (<code>data[0]</code>) like this:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            <span class="code_highlight">var unreached = data[0];</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we can access any of the supplied attributes of that JSON object using it's key.  So if we want to get the people group's name,  we can access it like this: <code>unreached['PeopNameInCountry']</code>.  Now that we can access the data, we just need to start putting the data into the HTML we already provided.  If you look at the HTML of the <code>index.html</code> file you have been working on,  you will see the following code:</p>
                    <pre>
&lt;div id="jp_widget"&gt;
    &lt;div class="upgotd upgotd-title"&gt;
        &lt;a href="http://www.joshuaproject.net/upgotdfeed.php" class="upgotd-link"&gt;Unreached of the Day&lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="upgotd-image"&gt;
        &lt;a href="#" class="upgotd-link pg-link" id="people-group-image"&gt;
        &lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="upgotd upgotd-pray"&gt;Please pray for the ...&lt;/div&gt;
    &lt;div class="upgotd upgotd-people"&gt;
        &lt;a href="#" class="upgotd-link pg-link pg-name"&gt;&lt;/a&gt; of &lt;a href="#" class="upgotd-link country-link country-name"&gt;&lt;/a&gt;
    &lt;/div&gt;
    &lt;table align="center" class="upgotd-table" cellpadding="0" cellspacing="0"&gt;
        &lt;tbody&gt;&lt;tr&gt;
            &lt;td width="65"&gt;Population:&lt;/td&gt;
            &lt;td width="135" class="pg-population"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Language:&lt;/td&gt;
            &lt;td class="pg-language"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Religion:&lt;/td&gt;
            &lt;td class="pg-religion"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Evangelical:&lt;/td&gt;
            &lt;td class="pg-evangelical"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Status:&lt;/td&gt;
            &lt;td&gt;
                &lt;a href="http://www.joshuaproject.net/definitions.php?term=25" class="upgotd-link pg-scale-text"&gt;&lt;/a&gt; (&lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link pg-scale"&gt;&lt;/a&gt;&nbsp;&lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link" id="progress-scale-image"&gt;&lt;/a&gt;)
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;&lt;/table&gt;
    &lt;div class="upgotd upgotd-footer"&gt;Add this daily global vision feature to &lt;br&gt;&lt;a href="/upgotdfeed.php" class="upgotd-link"&gt;&lt;b&gt;your website&lt;/b&gt;&lt;/a&gt; or get it &lt;a href="http://www.unreachedoftheday.org/unreached-email.php" class="upgotd-link"&gt;&lt;b&gt;by email&lt;/b&gt;&lt;/a&gt;.&lt;/div&gt;
&lt;/div&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;As you look through that code, you will see unique classes and ids set to different elements.  We will use these classes/ids to let Javascript manipulate it and add the appropriate content from the API.  Here is a list of all the classes/ids, and how we will manipulate those elements to show people group data:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="active text-center">Widget Classes</th>
                            </tr>
                            <tr>
                                <th class="text-center">ID/Class</th>
                                <th class="text-center">Data Accessible With</th>
                                <th class="text-center">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;country&#8209;link</td>
                                <td><code>unreached['CountryURL']</code></td>
                                <td>We will set the link's <code>href</code> to the Joshua Project's people group's country URL</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;country&#8209;name</td>
                                <td><code>unreached['Ctry']</code></td>
                                <td>We will set the text to the people group's country name</td>
                            </tr>
                            <tr>
                                <td><span class="label label-primary">id</span>&nbsp;people&#8209;group&#8209;image</td>
                                <td><code>unreached['PeopleGroupPhotoURL']</code></td>
                                <td>We will create an image with a <code>src</code> equal to the location of the people group's image.  We will also set it's dimensions to 128 X 160</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;evangelical</td>
                                <td><code>unreached['PercentEvangelical']</code></td>
                                <td>We will set a percentage formatted number totaling the people group's percentage of Evangelicals</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;language</td>
                                <td><code>unreached['PrimaryLanguageName']</code></td>
                                <td>We will set the text to the people group's primary language</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;link</td>
                                <td><code>unreached['PeopleGroupURL']</code></td>
                                <td>We will set the link's <code>href</code> to the Joshua Project's people group's URL</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;name</td>
                                <td><code>unreached['PeopNameInCountry']</code></td>
                                <td>We will set the text to the people group name</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;population</td>
                                <td><code>unreached['Population']</code></td>
                                <td>We will set a comma formatted number totaling the people group's population</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;religion</td>
                                <td><code>unreached['PrimaryReligion']</code></td>
                                <td>We will set the text to the people group's primary religion</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;scale</td>
                                <td><code>unreached['JPScale']</code></td>
                                <td>We will set the text to the people group's progress scale number</td>
                            </tr>
                            <tr>
                                <td><span class="label label-primary">id</span>&nbsp;progress&#8209;scale&#8209;image</td>
                                <td><code>unreached['JPScaleImageURL']</code></td>
                                <td>We will create an image with a <code>src</code> equal to the progress scale image for the people group</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;scale&#8209;text</td>
                                <td><code>unreached['JPScaleText']</code></td>
                                <td>We will set the text to the people group's progress scale text</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;So let us begin by completing all the tasks that say <em>"We will set the text to..."</em>.  This is easy to accomplish by using JQuery's <code>.text()</code> method. (<a href="http://api.jquery.com/text/" target="_blank">JQuery Docs</a>)  Here is the code:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            var unreached = data[0];
            <span class="code_highlight">/* Set the text of each class to the appropriate data */
            $('.country-name').text(unreached['Ctry']);
            $('.pg-language').text(unreached['PrimaryLanguageName']);
            $('.pg-name').text(unreached['PeopNameInCountry']);
            $('.pg-religion').text(unreached['PrimaryReligion']);
            $('.pg-scale').text(unreached['JPScale']);
            $('.pg-scale-text').text(unreached['JPScaleText']);</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you refresh the webpage, you should see something like this with different data:</p>
                    <img src="img/getting_started/jquery_text_example.png" alt="Snapshot of the Adding text() method" class="img-responsive">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;But why different data!  If you do not supply a month or day parameter,  the API will return today's unreached people group of the day by default.  Most likely you are not doing this tutorial the same day as I was.  This now leaves us with the following tasks:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="active text-center">Widget Classes</th>
                            </tr>
                            <tr>
                                <th class="text-center">ID/Class</th>
                                <th class="text-center">Data Accessible With</th>
                                <th class="text-center">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;country&#8209;link</td>
                                <td><code>unreached['CountryURL']</code></td>
                                <td>We will set the link's <code>href</code> to the Joshua Project's people group's country URL</td>
                            </tr>
                            <tr>
                                <td><span class="label label-primary">id</span>&nbsp;people&#8209;group&#8209;image</td>
                                <td><code>unreached['PeopleGroupPhotoURL']</code></td>
                                <td>We will create an image with a <code>src</code> equal to the location of the people group's image.  We will also set it's dimensions to 128 X 160</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;evangelical</td>
                                <td><code>unreached['PercentEvangelical']</code></td>
                                <td>We will set a percentage formatted number totaling the people group's percentage of Evangelicals</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;link</td>
                                <td><code>unreached['PeopleGroupURL']</code></td>
                                <td>We will set the link's <code>href</code> to the Joshua Project's people group's URL</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;population</td>
                                <td><code>unreached['Population']</code></td>
                                <td>We will set a comma formatted number totaling the people group's population</td>
                            </tr>
                            <tr>
                                <td><span class="label label-primary">id</span>&nbsp;progress&#8209;scale&#8209;image</td>
                                <td><code>unreached['JPScaleImageURL']</code></td>
                                <td>We will create an image with a <code>src</code> equal to the progress scale image for the people group</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Let us now handle the two links that need URLS (.country-link, and .pg-link).  We will use JQuery's <code>.attr()</code> method (<a href="http://api.jquery.com/attr/" target="_blank">JQuery Docs</a>) to handle it.  Here is the code:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            var unreached = data[0];
            /* Set the text of each class to the appropriate data */
            $('.country-name').text(unreached['Ctry']);
            $('.pg-language').text(unreached['PrimaryLanguageName']);
            $('.pg-name').text(unreached['PeopNameInCountry']);
            $('.pg-religion').text(unreached['PrimaryReligion']);
            $('.pg-scale').text(unreached['JPScale']);
            $('.pg-scale-text').text(unreached['JPScaleText']);
            <span class="code_highlight">/* Handle the two links that need URL's*/
            $('.country-link').attr('href', unreached['CountryURL']);
            $('.pg-link').attr('href', unreached['PeopleGroupURL']);</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you refresh the page,  you may not see a difference.  Just hover your mouse over either the people group name or country name and verify the URL is set.  So here is what is remaining:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="active text-center">Widget Classes</th>
                            </tr>
                            <tr>
                                <th class="text-center">ID/Class</th>
                                <th class="text-center">Data Accessible With</th>
                                <th class="text-center">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="label label-primary">id</span>&nbsp;people&#8209;group&#8209;image</td>
                                <td><code>unreached['PeopleGroupPhotoURL']</code></td>
                                <td>We will create an image with a <code>src</code> equal to the location of the people group's image.  We will also set it's dimensions to 128 X 160</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;evangelical</td>
                                <td><code>unreached['PercentEvangelical']</code></td>
                                <td>We will set a percentage formatted number totaling the people group's percentage of Evangelicals</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;population</td>
                                <td><code>unreached['Population']</code></td>
                                <td>We will set a comma formatted number totaling the people group's population</td>
                            </tr>
                            <tr>
                                <td><span class="label label-primary">id</span>&nbsp;progress&#8209;scale&#8209;image</td>
                                <td><code>unreached['JPScaleImageURL']</code></td>
                                <td>We will create an image with a <code>src</code> equal to the progress scale image for the people group</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK,  we are getting close.  Let us now tackle the two images.  We will create an <code>&lt;img&gt;</code> tag similar to how we created the <code>&lt;p&gt;&lt;/p&gt;</code> tag in the <code>.fail()</code> callback (<a href="hhttp://api.jquery.com/deferred.fail/" target="_blank">Jquery Docs</a>).  We will use JQuery's <code>.attr()</code> method (<a href="http://api.jquery.com/attr/" target="_blank">JQuery Docs</a>) to set the <code>src</code> attribute, and JQuery's <code>.css()</code> method (<a href="http://api.jquery.com/css/" target="_blank">JQuery Docs</a>) to add width and height.  Finally,  we will append the image to the element using JQuery's <code>.append()</code> method (<a href="http://api.jquery.com/append/" target="_blank">JQuery Docs</a>).  Here is the code:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            var unreached = data[0];
            /* Set the text of each class to the appropriate data */
            $('.country-name').text(unreached['Ctry']);
            $('.pg-language').text(unreached['PrimaryLanguageName']);
            $('.pg-name').text(unreached['PeopNameInCountry']);
            $('.pg-religion').text(unreached['PrimaryReligion']);
            $('.pg-scale').text(unreached['JPScale']);
            $('.pg-scale-text').text(unreached['JPScaleText']);
            /* Handle the two links that need URL's*/
            $('.country-link').attr('href', unreached['CountryURL']);
            $('.pg-link').attr('href', unreached['PeopleGroupURL']);
            <span class="code_highlight">/* Append the images */
            var pgSettings = {'height': '160px', 'width': '128px'};
            var pgImg = $('&lt;img/&gt;').attr('src', unreached['PeopleGroupPhotoURL']).css(pgSettings);
            $('#people-group-image').append(pgImg);
            var scaleImg = $('&lt;img/&gt;').attr('src', unreached['JPScaleImageURL']);
            $('#progress-scale-image').append(scaleImg);</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you refresh the page in your browser, you should see something like this:</p>
                    <img src="img/getting_started/jquery_images_example.png" alt="Snapshot of the images" class="img-responsive">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;So here is what is remaining:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="active text-center">Widget Classes</th>
                            </tr>
                            <tr>
                                <th class="text-center">ID/Class</th>
                                <th class="text-center">Data Accessible With</th>
                                <th class="text-center">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;evangelical</td>
                                <td><code>unreached['PercentEvangelical']</code></td>
                                <td>We will set a percentage formatted number totaling the people group's percentage of Evangelicals</td>
                            </tr>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;population</td>
                                <td><code>unreached['Population']</code></td>
                                <td>We will set a comma formatted number totaling the people group's population</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;So let's tackle the percentage of Evangelicals in the people group.  We first need to check if it is set to null,  if so then we will display 0.00.  If not null, we will use Javascript's <code>parseFloat()</code> method (<a href="http://www.w3schools.com/jsref/jsref_parsefloat.asp" target="_blank">Docs</a>) to turn the number to a float.  After that we format it to 2 decimals using Javascripts <code>toFixed()</code> method (<a href="http://www.w3schools.com/jsref/jsref_tofixed.asp" target="_blank">Docs</a>).  Finally,  we will use JQuery's <code>.text()</code> method (<a href="http://api.jquery.com/text/" target="_blank">JQuery Docs</a>) to set the text for the element.  Here is the code:<p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            var unreached = data[0];
            /* Set the text of each class to the appropriate data */
            $('.country-name').text(unreached['Ctry']);
            $('.pg-language').text(unreached['PrimaryLanguageName']);
            $('.pg-name').text(unreached['PeopNameInCountry']);
            $('.pg-religion').text(unreached['PrimaryReligion']);
            $('.pg-scale').text(unreached['JPScale']);
            $('.pg-scale-text').text(unreached['JPScaleText']);
            /* Handle the two links that need URL's*/
            $('.country-link').attr('href', unreached['CountryURL']);
            $('.pg-link').attr('href', unreached['PeopleGroupURL']);
            /* Append the images */
            var pgSettings = {'height': '160px', 'width': '128px'};
            var pgImg = $('&lt;img/&gt;').attr('src', unreached['PeopleGroupPhotoURL']).css(pgSettings);
            $('#people-group-image').append(pgImg);
            var scaleImg = $('&lt;img/&gt;').attr('src', unreached['JPScaleImageURL']);
            $('#progress-scale-image').append(scaleImg);
            <span class="code_highlight">/* Set the Percent Evangelical */
            if (unreached['PercentEvangelical'] == null) {
                percent_evangelical = '0.00';
            } else {
                percent_evangelical = parseFloat(unreached['PercentEvangelical']).toFixed(2);
            }; 
            $('.pg-evangelical').text(percent_evangelical+'%');</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Once you reload the web page,  you should now see the percentage like this:</p>
                    <img src="img/getting_started/percent_evangelical_example.png" alt="Snapshot of the Percent Evangelical" class="img-responsive">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;So we only have one item remaining:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="active text-center">Widget Classes</th>
                            </tr>
                            <tr>
                                <th class="text-center">ID/Class</th>
                                <th class="text-center">Data Accessible With</th>
                                <th class="text-center">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="label label-info">class</span>&nbsp;pg&#8209;population</td>
                                <td><code>unreached['Population']</code></td>
                                <td>We will set a comma formatted number totaling the people group's population</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First,  we will need to create a custom function for formatting a comma seperated value.  We will use Javascript's <code>toString()</code> method (<a href="http://www.w3schools.com/jsref/jsref_tostring_number.asp" target="_blank">Docs</a>) to make sure the value is a string.  We then will use <a href="http://en.wikipedia.org/wiki/Regular_expression" title="Find Out More About Regular Expressions" target="_blank">Regular Expressions</a> to format it.  Here is the code:</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            var unreached = data[0];
            /* Set the text of each class to the appropriate data */
            $('.country-name').text(unreached['Ctry']);
            $('.pg-language').text(unreached['PrimaryLanguageName']);
            $('.pg-name').text(unreached['PeopNameInCountry']);
            $('.pg-religion').text(unreached['PrimaryReligion']);
            $('.pg-scale').text(unreached['JPScale']);
            $('.pg-scale-text').text(unreached['JPScaleText']);
            /* Handle the two links that need URL's*/
            $('.country-link').attr('href', unreached['CountryURL']);
            $('.pg-link').attr('href', unreached['PeopleGroupURL']);
            /* Append the images */
            var pgSettings = {'height': '160px', 'width': '128px'};
            var pgImg = $('&lt;img/&gt;').attr('src', unreached['PeopleGroupPhotoURL']).css(pgSettings);
            $('#people-group-image').append(pgImg);
            var scaleImg = $('&lt;img/&gt;').attr('src', unreached['JPScaleImageURL']);
            $('#progress-scale-image').append(scaleImg);
            /* Set the Percent Evangelical */
            if (unreached['PercentEvangelical'] == null) {
                percent_evangelical = '0.00';
            } else {
                percent_evangelical = parseFloat(unreached['PercentEvangelical']).toFixed(2);
            }; 
            $('.pg-evangelical').text(percent_evangelical+'%');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
    <span class="code_highlight">/* Number formating method. */
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };</span>
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Finally we use JQuery's <code>.text()</code> method. (<a href="http://api.jquery.com/text/" target="_blank">JQuery Docs</a>) to set the text of the element.</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            var unreached = data[0];
            /* Set the text of each class to the appropriate data */
            $('.country-name').text(unreached['Ctry']);
            $('.pg-language').text(unreached['PrimaryLanguageName']);
            $('.pg-name').text(unreached['PeopNameInCountry']);
            $('.pg-religion').text(unreached['PrimaryReligion']);
            $('.pg-scale').text(unreached['JPScale']);
            $('.pg-scale-text').text(unreached['JPScaleText']);
            /* Handle the two links that need URL's*/
            $('.country-link').attr('href', unreached['CountryURL']);
            $('.pg-link').attr('href', unreached['PeopleGroupURL']);
            /* Append the images */
            var pgSettings = {'height': '160px', 'width': '128px'};
            var pgImg = $('&lt;img/&gt;').attr('src', unreached['PeopleGroupPhotoURL']).css(pgSettings);
            $('#people-group-image').append(pgImg);
            var scaleImg = $('&lt;img/&gt;').attr('src', unreached['JPScaleImageURL']);
            $('#progress-scale-image').append(scaleImg);
            /* Set the Percent Evangelical */
            if (unreached['PercentEvangelical'] == null) {
                percent_evangelical = '0.00';
            } else {
                percent_evangelical = parseFloat(unreached['PercentEvangelical']).toFixed(2);
            }; 
            $('.pg-evangelical').text(percent_evangelical+'%');
            <span class="code_highlight">/* Set the Population */
            $('.pg-population').text(numberWithCommas(unreached['Population']));</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
    /* Number formating method. */
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Here is the final result of our newly created widget:</p>
                    <img src="img/getting_started/final_javascript.png" alt="Snapshot of Final Widget" class="img-responsive">
                    <h4 id="javascript-finishing-touches">Finishing Touches</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you reload the web page, you will notice a delay before all the information is correctly displayed.  Visitors may find this unappealing.  So we will hide the widget, and display it when it is ready.  First we need to add a <code>hidden</code> class to the widget.  This class hides the widget from view.  Here is the code:</p>
                    <pre>
&lt;div id="jp_widget"<span class="code_highlight"> class="hidden"</span>&gt;
    &lt;div class="upgotd upgotd-title"&gt;
        &lt;a href="http://www.joshuaproject.net/upgotdfeed.php" class="upgotd-link"&gt;Unreached of the Day&lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="upgotd-image"&gt;
        &lt;a href="#" class="upgotd-link pg-link" id="people-group-image"&gt;
        &lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="upgotd upgotd-pray"&gt;Please pray for the ...&lt;/div&gt;
    &lt;div class="upgotd upgotd-people"&gt;
        &lt;a href="#" class="upgotd-link pg-link pg-name"&gt;&lt;/a&gt; of &lt;a href="#" class="upgotd-link country-link country-name"&gt;&lt;/a&gt;
    &lt;/div&gt;
    &lt;table align="center" class="upgotd-table" cellpadding="0" cellspacing="0"&gt;
        &lt;tbody&gt;&lt;tr&gt;
            &lt;td width="65"&gt;Population:&lt;/td&gt;
            &lt;td width="135" class="pg-population"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Language:&lt;/td&gt;
            &lt;td class="pg-language"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Religion:&lt;/td&gt;
            &lt;td class="pg-religion"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Evangelical:&lt;/td&gt;
            &lt;td class="pg-evangelical"&gt;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Status:&lt;/td&gt;
            &lt;td&gt;
                &lt;a href="http://www.joshuaproject.net/definitions.php?term=25" class="upgotd-link pg-scale-text"&gt;&lt;/a&gt; (&lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link pg-scale"&gt;&lt;/a&gt;&nbsp;&lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link" id="progress-scale-image"&gt;&lt;/a&gt;)
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;&lt;/table&gt;
    &lt;div class="upgotd upgotd-footer"&gt;Add this daily global vision feature to &lt;br&gt;&lt;a href="/upgotdfeed.php" class="upgotd-link"&gt;&lt;b&gt;your website&lt;/b&gt;&lt;/a&gt; or get it &lt;a href="http://www.unreachedoftheday.org/unreached-email.php" class="upgotd-link"&gt;&lt;b&gt;by email&lt;/b&gt;&lt;/a&gt;.&lt;/div&gt;
&lt;/div&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Then we will use JQuery's <code>.fadeIn()</code> method (<a href="http://api.jquery.com/fadeIn/" target="_blank">JQuery Docs</a>) to fade in the widget when we finished setting it up.</p>
                    <pre>
&lt;script type="text/javascript"&gt;
    var DOMAIN = '<?php echo $DOMAIN_ADDRESS; ?>';
    var API_KEY = YOUR_API_KEY;
    jQuery(document).ready(function($) {    
        $.ajax({
            url: DOMAIN+'/v1/people_groups/daily_unreached.json',
            dataType: 'json',
            data: {api_key: API_KEY},
            type: 'GET'
        })
        .done(function(data) {
            var unreached = data[0];
            /* Set the text of each class to the appropriate data */
            $('.country-name').text(unreached['Ctry']);
            $('.pg-language').text(unreached['PrimaryLanguageName']);
            $('.pg-name').text(unreached['PeopNameInCountry']);
            $('.pg-religion').text(unreached['PrimaryReligion']);
            $('.pg-scale').text(unreached['JPScale']);
            $('.pg-scale-text').text(unreached['JPScaleText']);
            /* Handle the two links that need URL's*/
            $('.country-link').attr('href', unreached['CountryURL']);
            $('.pg-link').attr('href', unreached['PeopleGroupURL']);
            /* Append the images */
            var pgSettings = {'height': '160px', 'width': '128px'};
            var pgImg = $('&lt;img/&gt;').attr('src', unreached['PeopleGroupPhotoURL']).css(pgSettings);
            $('#people-group-image').append(pgImg);
            var scaleImg = $('&lt;img/&gt;').attr('src', unreached['JPScaleImageURL']);
            $('#progress-scale-image').append(scaleImg);
            /* Set the Percent Evangelical */
            if (unreached['PercentEvangelical'] == null) {
                percent_evangelical = '0.00';
            } else {
                percent_evangelical = parseFloat(unreached['PercentEvangelical']).toFixed(2);
            }; 
            $('.pg-evangelical').text(percent_evangelical+'%');
            /* Set the Population */
            $('.pg-population').text(numberWithCommas(unreached['Population']));
            <span class="code_highlight">/* Fade in the widget */
            $('div#jp_widget').fadeIn('slow');</span>
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var pTagSettings = {'color': 'red', 'font-weight': 'bold'};
            var pTag = $('&lt;p/&gt;').text('There was an error: '+errorThrown).css(pTagSettings);
            $('body').prepend(pTag);
        });
    });
    /* Number formating method. */
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };
&lt;/script&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Congratulations!  You have completed the Javascript tutorial.  If you would like to download the sample code,  you can visit our <a href="https://github.com/MissionalDigerati/joshua_project_api_sample_code" target="_blank">Github Account</a>.</p>
                    <div class="page-header">
                        <h3 id="php">PHP Example</h3>
                    </div>
                    <h4 id="php-setup">Setup</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Before you start this tutorial,  you will need some understanding of the <a href="http://www.w3schools.com/php/" target="_blank" title="Learn More About PHP">PHP programming language</a>.  You will also need a PHP environment setup on your local machine, or be able to upload your code to a PHP web server.  This tutorial does not cover how to accomplish that.  Once you have an environment setup,  you will need to download the <a href="files/starting_code/php.zip">starting code</a>.</p>
                    <h4 id="php-calling-the-api">Calling the API</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Let us start by creating 3 variables to hold the API domain, API key, and the URL to get the unreached of the day information.  Here is the code:</p>
                    <pre>
&lt;?php
<span class="code_highlight">$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To access an API with PHP,  we need to use the <a href="http://php.net/manual/en/book.curl.php" target="_blank" title="Learn More About cURL">cURL</a> Library.</p>
                    <blockquote cite="http://en.wikipedia.org/wiki/CURL">
                        <p>cURL is a computer software project providing a library and command-line tool for transferring data using various protocols.<br><br><em><a href="http://en.wikipedia.org/wiki/CURL" target="_blank">Wikipedia</a></em></p>
                    </blockquote>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PHP offers methods for making cURL requests in the code.  Let us begin by opening a cURL connection using PHP's <code>curl_init()</code> function (<a href="http://www.php.net/manual/en/function.curl-init.php" target="_blank">PHP DOCS</a>).</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
<span class="code_highlight">/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Next we need to set some options for the request using PHP's <code>curl_setopt()</code> function (<a href="http://www.php.net/manual/en/function.curl-setopt.php" target="_blank">PHP DOCS</a>).</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
<span class="code_highlight">/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Here are the options that we set:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Option</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>CURLOPT_URL</code></td>
                                <td>The URL to fetch</td>
                                <td><?php echo $DOMAIN_ADDRESS; ?>/v1/people_groups/daily_unreached.json?api_key=YOUR_API_KEY</td>
                            </tr>
                            <tr>
                                <td><code>CURLOPT_RETURNTRANSFER</code></td>
                                <td>Returns the response from CURL_EXEC as a string</td>
                                <td>1 (true)</td>
                            </tr>
                            <tr>
                                <td><code>CURLOPT_TIMEOUT</code></td>
                                <td>The maximum number of seconds cURL should execute request</td>
                                <td>60 seconds</td>
                            </tr>
                            <tr>
                                <td><code>CURLOPT_CUSTOMREQUEST</code></td>
                                <td>Set a custom HTTP request method</td>
                                <td>GET</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we need to execute the cURL request using PHP's <code>curl_exec()</code> function (<a href="http://www.php.net/manual/en/function.curl-exec.php" target="_blank">PHP DOCS</a>).  If the request fails,  we will call PHP's <code>die()</code> function (<a href="http://www.php.net/manual/en/function.die.php" target="_blank">PHP DOCS</a>) to stop executing the script.  If it is successful,  we will assign the result to a variable so we can access the JSON response.</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
<span class="code_highlight">/**
 * execute request
 *
 * @author Johnathan Pulos
 */
$result = curl_exec($ch) or die(curl_error($ch));</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we need to clean up by closing the cURL request.  We do this by using PHP's <code>curl_close()</code> function (<a href="http://www.php.net/manual/en/function.curl-close.php" target="_blank">PHP DOCS</a>).</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
/**
 * execute request
 *
 * @author Johnathan Pulos
 */
$result = curl_exec($ch) or die(curl_error($ch));
<span class="code_highlight">/**
 * close connection
 *
 * @author Johnathan Pulos
 */
curl_close($ch);</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have now completed the request.  Let's add some temporary code to verify we have the data.  We will remove this code before moving forward.</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
/**
 * execute request
 *
 * @author Johnathan Pulos
 */
$result = curl_exec($ch) or die(curl_error($ch));
/**
 * close connection
 *
 * @author Johnathan Pulos
 */
curl_close($ch);
<span class="code_highlight">/**
 * TEMP: Print out the results
 *
 * @author Johnathan Pulos
 */
print_r($result);
exit();</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;When you save and run this code,  you will see something similar to the <a href="#overview-response">response code</a> we showed up above.  If you do not get a response,  then you need to check if you created your <a href="#overview-url-structure">URL structure</a> correctly.  As you can see in the response,  we are receiving a <a href="http://us1.php.net/manual/en/function.array.php" target="_blank" title="What is an Array">PHP Array</a> of a single JSON object.  In order to use this JSON object in PHP,  we need to convert it to a PHP <a href="http://www.w3schools.com/php/php_arrays_multi.asp" target="_blank" title="Learn More About Multidimensional Arrays">multidimensional array</a>.  We can do this with a handy PHP function <code>decoded_json()</code> (<a href="http://php.net/manual/en/function.json-decode.php" target="_blank">PHP DOCS</a>)</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
/**
 * execute request
 *
 * @author Johnathan Pulos
 */
$result = curl_exec($ch) or die(curl_error($ch));
/**
 * close connection
 *
 * @author Johnathan Pulos
 */
curl_close($ch);
<span class="code_highlight">/**
 * decode the response JSON
 *
 * @author Johnathan Pulos
 */
$decoded_json = json_decode($result, true);</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We should also add error checking.  If the <a href="http://www.w3schools.com/php/php_arrays_multi.asp" target="_blank" title="Learn More About Multidimensional Arrays">multidimensional array</a> is not an array, we will stop executing the code with a message.</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
/**
 * execute request
 *
 * @author Johnathan Pulos
 */
$result = curl_exec($ch) or die(curl_error($ch));
/**
 * close connection
 *
 * @author Johnathan Pulos
 */
curl_close($ch);
/**
 * decode the response JSON
 *
 * @author Johnathan Pulos
 */
$decoded_json = json_decode($result, true);
<span class="code_highlight">if (!is_array($decoded_json)) {
    echo "Unable to retrieve the JSON.";
    exit;
}</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that we created a <a href="http://www.w3schools.com/php/php_arrays_multi.asp" target="_blank" title="Learn More About Multidimensional Arrays">multidimensional array</a>,  we can access the first object using its index like this: <code>$decoded_json[0]</code>.  We will set this to a variable for easy access.</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
/**
 * execute request
 *
 * @author Johnathan Pulos
 */
$result = curl_exec($ch) or die(curl_error($ch));
/**
 * close connection
 *
 * @author Johnathan Pulos
 */
curl_close($ch);
/**
 * decode the response JSON
 *
 * @author Johnathan Pulos
 */
$decoded_json = json_decode($result, true);
if (!is_array($decoded_json)) {
    echo "Unable to retrieve the JSON.";
    exit;
}
<span class="code_highlight">/**
 * Assign the first object to a variable
 *
 * @author Johnathan Pulos
 */
$unreached = $decoded_json[0];</span>
?&gt;
                    </pre>
                    <h4 id="php-creating-the-widget">Creating the Widget</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we can access any of the supplied attributes of that JSON object using it's key.  So if we want to get the people group's name,  we can access it like this: <code>$unreached['PeopNameInCountry']</code>.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The attribute percentage of Evangelicals in the people group can be set to null,  if so then we should display 0.00.  So let's add a check to evaluate the value, and set it to 0 if it is null.  Here is the code:</p>
                    <pre>
&lt;?php
$domain = "<?php echo $DOMAIN_ADDRESS; ?>";
$api_key = YOUR_API_KEY;
$url = $domain . "/v1/people_groups/daily_unreached.json?api_key=" . $api_key;
/**
 * open connection
 *
 * @author Johnathan Pulos
 */
$ch = curl_init();
/**
 * Setup cURL
 *
 * @author Johnathan Pulos
 */
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
/**
 * execute request
 *
 * @author Johnathan Pulos
 */
$result = curl_exec($ch) or die(curl_error($ch));
/**
 * close connection
 *
 * @author Johnathan Pulos
 */
curl_close($ch);
/**
 * decode the response JSON
 *
 * @author Johnathan Pulos
 */
$decoded_json = json_decode($result, true);
if (!is_array($decoded_json)) {
    echo "Unable to retrieve the JSON.";
    exit;
}
/**
 * Assign the first object to a variable
 *
 * @author Johnathan Pulos
 */
$unreached = $decoded_json[0];
<span class="code_highlight">/**
 * Handle the null value
 *
 * @author Johnathan Pulos
 */
if ($unreached['PercentEvangelical'] == null) {
    $unreached['PercentEvangelical'] = 0;
}</span>
?&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we just need to add the data to the HTML widget code for displaying.  We will use PHP's <code>echo()</code> function (<a href="http://www.php.net/manual/en/function.echo.php" target="_blank">PHP DOCS</a>) to display the data in the appropriate place.  We will also use PHP's  <code>number_format()</code> function (<a href="http://www.php.net/manual/en/function.number-format.php" target="_blank">PHP DOCS</a>) to format the people group's population, and percent of Evangelicals.  In the <code>index.php</code> file that you have been working on,  update the code to what is below:</p>
                    <pre>
&lt;div id="jp_widget"&gt;
    &lt;div class="upgotd upgotd-title"&gt;
        &lt;a href="http://www.joshuaproject.net/upgotdfeed.php" class="upgotd-link"&gt;Unreached of the Day&lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="upgotd-image"&gt;
        &lt;a href="<span class="code_highlight">&lt;?php echo $unreached['PeopleGroupURL']; ?&gt;</span>" class="upgotd-link pg-link" id="people-group-image"&gt;
            <span class="code_highlight">&lt;img src="&lt;?php echo $unreached['PeopleGroupPhotoURL']; ?&gt;" height="160" width="128" alt="Unreached of the Day Photo"&gt;</span>
        &lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="upgotd upgotd-pray"&gt;Please pray for the ...&lt;/div&gt;
    &lt;div class="upgotd upgotd-people"&gt;
        &lt;a href="<span class="code_highlight">&lt;?php echo $unreached['PeopleGroupURL']; ?&gt;</span>" class="upgotd-link pg-link pg-name"&gt;<span class="code_highlight">&lt;?php echo $unreached['PeopNameInCountry']; ?&gt;</span>&lt;/a&gt; of &lt;a href="<span class="code_highlight">&lt;?php echo $unreached['CountryURL']; ?&gt;</span>" class="upgotd-link country-link country-name"&gt;<span class="code_highlight">&lt;?php echo $unreached['Ctry']; ?&gt;</span>&lt;/a&gt;
    &lt;/div&gt;
    &lt;table align="center" class="upgotd-table" cellpadding="0" cellspacing="0"&gt;
        &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td width="65"&gt;
                Population:
            &lt;/td&gt;
            &lt;td width="135" class="pg-population"&gt;
                <span class="code_highlight">&lt;?php echo number_format($unreached['Population']); ?&gt;</span>
            &lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;
                Language:
            &lt;/td&gt;
            &lt;td class="pg-language"&gt;
                <span class="code_highlight">&lt;?php echo $unreached['PrimaryLanguageName']; ?&gt;</span>
            &lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;
                Religion:
            &lt;/td&gt;
            &lt;td class="pg-religion"&gt;
                <span class="code_highlight">&lt;?php echo $unreached['PrimaryReligion']; ?&gt;</span>
            &lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;
                Evangelical:
            &lt;/td&gt;
            &lt;td class="pg-evangelical"&gt;
                <span class="code_highlight">&lt;?php echo number_format($unreached['PercentEvangelical'], 2); ?&gt;</span>%
            &lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;Status:&lt;/td&gt;
            &lt;td&gt;
                &lt;a href="http://www.joshuaproject.net/definitions.php?term=25" class="upgotd-link pg-scale-text"&gt;
                    <span class="code_highlight">&lt;?php echo $unreached['JPScaleText']; ?&gt;</span>
                &lt;/a&gt; (
                &lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link pg-scale"&gt;
                    <span class="code_highlight">&lt;?php echo $unreached['JPScale']; ?&gt;</span>
                &lt;/a&gt;
                &lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link" id="progress-scale-image"&gt;
                    <span class="code_highlight">&lt;img src="&lt;?php echo $unreached['JPScaleImageURL']; ?&gt;" alt="Progress Scale"&gt;</span>
                &lt;/a&gt;)
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;&lt;/table&gt;
    &lt;div class="upgotd upgotd-footer"&gt;
        Add this daily global vision feature to &lt;br&gt;
        &lt;a href="/upgotdfeed.php" class="upgotd-link"&gt;&lt;b&gt;your website&lt;/b&gt;&lt;/a&gt; or get it &lt;a href="http://www.unreachedoftheday.org/unreached-email.php" class="upgotd-link"&gt;&lt;b&gt;by email&lt;/b&gt;&lt;/a&gt;.
    &lt;/div&gt;
&lt;/div&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you reload the webpage, you should see the widget with all the API data.</p>
                    <img src="img/getting_started/final_php.png" alt="Snapshot of Final Widget" class="img-responsive">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Congratulations!  You have completed the PHP tutorial.  If you would like to download the sample code,  you can visit our <a href="https://github.com/MissionalDigerati/joshua_project_api_sample_code" target="_blank">Github Account</a>.</p>
                    <div class="page-header">
                        <h3 id="python">Python Example</h3>
                    </div>
                    <h4 id="python-setup">Setup</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Before starting this tutorial,  you will need to have some understanding of the <a href="http://www.python.org/" target="_blank" title="Learn More About Python">Python programming language</a>.  We will be using Python version 3.3.  You will also need Python running in your command line utility.  This tutorial does not show how to install Python.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In this tutorial,  we will build a generator that creates the necessary HTML & CSS for the widget.  Everytime you run the script from the command line,  it will create the widget with the latest people group data.  Once you have downloaded and unzipped the <a href="files/starting_code/python.zip">Python starting code</a>, open it up and look around.  Here is the basic code structure:</p>
                    <p>
                        <ul>
                            <li>
                                <code>css</code> - directory for CSS Stylesheets
                                <ul>
                                    <li><code>styles.css</code> - the basic styles for the widget</li>
                                </ul>
                            </li>
                            <li>
                                <code>generated_code</code> - directory for the code created by our generator
                            </li>
                            <li>
                                <code>templates</code> - directory for the HTML templates
                                <ul>
                                    <li><code>index.html</code> - the HTML template for the widget</li>
                                </ul>
                            </li>
                            <li>
                                <code>generate_widget.py</code> - the generator script we will build
                            </li>
                        </ul>
                    </p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Go ahead and open the <code>generate_widget.py</code> file in your favorite text editor.  We will begin by including several Python modules using the <code>import</code> statement (<a href="http://docs.python.org/dev/reference/import.html" target="_blank">Python Docs</a>).  We will need the following modules: json (<a href="http://docs.python.org/3.3/library/json.html" target="_blank">Python Docs</a>), urllib.request (<a href="http://docs.python.org/3.3/library/urllib.request.html?highlight=urllib2" target="_blank">Python Docs</a>), urllib.error (<a href="http://docs.python.org/3/library/urllib.error.html" target="_blank">Python Docs</a>), string (<a href="http://docs.python.org/3.3/library/string.html" target="_blank">Python Docs</a>), and sys (<a href="http://docs.python.org/3.3/library/sys.html" target="_blank">Python Docs</a>). The code looks like this:</p>
                    <pre>
#!/usr/bin/python
<span class="code_highlight"># import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys</span>
                    </pre>
                    <h4 id="python-calling-the-api">Calling the API</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that we have imported the necessary modules,  we will need to generate the API request.  We will start by creating 3 variables for the API domain, API key, and the API URL for the request.  Here is the code:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
<span class="code_highlight"># set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We will call the API within a <code>try...except</code> block. (<a href="http://docs.python.org/3/tutorial/errors.html#handling-exceptions" target="_blank">Python Docs</a>)  Using Python's urllib.error module (<a href="http://docs.python.org/3/library/urllib.error.html" target="_blank">Python Docs</a>),  we will be warned if the API request returns a<code>HTTPError</code> or <code>URLError</code>.  Here is the block:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
<span class="code_highlight">try:
    # request the API for the Daily Unreached People Group
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now if there is a <code>HTTPError</code> or <code>URLError</code>, we will be able to see what happened.  Now we need to make the request using the urllib.request module (<a href="http://docs.python.org/3.3/library/urllib.request.html?highlight=urllib2" target="_blank">Python Docs</a>) by calling the <code>urlopen()</code> function (<a href="http://docs.python.org/3.3/library/urllib.request.html#urllib.request.urlopen" target="_blank">Python Docs</a>).  Here is the code:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    <span class="code_highlight">request = urllib.request.urlopen(api_url)</span>
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If everything is done correctly,  we should be able to run the code from your command line utility, and see no errors.  Let's add some temporary code to see if we got a response.  Add the following code:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    <span class="code_highlight">response = request.read()
    print(response)</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If everything went according to plan,  you should see something similar to the <a href="#overview-response">API response</a> shown above.  Using the response of the request, which is set to the <code>request</code> variable,  we need to use the <code>.read()</code> function of the urllib.request module (<a href="http://docs.python.org/3.3/library/urllib.request.html?highlight=urllib2" target="_blank">Python Docs</a>).  We will also need to use the <code>decode()</code> function of the module to decode it to UTF-8.  Here is how it should be written:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    <span class="code_highlight"># decode the response
    response = request.read().decode("utf8")</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Next we need to convert the JSON to a Python object using the json module's (<a href="http://docs.python.org/3.3/library/json.html" target="_blank">Python Docs</a>) <code>loads()</code> function (<a href="http://docs.python.org/3.3/library/json.html#json.loads" target="_blank">Python Docs</a>).  We will also print out the result temporarily so we can look at it.  Here is how you will accomplish this:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    <span class="code_highlight"># load the JSON
    data = json.loads(response)
    print(data)</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you look at the response,  you can see that we have a Python list (<a href="http://docs.python.org/3.3/library/stdtypes.html?highlight=list#list" target="_blank">Python Docs</a>) containing a single Python dict (<a href="http://docs.python.org/3.3/library/stdtypes.html?highlight=dict#dict" target="_blank">Python Docs</a>).  To access the first dict, we can refer to it using the index of 0 similar to <code>data[0]</code>.  So let's set a variable to the first dict:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    # load the JSON
    data = json.loads(response)
    <span class="code_highlight">unreached = data[0]</span>
                    </pre>
                    <h4 id="python-creating-the-widget">Creating the Widget</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we can access any of the supplied attributes of that dict object using it's key. So if we want to get the people group's name, we can access it like this: <code>unreached['PeopNameInCountry']</code>.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Let's now turn our attention to formating the data for proper displaying.  The population of the people group needs to be a comma seperated number.  We will first convert the population to an integer using Python's built-in <code>int()</code> function. (<a href="http://docs.python.org/3.3/library/functions.html#int" target="_blank">Python Docs</a>)  We will then use Python's built-in <code>format()</code> function (<a href="http://docs.python.org/3.3/library/functions.html#format" target="_blank">Python Docs</a>) to format it into a comma seperated integer.  Here is the resulting code:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    # load the JSON
    data = json.loads(response)
    unreached = data[0]
    <span class="code_highlight"># format population to be a comma seperated integer
    unreached['Population'] = format(int(unreached['Population']), ',d')</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Another formatting issue is related to the percent of Evangelicals.  It is possible that it will be set to null.  I would prefer to show 0.00 instead of null.  So we will use Python's <code>if</code> condition to check if the percent of Evangelicals is <code>None</code>,  if so we will set it to 0.  We will then use Python's built-in <code>float()</code> function (<a href="http://docs.python.org/3.3/library/functions.html#float" target="_blank">Python Docs</a>) to format it as a floating point (decimal).  Here is the code:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    # load the JSON
    data = json.loads(response)
    unreached = data[0]
    # format population to be a comma seperated integer
    unreached['Population'] = format(int(unreached['Population']), ',d')
    <span class="code_highlight"># check if percent of Evangelicals is None
    if unreached['PercentEvangelical'] is None:
        unreached['PercentEvangelical'] = '0'
    # format percent of Evangelicals to percent
    unreached['PercentEvangelical'] = float(unreached['PercentEvangelical'])</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;So we are now on the last stretch!  All we have to do now is generate the widget HTML file.  We will use Python's Template strings (<a href="http://docs.python.org/3.3/library/string.html#template-strings" target="_blank">Python Docs</a>) in order to substitute unique $-based variables in our template file with the unreached people group information we already retrieved.  After we have replaced everything,  we will then save the final file as <code>generated_code/widget.html</code>.  First,  open the <code>templates/index.html</code> file.  Now add the following $-based variables to the file:</p>
                    <pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;
    &lt;head&gt;
        &lt;title&gt;Joshua Project | Sample Code (Javascript)&lt;/title&gt;
        &lt;link rel="stylesheet" type="text/css" href="../../css/styles.css"&gt;
    &lt;/head&gt;
    &lt;body&gt;
        &lt;p&gt;
            This Sample Code is designed to demonstrate how to retrieve the Daily Unreached from the &lt;a href="" class="domain-link"&gt;Joshua Project API&lt;/a&gt; using Python.
        &lt;/p&gt;
        &lt;div id="jp_widget"&gt;
            &lt;div class="upgotd upgotd-title"&gt;
                &lt;a href="http://www.joshuaproject.net/upgotdfeed.php" class="upgotd-link"&gt;Unreached of the Day&lt;/a&gt;
            &lt;/div&gt;
            &lt;div class="upgotd-image"&gt;
                &lt;a href="<span class="code_highlight">$PeopleGroupURL</span>" class="upgotd-link pg-link" id="people-group-image"&gt;
                    &lt;img src="<span class="code_highlight">$PeopleGroupPhotoURL</span>" height="160" width="128" alt="Unreached of the Day Photo"&gt;
                &lt;/a&gt;
            &lt;/div&gt;
            &lt;div class="upgotd upgotd-pray"&gt;Please pray for the ...&lt;/div&gt;
            &lt;div class="upgotd upgotd-people"&gt;
                &lt;a href="<span class="code_highlight">$PeopleGroupURL</span>" class="upgotd-link pg-link pg-name"&gt;<span class="code_highlight">$PeopNameInCountry</span>&lt;/a&gt; of &lt;a href="<span class="code_highlight">$CountryURL</span>" class="upgotd-link country-link country-name"&gt;<span class="code_highlight">$Ctry</span>&lt;/a&gt;
            &lt;/div&gt;
            &lt;table align="center" class="upgotd-table" cellpadding="0" cellspacing="0"&gt;
                &lt;tbody&gt;&lt;tr&gt;
                    &lt;td width="65"&gt;Population:&lt;/td&gt;
                    &lt;td width="135" class="pg-population"&gt;<span class="code_highlight">$Population</span>&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Language:&lt;/td&gt;
                    &lt;td class="pg-language"&gt;<span class="code_highlight">$PrimaryLanguageName</span>&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Religion:&lt;/td&gt;
                    &lt;td class="pg-religion"&gt;<span class="code_highlight">$PrimaryReligion</span>&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Evangelical:&lt;/td&gt;
                    &lt;td class="pg-evangelical"&gt;<span class="code_highlight">$PercentEvangelical%</span>&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Status:&lt;/td&gt;
                    &lt;td&gt;
                        &lt;a href="http://www.joshuaproject.net/definitions.php?term=25" class="upgotd-link pg-scale-text"&gt;
                            <span class="code_highlight">$JPScaleText</span>
                        &lt;/a&gt; (
                        &lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link pg-scale"&gt;
                            <span class="code_highlight">$JPScale</span>
                        &lt;/a&gt;
                        &lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link" id="progress-scale-image"&gt;
                            &lt;img src="<span class="code_highlight">$JPScaleImageURL</span>" alt="Progress Scale"&gt;
                        &lt;/a&gt;)
                    &lt;/td&gt;
                &lt;/tr&gt;
            &lt;/tbody&gt;&lt;/table&gt;
            &lt;div class="upgotd upgotd-footer"&gt;Add this daily global vision feature to &lt;br&gt;&lt;a href="/upgotdfeed.php" class="upgotd-link"&gt;&lt;b&gt;your website&lt;/b&gt;&lt;/a&gt; or get it &lt;a href="http://www.unreachedoftheday.org/unreached-email.php" class="upgotd-link"&gt;&lt;b&gt;by email&lt;/b&gt;&lt;/a&gt;.&lt;/div&gt;
        &lt;/div&gt;
    &lt;/body&gt;
&lt;/html&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;As you can see in the code above,  we will use Python's Template strings (<a href="http://docs.python.org/3.3/library/string.html#template-strings" target="_blank">Python Docs</a>) to replace <code>$Population</code> with <code>unreached['Population']</code> in our <code>generate_widget.py</code> script.  The first step is to retrieve the template file using Python's built-in <code>open()</code> function (<a href="http://docs.python.org/3.3/library/functions.html#open" target="_blank">Python Docs</a>) which will open the file.  After opening the file,  we want to use Python's file object <code>.read()</code> function (<a href="http://docs.python.org/3/tutorial/inputoutput.html#reading-and-writing-files" target="_blank">Python Docs</a>) to read the file contents into a variable.  here is the code:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    # load the JSON
    data = json.loads(response)
    unreached = data[0]
    # format population to be a comma seperated integer
    unreached['Population'] = format(int(unreached['Population']), ',d')
    # check if percent of Evangelicals is None
    if unreached['PercentEvangelical'] is None:
        unreached['PercentEvangelical'] = '0'
    # format percent of Evangelicals to percent
    unreached['PercentEvangelical'] = float(unreached['PercentEvangelical'])
    <span class="code_highlight"># get the template file
    index_file = open('templates/index.html').read()</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that we have the template file's content,  we can feed that to Python's <code>string.Template</code> function (<a href="http://docs.python.org/3.3/library/string.html#string.Template" target="_blank">Python Docs</a>) to initialize a new template.  Here is the code:</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    # load the JSON
    data = json.loads(response)
    unreached = data[0]
    # format population to be a comma seperated integer
    unreached['Population'] = format(int(unreached['Population']), ',d')
    # check if percent of Evangelicals is None
    if unreached['PercentEvangelical'] is None:
        unreached['PercentEvangelical'] = '0'
    # format percent of Evangelicals to percent
    unreached['PercentEvangelical'] = float(unreached['PercentEvangelical'])
    # get the template file
    index_file = open('templates/index.html').read()
    <span class="code_highlight"># initialize a new template
    template = string.Template(index_file)</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Let's trigger the substitution of the $-based variables using Python's string.Template <code>.substitute()</code> function (<a href="http://docs.python.org/3.3/library/string.html#string.Template.substitute" target="_blank">Python Docs</a>) on the new template object we created.  We will pass into it the <code>unreached</code> variable for replacement.</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    # load the JSON
    data = json.loads(response)
    unreached = data[0]
    # format population to be a comma seperated integer
    unreached['Population'] = format(int(unreached['Population']), ',d')
    # check if percent of Evangelicals is None
    if unreached['PercentEvangelical'] is None:
        unreached['PercentEvangelical'] = '0'
    # format percent of Evangelicals to percent
    unreached['PercentEvangelical'] = float(unreached['PercentEvangelical'])
    # get the template file
    index_file = open('templates/index.html').read()
    # initialize a new template
    template = string.Template(index_file)
    <span class="code_highlight"># make the substitution
    final_code = template.substitute(unreached)</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The variable <code>final_code</code> now holds the final code we need to write into our <code>generated_code/widget.html</code> file.  We will use Python's built-in <code>open()</code> function (<a href="http://docs.python.org/3.3/library/functions.html#open" target="_blank">Python Docs</a>) to open the new file.  You will notice that we added a second parameter (<code>'w'</code>) to make the file writable.  We will finally write the file using Python's file object <code>.write()</code> function (<a href="http://docs.python.org/3/tutorial/inputoutput.html#reading-and-writing-files" target="_blank">Python Docs</a>) passing it the <code>final_code</code> variable.</p>
                    <pre>
#!/usr/bin/python
# import the necessary libraries
import json
import urllib.request
import urllib.error
import string
import sys
# set some important variables
domain = "http://jpapi.codingstudio.org"
api_key = YOUR_API_KEY
url = domain+"/v1/people_groups/daily_unreached.json?api_key="+api_key
try:
    # request the API for the Daily Unreached People Group
    request = urllib.request.urlopen(api_url)
except urllib.error.HTTPError as e:
    print('The server couldn\'t fulfill the request.')
    print('Error code: ', e.code)
    exit
except urllib.error.URLError as e:
    print('We failed to reach a server.')
    print('Reason: ', e.reason)
    exit
else:
    # Everything worked
    # decode the response
    response = request.read().decode("utf8")
    # load the JSON
    data = json.loads(response)
    unreached = data[0]
    # format population to be a comma seperated integer
    unreached['Population'] = format(int(unreached['Population']), ',d')
    # check if percent of Evangelicals is None
    if unreached['PercentEvangelical'] is None:
        unreached['PercentEvangelical'] = '0'
    # format percent of Evangelicals to percent
    unreached['PercentEvangelical'] = float(unreached['PercentEvangelical'])
    # get the template file
    index_file = open('templates/index.html').read()
    # initialize a new template
    template = string.Template(index_file)
    # make the substitution
    final_code = template.substitute(unreached)
    <span class="code_highlight"># create the widget.html file
    widget_file = open('generated_code/widget.html','w')
    widget_file.write(final_code)</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you run this script from your command line utility,  you will see that it generates the <code>generated_code/widget.html</code> file.  Now open that file in your favorite web browser.  This is what you should see:</p>
                    <img src="img/getting_started/final_python.png" alt="Snapshot of Final Widget" class="img-responsive">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Congratulations!  You have completed the Python tutorial.  If you would like to download the sample code,  you can visit our <a href="https://github.com/MissionalDigerati/joshua_project_api_sample_code" target="_blank">Github Account</a>.</p>
                    <div class="page-header">
                        <h3 id="ruby">Ruby Example</h3>
                    </div>
                    <h4 id="ruby-setup">Setup</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Before starting this tutorial,  you will need to have a basic understanding of the <a href="https://www.ruby-lang.org/" target="_blank" title="Find out more about Ruby">Ruby programming language</a>.  We will be using Ruby version 2.0 in this tutorial.  You will also need to be able to run ruby scripts in your command line utility.  This tutorial does not discuss how to install Ruby.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In this tutorial,  we will build a generator that creates the necessary HTML & CSS for the widget.  Everytime you run the script from the command line,  it will create the widget with the latest people group data.  Once you have downloaded and unzipped the <a href="files/starting_code/ruby.zip">Ruby starting code</a>, open it up and look around.  Here is the basic code structure:</p>
                    <p>
                        <ul>
                            <li>
                                <code>css</code> - directory for CSS Stylesheets
                                <ul>
                                    <li><code>styles.css</code> - the basic styles for the widget</li>
                                </ul>
                            </li>
                            <li>
                                <code>generated_code</code> - directory for the code created by our generator
                            </li>
                            <li>
                                <code>templates</code> - directory for the HTML/ERB templates
                                <ul>
                                    <li><code>index.html.erb</code> - the HTML/ERB template for the widget</li>
                                </ul>
                            </li>
                            <li>
                                <code>generate_widget.rb</code> - the generator script we will build
                            </li>
                        </ul>
                    </p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Go ahead and open the <code>generate_widget.rb</code> file in your favorite text editor.  We will begin by including any modules/gems we will need for the script.  We will use Ruby's <code>require</code> method (<a href="http://www.ruby-doc.org/core-2.0.0/Kernel.html#method-i-require" target="_blank">Ruby Docs</a>) to include the Net/HTTP module (<a href="http://ruby-doc.org/stdlib-2.0.0/libdoc/net/http/rdoc/Net/HTTP.html" target="_blank">Ruby Docs</a>), and JSON module (<a href="http://www.ruby-doc.org/stdlib-1.9.3/libdoc/json/rdoc/JSON.html" target="_blank">Ruby Docs</a>).  We will also need the <a href="http://www.kuwata-lab.com/erubis/" target="_blank">Erubis</a> gem.  <em>You will need to install Erubis using to command <code>gem install erubis</code>.</em>  Here is the start of our code:</p>
                    <pre>
<span class="code_highlight"># We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"</span>
                    </pre>
                    <h4 id="ruby-calling-the-api">Calling the API</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that we have required the necessary gems/modules,  we need to generated the API request.  We will start by creating 3 variables for the API domain, API key, and the API path for the request. <strong>Remember to add your API key!</strong>  Here is the code:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
<span class="code_highlight"># set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We will call the API in a Ruby <code>begin...end</code> block. (<a href="http://www.ruby-doc.org/core-2.0.0/doc/syntax/exceptions_rdoc.html" target="_blank">Ruby Docs</a>)  This will allow us the opportunity to rescue from a failed request, and print out the error.  Here is the block:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
<span class="code_highlight">begin
    # Make the request to the Joshua Project API
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We will now use the Net/HTTP module (<a href="http://ruby-doc.org/stdlib-2.0.0/libdoc/net/http/rdoc/Net/HTTP.html" target="_blank">Ruby Docs</a>) to send the <code>GET</code> request to the API.  We will use it's <code>.get()</code> method for this. (<a href="http://ruby-doc.org/stdlib-2.0.0/libdoc/net/http/rdoc/Net/HTTP.html#method-c-get" target="_blank">Ruby Docs</a>).  We will then set the response to the variable <code>response</code>.  Here is the code to accomplish this:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    <span class="code_highlight">response = Net::HTTP.get(domain, api_path)</span>
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that we have a response,  we need to parse the JSON into a Ruby object.  This is where the JSON module (<a href="http://www.ruby-doc.org/stdlib-1.9.3/libdoc/json/rdoc/JSON.html" target="_blank">Ruby Docs</a>) comes in handy.  Weill use it's <code>.parse()</code> method (<a href="http://www.ruby-doc.org/stdlib-2.0/libdoc/json/rdoc/JSON.html#method-i-parse" target="_blank">Ruby Docs</a>) to accomplish this.  Let us also add some temporary code to display what we receive back.  Here is the code:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    <span class="code_highlight"># Parse the response
    data = JSON.parse(response)
    puts data</span>
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now run this code in your command line utility, and you should see something very similar to the <a href="#api-response">API response</a> we showed you up top.  The <code>.parse()</code> method (<a href="http://www.ruby-doc.org/stdlib-2.0/libdoc/json/rdoc/JSON.html#method-i-parse" target="_blank">Ruby Docs</a>) converted the JSON to a Ruby Array (<a href="http://ruby-doc.org/core-2.0.0/Array.html" target="_blank">Ruby Docs</a>) of Hashes (<a href="http://ruby-doc.org/core-2.0.0/Hash.html" target="_blank">Ruby Docs</a>).  To access the first Hash,  we can use the Array index of the first object 0 like this: <code>data[0]</code>.</p> 
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    # Parse the response
    data = JSON.parse(response)
    <span class="code_highlight">unreached = data[0]</span>
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
                    </pre>
                    <h4 id="ruby-creating-the-widget">Creating the Widget</h4>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we can access any of the supplied attributes of that Hash object using it's key. So if we want to get the people group's name, we can access it like this: <code>unreached['PeopNameInCountry']</code>.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In order to display the information properly,  we need to format the population and percent of Evangelicals.  For the population,  we want to format it as a comma seperated number.  To do this,  we will convert the population to a string using Ruby's <code>.to_s</code> method. (<a href="http://ruby-doc.org/core-2.0.0/Object.html#method-i-to_s" target="_blank">Ruby Docs</a>)  After converting it to a string,  we will use Ruby's String <code>.gsub()</code> method (<a href="http://ruby-doc.org/core-2.0.0/String.html#method-i-gsub" target="_blank">Ruby Docs</a>) to use a <a href="http://en.wikipedia.org/wiki/Regular_expression" target="_blank">Regular Expression</a> to format the string.  Here is the code to format the population:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    # Parse the response
    data = JSON.parse(response)
    unreached = data[0]
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
<span class="code_highlight"># format the population to a comma seperated value
unreached['Population'] = unreached['Population'].to_s.gsub(/(\d)(?=(\d{3})+$)/,'\1,')</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sometimes the percent of Evangelicals can be nil.  I would prefer to display 0.00 rather then nil.  To do this,  we will use Ruby's <code>if...end</code> block and the <code>.nil?</code> method (<a href="http://www.ruby-doc.org/core-2.0.0/NilClass.html#method-i-nil-3F" target="_blank">Ruby Docs</a>) to check it's value.</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    # Parse the response
    data = JSON.parse(response)
    unreached = data[0]
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
# format the population to a comma seperated value
unreached['Population'] = unreached['Population'].to_s.gsub(/(\d)(?=(\d{3})+$)/,'\1,')
<span class="code_highlight"># Lets handle the evangelical number since it can be nil
if unreached['PercentEvangelical'].nil?
    unreached['PercentEvangelical'] = "0.00"
else
    # format the percent to a floating point (decimal)
end</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If the percent of Evangelicals is not nil, then we want to convert it to a floating point (decimal).</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    # Parse the response
    data = JSON.parse(response)
    unreached = data[0]
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
# format the population to a comma seperated value
unreached['Population'] = unreached['Population'].to_s.gsub(/(\d)(?=(\d{3})+$)/,'\1,')
# Lets handle the evangelical number since it can be nil
if unreached['PercentEvangelical'].nil?
    unreached['PercentEvangelical'] = "0.00"
else
    # format the percent to a floating point (decimal)
    <span class="code_highlight">unreached['PercentEvangelical'] = '%.2f' % unreached['PercentEvangelical']</span>
end
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that all the attributes have been formatted, we are ready to create the <code>generated_code/widget.html</code> file.  We will use a common Ruby templating gem called <a href="http://www.kuwata-lab.com/erubis/" target="_blank">Erubis</a>.  This templating engine gives us the ability to embed Ruby in a HTML template file.  All Ruby is wrapped with a <code><% %></code> tag.  Any ruby within those tags will be run when the template is processed.  Open the <code>templates/index.html.erb</code> file and update with the following Erubis code:</p>
                    <pre>
&lt;html&gt;
    &lt;head&gt;
        &lt;title&gt;Joshua Project&lt;/title&gt;
        &lt;link rel="stylesheet" type="text/css" href="../css/styles.css"&gt;
    &lt;/head&gt;
    &lt;body&gt;
        &lt;div id="jp_widget"&gt;
            &lt;div class="upgotd upgotd-title"&gt;
                &lt;a href="http://www.joshuaproject.net/upgotdfeed.php" class="upgotd-link"&gt;Unreached of the Day&lt;/a&gt;
            &lt;/div&gt;
            &lt;div class="upgotd-image"&gt;
                &lt;a href="<span class="code_highlight">&lt;%= unreached['PeopleGroupURL'] %&gt;</span>" class="upgotd-link pg-link" id="people-group-image"&gt;
                    &lt;img src="<span class="code_highlight">&lt;%= unreached['PeopleGroupPhotoURL'] %&gt;</span>" height="160" width="128" alt="Unreached of the Day Photo"&gt;
                &lt;/a&gt;
            &lt;/div&gt;
            &lt;div class="upgotd upgotd-pray"&gt;Please pray for the ...&lt;/div&gt;
            &lt;div class="upgotd upgotd-people"&gt;
                &lt;a href="<span class="code_highlight">&lt;%= unreached['PeopleGroupURL'] %&gt;</span>" class="upgotd-link pg-link pg-name"&gt;<span class="code_highlight">&lt;%= unreached['PeopNameInCountry'] %&gt;</span>&lt;/a&gt; of &lt;a href="<span class="code_highlight">&lt;%= unreached['CountryURL'] %&gt;</span>" class="upgotd-link country-link country-name"&gt;<span class="code_highlight">&lt;%= unreached['Ctry'] %&gt;</span>&lt;/a&gt;
            &lt;/div&gt;
            &lt;table align="center" class="upgotd-table" cellpadding="0" cellspacing="0"&gt;
                &lt;tbody&gt;&lt;tr&gt;
                    &lt;td width="65"&gt;Population:&lt;/td&gt;
                    &lt;td width="135" class="pg-population"&gt;<span class="code_highlight">&lt;%= unreached['Population'] %&gt;</span>&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Language:&lt;/td&gt;
                    &lt;td class="pg-language"&gt;<span class="code_highlight">&lt;%= unreached['PrimaryLanguageName'] %&gt;</span>&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Religion:&lt;/td&gt;
                    &lt;td class="pg-religion"&gt;<span class="code_highlight">&lt;%= unreached['PrimaryReligion'] %&gt;</span>&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Evangelical:&lt;/td&gt;
                    &lt;td class="pg-evangelical"&gt;<span class="code_highlight">&lt;%= unreached['PercentEvangelical'] %&gt;</span>%&lt;/td&gt;
                &lt;/tr&gt;
                &lt;tr&gt;
                    &lt;td&gt;Status:&lt;/td&gt;
                    &lt;td&gt;
                        &lt;a href="http://www.joshuaproject.net/definitions.php?term=25" class="upgotd-link pg-scale-text"&gt;
                            <span class="code_highlight">&lt;%= unreached['JPScaleText'] %&gt;</span>
                        &lt;/a&gt; (
                        &lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link pg-scale"&gt;
                            <span class="code_highlight">&lt;%= unreached['JPScale'] %&gt;</span>
                        &lt;/a&gt;
                        &lt;a href="http://www.joshuaproject.net/global-progress-scale.php" class="upgotd-link" id="progress-scale-image"&gt;
                            &lt;img src="<span class="code_highlight">&lt;%= unreached['JPScaleImageURL'] %&gt;</span>" alt="Progress Scale"&gt;
                        &lt;/a&gt;)
                    &lt;/td&gt;
                &lt;/tr&gt;
            &lt;/tbody&gt;&lt;/table&gt;
            &lt;div class="upgotd upgotd-footer"&gt;Add this daily global vision feature to &lt;br&gt;&lt;a href="/upgotdfeed.php" class="upgotd-link"&gt;&lt;b&gt;your website&lt;/b&gt;&lt;/a&gt; or get it &lt;a href="http://www.unreachedoftheday.org/unreached-email.php" class="upgotd-link"&gt;&lt;b&gt;by email&lt;/b&gt;&lt;/a&gt;.&lt;/div&gt;
        &lt;/div&gt;
    &lt;/body&gt;
&lt;/html&gt;
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;As you can see in the code above, we will use Erubis to replace <code><% unreached['Population'] %></code> with <code>unreached['Population']</code> in our <code>generate_widget.rb</code> script.  We now need to read the templating file code into a variable using Ruby's File <code>.read()</code> method. (<a href="http://www.ruby-doc.org/core-2.0.0/IO.html#method-c-read" target="_blank">Ruby Docs</a>) After we have the content in the variable,  we need to pass it to a new instance of Erubis.  We will finally use Erubis' <code>.result()</code> method to replace all the tags with the appropriate variables. Go back to the <code>generate_widget.rb</code> file and add the following code:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    # Parse the response
    data = JSON.parse(response)
    unreached = data[0]
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
# format the population to a comma seperated value
unreached['Population'] = unreached['Population'].to_s.gsub(/(\d)(?=(\d{3})+$)/,'\1,')
# Lets handle the evangelical number since it can be nil
if unreached['PercentEvangelical'].nil?
    unreached['PercentEvangelical'] = "0.00"
else
    # format the percent to a floating point (decimal)
    unreached['PercentEvangelical'] = '%.2f' % unreached['PercentEvangelical']
end
<span class="code_highlight"># Generate the template
template_file = File.read("templates/index.html.erb")
template = Erubis::Eruby.new(template_file)
# run the Erubis substitution
widget_code = template.result({unreached: unreached})
</span>
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We can now write the new templated code to the <code>generated_code/widget.html</code> file.  In order to ensure that the file is written correctly, we will use Ruby's <code>begin...rescue...ensure</code> block.  This will ensure that the file is closed in case something goes wrong.  Add the following code:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    # Parse the response
    data = JSON.parse(response)
    unreached = data[0]
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
# format the population to a comma seperated value
unreached['Population'] = unreached['Population'].to_s.gsub(/(\d)(?=(\d{3})+$)/,'\1,')
# Lets handle the evangelical number since it can be nil
if unreached['PercentEvangelical'].nil?
    unreached['PercentEvangelical'] = "0.00"
else
    # format the percent to a floating point (decimal)
    unreached['PercentEvangelical'] = '%.2f' % unreached['PercentEvangelical']
end
<span class="code_highlight"># We will write the final HTML file
begin
    # write the new file
rescue IOError => e
    # We had an error
    puts "Unable to write the HTML file"
    puts e.message
    abort
ensure
    # ensure the file closes happens if this fails
end</span>
                    </pre>       
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;As you can see in the code,  if an error occurs it will display on the screen.  We now need to open the new file <code>generated_code/widget.html</code> for writing using Ruby's File <code>.open()</code> method. (<a href="http://www.ruby-doc.org/core-2.0.0/File.html#method-c-open" target="_blank">Ruby Docs</a>)  We will pass it the <code>'w'</code> option to make it writable.  We will also use Ruby's File <code>.close()</code> method (<a href="http://www.ruby-doc.org/core-2.0.0/IO.html#method-i-close" target="_blank">Ruby Docs</a>) in the ensure block to close the file.  Here is the code:</p>
                    <pre>
# We will use Erubis for the templating
require "erubis"
# We need net/http to handle the request to the API
require "net/http"
# We will need to parse the JSON response
require "json"
# set some important variables
domain = "jpapi.codingstudio.org"
api_key = YOUR_API_KEY
api_path = "/v1/people_groups/daily_unreached.json?api_key=#{api_key}"
begin
    # Make the request to the Joshua Project API
    response = Net::HTTP.get(domain, api_path)
    # Parse the response
    data = JSON.parse(response)
    unreached = data[0]
rescue Exception => e
    # We had an error
    puts "Unable to get the API data"
    puts e.message
    abort
end
# format the population to a comma seperated value
unreached['Population'] = unreached['Population'].to_s.gsub(/(\d)(?=(\d{3})+$)/,'\1,')
# Lets handle the evangelical number since it can be nil
if unreached['PercentEvangelical'].nil?
    unreached['PercentEvangelical'] = "0.00"
else
    # format the percent to a floating point (decimal)
    unreached['PercentEvangelical'] = '%.2f' % unreached['PercentEvangelical']
end
# We will write the final HTML file
begin
    # write the new file
    <span class="code_highlight"># open the final file
    file = File.open("generated_code/widget.html", "w")
    file.write(widget_code)</span>
rescue IOError => e
    # We had an error
    puts "Unable to write the HTML file"
    puts e.message
    abort
ensure
    # ensure the file closes happens if this fails
    <span class="code_highlight">file.close unless file == nil</span>
end
                    </pre>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now run the script from your command line utility.  Everytime you run it, it should generate a new <code>generated_code/widget.html</code> file with the latest people group.  Open the <code>generated_code/widget.html</code> file in your favorite web browser, and you should see this:</p>
                    <img src="img/getting_started/final_ruby.png" alt="Snapshot of Final Widget" class="img-responsive">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Congratulations!  You have completed the Ruby tutorial.  If you would like to download the sample code,  you can visit our <a href="https://github.com/MissionalDigerati/joshua_project_api_sample_code" target="_blank">Github Account</a>.</p>
                    <div class="page-header">
                        <h2 id="reporting-tutorial-errors">Errors In These Tutorials?</h2>
                    </div>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you find any errors within these tutorials, or would like to suggest a new language/approach, please submit your request at our <a href="https://github.com/MissionalDigerati/joshua_project_api/issues" target="_blank">Github issue tracker</a>.</p>
                </div>
            </div>
        </div>
        <div class="container" id="footer">
            <a href="http://www.joshuaproject.net/" target="_blank">Joshua Project</a> is a ministry of the  <a href="http://www.uscwm.org/" target="_blank">U.S. Center for World Mission</a>. API created by <a href="http://www.missionaldigerati.org" target="_blank">Missional Digerati</a>.  Icons provided by <a href="http://gemicon.net/" target="_blank">Gem Icon</a>.
        </div>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('body').scrollspy({
                  target: '#sidebar',
                  offset: 75
                });
                $(window).on('load', function () {
                  $('body').scrollspy('refresh');
                });
            });
        </script>
    </body>
</html>