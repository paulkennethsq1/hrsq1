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
 * @package   local_assessmentreport
 * @author    paul kenneth
 */

// We defined the web service functions to install.
defined('MOODLE_INTERNAL') || die();
$functions = array(
    'local_assessmentreport_get_user_reports' => array(
        'classname'   => 'local_assessmentreport_external',   // The class in externallib.php
        'methodname'  => 'get_user_reports',                  // The method in that class
        'classpath'   => 'local/assessmentreport/externallib.php',
        'description' => 'Get all user reports',
        'type'        => 'read',   // or 'write' if it modifies data
        'ajax'        => true
    ),
);

// Define web services to install automatically.
$services = array(
    'Local Assessment report' => array(
        'functions' => array(
            'local_assessmentreport_get_user_reports',
        ),
        'restrictedusers' => 0,  // 0 = any authorized user can access
        'enabled' => 1,          // Service is enabled
    )
);
