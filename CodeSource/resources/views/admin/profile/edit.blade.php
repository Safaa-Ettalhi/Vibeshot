@extends('admin.layouts.app')

@section('title', 'Update profil')
@section('content')
<div class="edit-profile-container">
    <div class="edit-profile-wrapper">
    <h1 class="edit-profile-title text-2xl font-bold mb-6">Edit Profile</h1>
        
        <div class="profile-info-card admin-card mb-6">
            <div class="admin-card-header profile-header">
                <h2 class="profile-section-title font-semibold">Profile Information</h2>
            </div>
            
            <div class="admin-card-body profile-form-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                    @csrf
                    @method('PUT')
                    <div class="profile-images-section mb-6">
                        <div class="preview-container relative">
                         
                            <div class="cover-preview">
                            <img src="{{ $user->cover_image ? asset('storage/' . $user->cover_image) : asset('images/default-cover.png') }}" 
                            alt="{{ $user->name }}"               
                            class="cover-img w-full h-40 object-cover rounded-lg">
                            </div>
                            
                          
                            <div class="profile-pic-preview">
                                <div class="relative">
                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" 
                                         alt="Current profile image" 
                                         class="profile-pic-img w-20 h-20 rounded-full object-cover border-4 border-gray-900 absolute -bottom-12 left-6">
                                </div>
                            </div>
                        </div>
                        
                      
                        <div class="image-upload-fields mt-12 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="profile-image-upload">
                                <label for="profile_image" class="form-label upload-label block text-sm font-medium text-gray-400">Profile Image</label>
                                <input type="file" id="profile_image" name="profile_image" class="form-control file-input admin-form-control" accept="image/*">
                                
                                @error('profile_image')
                                    <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="cover-image-upload">
                                <label for="cover_image" class="form-label upload-label block text-sm font-medium text-gray-400">Cover Image</label>
                                <input type="file" id="cover_image" name="cover_image" class="form-control file-input admin-form-control" accept="image/*">
                                
                                @error('cover_image')
                                    <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group user-name-field mb-4">
                        <label for="name" class="form-label block text-sm font-medium text-gray-400">Name</label>
                        <input type="text" id="name" name="name" class="form-control text-input admin-form-control" value="{{ old('name', $user->name) }}" required>
                        
                        @error('name')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group username-field mb-4">
                        <label for="username" class="form-label block text-sm font-medium text-gray-400">Username</label>
                        <input type="text" id="username" name="username" class="form-control text-input admin-form-control" value="{{ old('username', $user->username) }}" required>
                        
                        @error('username')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group email-field mb-4">
                        <label for="email" class="form-label block text-sm font-medium text-gray-400">Email</label>
                        <input type="email" id="email" name="email" class="form-control text-input admin-form-control" value="{{ old('email', $user->email) }}" required>
                        
                        @error('email')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group bio-field mb-4">
                        <label for="bio" class="form-label block text-sm font-medium text-gray-400">Bio</label>
                        <textarea id="bio" name="bio" class="form-control textarea-input admin-form-control" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        
                        @error('bio')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-actions flex justify-end">
                        <button type="submit" class="save-btn admin-btn admin-btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="password-card admin-card">
            <div class="admin-card-header password-header">
                <h2 class="password-section-title font-semibold">Change Password</h2>
            </div>
            
            <div class="admin-card-body password-form-body">
                <form action="{{ route('admin.profile.password.update') }}" method="POST" class="password-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group current-password-field mb-4">
                        <label for="current_password" class="form-label block text-sm font-medium text-gray-400">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control password-input admin-form-control" required>
                        
                        @error('current_password')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group new-password-field mb-4">
                        <label for="password" class="form-label block text-sm font-medium text-gray-400">New Password</label>
                        <input type="password" id="password" name="password" class="form-control password-input admin-form-control" required>
                        
                        @error('password')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group confirm-password-field mb-4">
                        <label for="password_confirmation" class="form-label block text-sm font-medium text-gray-400">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control password-input admin-form-control" required>
                    </div>
                    
                    <div class="form-actions flex justify-end">
                        <button type="submit" class="password-btn admin-btn admin-btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-pic-preview {
        position: relative;
        height: 0;
    }
    
    .cover-img {
        width: 100%;
        height: 300px;
        border-radius: 0 0 30px 30px;
        object-fit: cover;
    }
    
    .profile-pic-img {
        position: absolute;
        bottom: 10px;
        left: 10px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--dark-bg);
    }
    
    .image-upload-fields {
        margin-top: 80px;
    }
</style>
@endsection
