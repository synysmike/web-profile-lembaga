<?php

namespace App\Models\Concerns;

trait HasVisibility
{
    public function scopeVisible($query)
    {
        return $query->where($this->getTable().'.is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy($this->getTable().'.position')->orderBy($this->getTable().'.id');
    }
}
