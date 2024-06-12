<?php

namespace App\Traits;


use App\Helpers\MasterHelper;

trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)) {
            return MasterHelper::decrypt($value);
        }
        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = MasterHelper::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
}
