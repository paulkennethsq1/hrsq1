<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade script for local_corechanges.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_corechanges_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
    $table = new xmldb_table('user');

    if ($oldversion < 2025091901) {  // <-- bump version.php to this number

        // degree
        $field = new xmldb_field('degree', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'id');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // batch
        $field = new xmldb_field('batch', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'degree');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // department
        $field = new xmldb_field('department', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'batch');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // CGPA
        $field = new xmldb_field('cgpa', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'department');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // year of passed out
        $field = new xmldb_field('yearofpassedout', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'cgpa');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // question type
        $field = new xmldb_field('questiontype', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'yearofpassedout');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // relocate
        $field = new xmldb_field('relocate', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'questiontype');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // backlog
        $field = new xmldb_field('backlog', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'relocate');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // immediate join
        $field = new xmldb_field('immediatejoin', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'backlog');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // offer in hand
        $field = new xmldb_field('offerinhand', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'immediatejoin');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Mark upgrade savepoint
        upgrade_plugin_savepoint(true, 2025091901, 'local', 'corechanges');
    }

    return true;
}
