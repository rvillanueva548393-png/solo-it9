@extends('layout')

@section('content')
<div class="p-8 h-full bg-gray-50">
    
    <!-- Page Title -->
    <div class="flex items-center mb-8">
        <div class="w-1.5 h-6 bg-orange-400 mr-3 rounded-full"></div>
        <h1 class="text-xl font-bold text-blue-900">Attendance Analytics</h1>
    </div>

    <!-- Analytics Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 min-h-[500px] flex items-center justify-center">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-20 items-center w-full max-w-4xl">
            
            <!-- Left Side: Circular Graph -->
            <div class="flex flex-col items-center justify-center relative">
                <!-- SVG Circle Chart -->
                <div class="relative h-64 w-64">
                    <svg class="h-full w-full transform -rotate-90" viewBox="0 0 100 100">
                        <!-- Background Circle (Gray) -->
                        <circle cx="50" cy="50" r="40" fill="transparent" stroke="#F3F4F6" stroke-width="12" />
                        <!-- Progress Circle (Orange) -->
                        <circle cx="50" cy="50" r="40" fill="transparent" stroke="#FB923C" stroke-width="12" 
                                stroke-dasharray="251.2" 
                                stroke-dashoffset="{{ 251.2 - (251.2 * $percentage) / 100 }}"
                                stroke-linecap="round" 
                                class="transition-all duration-1000 ease-out"/>
                    </svg>
                    
                    <!-- Center Text -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-5xl font-bold text-blue-900">{{ $percentage }}%</span>
                        <span class="text-sm font-bold text-gray-400 uppercase tracking-wider mt-1">On-Time</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Statistics List -->
            <div class="space-y-6">
                
                <!-- On Time -->
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-100">
                    <div class="flex items-center">
                        <div class="h-3 w-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-600 font-bold">On-Time</span>
                    </div>
                    <span class="text-xl font-bold text-green-700">{{ $present }}</span>
                </div>

                <!-- Late -->
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg border border-orange-100">
                    <div class="flex items-center">
                        <div class="h-3 w-3 bg-orange-400 rounded-full mr-3"></div>
                        <span class="text-gray-600 font-bold">Late</span>
                    </div>
                    <span class="text-xl font-bold text-orange-600">{{ $late }}</span>
                </div>

                <!-- Absent -->
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-100">
                    <div class="flex items-center">
                        <div class="h-3 w-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-gray-600 font-bold">Absent</span>
                    </div>
                    <span class="text-xl font-bold text-red-600">{{ $absent }}</span>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100 my-4"></div>

                <!-- Total -->
                <div class="flex items-center justify-between px-4">
                    <span class="text-blue-900 font-bold text-lg">Total Employees</span>
                    <span class="text-2xl font-extrabold text-blue-900">{{ $totalEmployees }}</span>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection