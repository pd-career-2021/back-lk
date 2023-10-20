<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Audience;

use App\Orchid\Layouts\Audience\AudienceEditLayout;
use App\Orchid\Layouts\Audience\AudienceListLayout;
use Illuminate\Http\Request;
use App\Models\Audience;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AudienceListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'audiences' => Audience::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Аудитория';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return '';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.systems.audiences.create'),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            AudienceListLayout::class,

            Layout::modal('asyncEditAudienceModal', AudienceEditLayout::class)
                ->async('asyncGetAudience'),
        ];
    }

    /**
     * @param Audience $audience
     *
     * @return array
     */
    public function asyncGetSocial(Audience $audience): iterable
    {
        return [
            'audience' => $audience,
        ];
    }

    /**
     * @param Request $request
     * @param Audience $audience
     */
    public function saveSocial(Request $request, Audience $audience): void
    {
        $audience->fill($request->input('audience'))->save();
 
        Toast::info("Социальная сеть была сохранена");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Audience::findOrFail($request->get('id'))->delete();

        Toast::info("Социальная сеть была удалена");
    }
}
