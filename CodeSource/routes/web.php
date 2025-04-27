<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\Admin\CommentAdminController;
use App\Http\Controllers\TrendingController;
use App\Http\Controllers\Admin\AdminProfileController;

Route::get('/blocked', function () {
    return view('auth.blocked');
})->name('blocked');

Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Route de déconnexion 
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Routes utilisateur non admin
Route::middleware(['auth', 'not.admin'])->group(function () {
    // Page d'accueil
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home/search', [HomeController::class, 'search'])->name('home.search');
    
    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::delete('/post-images/{image}', [PostController::class, 'destroyImage'])->name('posts.images.destroy');
    
    // Likes
    Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('likes.destroy');
    
    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/posts/{post}/bookmarks', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/posts/{post}/bookmarks', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    
    // Profil
    Route::get('/profile/{username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Abonnements
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('follow.destroy');
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread.count');
        Route::get('/count', [NotificationController::class, 'getUnreadCount'])->name('count'); 
        Route::get('/filter/{type}', [NotificationController::class, 'filterByType'])->name('filter');
    });
   
    // Recherche
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    
    // Routes pour les commentaires
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Trending
    Route::get('/trending', [TrendingController::class, 'index'])->name('trending.index');
});

// Routes pour l'administration
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les utilisateurs
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::post('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
    
    // Routes pour les publications
    Route::resource('posts', PostAdminController::class);
    Route::post('posts/{post}/hide', [PostAdminController::class, 'hide'])->name('posts.hide');
    Route::post('posts/{post}/unhide', [PostAdminController::class, 'unhide'])->name('posts.unhide');
    
    // Routes pour les commentaires
    Route::resource('comments', CommentAdminController::class)->only(['index', 'show', 'destroy']);
    
    // Profil admin
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
});