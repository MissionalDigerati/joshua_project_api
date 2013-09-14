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
                    <li class="active"><a href="/">Home</a></li>
                    <li><a href="/docs/v1/#!/people_groups">Documentation</a></li>
                    <li><a href="/docs/v1/sample_code">Sample Code</a></li>
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
                            <li><a href="#javascript">Javascript (JQuery) Example</a></li>
                            <li><a href="#php">PHP Example</a></li>
                            <li><a href="#python">Python Example</a></li>
                            <li><a href="#ruby">Ruby Example</a></li>
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
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Below is a typical request for data from the Joshua Project API.   In this example,  we will request the Joshua Project unreached people group of the day for January 11th.</p>
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
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Once you have a response,  you can use and manipulate the provided data.  In the tutorial below,  we will look at developing a Joshua Project Unreached of the Widget using the server response.</p>
                    <h3 id="javascript">Javascript (JQuery) Example</h3>
                    <h3 id="php">PHP Example</h3>
                    <h3 id="python">Python Example</h3>
                    <h3 id="ruby">Ruby Example</h3>
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