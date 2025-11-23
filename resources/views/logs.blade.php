@extends('layout')

@section('content')
<div class="p-8 bg-gray-50 h-full">
    
    <!-- Header / Search -->
    <header class="flex justify-between items-center mb-8">
        <div class="relative w-96">
            <i data-lucide="search" class="absolute left-3 top-3 h-5 w-5 text-gray-400"></i>
            <input type="text" placeholder="Search logs..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border-none bg-white shadow-sm focus:ring-2 focus:ring-orange-200 focus:outline-none text-sm text-gray-600">
        </div>
        <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-400 hover:text-orange-500 cursor-pointer">
            <i data-lucide="bell" class="h-5 w-5"></i>
        </div>
    </header>

    <!-- White Card Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 min-h-[600px] relative flex flex-col">
        
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-1.5 h-6 bg-orange-400 mr-3 rounded-full"></div>
                <h2 class="text-xl font-bold text-blue-900">Daily Log</h2>
            </div>
            <!-- Filter Button -->
            <button class="flex items-center text-sm font-bold text-gray-500 hover:text-blue-900 transition">
                Filter <i data-lucide="chevron-down" class="ml-1 h-4 w-4"></i>
            </button>
        </div>

        <!-- Table -->
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-100 text-gray-500 font-bold text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-lg">Name</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Time In</th>
                        <th class="px-6 py-4">Time Out</th>
                        <th class="px-6 py-4 rounded-tr-lg">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($attendances as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Name & Photo -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($log->employee && $log->employee->Photo)
                                    <img src="{{ asset('storage/' . $log->employee->Photo) }}" class="h-8 w-8 rounded-full mr-3 object-cover border border-gray-200">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 mr-3 flex items-center justify-center text-xs font-bold border border-blue-200">
                                        {{ substr($log->employee->FirstName ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                <span class="font-bold text-gray-700 text-sm">
                                    {{ $log->employee->FirstName ?? 'Unknown' }} {{ $log->employee->LastName ?? '' }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Date -->
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                            {{ \Carbon\Carbon::parse($log->Date)->format('F d, Y') }}
                        </td>

                        <!-- Time In -->
                        <td class="px-6 py-4 text-sm text-blue-600 font-bold">
                            {{ $log->CheckInTime ? \Carbon\Carbon::parse($log->CheckInTime)->format('h:i A') : '--:--' }}
                        </td>

                        <!-- Time Out -->
                        <td class="px-6 py-4 text-sm text-gray-600 font-bold">
                            {{ $log->CheckOutTime ? \Carbon\Carbon::parse($log->CheckOutTime)->format('h:i A') : '--:--' }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                {{ $log->Status === 'Present' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $log->Status === 'Late' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $log->Status === 'Absent' ? 'bg-gray-100 text-gray-500' : '' }}">
                                {{ $log->Status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="clipboard-x" class="h-8 w-8 mb-2 opacity-50"></i>
                                <p>No attendance logs found yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection