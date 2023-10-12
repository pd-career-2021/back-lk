<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Social;

use App\Orchid\Layouts\Social\SocialEditLayout;
use App\Orchid\Layouts\Social\SocialListLayout;
use Illuminate\Http\Request;
use App\Models\Social;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SocialListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'socials' => Social::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Социальные сети';
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
                ->route('platform.systems.socials.create'),
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
            SocialListLayout::class,

            Layout::modal('asyncEditSocialModal', SocialEditLayout::class)
                ->async('asyncGetSocial'),
        ];
    }

    /**
     * @param Social $social
     *
     * @return array
     */
    public function asyncGetSocial(Social $social): iterable
    {
        $social->load('attachment');

        return [
            'social' => $social,
        ];
    }

    /**
     * @param Request $request
     * @param Social $social
     */
    public function saveSocial(Request $request, Social $social): void
    {
        $social->fill($request->input('social'))->save();
 
        Toast::info("Социальная сеть была сохранена");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Social::findOrFail($request->get('id'))->delete();

        Toast::info("Социальная сеть была удалена");
    }
}
