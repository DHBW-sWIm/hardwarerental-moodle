<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class stdntAntragForm extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'ausleiher', 'Your Name:',$attributes='size=“100”');
        $mform->setType('ausleiher', PARAM_NOTAGS);

        $mform->addElement('text', 'matrikel', 'Matriculation Number:');
        $mform->setType('matrikel', PARAM_NOTAGS);

        $mform->addElement('text', 'mail', 'E-Mail:');
        $mform->setType('mail', PARAM_NOTAGS);

        $mform->addElement('text', 'grund', 'Reason:'); // Add elements to your form
        $mform->setType('grund', PARAM_NOTAGS);        //Default value

        $mform->addElement('date_selector', 'returnDate', 'Return Date:');

        $mform->addElement('text', 'anmerkung', 'Comment:');
        $mform->setType('anmerkung', PARAM_NOTAGS);


        $mform->addElement('select', 'assignee',
            'Responsible:', array('Prof. Martin', 'Prof. Koslowski', 'Gzuz', 'Tichy'));

        $mform->addElement('button', 'newResp', 'Add Responsible');


        // error_log("TEST FROM INSIDE FORM");

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'resourceid');
        $mform->setType('resourceid', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Request');
        $mform->addElement('cancel', 'cancelBtn', 'Back');

        // error_log("TEST FROM AFTER SUBMIT IN FORM");

    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
