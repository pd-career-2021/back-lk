<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title("Система"),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),

            Menu::make("Факультеты")
                ->icon('graduation')
                ->route('platform.systems.faculties'),

            Menu::make("Социальные сети")
                ->icon('social-vkontakte')
                ->route('platform.systems.socials'),

            Menu::make("Вакансии")
                ->icon('docs')
                ->route('platform.employment.vacancies')
                ->title("Трудоустройство"),

            Menu::make("Отклики")
                ->icon('action-undo')
                ->route('platform.employment.applications'),

            Menu::make("Мероприятия")
                ->icon('calendar')
                ->route('platform.publications.events')
                ->title("Публикации"),

            Menu::make("Новости")
                ->icon('text-left')
                ->route('platform.publications.news'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users'))
                ->addPermission('platform.systems.faculties', "Факультеты"),
            ItemPermission::group('Трудоустройство')
                ->addPermission('platform.employment.vacancies', "Вакансии")
                ->addPermission('platform.employment.applications', 'Отклики'),
        ];
    }
}
