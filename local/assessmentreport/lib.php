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
 * @package    local_assessment
 * @author     Paul kenneth k
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
function local_assessmentreport_get_user_reports($batch) {
    global $DB, $USER;
    try {
    // Example: $batch can be 0 (all) or a specific batch number
    $batch = isset($batch) ? $batch : 0;

    $whereBatch = '';
    $params = [];

    if ($batch != 0) { 
        $whereBatch = " AND u.batch = :batch";
        $params['batch'] = $batch;
    }

    $sql = "SELECT 
                u.id AS userid,  
                u.batch,
                CONCAT(u.firstname, ' ', u.lastname) AS username,
                u.email,
                u.phone1 AS phone,
                u.degree,
                u.department,
                u.cgpa,
                u.yearofpassedout,
                u.questiontype,
                u.relocate AS work_on_chennai,
                u.backlog,
                u.immediatejoin,
                u.offerinhand,
                u.city,
                qa_main.quiz AS quizid,
                u.timecreated,
                SUM(CASE WHEN qa.slot BETWEEN 1 AND 25 AND qas.fraction > 0 THEN 1 ELSE 0 END) AS correct25,
                SUM(CASE WHEN qa.slot BETWEEN 26 AND 80 AND qas.fraction > 0 THEN 1 ELSE 0 END) AS correct2665,
                SUM(CASE WHEN qas.fraction > 0 THEN 1 ELSE 0 END) AS total_correct
            FROM sq_user u
            JOIN sq_quiz_attempts qa_main 
                ON qa_main.userid = u.id
                AND qa_main.quiz IN (9, 10)
            JOIN sq_question_attempts qa 
                ON qa.questionusageid = qa_main.uniqueid
            JOIN sq_question_attempt_steps qas 
                ON qas.questionattemptid = qa.id
                AND qas.id = (
                    SELECT MAX(id) 
                    FROM sq_question_attempt_steps 
                    WHERE questionattemptid = qa.id
                )
            WHERE 1=1 $whereBatch AND u.id NOT IN (1,2)
            GROUP BY u.id, username, u.degree, u.department, u.cgpa, u.yearofpassedout, u.questiontype,
                    u.relocate, u.backlog, u.immediatejoin, u.city, qa_main.quiz
            ORDER BY username, qa_main.quiz";

    $records = $DB->get_records_sql($sql, $params);


        $cleaned = [];

        foreach ($records as $record) {
            $cleaned[] = [
                'username' => (string)$record->username,
                'batch' => (string)$record->batch,
                'email' => (string)$record->email,
                'phone' => (string)$record->phone,
                'degree' => (string)$record->degree,
                'department' => (string)$record->department,
                'cgpa' => (string)$record->cgpa,
                'yearofpassedout' => (string)$record->yearofpassedout,
                'questiontype' => (string)$record->questiontype,
                'work_on_chennai' => $record->work_on_chennai == 1 ? 'Yes' : 'No',
                'backlog'         => $record->backlog == 1 ? 'Yes' : 'No',
                'offerinhand'     => $record->offerinhand == 1 ? 'Yes' : 'No',
                'immediatejoin'   => $record->immediatejoin == 1 ? 'Yes' : 'No',
                'city' => (string)$record->city,
                'correct25' => (string)$record->correct25,
                'correct2665' => (string)$record->correct2665,
                'total_correct' => (string)$record->total_correct,
                'timecreated' => isset($record->timecreated) ? time_ago($record->timecreated) : null,
            ];
        }
        return ['data' => $cleaned];

    } catch (Exception $e) {
        return ['data' => []]; 
    }
}



function time_ago($timestamp) {
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 172800) {
        return 'Yesterday';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 31536000) {
        $months = floor($diff / 2592000);
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    } else {
        $years = floor($diff / 31536000);
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    }
}

