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
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_css_meta.html');
?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'nav.html');
?>
        <div class="container">
            <div class="col-sm-8">
                <div class="page-header">
                    <h2>Welcome to the Joshua Project API</h2>
                </div>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Joshua Project is a research initiative seeking to highlight the
                    ethnic people groups of the world with the fewest followers of Christ.  This API was developed to
                    provide believers easier access to the Joshua Project's rich data.  Once you request and receive an
                    API key you will have access to data regarding people groups, countries and languages throughout
                    the world.  You will also have access to current missional work being done in those areas.  If you
                    would like to know more, check out the links below!</p>
                <div class="beginner_panel row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h3 class="panel-title"><img src="img/compass.png" alt="compass"> Getting Started</h3>
                          </div>
                          <div class="panel-body">
                            <p>If you are new to API development,  this tutorial will walk you through the basics of
                                using the Joshua Project API.  We offer the tutorial in PHP, Javascript, Ruby, and
                                Python.  So buckle up and prepare to learn API development.</p>
                          </div>
                          <div class="panel-footer">
                            <a href="/getting_started" class="btn btn-success pull-right">Start Now >></a>
                            <span class="clearfix"></span>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h3 class="panel-title"><img src="img/computer.png" alt="computer"> Sample Code</h3>
                          </div>
                          <div class="panel-body">
                            <p>If you need to see working examples of the Joshua Project API,  you can visit our
                                <a href="/v1/docs/sample_code">Sample Code</a> section.  Here you can browse the
                                repository of sample code, and find examples written in PHP, Javascript, Ruby, and
                                Python.</p>
                          </div>
                          <div class="panel-footer">
                            <a href="/v1/docs/sample_code" class="btn btn-success pull-right">View Sample Code >></a>
                            <span class="clearfix"></span>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="beginner_panel row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h3 class="panel-title"><img src="img/documents.png" alt="documents"> Documentation</h3>
                          </div>
                          <div class="panel-body">
                            <p>To get a better understanding of the API, we highly encourage you to look over the
                                documentation.  The documentation will show you all the resources available to you.
                                Make sure you have an API Key to use the interactive documentation.</p>
                          </div>
                            <div class="panel-footer">
                            <a href="/v1/docs/available_api_requests#!/continents" class="btn btn-success pull-right">
                                View Documentation >></a>
                                <span class="clearfix"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h3 class="panel-title"><img src="img/people.png" alt="people"> Learn More</h3>
                          </div>
                          <div class="panel-body">
                            <p>Joshua Project is a research initiative seeking to highlight the ethnic people groups of
                                the world with the fewest followers of Christ. Accurate, regularly updated ethnic
                                people group information is critical for understanding and completing the Great
                                Commission.</p>
                          </div>
                          <div class="panel-footer">
                            <a href="https://joshuaproject.net/" target="_blank" class="btn btn-success pull-right">
                                Visit Website >></a>
                            <span class="clearfix"></span>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
<?php
if ((isset($data['api_key'])) && ($data['api_key'] == 'true')) {
    ?>
                <div class="alert alert-success">
                    Thank you!  We made you a shiny new API key.  Before you can retrieve it,  please visit the email
                    you provided and click the link in the email we sent you.  Happy programming!
                </div>
    <?php
}
?>
                <div class="page-header">
                    <h2>Request An API Key</h2>
                </div>
                <p>If you would like to get an API key, please complete the form below and verify your email
                    address.</p>
                <form class="form-horizontal" method="POST" action="/api_keys/new" role="form">
                    <fieldset>
<?php
if ((!empty($errors)) && (in_array('name', $errors))) {
    ?>
                        <div class="form-group has-error">
    <?php
} else {
    ?>
                        <div class="form-group">
    <?php
}
?>
                            <label class="control-label col-lg-4" for="input-name">Name
                                <span class="required-field">*</span></label>
                            <div class="controls col-lg-8">
