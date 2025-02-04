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
 * Tool for sorting question categories in alphabetical order.
 *
 * @package    qbank_resortcategory
 * @copyright  2016 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../../config.php");
require_once("$CFG->dirroot/question/editlib.php");

use core_question\local\bank\helper as core_question_local_bank_helper;
use core_question\output\qbank_action_menu;
use qbank_resortcategory\question_category_object;
use qbank_resortcategory\resort_form;

require_login();
core_question_local_bank_helper::require_plugin_enabled('qbank_quickrenamecategories');

$cmid = optional_param('cmid', 0, PARAM_INT);
if ($cmid) {
    $pageparams = ['cmid' => $cmid];
    [$module, $cm] = get_module_from_cmid($cmid);
    require_login($cm->course, false, $cm);
    $PAGE->set_cm($cm);
    $context = context_module::instance($cmid);
} else {
    $courseid = required_param('courseid', PARAM_INT);
    $pageparams = ['courseid' => $courseid];
    $course = get_course($courseid);
    require_login($course);
    $PAGE->set_course($course);
    $context = context_course::instance($courseid);
}
require_capability('moodle/question:managecategory', $context);

$PAGE->set_pagelayout('admin');
$url = new moodle_url('/question/bank/resortcategory/resort.php', $pageparams);
$PAGE->set_url($url);
$PAGE->set_title(get_string('selectcategory', 'qbank_resortcategory'));
$PAGE->set_heading($COURSE->fullname);

$mform = new resort_form($url, ['context' => $context]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/question/bank/managecategories/category.php', $pageparams));
} else if ($data = $mform->get_data()) {
    require_sesskey();
    $qcobject = new question_category_object();
    $qcobject->resort_category($data->category);
    redirect(new moodle_url('/question/bank/managecategories/category.php', $pageparams));
}

echo $OUTPUT->header();
$renderer = $PAGE->get_renderer('core_question', 'bank');
$qbankaction = new qbank_action_menu($url);
echo $renderer->render($qbankaction);

echo $OUTPUT->heading(get_string('resortcategory', 'qbank_resortcategory'));
$mform->display();
echo $OUTPUT->footer();
