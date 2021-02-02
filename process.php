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
 * @package   local_course_templates
 * @copyright 2017 onwards, emeneo (www.emeneo.com)
 * @copyright 2021 TenTwentyFour (www.tentwentyfour.lu)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once dirname(__FILE__) . '/../../config.php';
require_once '../../course/externallib.php';

global $CFG, $DB;

require_login();
require_sesskey();

if (!defined('AJAX_SCRIPT')) {
    define('AJAX_SCRIPT', true);
}

$context = context_user::instance($USER->id);
require_capability('local/course_templates:view', $context);

// TODO: check option users further.
$fullname = optional_param('course_name', '', PARAM_RAW);
$shortname = optional_param('course_short_name', '', PARAM_RAW);
$categoryid = optional_param('cateid', 0, PARAM_INT);
$courseid = optional_param('cid', 0, PARAM_INT);
$options = [
    // ['name' => 'blocks', 'value' => 1],  // useless, because this is the default
    // ['name' => 'activities', 'value' => 1],  // useless, because this is the default
    // ['name' => 'filters', 'value' => 1], // useless, because this is the default
    // ['name' => 'users', 'value' => 1],  // The only change to the default setting
    // Note: I disabled the user setting override. If this should actually be used, hten
    // it should be made a parameter to the course template stepper form
    // and depend on the user having the appropriate perms.
    // See https://docs.moodle.org/310/en/Capabilities/moodle/backup:userinfo
    // What is weird is that although a user has the backup:userinfo perm,
    // setting this option will still fail with an "error/setting_locked_by_permission" exception
    // it's probably yet another permission.
];
$visible = 1;

$start_datetime = optional_param('start_datetime', '', PARAM_RAW);
$end_datetime = optional_param('end_datetime', '', PARAM_RAW);
$location = optional_param('location', '', PARAM_RAW);
$course_date = optional_param('course_date', '', PARAM_RAW);

if (!empty($start_datetime)) {
    $start_datetime = strtotime($course_date.' '.$start_datetime);
}

if (!empty($end_datetime)) {
    $end_datetime = strtotime($course_date.' '.$end_datetime);
}

if (!$fullname || !$shortname || !$categoryid || !$courseid) {
    exit(
        json_encode(
            [
                'status' => 2,
                'id' => $courseid,
                'cateid' => $categoryid
            ]
        )
    );
}

try {
    $externalObj = new core_course_external();
    $new_course = $externalObj->duplicate_course(
        $courseid,
        $fullname,
        $shortname,
        $categoryid,
        $visible,
        $options
    );

    if (isset($new_course['id'])) {
        $course = $DB->get_record('course', ['id' => $new_course['id']]);

        if (!empty($start_datetime)) {
            $course->startdate = $start_datetime;
            $course->enddate = $end_datetime;
            $DB->update_record('course', $course);
        }

        if (!empty($location)) {
            $event_option = $DB->get_record(
                'course_format_options',
                [
                    'courseid' => $course->id,
                    'format' => 'event',
                    'name'=>'location',
                ]
            );
            $event_option->value = $location;
            $DB->update_record('course_format_options', $event_option);
        }

        exit(
            json_encode([
                'status' => 1,
                'id' => $new_course['id'],
                'shortname' => $new_course['shortname'],
            ])
        );
    } else {
        exit(
            json_encode([
                'status' => 3,  // index.php only knows 1 or 2
                'error' => 0,
                'message' => 'Course duplication failed',
            ])
        );
    }
} catch (\Throwable $e) {
    exit(
        json_encode([
            'status' => 0,
            'error' => $e->getCode(),
            'message' => $e->getMessage(),
        ])
    );
}
