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
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see       https://docs.moodle.org/dev/Admin_settings
 */

defined('MOODLE_INTERNAL') || die;

$systemcontext = context_system::instance();
$capabilities = [
    'local/course_templates:use',
];

if ($hassiteconfig or has_any_capability($capabilities, $systemcontext)) {

    // Adds an entry to the "Courses" section in SiteAdministration
    $ADMIN->add('courses',
        new admin_externalpage(
            'local_course_templates',
            get_string('addcourse', 'local_course_templates'),
            new moodle_url('/local/course_templates/index.php'),
            ['local/course_templates:use']
        )
    );

    // Course template settings
    // Setting for this plug-in. Found at /admin/settings.php?section=local_course_templates_settings
    $settings = new admin_settingpage(
        'local_course_templates_settings',
        get_string('pluginname', 'local_course_templates')
    );

    if ($ADMIN->fulltree) {
        $options = [
            1 => get_string('jumpto_coursepage', 'local_course_templates'),
            2 => get_string('jumpto_coursesettingspage', 'local_course_templates')
        ];
        $settings->add(
            new admin_setting_configselect(
                'local_course_templates/jump_to',
                get_string('jumpto', 'local_course_templates'),
                '',
                1,
                $options
            )
        );
    }

    // the call below makes moodle complain that the parent (??) does not exist. Doesn't make sense though, because
    // localplugins is added before plugins are even loaded… in admin/settings/plugins.php
    // if (!$ADMIN->locate('localplugins')) {
    //     $ADMIN->add('courses', new admin_category('backups', new lang_string('backups', 'admin')));
    // }

    // $ADMIN->add(
    //     'localplugins',
    //     new admin_category(
    //         'local_course_templates_settings_category',
    //         get_string('pluginname', 'local_course_templates')
    //     )
    // );

    $ADMIN->add('localplugins', $settings);
    // $ADMIN->add('local_course_templates_settings_category', $settings);
}
