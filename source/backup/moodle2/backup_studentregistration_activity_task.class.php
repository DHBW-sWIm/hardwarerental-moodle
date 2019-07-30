<?php

/**
 * Defines backup_studentregistration_activity_task class
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/studentregistration/backup/moodle2/backup_studentregistration_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the studentregistration instance
 *
 * @package   mod_studentregistration
 * @category  backup
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_studentregistration_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the studentregistration.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_studentregistration_activity_structure_step('studentregistration_structure', 'studentregistration.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the list of studentregistrations.
        $search = '/(' . $base . '\/mod\/studentregistration\/index.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@studentregistrationINDEX*$2@$', $content);

        // Link to studentregistration view by moduleid.
        $search = '/(' . $base . '\/mod\/studentregistration\/view.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@studentregistrationVIEWBYID*$2@$', $content);

        return $content;
    }
}
