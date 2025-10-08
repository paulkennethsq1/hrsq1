<?php

require('../config.php');
require_once($CFG->dirroot.'/user/lib.php');  // for user_create_user
require_once($CFG->libdir.'/formslib.php');
require_once('login_signup_form.php');

$PAGE->set_url(new moodle_url('/login/signup.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('login');

$form = new login_signup_form();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/'));
} else if ($data = $form->get_data()) {
    try {

        $firstname  = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", trim($data->firstname)));
        $lastname   = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", trim($data->lastname)));
        $fathername = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", trim($data->fathername)));

 
        $user = new stdClass();
        $user->username  = core_text::strtolower($firstname . $lastname . $fathername);
        $user->firstname = $firstname;
        $user->lastname  = $lastname;
        $user->email     = $data->email;
        $user->password  = $data->password;
        $user->auth      = 'manual';
        $user->confirmed = 1;
        $user->policyagreed = 1;
        $user->mnethostid = $CFG->mnet_localhost_id;
        $user->city = $data->city ?? '';
        $user->country = 'IN';
        $user->lang = 'en';
        $user->timezone = '99';
        

        $newuserid = user_create_user($user, false, true);

        $user = $DB->get_record('user', ['id' => $newuserid], '*', MUST_EXIST);

        $customuser = new stdClass();
        $customuser->id = $newuserid; 
        $customuser->batch  = $data->batch;
        $customuser->degree = $data->degree;
        $customuser->phone1 = $data->phone;
        $customuser->department = $data->department;
        $customuser->cgpa   = $data->cgpa;
        $customuser->yearofpassedout = $data->yop;
        $customuser->questiontype = $data->questionson;
        $customuser->relocate = $data->relocate;
        $customuser->backlog  = $data->backlog;
        $customuser->fathername  = $data->fathername;
        $customuser->immediatejoin = $data->immediate;
        $customuser->offerinhand = $data->offer;
        $customuser->collegename = $data->collegename;
        $customuser->gender = $data->gender;
        $customuser->city = $data->city ?? '';
        $customuser->country = 'IN';

        $result = $DB->update_record('user', $customuser);


        $courseidnumber = $data->questionson;
        $course = $DB->get_record('course', ['idnumber' => $courseidnumber], '*', MUST_EXIST);
        $courseid = $course->id;
        $enrol = enrol_get_plugin('manual');
  
        if ($enrol) {
            $instances = enrol_get_instances($courseid, true);
            foreach ($instances as $instance) {
                if ($instance->enrol === 'manual') {
                    $enrol->enrol_user($instance, $newuserid, 5); 
                    break;
                }
            }
        }


        $user = $DB->get_record('user', ['id' => $newuserid], '*', MUST_EXIST);

        complete_user_login($user);
        $quiz = $DB->get_record('quiz', ['course' => $courseid], '*', MUST_EXIST);

        // Get the course module (cm) for this quiz.
        $cm = get_coursemodule_from_instance('quiz', $quiz->id, $courseid, false, MUST_EXIST);

        // Correct URL: use cmid (not quiz id).
        $url = new moodle_url('/mod/quiz/view.php', ['id' => $cm->id]);

        redirect($url);


    } catch (Exception $e) {
        echo "Error creating user: " . $e->getMessage();
    }
} else {
    echo $OUTPUT->header();
    $form->display();
    echo $OUTPUT->footer();
}
