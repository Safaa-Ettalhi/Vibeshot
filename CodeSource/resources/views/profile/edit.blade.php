@extends('layouts.app')

@section('content')
<div class="edit-profile-container container">
    <div class="edit-profile-wrapper ">
        <h1 class="edit-profile-title text-2xl font-bold mt-6 mb-6">Edit Profile</h1>        
        <div class="profile-info-card card mb-6">
            <div class="card-header profile-header">
                <h2 class="profile-section-title font-semibold">Profile Information</h2>
            </div>
            
            <div class="card-body profile-form-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Profile Images Section -->
                    <div class="profile-images-section mb-6">
                        <div class="preview-container relative">
                         
                            <div class="cover-preview">
                            <img src="{{ $user->cover_image ? asset('storage/' . $user->cover_image) : asset('images/default-cover.png') }}" 
                            alt="{{ $user->name }}"               
                            class="cover-img ">
                            </div>
                            
                          
                            <div class="profile-pic-preview">
                                <div class="relative">
                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" 
                                         alt="Current profile image" 
                                         class="profile-pic-img">
                                </div>
                            </div>
                        </div>
                        
                      
                        <div class="image-upload-fields mt-12 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="profile-image-upload">
                                <label for="profile_image" class="form-label upload-label">Profile Image</label>
                                <input type="file" id="profile_image" name="profile_image" class="form-control file-input" accept="image/*">
                                
                                @error('profile_image')
                                    <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="cover-image-upload">
                                <label for="cover_image" class="form-label upload-label">Cover Image</label>
                                <input type="file" id="cover_image" name="cover_image" class="form-control file-input" accept="image/*">
                                
                                @error('cover_image')
                                    <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Information  -->
                    <div class="form-group user-name-field">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control text-input" value="{{ old('name', $user->name) }}" required>
                        
                        @error('name')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group username-field">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control text-input" value="{{ old('username', $user->username) }}" required>
                        
                        @error('username')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group email-field">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control text-input" value="{{ old('email', $user->email) }}" required>
                        
                        @error('email')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group bio-field">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea id="bio" name="bio" class="form-control textarea-input" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        
                        @error('bio')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-actions flex justify-end">
                        <button type="submit" class="save-btn btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="password-card card">
            <div class="card-header password-header">
                <h2 class="password-section-title font-semibold">Change Password</h2>
            </div>
            
            <div class="card-body password-form-body">
                <form action="{{ route('profile.password.update') }}" method="POST" class="password-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group current-password-field">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control password-input" required>
                        
                        @error('current_password')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group new-password-field">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-control password-input" required>
                        
                        @error('password')
                            <span class="error-message text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group confirm-password-field">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control password-input" required>
                    </div>
                    
                    <div class="form-actions flex justify-end">
                        <button type="submit" class="password-btn btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection