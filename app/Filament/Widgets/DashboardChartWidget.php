<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Overview';
    protected static ?int $sort = 2;

    protected function getData(): array
    {

        $start = $this->filters['startDate'];
        $end  = $this->filters['endDate'];
        $data = Trend::model(User::class)
            ->between(
                start: $start ? Carbon::parse($start) : now()->subMonths(6),
                end: $end ? Carbon::parse($end) : now(),
            )
            ->perMonth()
            ->count();

        $category = Trend::model(Category::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        $post = Trend::model(Post::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        $comments = Trend::model(Comment::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'User Registration',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#228f2b',
                ],
                [
                    'label' => 'Categories',
                    'data' => $category->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#f0071e',
                ],
                [
                    'label' => 'Posts',
                    'data' => $post->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#e8f007',
                ],
                [
                    'label' => 'Comments',
                    'data' => $comments->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#0205bd',
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
