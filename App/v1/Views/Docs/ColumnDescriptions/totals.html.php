<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include($viewDirectory . "Partials" . DIRECTORY_SEPARATOR . "site_wide_css_meta.html");
?>
  </head>
<body>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'nav.html');
?>
  <div class="container">
    <div class="page-header">
      <h2>Total Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>id</td>
                    <td>
                        The unique id for the given total. This id explains what the total represents.  Here are the available totals.
                        <table class='table'>
                            <tbody>
                                <tr>
                                    <td>CntBuddhistPeopGroups</td>
                                    <td>Count of People Groups with Primary Religion Buddhist</td>
                                </tr>
                                <tr>
                                    <td>CntChristianPeopGroups</td>
                                    <td>Count of People Groups with Primary Religion Christian</td>
                                </tr>
                                <tr>
                                    <td>CntContinents</td>
                                    <td>Count of Continents</td>
                                </tr>
                                <tr>
                                    <td>CntCountries</td>
                                    <td>Count of Countries</td>
                                </tr>
                                <tr>
                                    <td>CntCountries1040</td>
                                    <td>Count of Countries in the <a href="https://joshuaproject.net/resources/articles/10_40_window" target="_blank">10/40 Window</a>.</td>
                                </tr>
                                <tr>
                                    <td>CntCountriesLR</td>
                                    <td>Count of Countries considered <a href="https://joshuaproject.net/help/definitions#unreached" target="_blank">Unreached using 2% / 5% rule</a>.</td>
                                </tr>
                                <tr>
                                    <td>CntCtryChristian</td>
                                    <td>Count of Countries with Primary Religion Christian</td>
                                </tr>
                                <tr>
                                    <td>CntHinduPeopGroups</td>
                                    <td>Count of People Groups with Primary Religion Hindu</td>
                                </tr>
                                <tr>
                                    <td>CntLangJesusFilm</td>
                                    <td>Count of Languages that have the Jesus Film</td>
                                </tr>
                                <tr>
                                    <td>CntLangNoResources</td>
                                    <td>Count of Languages with no reported resources e.g. Bible, stories, app, training</td>
                                </tr>
                                <tr>
                                    <td>CntLangPortions</td>
                                    <td>Count of Languages with Bible Portions</td>
                                </tr>
                                <tr>
                                    <td>CntLangRecordings</td>
                                    <td>Count of Languages with Audio Recordings</td>
                                </tr>
                                <tr>
                                    <td>CntMuslimPeopGroups</td>
                                    <td>Count of People Groups with Primary Religion Muslim</td>
                                </tr>
                                <tr>
                                    <td>CntPCLR</td>
                                    <td>Count of People Clusters considered <a href="https://joshuaproject.net/help/definitions#unreached" target="_blank">Unreached using 2% / 5% rule</a>.</td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtry</td>
                                    <td>Count of People Groups using PGIC perspective</td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtry1040</td>
                                    <td>Count of People Groups in the 10/40 Window</td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryFrontier</td>
                                    <td>Count of People Groups considered Frontier using 0.1% and no movement rule</td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryGreat50KLR</td>
                                    <td>Count of People Groups greater than 50K population considered unreached</td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryLR</td>
                                    <td>Count of People Groups considered <a href="https://joshuaproject.net/help/definitions#unreached" target="_blank">Unreached</a></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryLR1040</td>
                                    <td>Count of People Groups in the 10/40 Window considered <a href="https://joshuaproject.net/help/definitions#unreached" target="_blank">Unreached</a></td>
                                </tr>
                                <tr>
                                    <td>CntPeopleID1</td>
                                    <td>Count of Affinity Blocs</td>
                                </tr>
                                <tr>
                                    <td>CntPeopleID2</td>
                                    <td>Count of People Clusters</td>
                                </tr>
                                <tr>
                                    <td>CntPeopleID3</td>
                                    <td>Count of People Groups Across Countries (PGAC)</td>
                                </tr>
                                <tr>
                                    <td>CntPGACLR</td>
                                    <td>Count of People Groups Across Countries (PGAC) considered <a href="https://joshuaproject.net/help/definitions#unreached" target="_blank">Unreached using 2% / 5% rule</a></td>
                                </tr>
                                <tr>
                                    <td>CntRegions</td>
                                    <td>Count of Regions</td>
                                </tr>
                                <tr>
                                    <td>CntTotalLanguages</td>
                                    <td>Count of Languages</td>
                                </tr>
                                <tr>
                                    <td>CntWorkersNeeded</td>
                                    <td>Count of Workers Needed</td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtry</td>
                                    <td>Summation of all People Group populations i.e. World Population</td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtry1040</td>
                                    <td>Population of People Groups in the <a href="https://joshuaproject.net/resources/articles/10_40_window" target="_blank">10/40 Window</a></td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtryFrontier</td>
                                    <td>Population of People Groups considered <a href="https://joshuaproject.net/frontier" target="_blank">Unreached Frontier</a></td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtryLR</td>
                                    <td>Population of People Groups considered <a href="https://joshuaproject.net/help/definitions#unreached" target="_blank">Unreached</a></td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtryLR1040</td>
                                    <td>Population of People Groups considered <a href="https://joshuaproject.net/help/definitions#unreached" target="_blank">Unreached</a> in the <a href="https://joshuaproject.net/resources/articles/10_40_window" target="_blank">10/40 Window</a></td>
                                </tr>
                                <tr>
                                    <td>WorldChristianPct</td>
                                    <td>Global Percent Christian Adherent</td>
                                </tr>
                                <tr>
                                    <td>WorldEvangelicalPct</td>
                                    <td>Global Percent Evangelical</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>Value</td>
                    <td>The total for the given id.</td>
                </tr>
                <tr>
                    <td>RoundPrecision</td>
                    <td>The rounding precision.</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'footer.html');
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.documentation-nav, li.regions-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>
