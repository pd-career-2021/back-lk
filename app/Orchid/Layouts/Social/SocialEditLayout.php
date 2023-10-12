<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Social;

use App\Models\Employer;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class SocialEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('social.name')
                ->type('text')
                ->max(45)
                ->required()
                ->title('Название')
                ->placeholder('Название'),

            Input::make('social.link')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),

            Relation::make('social.employer_id')
                ->fromModel(Employer::class, 'full_name')
                ->title('Работодатель')
        ];
    }
}
