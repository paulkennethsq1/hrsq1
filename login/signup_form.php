<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once('lib.php');

class login_signup_form extends moodleform implements renderable, templatable {

    function definition() {
        global $CFG;

        $mform = $this->_form;

        // Start Bootstrap card
        $mform->addElement('html', '
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-lg rounded-3">
                            <div class="card-header text-center bg-primary text-white">
                                <h4>Create Account</h4>
                            </div>
                            <div class="card-body">
        ');

        // Batch
        $batchoptions = ['' => 'Please select'] + array_combine(range(1,10), range(1,10));
        $mform->addElement('select', 'batch', 'Batch', $batchoptions);
        $mform->setType('batch', PARAM_TEXT);
        $mform->addRule('batch', 'Please select the correct batch', 'required', null, 'client');
        $mform->setDefault('batch', '');

        // Name
        $mform->addElement('text', 'username', 'Name', 'maxlength="100" size="12" autocapitalize="none"');
        $mform->setType('username', PARAM_RAW);
        $mform->addRule('username', get_string('missingusername'), 'required', null, 'client');

        // Default password (hidden)
        $mform->addElement('hidden', 'password', 'Demouser@sq1');
        $mform->setType('password', PARAM_RAW);

        $mform->addElement('hidden', 'sesskey', sesskey());
        $mform->setType('sesskey', PARAM_RAW);


        // Email
        $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="25"');
        $mform->setType('email', core_user::get_property_type('email'));
        $mform->addRule('email', get_string('missingemail'), 'required', null, 'client');
        $mform->addRule('email', get_string('invalidemail'), 'email', null, 'client');
        $mform->setForceLtr('email');

        // Phone
        $mform->addElement('text', 'phone', 'Mobile Number', 'maxlength="100" size="12" autocapitalize="none"');
        $mform->setType('phone', PARAM_RAW);
        $mform->addRule('phone', 'Mobile number missing', 'required', null, 'client');

        // Degree
        $degreeoptions = [
            '' => 'Please select',
            '1' => 'BE/ME B.Tech or M.Tech',
            '2' => 'BSc or MSc',
            '3' => 'MCA or BCA',
            '4' => 'Others'
        ];
        $mform->addElement('select', 'Degree', 'Degree', $degreeoptions);
        $mform->setType('Degree', PARAM_TEXT);
        $mform->addRule('Degree', 'Please select a valid Degree', 'required', null, 'client');
        $mform->setDefault('Degree', '');

        // Department
        $departmentoptions = [
            '' => 'Please select',
            '1' => 'Cyber security',
            '2' => 'Others'
        ];
        $mform->addElement('select', 'Department', 'Department', $departmentoptions);
        $mform->setType('Department', PARAM_TEXT);
        $mform->addRule('Department', 'Please select a valid Department', 'required', null, 'client');
        $mform->setDefault('Department', '');

        // Year of passing
        $mform->addElement('text', 'YOP', 'Year of passing', 'maxlength="4" size="12" autocapitalize="none"');
        $mform->setType('YOP', PARAM_INT);
        $mform->addRule('YOP', 'Year of passing is required', 'required', null, 'client');

        // CGPA
        $mform->addElement('text', 'cgpa', 'CGPA', 'maxlength="5" size="12"');
        $mform->setType('cgpa', PARAM_RAW);
        $mform->addRule('cgpa', 'CGPA is required', 'required', null, 'client');

        // Questions On
        $questionoptions = [
            '' => 'Please select',
            '1997' => 'Cyber security',
            '1998' => 'Data science',
            '1999' => 'Others'
        ];
        $mform->addElement('select', 'questionson', 'Questions On', $questionoptions);
        $mform->setType('questionson', PARAM_TEXT);
        $mform->addRule('questionson', 'Please select any value', 'required', null, 'client');
        $mform->setDefault('questionson', '');

        // ✅ Radio buttons
        $this->add_radio($mform, 'relocate', 'Willing to work in Chennai', 1);
        $this->add_radio($mform, 'backlog', 'Current Backlog', 1);
        $this->add_radio($mform, 'immediate', 'Immediate Joiner', 1);
        $this->add_radio($mform, 'offer', 'Offer in hand', 1);

        // City
        $mform->addElement('text', 'city', 'Current City', 'maxlength="120" size="20"');
        $mform->setType('city', core_user::get_property_type('city'));
        if (!empty($CFG->defaultcity)) {
            $mform->setDefault('city', $CFG->defaultcity);
        }

        // Profile fields
        profile_signup_fields($mform);

        // CAPTCHA if enabled

// var_dump($mform);die;
        // Plugin hook
        core_login_extend_signup_form($mform);

        // Site policy checkbox
        $manager = new \core_privacy\local\sitepolicy\manager();
        $manager->signup_form($mform);

        // Submit buttons
        $this->set_display_vertical();
        $this->add_action_buttons(true, get_string('createaccount'));

        // Close HTML
        $mform->addElement('html', '
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ');
    }

    // Trim fields after data
    function definition_after_data(){
        $mform = $this->_form;
        $mform->applyFilter('username', 'trim');
        foreach (useredit_get_required_name_fields() as $field) {
            $mform->applyFilter($field, 'trim');
        }
    }

    // Validation
    public function validation($data, $files) {

        $errors = parent::validation($data, $files);
        $errors = array_merge($errors, core_login_validate_extend_signup_form($data));

        if (signup_captcha_enabled()) {
            $recaptchaelement = $this->_form->getElement('recaptcha_element');
            if (!empty($this->_form->_submitValues['g-recaptcha-response'])) {
                $response = $this->_form->_submitValues['g-recaptcha-response'];
                if (!$recaptchaelement->verify($response)) {
                    $errors['recaptcha_element'] = get_string('incorrectpleasetryagain', 'auth');
                }
            } else {
                $errors['recaptcha_element'] = get_string('missingrecaptchachallengefield');
            }
        }

        $errors += signup_validate_data($data, $files);

        return $errors;
    }

    // Mustache export
    public function export_for_template(renderer_base $output) {
        ob_start();
        $this->display();
        $formhtml = ob_get_contents();
        ob_end_clean();
        return ['formhtml' => $formhtml];
    }

    // Helper to add Yes/No radio buttons
    private function add_radio($mform, $name, $label, $default = 1) {
        $mform->addElement('radio', $name, $label, 'Yes', 1);
        $mform->addElement('radio', $name, '', 'No', 0);
        $mform->setType($name, PARAM_INT);
        $mform->addRule($name, 'Please select Yes or No', 'required', null, 'client');
        $mform->setDefault($name, $default);
    }
}
