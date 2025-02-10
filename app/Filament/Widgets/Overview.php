<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class Overview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {
        $start = $this->filters['startDate'];
        $end  = $this->filters['endDate'];
    
        return [
            Stat::make(
                'Users',
                User::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                    ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                    ->count()
            )
                ->description(' New Users that have Joined')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->chart([1, 2, 30, 40, 55])
                ->color('success'),
    
            Stat::make(
                'Categories',
                Category::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                    ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                    ->count()
            )
                ->description(' Total Categories')
                ->descriptionIcon('heroicon-o-numbered-list', IconPosition::Before)
                ->chart([1, 2, 30, 40, 55])
                ->color('danger'),
    
            Stat::make(
                'Posts',
                Post::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                    ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                    ->count()
            )
                ->description(' Total Posts')
                ->descriptionIcon('heroicon-o-arrow-up-on-square-stack', IconPosition::Before)
                ->chart([1, 2, 30, 40, 55])
                ->color('warning'),
    
            Stat::make(
                'Comments',
                Comment::when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
                    ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
                    ->count()
            )
                ->description(' Total Comments')
                ->descriptionIcon('heroicon-o-rectangle-stack', IconPosition::Before)
                ->chart([1, 2, 30, 40, 55])
                ->color('info'),
        ];
    }
}
