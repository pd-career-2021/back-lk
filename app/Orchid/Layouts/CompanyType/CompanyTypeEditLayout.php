<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CompanyType;

use App\Models\CompanyType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class CompanyTypeEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('company_type.title')
                ->type('text')
                ->max(45)
                ->required()
                ->title('Заголовок')
                ->placeholder('Заголовок'),
        ];
    }
}
