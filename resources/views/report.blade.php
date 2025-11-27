@extends('layout')

@section('content')
<div class="p-8 bg-gray-50 h-full overflow-y-auto">
    
    <!-- Page Title -->
    <div class="flex items-center mb-8">
        <div class="w-1.5 h-6 bg-orange-400 mr-3 rounded-full"></div>
        <h1 class="text-xl font-bold text-blue-900">Weekly Report</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Employees -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between h-32">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Employees</p>
                    <h2 class="text-3xl font-extrabold text-blue-900 mt-1">{{ $totalEmployees }}</h2>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </div>
            </div>
            <div class="flex items-center text-xs text-green-600 font-bold">
                <i data-lucide="trending-up" class="h-3 w-3 mr-1"></i> +2 New this week
            </div>
        </div>

        <!-- On Time -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between h-32">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">On Time Today</p>
                    <h2 class="text-3xl font-extrabold text-green-600 mt-1">{{ $onTime }}</h2>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i data-lucide="check-circle" class="h-6 w-6"></i>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $totalEmployees > 0 ? ($onTime / $totalEmployees) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Late -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between h-32">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Late Today</p>
                    <h2 class="text-3xl font-extrabold text-orange-500 mt-1">{{ $late }}</h2>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                    <i data-lucide="clock" class="h-6 w-6"></i>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                <div class="bg-orange-400 h-1.5 rounded-full" style="width: {{ $totalEmployees > 0 ? ($late / $totalEmployees) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Absent (Previously On Leave) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between h-32">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Absent</p>
                    <!-- You will need to calculate $absent in your Controller, 
                         or rename $onLeave to $absent in your Controller and View -->
                    <h2 class="text-3xl font-extrabold text-red-600 mt-1">{{ $onLeave }}</h2> 
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-red-600">
                    <i data-lucide="x-circle" class="h-6 w-6"></i>
                </div>
            </div>
            <!-- Optional: Add a progress bar for Absent too if you like -->
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $totalEmployees > 0 ? ($onLeave / $totalEmployees) * 100 : 0 }}%"></div>
            </div>
        </div>

    </div>

    <!-- Recent Reports / Activity Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-blue-900">System Activity Report</h2>
            <button class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center">
                Download PDF <i data-lucide="download" class="h-4 w-4 ml-2"></i>
            </button>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 font-bold text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-4">Activity Description</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentActivities as $activity)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-700">
                            {{ $activity->description }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{ \Carbon\Carbon::parse($activity->created_at)->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                Completed
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            No recent reports generated.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection