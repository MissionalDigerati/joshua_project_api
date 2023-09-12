<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_css_meta.html');
?>
  </head>
<body>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'nav.html');
?>
  <div class="container">
    <div class="page-header">
      <h2>Language Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>AudioRecordings</td>
                    <td>Does this language have access to an audio recorded Bible?</td>
                </tr>
                <tr>
                    <td>BibleStatus</td>
                    <td>The current status of translation of the Bilbe in this langauge.</td>
                </tr>
                <tr>
                    <td>Bible</td>
                    <td>The year this language's Bible was completed.</td>
                </tr>
                <tr>
                    <td>HubCountry</td>
                    <td>The main concentration of this language.</td>
                </tr>
                <tr>
                    <td>JF</td>
                    <td>Is the Jesus Film available in this language?</td>
                </tr>
                <tr>
                    <td>JPScale</td>
                    <td>Joshua Project Progress Scale for this language, derived from the people group data who speak 
                        this language.</td>
                </tr>
                <tr>
                    <td>Language</td>
                    <td>The name of this language.</td>
                </tr>
                <tr>
                    <td>LeastReached</td>
                    <td>Is this language considered unreached, derived from the people group data who speak this 
                        language?</td>
                </tr>

                <tr>
                    <td>NbrCountries</td>
                    <td>Estimated number of countries this language is spoken in.</td>
                </tr>
                <tr>
                    <td>NbrPGICs</td>
                    <td>Number of people groups speaking this language.</td>
                </tr>
                <tr>
                    <td>NTYear</td>
                    <td>The year the translation of the New Testament was completed in this language.</td>
                </tr>
                <tr>
                    <td>PortionsYear</td>
                    <td>The year that the portions of the Bible was completed in this language.</td>
                </tr>
                <tr>
                    <td>PrimaryReligion</td>
                    <td>Primary religion (text) for this language [1 - Christianity, 2 - Buddhism, 4 - Ethnic Religions,
                        5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small, 9 - Unknown]</td>
                </tr>
                <tr>
                    <td>RLG3</td>
                    <td>Primary religion (number) of the people group in this language [1 - Christianity, 2 - Buddhism, 
                        4 - Ethnic Religions, 5 - Hinduism, 6 - Islam, 7 - Non-Religious, 8 - Other/Small, 
                        9 - Unknown]</td>
                </tr>
                <tr>
                    <td>ROG3</td>
                    <td>HIS Registry of Geography (ROG) country code, similar to 2 letter ISO language code.</td>
                </tr>
                <tr>
                    <td>ROL3</td>
                    <td>The ISO 3 Letter Code for this language.</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>Language status [L - Living, E - Extinct, N - Nearly extinct, H - Historical, A - Ancient, 
                        C - Constructed].</td>
                </tr>
                <tr>
                    <td>TranslationNeedQuestionable</td>
                    <td>Is the need for Bible translation questionable?</td>
                </tr>
                <tr>
                    <td>WebLangText</td>
                    <td>Language text display.</td>
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
                $('li.documentation-nav, li.languages-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>
