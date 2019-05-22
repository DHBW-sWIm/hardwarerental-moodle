<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class labNewResourceForm extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** NAME *************/
		$mform->addElement('text', 'name', 'Name:');
		$mform->setType('name', PARAM_NOTAGS);

        //Dropwdown-Menü wird erstellt, um Hauptkategorie auszuwählen
        $mform->addElement('select', 'category',
            'Category:', array('Smartphone', 'Tablet', 'Laptop', 'Computer','Software', 'Printer'));

        $mform->addElement('button', 'btn1', 'Add Category');

        //Radiobuton um Stückgut oder Schüttgut auszuwählen
        $mform->addElement('select', 'resourcetype',
            'Type:', array('Piece Material', 'Bulk Material'));

        $mform->addElement('select', 'type',
            'Manufacturer:', array('Apple', 'Samsung', 'Huawei', 'Xiaomi', 'Dell', 'Lenovo', 'Asus'));

        $mform->addElement('button', 'btn2', 'New Manufacturer');

        $mform->addElement('text', 'description', 'Description:');
        $mform->setType('description', PARAM_NOTAGS);

        $mform->addElement('text', 'quantity', 'Quantity:');
        $mform->setType('quantity', PARAM_INT);

        $mform->addElement('text', 'comment', 'Comment:');
        $mform->setType('comment', PARAM_NOTAGS);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Next');
        $mform->addElement('cancel', 'cancelBtn', 'Cancel');

    }
    //Custom validation should be added here
    function validation($data, $files) {
   
        return array();
    }
}

