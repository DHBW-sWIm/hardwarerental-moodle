<?php
/**
 * Created by PhpStorm.
 * User: D067810
 * Date: 30.01.2019
 * Time: 16:02
 */

class camunda_var
{
    var $value;
    var $type;

    /**
     * CamundaVar constructor.
     * @param $value
     * @param $type
     */
    public function __construct($value, $type)
    {
        $this->value = $value;
        $this->type = $type;
    }
}