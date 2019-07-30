<?php
require_once("$CFG->libdir/formslib.php");

class flecturehours extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('header', 'header', 'Add new Entry:');

        $mform->addElement('text', 'firstname', 'First name:');
        $mform->setType('firstname', PARAM_TEXT);
        $mform->addRule('firstname', 'Please enter a first name', 'required');

        $mform->addElement('text', 'surname', 'Surname:');
        $mform->setType('surname', PARAM_TEXT);
        $mform->addRule('surname', 'Please enter a surname', 'required');

        $mform->addElement('text', 'company', 'Company:');
        $mform->setType('company', PARAM_TEXT);
        $mform->addRule('company', 'Please enter a company', 'required');

        $mform->addElement('text', 'lhours', 'No. of lecture hours:');
        $mform->setType('lhours', PARAM_INT);

        $mform->addElement('text', 'exams', 'No. of exam supervisions:');
        $mform->setType('exams', PARAM_INT);

        $mform->addElement('text', 'papers', 'No. of academic papers:');
        $mform->setType('papers', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Submit');

    }

    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
