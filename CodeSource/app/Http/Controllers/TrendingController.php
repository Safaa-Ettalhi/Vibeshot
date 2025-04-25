<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrendingController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'week');
        $type = $request->input('type', 'all');
        
        $dateFrom = now();
        switch ($period) {
            case 'today':
                $dateFrom = now()->startOfDay();
                break;
            case 'week':
                $dateFrom = now()->subWeek();
                break;
            case 'month':
                $dateFrom = now()->subMonth();
                break;
            case 'year':
                $dateFrom = now()->subYear();
                break;
        }
        
        $query = Post::withCount(['likes', 'comments', 'shares'])
            ->where('created_at', '>=', $dateFrom)
            ->where('is_hidden', false);
        
        if ($type === 'images') {
            $query->where(function($q) {
                $q->whereHas('images')
                  ->orWhereNotNull('image_path');
            })->whereNull('caption');
        }
        
        $query->orderBy('likes_count', 'desc');
        
        $trendingPosts = $query->get();
        $postsCount = $trendingPosts->count();
        
        $stats = [
            'today' => Post::where('created_at', '>=', now()->startOfDay())->count(),
            'week' => Post::where('created_at', '>=', now()->subWeek())->count(),
            'month' => Post::where('created_at', '>=', now()->subMonth())->count(),
            'year' => Post::where('created_at', '>=', now()->subYear())->count(),
        ];
            
        return view('trending.index', compact('trendingPosts', 'period', 'type', 'stats'));
    }
}
