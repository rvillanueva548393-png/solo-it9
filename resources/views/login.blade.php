@extends('layout')

@section('content')
<div class="flex h-screen w-full overflow-hidden">
    <!-- Left Side: Image Overlay -->
    <div class="hidden md:block md:w-1/2 lg:w-3/5 relative bg-slate-900">
        <img 
            src="https://images.unsplash.com/photo-1554469384-e58fac16e23a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
            alt="Building" 
            class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-90"></div>
        
        <div class="absolute bottom-10 left-10 text-white p-6">
            <h1 class="text-4xl font-bold tracking-wider mb-2 text-blue-200">DJLN</h1>
            <h2 class="text-2xl font-semibold tracking-widest text-gray-300">ATTENDANCE</h2>
            <div class="mt-4 w-16 h-1 bg-yellow-500 rounded-full"></div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 lg:w-2/5 bg-white flex flex-col justify-center items-center p-8 relative">
        <div class="w-full max-w-md space-y-8 text-center">
            
            <!-- Logo Area -->
            <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center border-2 border-blue-900 mb-4 overflow-hidden relative">
                 <span class="text-[10px] font-bold text-blue-900 text-center px-1">DJLN LOGO</span>
            </div>
            
            <h2 class="text-xl font-bold text-blue-900 tracking-wide">LOGIN</h2>
            <p class="mt-2 text-sm text-gray-500">Login with your <span class="text-yellow-500 font-bold">Email</span> credential.</p>

            <!-- Form -->
            <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf <!-- Security Token Required by Laravel -->
                
                <!-- Global Error Message (Optional) -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="space-y-4">
                    <!-- Email Field -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" required 
                            class="block w-full pl-10 pr-3 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:ring-2 focus:ring-yellow-500 focus:outline-none" 
                            placeholder="Email"
                            value="{{ old('email') }}">
                    </div>
                    <!-- Show specific email error below input -->
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Password Field -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                             <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-yellow-500 focus:outline-none" 
                            placeholder="Enter password...">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 font-bold rounded-full text-blue-900 bg-yellow-400 hover:bg-yellow-500 transition shadow-md">
                    LOGIN
                </button>
            </form>
        </div>
    </div>
</div>
@endsection