@extends('layouts.app')

@section('title', 'Edit Comment')
@section('content')
<div class="container">
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h1 class="text-xl font-semibold">Edit Comment</h1>
            </div>
            
            <div class="card-body">
                <form action="{{ route('comments.update', $comment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-300 mb-2">Comment</label>
                        <textarea name="content" id="content" rows="4" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all resize-none">{{ $comment->content }}</textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('posts.show', $comment->post_id) }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Update Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
