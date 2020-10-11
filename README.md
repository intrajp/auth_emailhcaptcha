# auth_emailhcaptcha
Moodle plugin which enables to set hcaptcha.

First, you should do this.

This plugin does not override lib/authlib.php
So, please edit lib/authlib.php as below.

line 554
add these lines
========
    // added by intrajp
    function is_hcaptcha_enabled() {
        return false;
    }
    // end added by intrajp
========
line 986
add these lines
========
function signup_hcaptcha_enabled() {
    global $CFG;
    $authplugin = get_auth_plugin($CFG->registerauth);
    return !empty($CFG->hcaptchapublickey) && !empty($CFG->hcaptchaprivatekey) && $authplugin->is_captcha_enabled();
}
========

Also, please edit admin/settings/plugins.php as below.

line 119
add these lines
========
   // added by intrajp
    $setting = new admin_setting_configtext('hcaptchapublickey', new lang_string('hcaptchapublickey', 'admin'), new lang_string('confighcaptchapublickey', 'admin'), '', PARAM_NOTAGS);
    $setting->set_force_ltr(true);
    $temp->add($setting);
    $setting = new admin_setting_configtext('hcaptchaprivatekey', new lang_string('hcaptchaprivatekey', 'admin'), new lang_string('confighcaptchaprivatekey', 'admin'), '', PARAM_NOTAGS);
    $setting->set_force_ltr(true);
    $temp->add($setting);
    $ADMIN->add('authsettings', $temp);
    // end added by intrajp
========

Also, please edit lang/en/admin.php as below.

line 326
add these lines
========
//added by intrajp
$string['confighcaptchaprivatekey'] = 'String of characters (secret key) used to communicate between your Moodle server and the hcaptcha server. hCAPTCHA keys can be obtained from <a target="_blank" href="https://www.hcaptcha.com">hCAPTCHA</a>.';
$string['confighcaptchapublickey'] = 'String of characters (site key) used to display the hCAPTCHA element in the signup form. hCAPTCHA keys can be obtained from <a target="_blank" href="https://www.hcaptcha.com">hCAPTCHA</a>.';
//end added by intrajp
========

line 1042
add these lines
========
//added by intrajp
$string['hcaptchaprivatekey'] = 'hCAPTCHA secret key';
$string['hcaptchapublickey'] = 'hCAPTCHA site key';
//end added by intrajp
========

Also, please edit login/signup_form.php as below.

line 100
========
        // added by intrajp
        if (signup_hcaptcha_enabled()) {
            $mform->addElement('hcaptcha', 'hcaptcha_element', get_string('security_question', 'auth'));
            $mform->addHelpButton('hcaptcha_element', 'hcaptcha', 'auth');
            $mform->closeHeaderBefore('hcaptcha_element');
        }
        // end added by intrajp
========

line 157
========
        // added by intrajp
        if (signup_hcaptcha_enabled()) {
            // do nothing
        }
        // end added by intrajp
========

Also, please edit lib/formslib.php as below.

line 3385
========
// added by intrajp
MoodleQuickForm::registerElementType('hcaptcha', "$CFG->libdir/form/hcaptcha.php", 'MoodleQuickForm_hcaptcha');
// end added by intrajp
========

Next, create these 3 files.

# cp lib/form/recaptcha.php lib/for/hcaptcha.php
# cp webservice/recaptcha.php webservice/hcaptcha.php
Edit lines as it looks hcaptcha 

# cp lib/recaptchalib_v2.php auth/emailhcaptcha/hcaptchalib.php
Edit line 57,58 as it looks hcaptcha
