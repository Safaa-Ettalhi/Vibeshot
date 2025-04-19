@extends('layouts.app')

@section('content')
<div class="container">
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h1 class="text-xl font-semibold">Edit Post</h1>
            </div>
            
            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="update-form" data-existing-images="{{ $post->images->count() }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                
                <div class="card-body">
                    <div class="mb-4">
                        <label for="caption" class="form-label">Caption</label>
                        <textarea name="caption" id="caption" class="form-control" rows="4">{{ old('caption', $post->caption) }}</textarea>
                        @error('caption')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($post->images->count() > 0)
                        <div class="mb-4">
                            <label class="form-label mb-2">Current Images</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($post->images as $image)
                                    <div class="relative group" id="existing-image-{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post image" class="rounded-lg w-full h-32 object-cover">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" onclick="deleteImage({{ $image->id }})" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <label for="image-upload" class="form-label">
                            @if($post->images->count() == 0)
                                Add Images (required)
                            @else
                                Add New Images (optional)
                            @endif
                        </label>
                        <div class="border-2 border-dashed border-gray-600 rounded-lg p-4">
                            <input type="file" name="images[]" id="image-upload" class="hidden" accept="image/*" multiple onchange="previewImages(this)">
                            <label for="image-upload" class="flex flex-col items-center justify-center cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                <span class="mt-2 text-sm text-gray-400">Click to select images</span>
                            </label>
                            
                            <div id="image-preview-container" class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4"></div>
                        </div>
                        
                        <div id="selected-files-info" class="mt-2 text-sm text-gray-400"></div>
                        
                        @error('images')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @error('images.*')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="save-changes-btn">Save Changes</button>
                </div>
            </form>
            
            <form id="delete-image-form" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
function previewImages(input) {
    const previewContainer = document.getElementById('image-preview-container');
    const selectedFilesInfo = document.getElementById('selected-files-info');
    previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        selectedFilesInfo.textContent = `${input.files.length} file(s) selected`;
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const imageId = 'new-image-' + i;
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imageContainer = document.createElement('div');
                imageContainer.className = 'relative group';
                imageContainer.id = imageId;
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'rounded-lg w-full h-32 object-cover';
                img.alt = 'Preview';
                
                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 transition-colors';
                removeButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                
                removeButton.addEventListener('click', function() {
                    document.getElementById(imageId).remove();
                    
                    const remainingPreviews = previewContainer.querySelectorAll('div').length;
                    selectedFilesInfo.textContent = remainingPreviews > 0 
                        ? `${remainingPreviews} file(s) selected` 
                        : 'No files selected';
                });
                
                imageContainer.appendChild(img);
                imageContainer.appendChild(removeButton);
                previewContainer.appendChild(imageContainer);
            }
            
            reader.readAsDataURL(file);
        }
    } else {
        selectedFilesInfo.textContent = 'No files selected';
    }
}

function deleteImage(imageId) {
    if (confirm('Are you sure you want to remove this image?')) {
        const form = document.getElementById('delete-image-form');
        if (form) {
            form.action = "/post-images/" + imageId;
            form.submit();
        } else {
            console.error("Delete form not found");
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('update-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Utiliser l'attribut data-existing-images
            const existingImagesCount = parseInt(form.getAttribute('data-existing-images') || '0');
            const hasExistingImages = existingImagesCount > 0;
            const hasNewImages = document.getElementById('image-upload').files.length > 0;
            
            if (!hasExistingImages && !hasNewImages) {
                e.preventDefault();
                alert('At least one image is required for your post.');
                return false;
            }
            
            console.log("Submitting update form...");
            return true;
        });
    }
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection