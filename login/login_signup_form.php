<?php
defined('MOODLE_INTERNAL') || die();
define('NO_MOODLE_COOKIES', true);

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');


class login_signup_form extends moodleform {

    function definition() {
        global $CFG ,$PAGE;
        $mform = $this->_form;

        $logourl = $PAGE->theme->setting_file_url('logo', 'logo');

        $mform->addElement('html', '
		<style>
        /* Hide required asterisks for this form only */
        .fitem .req {
            display: none !important;
        }
        .fitem .req:before {
            display: none !important;
        }
	/* Hide red exclamation icons for this form only */
        .fa-circle-exclamation {
            display: none !important;
        }
        .icon.fa.fa-circle-exclamation {
            display: none !important;
        }
        </style>
        <div class="container pt-5 text-center" style="background-color: #e6d9fc;">
            <div class="mb-3">
                <img src="'.$logourl.'" alt="Site Logo" class="img-fluid" style="max-width: 150px;">
            </div>
            <div class="card shadow-sm p-4" style="background-color: #e6d9fc;">
                <h2 class="text-center mb-4">Student Registration</h2>
        ');

        $mform->addElement('html', '<div class="row g-3">');

        $mform->addElement('html', '<div class="col-md-6">');

        $this->add_select_row($mform, 'batch', 'Batch', array_combine(range(1,10), range(1,10)), 'Please select batch');
        $this->add_text_row($mform, 'firstname', 'First Name', 'Please enter your firstname');
        $this->add_text_row($mform, 'lastname', 'Last Name', 'Please enter your lastname');
        $this->add_text_row($mform, 'fathername', 'Father Name', 'Please enter your Father name');
        $this->add_text_row($mform, 'email', 'Email', 'Please enter a valid email', PARAM_EMAIL);
        $this->add_text_row($mform, 'phone', 'Mobile Number', 'Mobile number required', PARAM_INT);

        $genderoptions = [
            '' => 'Please select',
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Pefer not to say',

        ];
        $this->add_select_row($mform, 'gender', 'Gender', $genderoptions, 'Select gender', ['class' => 'form-control w-100']);

        $this->add_text_row($mform, 'collegename', 'College Name', 'College name is  required', PARAM_INT);

        $degreeoptions = [
            '' => 'Please select',
            'BE' => 'BE',
            'ME' => 'ME',
            'B.Tech' => 'B.Tech',
            'M.Tech' => 'M.Tech',
            'BSc' => 'BSc',
            'MSc' => 'MSc',
            'MCA' => 'MCA',
            'BCA' => 'BCA',
            'Others' => 'Others'
        ];
        $this->add_select_row($mform, 'degree', 'Degree', $degreeoptions, 'Select degree', ['class' => 'form-control w-100']);

        $mform->addElement('html', '</div>'); 

        $mform->addElement('html', '<div class="col-md-6">');

        

        $departmentoptions = [
            '' => 'Please select',
            'Cyber Security' => 'Cyber Security',
            'aiml' => 'AI/ML',
            'Others' => 'Others'
        ];
        $this->add_select_row($mform, 'department', 'Department', $departmentoptions, 'Select department');

        $passingoptions = [
            '' => 'Please select',
            '2023' => '2023',
            '2024' => '2024',
            '2025' => '2025',
            '2026' => '2026',
           
        ];
        $this->add_select_row($mform, 'yop', 'Year of Passing', $passingoptions, 'Select an option');

        $this->add_text_row($mform, 'cgpa', 'Highest Degree Percentage', 'Enter CGPA', PARAM_FLOAT);

        $questionoptions = [
            '' => 'Please select',
            '601' => 'Cyber security',
            '602' => 'AI/ML',
            '603' => 'Others'
        ];
        $this->add_select_row($mform, 'questionson', 'Questions On', $questionoptions, 'Select an option');


        $this->add_radio_row($mform, 'backlog', 'Current Backlog');
        $this->add_radio_row($mform, 'immediate', 'Immediate Joiner');
        $this->add_radio_row($mform, 'offer', 'Offer in hand');
        $this->add_radio_row($mform, 'relocate', 'Willing to work in Chennai');

        $this->add_text_row($mform, 'city', 'Current City', 'Enter current city');

        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');

        $mform->addElement('hidden', 'password', 'Demouser@sq1');
        $mform->setType('password', PARAM_RAW);

        $this->add_action_buttons(false, 'Register');

        $mform->addElement('html', '</div></div>'); 
    }


    private function add_text_row($mform, $name, $label, $error='', $type=PARAM_TEXT) {
        $mform->addElement('html', '<div class="row mb-3 align-items-start">');
        $mform->addElement('html', '<label class="col-4 col-form-label text-start" for="'.$name.'"><strong>'.$label.'</strong></label>');
        $mform->addElement('html', '<div class="col-8">');
        
        $mform->addElement('text', $name);
        $mform->setType($name, $type);

        // Align validation error from start
        $mform->addElement('html', '<div class="text-start" id="'.$name.'_error"></div>');

        if ($error) {
            $mform->addRule($name, $error, 'required', null, 'client');
        }

        $mform->addElement('html', '</div></div>');
    }


    private function add_select_row($mform, $name, $label, $options, $error='') {
        $mform->addElement('html', '<div class="row mb-3 align-items-start">');

        $mform->addElement('html', '<label class="col-4 col-form-label text-start" for="'.$name.'"><strong>'.$label.'</strong></label>');


        $mform->addElement('html', '<div class="col-8">');
        $attributes = [
            'class' => 'form-select', 
            'id' => $name, 
            'style' => 'width:78%;'
        ];
        $mform->addElement('select', $name, '', $options, $attributes);
        $mform->setType($name, PARAM_TEXT);

        if ($error) {
            $mform->addRule($name, $error, 'required', null, 'client');
        }

        $mform->addElement('html', '</div></div>'); 
    }



    private function add_radio_row($mform, $name, $label) {
        $mform->addElement('html', '<div class="row mb-5 mt-5">');
            $mform->addElement('html', '<label class="col-6 text-start"><strong>'.$label.'</strong></label>');
            $mform->addElement('html', '<div class="col-6 d-flex flex-row">');

            // Moodle radios with Bootstrap classes
            $mform->addElement('radio', $name, '', 'Yes', 1);
            $mform->addElement('radio', $name, '', 'No', 0);

            $mform->setType($name, PARAM_INT);

            $mform->addElement('html', '</div></div>');
    }



    function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);

        if (!preg_match('/^[a-zA-Z]+$/', $data['firstname'])) {
            $errors['firstname'] = 'Firstname must contain only letters';
        }

        if (!preg_match('/^[a-zA-Z]+$/', $data['lastname'])) {
            $errors['lastname'] = 'Lastname must contain only letters';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } else if ($DB->record_exists('user', ['email' => $data['email']])) {
            $errors['email'] = 'This email is already registered';
        }

        if (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $errors['phone'] = 'Enter 10 digit valid number';
        } else if ($DB->record_exists('user', ['phone1' => $data['phone']])) {
            $errors['phone'] = 'This phone number is already registered';
        }

        if (!preg_match('/^\d{4}$/', $data['yop'])) {
            $errors['yop'] = 'Year of Passing must be 4 digits';
        }

        if (!is_numeric($data['cgpa'])) {
            $errors['cgpa'] = 'CGPA must be a number';
        } else if ($data['cgpa'] < 50 || $data['cgpa'] > 100) {
            $errors['cgpa'] = 'CGPA must be between 50 and 100 and no deciaml values allowed';
        }

        if (empty($data['city'])) {
            $errors['city'] = 'City is required';
        } else if (!preg_match('/^[a-zA-Z\s]+$/', $data['city'])) {
            $errors['city'] = 'City can only contain letters and spaces';
        }


        return $errors;
    }

}