<?php
if ((isset($data['name'])) && ($data['name'] != "")) {
    ?>
            <input type="text" name="name" id="input-name"  value="<?php echo $data['name']; ?>" class="form-control">
    <?php
} else {
    ?>
                                <input type="text" name="name" id="input-name" placeholder="Name" class="form-control">
    <?php
}
if ((!empty($errors)) && (in_array('name', $errors))) {
    ?>
                                <span class="help-block">Name is Required!</span>
    <?php
}
?>
                            </div>
                        </div>
<?php
if ((!empty($errors)) && (in_array('email', $errors))) {
    ?>
                        <div class="form-group has-error">
    <?php
} else {
    ?>
                        <div class="form-group">
    <?php
}
?>
            <label class="control-label col-lg-4" for="input-email">Email <span class="required-field">*</span></label>
                            <div class="controls col-lg-8">
<?php
if ((isset($data['email'])) && ($data['email'] != "")) {
    ?>
            <input type="text" name="email" id="input-email" value="<?php echo $data['email']; ?>" class="form-control">
    <?php
} else {
    ?>
                        <input type="text" name="email" id="input-email" placeholder="Email" class="form-control">
    <?php
}
if ((!empty($errors)) && (in_array('email', $errors))) {
    ?>
                                <span class="help-block">Email is Required!</span>
    <?php
}
?>
                            </div>
                        </div>
<?php
    $usage = (isset($data['usage'])) ? explode('|', $data['usage']) : [];
?>
                        <div class="form-group">
                            <label class="control-label col-lg-12 text-left" for="data-usage">
                                Anticipated use of data (check all that apply)<span class="required-field">*</span>
                            </label>
                            <div class="col-md-offset-2 col-lg-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="usage[]" data-tag="personal" value="personal interest" <?php echo in_array('personal interest', $usage) ? 'checked' : ''; ?>> Personal interest
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="usage[]" data-tag="presentation" value="presentation or to share with others" <?php echo in_array('presentation or to share with others', $usage) ? 'checked' : ''; ?>> Presentation or to share with others
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="usage[]" data-tag="research" value="research" <?php echo in_array('research', $usage) ? 'checked' : ''; ?>> Research
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="usage[]" data-tag="internal" value="organization internal use" <?php echo in_array('organization internal use', $usage) ? 'checked' : ''; ?>> Organization internal use
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="usage[]" data-tag="website" value="for a website" <?php echo in_array('for a website', $usage) ? 'checked' : ''; ?>> For a website
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="usage[]" data-tag="app" value="for a mobile app" <?php echo in_array('for a mobile app', $usage) ? 'checked' : ''; ?>> For a mobile app
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="usage[]" data-tag="other" value="other" <?php echo in_array('other', $usage) ? 'checked' : ''; ?>> Other
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group hidden" id="form-website-url">
                            <label class="control-label col-lg-4" for="input-website-url">Website URL <small>(If Published)</small></label>
                            <div class="controls col-lg-8">
                                <input type="text" name="website_url" id="input-website-url" placeholder="Website URL" class="form-control" value="<?php if ((isset($data['website_url'])) && ($data['website_url'] != "")) { echo $data['website_url']; } ?>">
                            </div>
                        </div>

                        <div class="form-group hidden" id="apple-store-url">
                            <label class="control-label col-lg-4" for="input-apple-store-url">Apple App Store URL <small>(If Published)</small></label>
                            <div class="controls col-lg-8">
                                <input type="text" name="apple_app_store" id="input-apple-store-url" placeholder="Apple App Store URL" class="form-control" value="<?php if ((isset($data['apple_app_store'])) && ($data['apple_app_store'] != "")) { echo $data['apple_app_store']; } ?>">
                            </div>
                        </div>

                        <div class="form-group hidden" id="google-store-url">
                            <label class="control-label col-lg-4" for="input-google-store-url">Google Play Store URL <small>(If Published)</small></label>
                            <div class="controls col-lg-8">
                                <input type="text" name="google_play_store" id="input-google-store-url" placeholder="Google Play Store URL" class="form-control" value="<?php if ((isset($data['google_play_store'])) && ($data['google_play_store'] != "")) { echo $data['google_play_store']; } ?>">
                            </div>
                        </div>

                        <div class="form-group hidden<?php echo ((!empty($errors)) && (in_array('other_purpose', $errors))) ? ' has-error' : ''; ?>" id="other-purpose">
                            <label class="control-label col-lg-4" for="input-other-purpose">Other Use  <small>(Please explain)</small><span class="required-field">*</span></label>
                            <div class="controls col-lg-8">
                                
                                <input type="text" name="other_purpose" id="input-other-purpose" placeholder="Other Use" class="form-control" value="<?php if ((isset($data['other_purpose'])) && ($data['other_purpose'] != "")) { echo $data['other_purpose']; } ?>">
                            </div>
                        </div>

                        <div class="terms-of-use checkbox<?php echo ((!empty($errors)) && (in_array('terms_of_use', $errors))) ? ' has-error' : ''; ?>">
                            <label>
                                <input type="checkbox" name="terms_of_use" value="true">
                                I agree to the <a href="/terms_of_use" target="_blank">Terms of Use</a><span class="required-field">*</span>&nbsp;&nbsp;(<a href="\files\pdfs\Joshua-Project-API-Terms-of-Use">PDF</a> <a href="\files\pdfs\joshua-project-API-terms-of-use.pdf"><span aria-hidden='true' class='glyphicon glyphicon-download-alt'></span></a>)
                            </label>
