<?php
require_once("$CFG->libdir/formslib.php");

class fdemandreg extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
        global $USER;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('header', 'header', 'Enter number of Students:');

        $mform->addElement('text', 'year', 'Planungsjahr');
        $mform->setType('year', PARAM_INT);

        $mform->addElement('text', 'wi_se', 'Software Engineering');
        $mform->setType('wi_se', PARAM_INT);

        $mform->addElement('text', 'wi_sc', 'Sales and Consulting');
        $mform->setType('wi_sc', PARAM_INT);

        $mform->addElement('text', 'wi_am', 'Application Management');
        $mform->setType('wi_am', PARAM_INT);

        $mform->addElement('text', 'wi_ds', 'Data Science');
        $mform->setType('wi_ds', PARAM_INT);

        $mform->addElement('text', 'wi_eg', 'E-Government');
        $mform->setType('wi_eg', PARAM_INT);

        $mform->addElement('text', 'wi_eh', 'E-Health');
        $mform->setType('wi_eh', PARAM_INT);

        $mform->addElement('text', 'wi_imbit', 'International Management for Business and Information Technology');
        $mform->setType('wi_imbit', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Submit');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
