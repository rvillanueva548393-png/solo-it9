@extends('layout')

@section('content')
<div class="p-8 bg-gray-50 h-full overflow-y-auto">
    
    <!-- Breadcrumb / Back Button -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="flex items-center text-gray-500 hover:text-blue-900 transition">
            <i data-lucide="arrow-left" class="h-5 w-5 mr-2"></i>
            <span class="text-sm font-bold">Back to Dashboard</span>
        </a>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        
        <!-- Top Header Section -->
        <div class="h-32 bg-gradient-to-r from-blue-900 to-blue-800 relative"></div>
        
        <div class="px-8 pb-8 relative">
            <!-- Profile Image -->
            <div class="absolute -top-16 left-8">
                <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-white group relative">
                    @if($employee->Photo)
                        <img src="{{ asset('storage/' . $employee->Photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-500 text-4xl font-bold">
                            {{ substr($employee->FirstName, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Header Info -->
            <div class="ml-40 pt-4 flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $employee->FirstName }} {{ $employee->LastName }}</h1>
                    <p class="text-blue-600 font-medium">{{ $employee->department->DepartmentName ?? 'No Department' }}</p>
                </div>
                <div class="flex space-x-3">
                    <!-- Edit Button (Toggles Form) -->
                    <button onclick="toggleEdit()" id="editBtn" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 flex items-center shadow-sm transition">
                        <i data-lucide="edit-2" class="h-4 w-4 mr-2"></i> Edit Profile
                    </button>
                    
                    <!-- Cancel Button (Hidden by default) -->
                    <button onclick="cancelEdit()" id="cancelBtn" class="hidden px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-200 flex items-center shadow-sm transition">
                        <i data-lucide="x" class="h-4 w-4 mr-2"></i> Cancel
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('employees.destroy', $employee->EmployeeID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-50 border border-red-100 text-red-600 rounded-lg text-sm font-bold hover:bg-red-100 flex items-center shadow-sm transition">
                            <i data-lucide="trash-2" class="h-4 w-4 mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Divider -->
            <div class="mt-8 border-b border-gray-100"></div>

            <!-- EDIT FORM START -->
            <form action="{{ route('employees.update', $employee->EmployeeID) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <!-- Details Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
                    
                    <!-- Left Column: Personal Information -->
                    <div class="lg:col-span-1 space-y-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Personal Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="block text-xs text-gray-400 mb-1">Email Address</span>
                                <input type="email" name="Email" value="{{ $employee->Email }}" disabled class="editable-input w-full text-sm font-bold text-gray-700 bg-transparent border border-transparent rounded px-2 py-1 focus:bg-white focus:border-blue-300 focus:outline-none transition">
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 mb-1">Phone Number</span>
                                <input type="text" name="ContactNumber" value="{{ $employee->ContactNumber }}" disabled class="editable-input w-full text-sm font-bold text-gray-700 bg-transparent border border-transparent rounded px-2 py-1 focus:bg-white focus:border-blue-300 focus:outline-none transition">
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 mb-1">Age</span>
                                <input type="number" name="Age" value="{{ $employee->Age }}" disabled class="editable-input w-full text-sm font-bold text-gray-700 bg-transparent border border-transparent rounded px-2 py-1 focus:bg-white focus:border-blue-300 focus:outline-none transition">
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 mb-1">Address</span>
                                <textarea name="Address" rows="2" disabled class="editable-input w-full text-sm font-bold text-gray-700 bg-transparent border border-transparent rounded px-2 py-1 focus:bg-white focus:border-blue-300 focus:outline-none transition resize-none">{{ $employee->Address }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Middle/Right Column: Professional Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Professional Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Department Card -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center mb-2">
                                    <div class="p-2 bg-blue-100 rounded-md text-blue-600 mr-3">
                                        <i data-lucide="briefcase" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500">Department</span>
                                </div>
                                <!-- Department Dropdown (Hidden by default, shown on edit) -->
                                <div class="ml-11">
                                    <p class="view-text text-lg font-bold text-gray-800">{{ $employee->department->DepartmentName ?? 'None' }}</p>
                                    <select name="DepartmentID" disabled class="editable-input hidden w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @foreach(\App\Models\Department::all() as $dept)
                                            <option value="{{ $dept->DepartmentID }}" {{ $employee->DepartmentID == $dept->DepartmentID ? 'selected' : '' }}>
                                                {{ $dept->DepartmentName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Shift Card -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center mb-2">
                                    <div class="p-2 bg-orange-100 rounded-md text-orange-600 mr-3">
                                        <i data-lucide="clock" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500">Shift Schedule</span>
                                </div>
                                <div class="ml-11">
                                    <p class="view-text text-lg font-bold text-gray-800">
                                        {{ $employee->shift->ShiftType ?? 'Standard' }}
                                        <span class="text-xs text-gray-400 block font-normal">
                                            {{ $employee->shift ? \Carbon\Carbon::parse($employee->shift->StartTime)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($employee->shift->EndTime)->format('h:i A') : '' }}
                                        </span>
                                    </p>
                                    <select name="ShiftID" disabled class="editable-input hidden w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mt-1">
                                        @foreach(\App\Models\Shift::all() as $shift)
                                            <option value="{{ $shift->ShiftID }}" {{ $employee->ShiftID == $shift->ShiftID ? 'selected' : '' }}>
                                                {{ $shift->ShiftType }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date Joined Card (Read Only) -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center mb-2">
                                    <div class="p-2 bg-green-100 rounded-md text-green-600 mr-3">
                                        <i data-lucide="calendar" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500">Date Joined</span>
                                </div>
                                <p class="text-lg font-bold text-gray-800 ml-11">{{ \Carbon\Carbon::parse($employee->created_at)->format('F d, Y') }}</p>
                            </div>

                            <!-- Employee ID Card (Read Only) -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center mb-2">
                                    <div class="p-2 bg-purple-100 rounded-md text-purple-600 mr-3">
                                        <i data-lucide="hash" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500">Employee ID</span>
                                </div>
                                <p class="text-lg font-bold text-gray-800 ml-11">EMP-{{ str_pad($employee->EmployeeID, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        <!-- Save Changes Button (Hidden by default) -->
                        <div id="saveBtnContainer" class="hidden flex justify-end mt-4">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105 flex items-center">
                                <i data-lucide="save" class="h-4 w-4 mr-2"></i> Save Changes
                            </button>
                        </div>

                    </div>
                </div>
            </form>
            <!-- EDIT FORM END -->

        </div>
    </div>

    <!-- NEW SECTION: ATTENDANCE HISTORY -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-blue-900">Attendance History</h2>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 font-bold text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Time In</th>
                        <th class="px-6 py-4">Time Out</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employee->attendances()->latest()->get() as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($log->Date)->format('F d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-blue-600 font-medium">
                            {{ $log->CheckInTime ? \Carbon\Carbon::parse($log->CheckInTime)->format('h:i A') : '--:--' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-medium">
                            {{ $log->CheckOutTime ? \Carbon\Carbon::parse($log->CheckOutTime)->format('h:i A') : '--:--' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                {{ $log->Status === 'Present' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $log->Status === 'Late' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $log->Status === 'Absent' ? 'bg-gray-100 text-gray-500' : '' }}">
                                {{ $log->Status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            No attendance records found for this employee.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- JavaScript to Handle Edit Toggle -->
<script>
    function toggleEdit() {
        // Enable all inputs with class 'editable-input'
        const inputs = document.querySelectorAll('.editable-input');
        inputs.forEach(input => {
            input.disabled = false;
            input.classList.remove('bg-transparent', 'border-transparent');
            input.classList.add('bg-white', 'border-gray-300', 'ring-2', 'ring-blue-100');
            
            // Show selects, hide text views
            if (input.tagName === 'SELECT') {
                input.classList.remove('hidden');
                // Find the previous sibling which is likely the p tag
                const pTag = input.previousElementSibling;
                if (pTag && pTag.classList.contains('view-text')) {
                    pTag.classList.add('hidden');
                }
            }
        });

        // Toggle Buttons
        document.getElementById('editBtn').classList.add('hidden');
        document.getElementById('cancelBtn').classList.remove('hidden');
        document.getElementById('saveBtnContainer').classList.remove('hidden');
    }

    function cancelEdit() {
        // Reload the page to reset form
        window.location.reload();
    }
</script>
@endsection