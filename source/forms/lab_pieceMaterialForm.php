<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class labPieceMaterialForm extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** ID *************/
        $mform->addElement('text', 'ident', 'Inventory Number:');
        $mform->setType('ident', PARAM_INT);

        /* ****************** SERIAL *************/
        $mform->addElement('text', 'serial', 'Serial Number:');
        $mform->setType('serial', PARAM_INT);

        /* ****************** NAME *************/
        $mform->addElement('text', 'equip', 'Configuration:');
        $mform->setType('equip', PARAM_NOTAGS);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Add');
        $mform->addElement('cancel', 'cancelBtn', 'Back');

    }
    //Custom validation should be added here
    function validation($data, $files) {

        return array();
    }
}

