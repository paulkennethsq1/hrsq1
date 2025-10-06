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
 * @package   local_hippa_admin_report
 * @author    Paul kenneth k
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/local/assessmentreport/lib.php');

require_login();

class local_assessmentreport_external extends external_api {
    
    /**
     * Retrive the data for admin
     *
     * This function retrieves the hippa user deataild and the score
     * @author Paul kenneth K
     * @return external_function_parameters has returns the hippa admin parameters
     */
    public static function get_user_reports_parameters() {
        return new external_function_parameters([
          
        ]);
    }

    public static function get_user_reports() {
        return local_assessmentreport_get_user_reports();
    }

    public static function get_user_reports_returns() {
        return new external_single_structure([
            'data' => new external_multiple_structure(
                new external_single_structure([
                    'username' => new external_value(PARAM_TEXT, 'username'),
                    'email' => new external_value(PARAM_TEXT, 'User email'),
                    'batch' => new external_value(PARAM_TEXT, 'Batch', VALUE_OPTIONAL),
                    'degree' => new external_value(PARAM_TEXT, 'Degree', VALUE_OPTIONAL),
                    'department' => new external_value(PARAM_TEXT, 'Department', VALUE_OPTIONAL),
                    'cgpa' => new external_value(PARAM_TEXT, 'CGPA', VALUE_OPTIONAL),
                    'questiontype' => new external_value(PARAM_TEXT, 'Question Type', VALUE_OPTIONAL),
                    'work_on_chennai' => new external_value(PARAM_TEXT, 'Work on Chennai', VALUE_OPTIONAL),
                    'backlog' => new external_value(PARAM_TEXT, 'Backlog', VALUE_OPTIONAL),
                    'offerinhand' => new external_value(PARAM_TEXT, 'Offer in hand', VALUE_OPTIONAL),
                    'immediatejoin' => new external_value(PARAM_TEXT, 'Immediate Join', VALUE_OPTIONAL),
                    'city' => new external_value(PARAM_TEXT, 'city', VALUE_OPTIONAL),
                    'correct25' => new external_value(PARAM_TEXT, 'correct25', VALUE_OPTIONAL),
                    'correct2665' => new external_value(PARAM_TEXT, 'correct2665', VALUE_OPTIONAL),
                    'total_correct' => new external_value(PARAM_TEXT, 'total_correct', VALUE_OPTIONAL),
                    'timecreated' => new external_value(PARAM_TEXT, 'Last login timestamp', VALUE_OPTIONAL),
                ])
            )
        ]);
    }


}
