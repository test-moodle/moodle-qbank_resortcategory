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

namespace qbank_resortcategory;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use moodleform;
use core_question\local\bank\question_edit_contexts;

/**
 * Form for selection of category to resort.
 *
 * @package    qbank_resortcategory
 * @copyright  2016 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class resort_form extends moodleform {

    /**
     * Form definition.
     */
    protected function definition() {
        global $OUTPUT;

        $mform = $this->_form;

        $context = $this->_customdata['context'];

        $mform->addElement('header', 'header', get_string('selectcategory', 'qbank_resortcategory'));

        $message = $OUTPUT->box(get_string('selectcategoryinfo', 'qbank_resortcategory'), 'generalbox boxaligncenter');
        $mform->addElement('html', $message);

        $qcontexts = new question_edit_contexts($context);
        $contexts = $qcontexts->having_cap('moodle/question:managecategory');

        $options = [];
        $options['contexts'] = $contexts;
        $options['top'] = true;
        $qcategory = $mform->addElement('questioncategory', 'category', get_string('category', 'question'), $options);

        $this->add_action_buttons(true, get_string('resortthiscategory', 'qbank_resortcategory'));
    }

}