<?php if ((!empty($errors)) && (in_array('terms_of_use', $errors))) { ?>
                                <span class="help-block">You must accept the Terms of Use!</span>
<?php } ?>
                        </div>

                        <div class="g-recaptcha" data-sitekey="<?php echo $recaptchaSiteKey; ?>"></div>

                        <div class="form-group">
                            <div class="controls col-lg-12">
                                <button type="submit" class="btn btn-primary btn-block">Send request</button><br>
                                <a type="button" class="btn btn-link" href="/resend_activation_links">
                                    Resend Activation Links</a>
                            </div>
                        </div>
                        <span class="required-field">* = Required</span>
                    </fieldset>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'footer.html');
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_footer_js.html');
?>

        <script type="text/javascript">
            $(document).ready(function() {
                equalHeight($('div.beginner_panel div.panel-body'));
                $(window).resize(function() {
                    $('div.beginner_panel div.panel-body').height('auto');
                });
                $('li.home-nav').addClass('active');
                handleForm();
            });
            function equalHeight(group) {
                var tallest = 0;
                group.each(function() {
                    var thisHeight = $(this).height();
                    if(thisHeight > tallest) {
                        tallest = thisHeight;
                    }
                });
                group.height(tallest);
            };
            function handleForm() {
                // check if is checked then display
                var $website = $('input[data-tag="website"]');
                if ($website.is(':checked')) {
                    $('#form-website-url').removeClass('hidden');
                }
                $website.click(function() {
                    if ($(this).is(':checked')) {
                        $('#form-website-url').removeClass('hidden');
                    } else {
                        $('#form-website-url').addClass('hidden');
                    }
                });
                var $app = $('input[data-tag="app"]');
                if ($app.is(':checked')) {
                    $('#apple-store-url').removeClass('hidden');
                    $('#google-store-url').removeClass('hidden');
                }
                $app.click(function() {
                    if ($(this).is(':checked')) {
                        $('#apple-store-url').removeClass('hidden');
                        $('#google-store-url').removeClass('hidden');
                    } else {
                        $('#apple-store-url').addClass('hidden');
                        $('#google-store-url').addClass('hidden');
                    }
                });
                var $other = $('input[data-tag="other"]');
                if ($other.is(':checked')) {
                    $('#other-purpose').removeClass('hidden');
                }
                $other.click(function() {
                    if ($(this).is(':checked')) {
                        $('#other-purpose').removeClass('hidden');
                    } else {
                        $('#other-purpose').addClass('hidden');
                    }
                });
            };
        </script>
    </body>
</html>
