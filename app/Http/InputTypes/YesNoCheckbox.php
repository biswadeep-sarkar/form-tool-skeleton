<?php

namespace App\Http\InputTypes;

use Biswadeep\FormTool\Core\InputTypes\CheckboxType;
use Biswadeep\FormTool\Core\InputTypes\Common\InputType;
use Biswadeep\FormTool\Core\InputTypes\Common\ICustomType;

class YesNoCheckbox extends CheckboxType implements ICustomType
{
    public int $type = InputType::Custom;

    public function getTableValue()
    {
        if ($this->value == $this->valueYes) {
            return '<span class="badge btn-success">'.  $this->captionYes .'</span>';
        } else {
            return '<span class="badge btn-danger">'. $this->captionNo .'</span>';
        }
    }
}