@extends('layout')

@section('content')
    <!-- Top Header -->
    <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 sticky top-0 z-20">
        <div class="w-full max-w-2xl relative">
            <i data-lucide="search" class="absolute left-3 top-2.5 h-5 w-5 text-blue-900"></i>
            <input type="text" class="pl-10 pr-4 py-2 w-full bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="Search employee...">
        </div>
        <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-400 hover:text-orange-500 cursor-pointer">
            <i data-lucide="bell" class="h-5 w-5"></i>
        </div>
    </header>

    <!-- Content Body -->
    <main class="flex-1 p-6 bg-gray-50">
        
        <!-- SUCCESS MESSAGE ALERT -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="h-6 w-6 mr-2"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
            <!-- View Logs Button -->
            <a href="{{ route('activity.logs') }}" class="text-xs font-bold underline hover:text-green-900">
                View Activity Logs
            </a>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 min-h-[500px]">
            <div class="p-6 border-b border-gray-100 flex items-center">
                <div class="w-1.5 h-6 bg-orange-400 rounded-full mr-3"></div>
                <h2 class="text-xl font-bold text-blue-900">Full Employee List</h2>
            </div>
            
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-100 text-gray-500 font-bold text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-lg">No.</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Contacts</th>
                            <th class="px-6 py-4">Department</th>
                            <th class="px-6 py-4 rounded-tr-lg">Status Today</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employees as $index => $employee)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-blue-600">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                <!-- WRAP THE CONTENT IN AN A TAG LINKING TO SHOW PAGE -->
                                <a href="{{ route('employees.show', $employee->EmployeeID) }}" class="flex items-center hover:text-blue-600 transition group">
                                    @if($employee->Photo)
                                        <!-- Displays the uploaded photo -->
                                        <img src="{{ asset('storage/' . $employee->Photo) }}" class="h-8 w-8 rounded-full mr-3 object-cover border border-gray-200 group-hover:border-blue-400">
                                    @else
                                        <!-- Displays Initial if no photo -->
                                        <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 mr-3 flex items-center justify-center text-xs font-bold border border-blue-200 group-hover:border-blue-400">
                                            {{ substr($employee->FirstName, 0, 1) }}
                                        </div>
                                    @endif
                                    {{ $employee->FirstName }} {{ $employee->LastName }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $employee->ContactNumber }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $employee->department->DepartmentName ?? 'None' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                    {{ $employee->Status === 'Present' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $employee->Status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">No employees found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection