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
 * @package   local_assessment
 * @author    paul kenneth
 */

// We defined the web service functions to install.
defined('MOODLE_INTERNAL') || die();
$functions = array(
    'local_assessment_get_user_list' => array(
        'classname' => 'local_assessment_external',
        'methodname' => 'get_user_reports',
        'classpath' => 'local/assessment/externallib.php',
        'description' => 'get_user_reports',
        'type' => 'write',
        'ajax' => true
    ),
);
// We define the services to install as pre-build services. A pre-build service is not editable by the administrator.
$services = array(
    'Local Admin Hippa Report' => array(
        'functions' => array(
            'local_assessment_get_user_list',
        ),
        'restrictedusers' => 0,
        'enabled' => 1,
    )
);
