<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Employer;

use App\Models\CompanyType;
use App\Models\Industry;
use App\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class EmployerEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('employer.full_name')
                ->type('text')
                ->max(128)
                ->required()
                ->title('Полное наименование')
                ->placeholder('Полное наименование'),

            Input::make('employer.short_name')
                ->type('text')
                ->max(64)
                ->title('Краткое наименование')
                ->placeholder('Краткое наименование'),

            TextArea::make('employer.desc')
                ->rows(3)
                ->max(1000)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),

            Relation::make('employer.user_id')
                ->fromModel(User::class, 'id')
                ->displayAppend('fullName')
                ->required()
                ->title('Пользователь'),

            Relation::make('employer.company_type_id')
                ->fromModel(CompanyType::class, 'title')
                ->required()
                ->title('Тип компании'),

            Relation::make('employer.industry_ids.')
                ->fromModel(Industry::class, 'title')
                ->multiple()
                ->required()
                ->title('Отрасли')
                ->help("Выберите отрасли на которых специализируется компания"),

            Upload::make('employer.attachment')
                ->groups('photo')
                ->maxFiles(1)
                ->acceptedFiles('.jpg,.png,.jpeg,.gif,.svg')
                ->title('Изображение'),
        ];
    }
}
