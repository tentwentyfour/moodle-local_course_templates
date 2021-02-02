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
 * @see       https://docs.moodle.org/dev/version.php
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_course_templates';
$plugin->cron = 0;
$plugin->version  = 2021020203;
$plugin->requires = 2019052000;
$plugin->maturity = MATURITY_STABLE;
$plugin->release = 'Course templates plugin Version 3.7-b';
$plugin->supported = [37, 310];
