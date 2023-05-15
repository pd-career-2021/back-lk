<?php

declare(strict_types=1);

namespace App\Orchid\Screens\News;

use App\Orchid\Layouts\News\NewsEditLayout;
use App\Orchid\Layouts\News\NewsListLayout;
use Illuminate\Http\Request;
use App\Models\News;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class NewsListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'news' => News::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Новости';
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
        return [
            // 'platform.systems.events',
        ];
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
                ->route('platform.publications.news.create'),
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
            NewsListLayout::class,

            Layout::modal('asyncEditNewsModal', NewsEditLayout::class)
                ->async('asyncGetNews'),
        ];
    }

    /**
     * @param News $news
     *
     * @return array
     */
    public function asyncGetNews(News $news): iterable
    {
        $news->load('attachment');
        return [
            'news' => $news,
        ];
    }

    /**
     * @param Request $request
     * @param News $news
     */
    public function saveNews(Request $request, News $news): void
    {
        $news->fill($request->input('news'))->save();
 
        Toast::info("Новость была сохранена");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        News::findOrFail($request->get('id'))->delete();

        Toast::info("Новость была удалена");
    }
}
