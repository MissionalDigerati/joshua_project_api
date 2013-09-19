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
                <div id="sidebar" class="col-sm-3">
                    <div class="hidden-print affix" role="complementary">
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
<!--                             <li><a href="#php">PHP Example</a></li>
                            <li><a href="#python">Python Example</a></li>
                            <li><a href="#ruby">Ruby Example</a></li> -->
                        </ul>
                    </div>
                </div>
                <div class="col-sm-9">
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
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have created a zip file containing the starting code for all the programming languages.  You can download the <a href="files/starting_code.zip">file here</a>. Once you unzip the file, you will see the following code structure:</p>
                    <p>
                        <ul>
                            <li>css
                                <ul><li>styles.css</li></ul>
                            </li>
                            <li>index.html</li>
                        </ul>
                    </p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The <code>css/styles.css</code> file offers the basic design for the widget.  Here is a look at the code:</p>
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
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The <code>index.html</code> file offers the basic structure of the final HTML file.  The code looks like this:</p>
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
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you load the <code>index.html</code> file in your browser, you should see something similar to this:</p>
                    <img src="img/getting_started/snapshot.png" alt="Snapshot of the Starting Code" class="img-responsive">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;As you can see, we are missing the most important information... the Joshua Project data.  Please choose your preferred programming language to continue this tutorial:</p>
                    <p>
                        <ul>
                            <li><a href="#javascript">Javascript (JQuery) Example</a></li>
                            <li><a href="#php">PHP Example</a></li>
                            <li><a href="#python">Python Example</a></li>
                            <li><a href="#ruby">Ruby Example</a></li>
                        </ul>
                    </p>
                    <h3 id="javascript">Javascript (JQuery) Example</h3>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Before beginning this tutorial,  you should have a good understanding of the <a href="http://www.w3schools.com/js/" target="_blank">Javascript programming language</a>, and the <a href="http://jquery.com/" target="_blank">JQuery library</a>!</p>
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
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now we can access any of the supplied attributes of that JSON object using it's key.  So if we want to get the people group's name,  we can access it like this: <code>unreached['PeopNameInCountry']</code>.Now that we can access the data, we just need to start putting the data into the HTML we already provided.  If you look at the HTML of the <code>index.html</code> file you have been working on,  you will see the following code:</p>
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