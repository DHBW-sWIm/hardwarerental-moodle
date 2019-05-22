<?php

class Resource{

    public $name;
    public $comment;
    public $description;
    public $category;
    public $resourcetype;
    public $quantity;
    public $id;
    public $serial;
    public $equipment;
    public $status;

    public function __construct($name, $comment, $description, $category, $resourcetype, $quantity, $id, $serial, $equipment, $status)
    {
        $this->name = $name;
        $this->comment = $comment;
        $this->description = $description;
        $this->category = $category;
        $this->resourcetype = $resourcetype;
        $this->quantity = $quantity;
        $this->id = $id;
        $this->serial = $serial;
        $this->equipment = $equipment;
        $this->status = $status;
    }

    public function request() {
        if($this->status == 'Verfügbar'){
            $this->status = 'Angefragt';
        }
    }
}