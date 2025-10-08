<?php
/**
 * @package    local_assessment
 * @author     Paul kenneth k
 */
 
require('../../config.php');
require_login();
require_once($CFG->dirroot . '/local/assessmentreport/lib.php');
 
$context = context_system::instance();
require_capability('moodle/site:config', $context);
//  $PAGE->set_pagelayout('embedded');
$PAGE->set_url(new moodle_url('/local/assessmentreport/index.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_assessment'));
// $PAGE->set_heading(get_string('pluginname', 'local_assessment'));
 
$hash = [
    'data' => []
];
 
echo $OUTPUT->header();
 
echo $OUTPUT->render_from_template('local_assessmentreport/index', $hash);
$PAGE->requires->js_call_amd('local_assessmentreport/index', 'init', []);
echo $OUTPUT->footer();