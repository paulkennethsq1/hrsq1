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

// Set layout to embedded to remove navbar
$PAGE->set_pagelayout('embedded');

$logourl = $PAGE->theme->setting_file_url('logo', 'logo');
$signupUrl = $CFG->wwwroot . '/login/signup.php';
echo $OUTPUT->header();
$hash = [
    'heading' => 'Campus Assessment',
    'logoUrl' => $logourl,

];

echo $OUTPUT->render_from_template('local_assessment/welcome', $hash);
$PAGE->requires->js_call_amd('local_assessment/welcome', 'init');
echo $OUTPUT->footer();