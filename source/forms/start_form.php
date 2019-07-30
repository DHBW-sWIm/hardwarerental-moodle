<?php
require_once("$CFG->libdir/formslib.php");

class start_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
