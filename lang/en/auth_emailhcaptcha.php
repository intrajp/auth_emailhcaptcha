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
 * Strings for component 'auth_emailhcaptcha', language 'en'.
 *
 * @package   auth_emailhcaptcha
 * @copyright 2020 Shintaro Fujiwara <shintaro.fujiwara@gmail.com> 
 * @copyright based on work by 1999 onwards Martin Dougiamas  {@link http://moodle.com} 
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_emailhcaptchadescription'] = '<p>Email-based self-registration with hcaptcha enables a user to create their own account via a \'Create new account\' button on the login page. The user then receives an email containing a secure link to a page where they can confirm their account. Future logins just check the username and password against the stored values in the Moodle database.</p><p>Note: In addition to enabling the plugin, email-based self-registration with hcaptcha must also be selected from the self registration drop-down menu on the \'Manage authentication\' page.</p>';
$string['auth_emailhcaptchanoemail'] = 'Tried to send you an email but failed!';
$string['auth_emailhcaptcha'] = 'Adds a visual/audio confirmation form element to the sign-up page for email self-registering users. This protects your site against spammers and contributes to a worthwhile cause. See https://hcaptcha for more details.';
$string['auth_emailhcaptcha_key'] = 'Enable hCAPTCHA element';
$string['auth_emailhcaptchasettings'] = 'Settings';
$string['pluginname'] = 'Email-based self-registration with hcaptcha';
$string['privacy:metadata'] = 'The Email-based self-registration authentication with hcaptcha plugin does not store any personal data.';
