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


/**
 * @package    local_reporttab
 * @author     Paul kenneth k
 */

require('../../config.php');
// require_login();
// require_once($CFG->dirroot . '/local/assessment/lib.php');
$PAGE->set_pagelayout('embedded');
 
$logourl = $PAGE->theme->setting_file_url('logo', 'logo');
$signupUrl = $CFG->wwwroot . '/login/signup.php';
echo $OUTPUT->header();
$hash = [
    'pageTitle' => 'Registration Instructions',
    'logoUrl' => $logourl,
    'heading' => 'Registration Instructions',
    'dos' => [
        "Enter correct details – Make sure your first name, last name, and father’s name match your official documents.",
        "Provide an active email ID and Mobile number – Double-check before submitting. This will be used for all communication.",
        "Select your correct Batch as per HR instruction, Year of Passing, Degree, and Department from the dropdown lists.",
        "Enter accurate highest degree percentage – As per your academic record.",
        "Choose the correct option you want to appear for “Test On” Cybersecurity , AI/ML or Others.",
        "Answer truthfully for Current Backlog, Immediate Joiner, Offer in Hand, and Willingness to work in Chennai.",
        "Fill in Current City correctly – Use the city you are currently residing in.",
        "Review all entries before clicking Register."
    ],
    'donts' => [
        "Do not leave mandatory fields blank.",
        "Do not use nicknames or short forms – Always enter your full legal name.",
        "Avoid fake or inactive email IDs and phone numbers – You may miss important updates.",
        "Do not round off Marks percentage incorrectly – Enter as per mark sheet.",
        "Don’t give incorrect information for backlog, offer in hand, or willingness to join – This may disqualify you later.",
        "Do not use all caps or all lowercase – Use proper formatting (e.g., “Durai Raj. S”).",
        "Don’t refresh or close the form while filling – It may erase entered data.",
        "Avoid multiple registrations – Register only once with the same details."
    ],
    'registerLink' => $signupUrl,
    'registerButton' => 'Click here to Register'
];

echo $OUTPUT->render_from_template('local_assessment/index', $hash);
$PAGE->requires->js_call_amd('local_assessment/index', 'init');
echo $OUTPUT->footer();
