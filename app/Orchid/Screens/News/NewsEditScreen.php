<?php

declare(strict_types=1);

namespace App\Orchid\Screens\News;

use App\Models\News;
use App\Orchid\Layouts\News\NewsEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class NewsEditScreen extends Screen
{
    /**
     * @var News
     */
    public $news;

    /**
     * Query data.
     *
     * @param News $news
     *
     * @return array
     */
    public function query(News $news): iterable
    {
        $news->load('attachment');
        $news->load('employer');

        return [
            'news' => $news,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->news->exists ? 'Редактировать новость' : 'Добавить новость';
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
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                ->method('remove')
                ->canSee($this->news->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(NewsEditLayout::class)
                ->title("Информация о новости")
                ->description('Основная информация о новости')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->news->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param News $news
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(News $news, Request $request)
    {
        $newsData = $request->get('news');

        $news
            ->fill($newsData)
            ->save();

        $news->attachment()->sync(
            $request->input('news.attachment', [])
        );

        Toast::info('Новость была сохранена');

        return redirect()->route('platform.publications.news');
    }

    /**
     * @param News $news
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(News $news)
    {
        $news->delete();

        Toast::info('Новость была удалена');

        return redirect()->route('platform.publications.news');
    }
}
