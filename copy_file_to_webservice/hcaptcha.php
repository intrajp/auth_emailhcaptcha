<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * A script to display a hCaptcha for the site.
 *
 * hCaptcha is restricted by domain, so it cannot be displayed in mobile and desktop apps.
 *
 * This script will display and initialize the hCaptcha, setting some empty callbacks by default. The client can override
 * those Javascript callbacks (in the "window" object).
 *
 * This script won't work in mobile and desktop apps unless $CFG->allowframembedding is enabled.
 *
 * @package    core_webservice
 * @copyright  2020 Shintaro Fujiwara <shintaro.fujiwara@gmail.com> 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_MOODLE_COOKIES', true);

require_once(__DIR__ . '/../config.php');

$lang = optional_param('lang', '', PARAM_LANG);

$content = '';

// Check that hCAPTCHA is configured.
if (!empty($CFG->hcaptchapublickey) && !empty($CFG->hcaptchaprivatekey)) {
    require_once($CFG->dirroot . '/auth/emailhcaptcha/hcaptchalib.php');

    $apiurl = HCAPTCHA_API_URL;
    $pubkey = $CFG->hcaptchapublickey;

    $jscode = "
        // Create empty callbacks by default. They can be overridden by the client.
        var hcaptchacallback = function() {};
        var hcaptchaexpiredcallback = function() {};
        var hcaptchaerrorcallback = function() {};

        var hcaptchaloaded = function() {
            grecaptcha.render('hcaptcha_element', {
                'sitekey' : '$pubkey',
                'callback' : 'hcaptchacallback',
                'expired-callback' : 'hcaptchaexpiredcallback',
                'error-callback' : 'hcaptchaerrorcallback'
            });
        }";

    $lang = hcaptcha_lang($lang);

    $apicode = "\n<script type=\"text/javascript\" ";
    $apicode .= "src=\"$apiurl?onload=hcaptchaloaded&render=explicit&hl=$lang\" async defer>";
    $apicode .= "</script>\n";

    $content = html_writer::script($jscode, '');
    $content .= html_writer::div('', 'hcaptcha_element', array('id' => 'hcaptcha_element'));
    $content .= $apicode;
} else {
    // To use reCAPTCHA you must have an API key.
    require_once($CFG->libdir . '/filelib.php');
    send_header_404();
    print_error('cannotusepage2');
}

$output = <<<OET
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body style="margin:0; padding:0">
        $content
    </body>
</html>
OET;
echo $output;
