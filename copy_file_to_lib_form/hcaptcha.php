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
 * recaptcha type form element
 *
 * Contains HTML class for a hcaptcha type element
 *
 * @package   core_form
 * @copyright 2020 Shintaro Fujiwara <shintaro.fujiwara@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('HTML/QuickForm/input.php');
require_once('templatable_form_element.php');

/**
 * hcaptcha type form element
 *
 * HTML class for a hcaptcha type element
 *
 * @package   core_form
 * @category  form
 * @copyright 2020 Shintaro Fujiwara <shintaro.fujiwara@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MoodleQuickForm_hcaptcha extends HTML_QuickForm_input implements templatable {
    use templatable_form_element {
        export_for_template as export_for_template_base;
    }

    /** @var string html for help button, if empty then no help */
    var $_helpbutton='';

    /**
     * constructor
     *
     * @param string $elementName (optional) name of the hcaptcha element
     * @param string $elementLabel (optional) label for hcaptcha element
     * @param mixed $attributes (optional) Either a typical HTML attribute string
     *              or an associative array
     */
    public function __construct($elementName = null, $elementLabel = null, $attributes = null) {
        parent::__construct($elementName, $elementLabel, $attributes);
        $this->_type = 'hcaptcha';
    }

    /**
     * Old syntax of class constructor. Deprecated in PHP7.
     *
     * @deprecated since Moodle 3.1
     */
    public function MoodleQuickForm_hcaptcha($elementName = null, $elementLabel = null, $attributes = null) {
        debugging('Use of class name as constructor is deprecated', DEBUG_DEVELOPER);
        self::__construct($elementName, $elementLabel, $attributes);
    }

    /**
     * Returns the hCAPTCHA element in HTML
     *
     * @return string The HTML to render
     */
    public function toHtml() {
        global $CFG;
        require_once($CFG->dirroot . '/auth/emailhcaptcha/hcaptchalib.php');

        return hcaptcha_get_challenge_html(HCAPTCHA_API_URL, $CFG->hcaptchapublickey);
    }

    /**
     * get html for help button
     *
     * @return string html for help button
     */
    function getHelpButton(){
        return $this->_helpbutton;
    }

    /**
     * Checks hcaptcha response.
     *
     * @param string $responsestr
     * @return bool
     */
    public function verify($responsestr) {
        global $CFG;
        require_once($CFG->dirroot . '/auth/emailhcaptcha/hcaptchalib.php');

        $response = hcaptcha_check_response(HCAPTCHA_VERIFY_URL, $CFG->hcaptchaprivatekey,
                        $responsestr);
        if (!$response['isvalid'] == false) {
            return true;
        } else {
            $attributes = $this->getAttributes();
            $attributes['error_message'] = $response['error'];
            $this->setAttributes($attributes);
	    redirect('/login/signup.php');
        }
        return true;
    }

    public function export_for_template(renderer_base $output) {
        $context = $this->export_for_template_base($output);
        $context['html'] = $this->toHtml();
        return $context;
    }

    /**
     * Get force LTR option.
     *
     * @return bool
     */
    public function get_force_ltr() {
        return true;
    }

}
