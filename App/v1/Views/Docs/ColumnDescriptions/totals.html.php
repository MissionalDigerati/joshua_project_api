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
                                    <td>The total number of people groups where Buddhism is the primary religion.</td>
                                </tr>
                                <tr>
                                    <td>CntChristianPeopGroups</td>
                                    <td>The total number of people groups where Christianity is the primary religion.</td>
                                </tr>
                                <tr>
                                    <td>CntContinents</td>
                                    <td>The total number of continents in the world.</td>
                                </tr>
                                <tr>
                                    <td>CntCountries</td>
                                    <td>The total number of countries in the world.</td>
                                </tr>
                                <tr>
                                    <td>CntCountries1040</td>
                                    <td><a>The total number of people groups living in the <a href="https://joshuaproject.net/resources/articles/10_40_window" target="_blank">1040 Window</a>.</td>
                                </tr>
                                <tr>
                                    <td>CntCountriesLR</td>
                                    <td>The total number of people groups that are considered <a href="https://joshuaproject.net/help/definitions#least-reached" target="_blank">least reached</a>.</td>
                                </tr>
                                <tr>
                                    <td>CntCtryChristian</td>
                                    <td>The total number of countries where Christianity is the primary religion.</td>
                                </tr>
                                <tr>
                                    <td>CntHinduPeopGroups</td>
                                    <td>The total number of people groups where Hinduism is the primary religion.</td>
                                </tr>
                                <tr>
                                    <td>CntLangJesusFilm</td>
                                    <td>The total number of languages that have access to the Jesus Film.</td>
                                </tr>
                                <tr>
                                    <td>CntLangNoResources</td>
                                    <td>The total number of langauges that have no access to Biblical resources.</td>
                                </tr>
                                <tr>
                                    <td>CntLangPortions</td>
                                    <td>The total number of langauges that have access to only portions of the Bible.</td>
                                </tr>
                                <tr>
                                    <td>CntLangRecordings</td>
                                    <td>The total number of langauges that have access to Biblical recordings.</td>
                                </tr>
                                <tr>
                                    <td>CntMuslimPeopGroups</td>
                                    <td>The total number of people groups where Islam is the primary religion.</td>
                                </tr>
                                <tr>
                                    <td>CntPCLR</td>
                                    <td>The total number of people clusters  that are considered <a href="https://joshuaproject.net/help/definitions#least-reached" target="_blank">least reached</a>.</td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtry</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtry1040</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryFrontier</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryGreat50KLR</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryLess10K</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryLess10KLR</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryLR</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryLR1040</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryLRNo5PctAdherents</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryNoPopl</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopCtryNoPoplLR</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopleID1</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopleID2</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPeopleID3</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntPGACLR</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>CntRegions</td>
                                    <td>The total number of regions in the world.</td>
                                </tr>
                                <tr>
                                    <td>CntTotalLanguages</td>
                                    <td>The total number of languages in the world.</td>
                                </tr>
                                <tr>
                                    <td>CntTotalSubgroups</td>
                                    <td>The total number of subgroups in the world.</td>
                                </tr>
                                <tr>
                                    <td>CntWorkersNeeded</td>
                                    <td>The total number of people groups where workers are needed.</td>
                                </tr>
                                <tr>
                                    <td>PoplCtryUN</td>
                                    <td>The total world population provided by the United Nations.</td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtry</td>
                                    <td>The total world population.</td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtry1040</td>
                                    <td>The total world population in the <a href="https://joshuaproject.net/resources/articles/10_40_window" target="_blank">1040 Window</a>.</td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtryFrontier</td>
                                    <td>The total world population that are considered <a href="https://joshuaproject.net/frontier" target="_blank">frontier unreached people</a>.</td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtryLR</td>
                                    <td>The total world population that are considered <a href="https://joshuaproject.net/help/definitions#least-reached" target="_blank">least reached people</a>.</td>
                                </tr>
                                <tr>
                                    <td>PoplPeopCtryLR1040</td>
                                    <td>The total world population that are considered <a href="https://joshuaproject.net/help/definitions#least-reached" target="_blank">least reached people</a> ans reside in the <a href="https://joshuaproject.net/resources/articles/10_40_window" target="_blank">1040 Window</a>.</td>
                                </tr>
                                <tr>
                                    <td>WorldChristianPct</td>
                                    <td>The percentage of the world that identifies as Christians.</td>
                                </tr>
                                <tr>
                                    <td>WorldEvangelicalPct</td>
                                    <td>The percentage of the world that identifies as Evangelical Christians.</td>
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
