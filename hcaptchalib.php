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
 * @package auth_emailhcaptcha 
 * @copyright 2020 Shintaro Fujiwara <shintaro.fujiwara@gmail.com> 
 * @copyright based on work by 2018 Jeff Webster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The hCAPTCHA URL's
 */
define('HCAPTCHA_API_URL', 'https://hcaptcha.com/1/api.js');
define('HCAPTCHA_VERIFY_URL', 'https://hcaptcha.com/siteverify');

/**
 ** Returns the language code the hCAPTCHA element should use.
 **
 ** @param string $lang Language to use. If not provided, get current language.
 ** @return string A language code
 **/
function hcaptcha_lang($lang = null) {

    if (empty($lang)) {
        $lang = current_language();
    }

    $glang = $lang;
    switch ($glang) {
        case 'en':
            $glang = 'en-GB';
            break;
        case 'en_us':
            $glang = 'en';
            break;
        case 'zh_cn':
            $glang = 'zh-CN';
            break;
        case 'zh_tw':
            $glang = 'zh-TW';
            break;
        case 'fr_ca':
            $glang = 'fr-CA';
            break;
        case 'pt_br':
            $glang = 'pt-BR';
            break;
        case 'he':
            $glang = 'iw';
            break;
    }
    // For any language code that didn't change reduce down to the base language.
    if (($lang === $glang) and (strpos($lang, '_') !== false)) {
        list($glang, $trash) = explode('_', $lang, 2);
    }
    return $glang;
}

/**
 * Gets the challenge HTML
 * This is called from the browser, and the resulting hCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 *
 * @param string $apiurl URL for hCAPTCHA API
 * @param string $pubkey The public key for hCAPTCHA
 * @param string $lang Language to use. If not provided, get current language.
 * @return string - The HTML to be embedded in the user's form.
 */
function hcaptcha_get_challenge_html($apiurl, $pubkey, $lang = null) {
    global $CFG, $PAGE;

    // To use hCAPTCHA you must have an API key.
    if ($pubkey === null || $pubkey === '') {
        return get_string('gethcaptchaapi', 'auth_emailhcaptcha');
    }

    $jscode = "
        var hcaptchacallback = function() {
            hcaptcha.render('hcaptcha_element', {
              'sitekey' : '$pubkey'
            });
        }";

    $lang = hcaptcha_lang($lang);
    $apicode = "\n<script type=\"text/javascript\" ";
    $apicode .= "src=\"$apiurl?onload=hcaptchacallback&render=explicit&hl=$lang\" async defer>";
    $apicode .= "</script>\n";

    $return = html_writer::script($jscode, '');
    $return .= html_writer::div('', 'hcaptcha_element', array('id' => 'hcaptcha_element'));
    $return .= $apicode;

    return $return;
}

/**
 * Calls an HTTP POST function to verify if the user's response was correct
 *
 * @param string $verifyurl URL for hCAPTCHA verification
 * @param string $privkey The private key for hCAPTCHA
 * @param string $response The response from hCAPTCHA
 * @return hCaptchaResponse
 */
function hcaptcha_check_response($verifyurl, $privkey, $response) {

    global $CFG;
    require_once($CFG->libdir.'/filelib.php');

    // Check response - isvalid boolean, error string.
    $checkresponse = array('isvalid' => false, 'error' => 'check-not-started');

    // To use hCAPTCHA you must have an API key.
    if ($privkey === null || $privkey === '') {
        $checkresponse['isvalid'] = false;
        $checkresponse['error'] = 'no-apikey';
        return $checkresponse;
    }

    // Discard spam submissions.
    if ($response === null || strlen($response) === 0) {
        $checkresponse['isvalid'] = false;
        $checkresponse['error'] = 'incorrect-captcha-sol';
        return $checkresponse;
    }

    // now the check has passwd
    $checkresponse['isvalid'] = true;
    $checkresponse['error'] = NULL;

    return $checkresponse;
}
