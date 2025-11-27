@extends('layout')

@section('content')
    <!-- White Card Container -->
    <div class="p-8 bg-gray-50 h-full">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 min-h-[600px] relative">
            
            <!-- Page Title -->
            <div class="flex items-center mb-8">
                <div class="w-1.5 h-6 bg-orange-400 mr-3 rounded-full"></div>
                <h1 class="text-xl font-bold text-blue-900">Register</h1>
            </div>

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="flex flex-col lg:flex-row gap-10">
                    
                    <!-- Left Side: Face Picture Upload -->
                    <div class="w-full lg:w-1/3 flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Face Picture</label>
                        <div class="relative flex-1 border-2 border-dashed border-gray-300 rounded-lg bg-gray-100 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-200 transition min-h-[300px]">
                            <input type="file" name="Photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i data-lucide="arrow-up-from-line" class="h-16 w-16 text-blue-900 mb-2"></i>
                            <span class="text-xs text-gray-500 font-medium">Upload Image</span>
                        </div>
                    </div>

                    <!-- Right Side: Form Inputs -->
                    <div class="w-full lg:w-2/3 space-y-5">
                        
                        <!-- First Name -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">First Name</label>
                            <input type="text" name="FirstName" placeholder="Enter first name..." class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        </div>

                        <!-- Middle Name -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Middle Name</label>
                            <input type="text" name="MiddleName" placeholder="Enter middle name..." class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        </div>

                        <!-- Last Name -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Last Name</label>
                            <input type="text" name="LastName" placeholder="Enter last name..." class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        </div>

                        <!-- Age -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Age</label>
                            <input type="number" name="Age" class="w-20 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        </div>

                        <!-- Contact Number -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Contact Number</label>
                            <input type="text" name="ContactNumber" placeholder="Enter contact number..." class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        </div>

                        <!-- Email Address (Required for Login) -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="Email" placeholder="Enter email address..." class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        </div>

                        <!-- Password (Required for Login) -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Password</label>
                            <input type="password" name="password" placeholder="Set a password..." class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        </div>

                        <!-- Complete Address -->
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Complete Address</label>
                            <input type="text" name="Address" placeholder="Enter complete address..." class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition ring-1 ring-blue-300"> 
                        </div>

                        <!-- Department -->
                        <div class="flex items-center">
                             <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Department</label>
                             <select name="DepartmentID" class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:outline-none">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->DepartmentID }}">{{ $dept->DepartmentName }}</option>
                                @endforeach
                             </select>
                        </div>

                        <!-- Shift -->
                        <div class="flex items-center">
                             <label class="w-1/3 text-xs font-bold text-gray-400 uppercase tracking-wider">Shift</label>
                             <select name="ShiftID" class="w-2/3 bg-gray-100 text-gray-800 rounded-md px-4 py-3 border-transparent focus:bg-white focus:outline-none">
                                <option value="">Select Shift</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->ShiftID }}">{{ $shift->ShiftType }}</option>
                                @endforeach
                             </select>
                        </div>

                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-12">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-bold py-2 px-10 rounded-full shadow-md text-sm transition transform hover:scale-105">
                        DONE
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection