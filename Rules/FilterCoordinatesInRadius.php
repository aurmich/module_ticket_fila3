<?php

namespace Modules\Ticket\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Geo\Actions\FilterCoordinatesInRadius as CoordinatesFilter;
use Modules\Ticket\Models\Ticket;
use Webmozart\Assert\Assert;

class FilterCoordinatesInRadius implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        Assert::isArray($value);
        $coordinatesArray = Ticket::where('latitude', '!=', null)
                    ->where('longitude', '!=', null)
                    ->select('id', 'latitude', 'longitude')->get()->toArray();
        $ticket_vicini = app(CoordinatesFilter::class)->execute($value['lat'], $value['lng'], $coordinatesArray, 1);
        // dddx([$value['lat'], $value['lng'], $coordinatesArray, $ticket_vicini]);
        if (count($ticket_vicini) > 0) {
            $fail('Ci sono già '.(string) count($ticket_vicini).' ticket in questa posizione');
        }
    }
}