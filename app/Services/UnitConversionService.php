<?php

namespace App\Services;

use App\Models\Unit;

class UnitConversionService
{
    public function convert($cant, $fromUnitId, $toUnitId)
    {
        if ($fromUnitId === $toUnitId) 
            return $cant;
        
        $fromBaseUnit = $this->getBaseUnitId($fromUnitId);
        $toBaseUnit = $this->getBaseUnitId($toUnitId);

        return $fromBaseUnit[0] == $toBaseUnit[0] ? $cant*$fromBaseUnit[1]/$toBaseUnit[1] : 0;
    }

    protected function getBaseUnitId($unitId)
    {
        $unit = Unit::find($unitId);
        $factor = $unit->factor;

        while ($unit->unit_id != $unit->id)
        {
            $unit = Unit::find($unit->unit_id);
            $factor *= $unit->factor;
        }

        return [$unit->id, $factor];
    }
}