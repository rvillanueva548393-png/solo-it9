@extends('layout')

@section('content')
<div class="p-8 h-full bg-gray-50">
    
    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center">
        <i data-lucide="check-circle" class="h-6 w-6 mr-2"></i>
        <p class="font-bold">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Error Message -->
    @if($errors->any())
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 min-h-[600px]">
        
        <!-- Page Title -->
        <div class="flex items-center mb-8">
            <div class="w-1.5 h-6 bg-orange-400 mr-3 rounded-full"></div>
            <h1 class="text-xl font-bold text-blue-900">Settings</h1>
        </div>

        <!-- Change Password Form -->
        <div class="max-w-xl">
            <h2 class="text-lg font-bold text-gray-700 mb-6 border-b border-gray-100 pb-2">Change Password</h2>

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Current Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Current Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" required class="w-full bg-gray-50 text-gray-800 rounded-lg px-4 py-3 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition" placeholder="Enter current password">
                        <i data-lucide="lock" class="absolute right-3 top-3.5 h-5 w-5 text-gray-400"></i>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="new_password" required class="w-full bg-gray-50 text-gray-800 rounded-lg px-4 py-3 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition" placeholder="Enter new password">
                        <i data-lucide="key" class="absolute right-3 top-3.5 h-5 w-5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" required class="w-full bg-gray-50 text-gray-800 rounded-lg px-4 py-3 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition" placeholder="Confirm new password">
                        <i data-lucide="check-circle" class="absolute right-3 top-3.5 h-5 w-5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pt-4">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-bold py-3 px-8 rounded-full shadow-md text-sm transition transform hover:scale-105">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection