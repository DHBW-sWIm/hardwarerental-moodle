<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class labRentalDetailForm extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** NAME *************/
        $mform->addElement('static', 'name', 'Student:');
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', 'Stemmi');

        /* ****************** STATUS *************/
        $mform->addElement('static', 'resource', 'Ressource:');
        $mform->setType('resource', PARAM_NOTAGS);
        $mform->setDefault('resource', 'iPhone 8');

        /* ****************** TYPE *************/
        $mform->addElement('static', 'date', 'Rückgabedatum:');
        $mform->setType('date', PARAM_NOTAGS);
        $mform->setDefault('date', '11.11.2011');

        /* ****************** TYPE *************/
        $mform->addElement('static', 'status', 'Status:');
        $mform->setType('status', PARAM_NOTAGS);
        $mform->setDefault('status', 'Angefragt');

        /* ****************** QUANTITY *************/
        $mform->addElement('static', 'reason', 'Grund:');
        $mform->setType('reason', PARAM_INT);
        $mform->setDefault('reason', 'Weil ichs brauch');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Bearbeiten');
        $mform->addElement('cancel', 'cancelBtn', 'Zurückgeben');

    }
    //Custom validation should be added here
    function validation($data, $files) {

        return array();
    }
}

