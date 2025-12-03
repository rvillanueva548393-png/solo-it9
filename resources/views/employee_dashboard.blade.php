<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal - DJLN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .timeline-scroll::-webkit-scrollbar { width: 4px; }
        .timeline-scroll::-webkit-scrollbar-track { background: #1e293b; }
        .timeline-scroll::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 h-screen font-sans overflow-hidden flex items-center justify-center p-6">
    
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[600px] flex overflow-hidden">
        
        <!-- LEFT SIDE: TIMELINE -->
        <div class="w-1/3 bg-[#0F172A] p-8 text-white flex flex-col relative">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Attendance History</h3>
            
            <div class="flex-1 overflow-y-auto timeline-scroll space-y-8 pr-2 relative">
                <div class="absolute left-[7px] top-2 bottom-0 w-[2px] bg-gray-700"></div>

                @forelse(Auth::guard('employee')->user()->attendances()->latest()->take(5)->get() as $log)
                <div class="relative pl-8">
                    <!-- Status Color Logic -->
                    <div class="absolute left-0 top-1.5 w-4 h-4 rounded-full border-2 border-[#0F172A] 
                        {{ stripos($log->Status, 'Late') !== false ? 'bg-orange-500' : 'bg-green-500' }}"></div>
                    
                    <p class="text-sm font-bold text-gray-200">{{ \Carbon\Carbon::parse($log->Date)->format('M d, Y') }}</p>
                    <div class="text-xs text-gray-400 mt-1">
                        <span class="block">In: {{ $log->CheckInTime ? \Carbon\Carbon::parse($log->CheckInTime)->format('g:i A') : '--:--' }}</span>
                        <span class="block">Out: {{ $log->CheckOutTime ? \Carbon\Carbon::parse($log->CheckOutTime)->format('g:i A') : '--:--' }}</span>
                    </div>
                </div>
                @empty
                <div class="pl-8 text-sm text-gray-500">No history yet.</div>
                @endforelse
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-800 flex justify-between items-center text-xs text-gray-500">
                <span>DJLN Systems</span>
                <span>v1.0</span>
            </div>
        </div>


        <!-- RIGHT SIDE: PROFILE & ACTION BUTTONS -->
        <div class="w-2/3 bg-white p-10 flex flex-col relative">
            
            <!-- Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">{{ now()->setTimezone('Asia/Manila')->format('l') }}</h1>
                    <p class="text-lg text-gray-500 font-medium">{{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}</p>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-50 text-red-500 p-3 rounded-full hover:bg-red-100 transition shadow-sm">
                        <i data-lucide="log-out" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>

            <!-- Profile Info -->
            <div class="flex flex-col items-center flex-1 justify-center">
                
                <div class="relative mb-4 group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full opacity-75 group-hover:opacity-100 transition duration-200 blur"></div>
                    <div class="relative w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-gray-100">
                        @if(Auth::guard('employee')->user()->Photo)
                            <img src="{{ asset('storage/' . Auth::guard('employee')->user()->Photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-300 text-3xl font-bold bg-slate-50">
                                {{ substr(Auth::guard('employee')->user()->FirstName, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-800 mb-1">
                    {{ Auth::guard('employee')->user()->FirstName }} {{ Auth::guard('employee')->user()->LastName }}
                </h2>
                <p class="text-xs font-semibold text-blue-500 bg-blue-50 px-3 py-1 rounded-full mb-6">
                    {{ Auth::guard('employee')->user()->department->DepartmentName ?? 'Employee' }}
                </p>

                <!-- ACTION BUTTONS SECTION -->
                <div class="w-full bg-gray-50 rounded-2xl p-6 border border-gray-100 text-center shadow-inner">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Today's Action</p>
                    
                    @php
                        // FIX: Get REAL-TIME status from today's attendance record
                        
                        $today = \Carbon\Carbon::today()->toDateString(); 
                        $attendance = Auth::guard('employee')->user()->attendances()
                                        ->where('Date', $today)
                                        ->first();

                        $isClockedIn = false;
                        $isClockedOut = false;
                        $isOnLunch = false;
                        $displayTime = '-- : --';

                        if ($attendance) {
                            if ($attendance->CheckOutTime) {
                                $isClockedOut = true;
                                $displayTime = \Carbon\Carbon::parse($attendance->CheckOutTime)->setTimezone('Asia/Manila')->format('g:i A');
                            } elseif ($attendance->LunchStart && !$attendance->LunchEnd) {
                                $isOnLunch = true;
                                $displayTime = \Carbon\Carbon::parse($attendance->LunchStart)->setTimezone('Asia/Manila')->format('g:i A');
                            } else {
                                $isClockedIn = true;
                                $displayTime = \Carbon\Carbon::parse($attendance->CheckInTime)->setTimezone('Asia/Manila')->format('g:i A');
                            }
                        }

                        // --- STRICT TIME LOGIC FOR VIEW ---
                        $now = \Carbon\Carbon::now('Asia/Manila');
                        
                        $employee = Auth::guard('employee')->user();
                        $shiftType = $employee->shift->ShiftType ?? 'Regular Shift';

                        // Define Rules (Must match Controller)
                        $shiftRules = [
                            'Morning Shift' => ['start' => '06:00:00', 'end' => '14:00:00', 'lunch' => '10:00:00'],
                            'Regular Shift' => ['start' => '08:00:00', 'end' => '17:00:00', 'lunch' => '12:00:00'],
                            'Night Shift'   => ['start' => '22:00:00', 'end' => '06:00:00', 'lunch' => '02:00:00'],
                        ];
                        $rules = $shiftRules[$shiftType] ?? $shiftRules['Regular Shift'];

                        // Parse Times
                        $shiftStart = \Carbon\Carbon::parse($rules['start'], 'Asia/Manila');
                        $shiftEnd   = \Carbon\Carbon::parse($rules['end'], 'Asia/Manila');
                        $lunchTime  = \Carbon\Carbon::parse($rules['lunch'], 'Asia/Manila');

                        // Night Shift Adjustment
                        if ($shiftStart->gt($shiftEnd)) {
                            $shiftEnd->addDay();
                            $lunchTime->addDay();
                        }

                        // Calculate Permissions
                        $canClockIn  = $now->gte($shiftStart->copy()->subHour()); // Allow 1hr early
                        $canLunch    = $now->gte($lunchTime);
                        $canClockOut = $now->gte($shiftEnd);
                    @endphp

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mb-4 text-green-700 bg-green-100 text-xs font-bold p-2 rounded border border-green-200 flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="h-3 w-3"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 text-red-700 bg-red-100 text-xs font-bold p-2 rounded border border-red-200 flex items-center justify-center gap-2">
                            <i data-lucide="alert-circle" class="h-3 w-3"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('attendance.update') }}" method="POST">
                        @csrf
                        
                        @if(!$isClockedIn && !$isClockedOut && !$isOnLunch)
                            <!-- 1. NOT CLOCKED IN -->
                            @if($canClockIn)
                                <button type="submit" name="action" value="checkin" class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-black text-xl rounded-xl shadow-lg transform transition hover:scale-105 flex items-center justify-center gap-3">
                                    <i data-lucide="log-in" class="h-6 w-6"></i>
                                    CHECK IN
                                </button>
                                <p class="text-xs text-gray-400 mt-3">Shift Starts: {{ $shiftStart->format('g:i A') }}</p>
                            @else
                                <button type="button" disabled class="w-full py-4 bg-gray-200 text-gray-400 font-black text-xl rounded-xl cursor-not-allowed flex items-center justify-center gap-3">
                                    <i data-lucide="clock" class="h-6 w-6"></i>
                                    WAIT FOR SHIFT
                                </button>
                                <p class="text-xs text-red-400 mt-3 font-bold">Too Early! Shift starts at {{ $shiftStart->format('g:i A') }}</p>
                            @endif

                        @elseif($isOnLunch)
                            <!-- 2B. ON LUNCH -->
                            <div class="text-3xl font-black text-orange-500 mb-4">{{ $displayTime }}</div>
                            <p class="text-xs text-orange-500 font-bold uppercase mb-4">On Lunch Break</p>
                            
                            <button type="submit" name="action" value="lunch_end" class="w-full py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg shadow-md transition flex items-center justify-center gap-2">
                                <i data-lucide="coffee" class="h-5 w-5"></i> End Lunch Break
                            </button>

                        @elseif($isClockedIn)
                            <!-- 2A. WORKING -->
                            <div class="text-3xl font-black text-blue-900 mb-4">{{ $displayTime }}</div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Start Lunch -->
                                @if($canLunch)
                                    <button type="submit" name="action" value="lunch_start" class="py-3 bg-orange-100 text-orange-600 font-bold rounded-lg hover:bg-orange-200 transition flex items-center justify-center gap-2 border border-orange-200">
                                        <i data-lucide="coffee" class="h-5 w-5"></i> Start Lunch
                                    </button>
                                @else
                                    <button type="button" disabled class="py-3 bg-gray-100 text-gray-400 font-bold rounded-lg flex items-center justify-center gap-2 border border-gray-200 cursor-not-allowed">
                                        <i data-lucide="coffee" class="h-5 w-5"></i> {{ $lunchTime->format('g:i A') }}
                                    </button>
                                @endif

                                <!-- Check Out -->
                                @if($canClockOut)
                                    <button type="submit" name="action" value="checkout" class="py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg shadow-md transition flex items-center justify-center gap-2 transform hover:scale-105">
                                        <i data-lucide="log-out" class="h-5 w-5"></i> Check Out
                                    </button>
                                @else
                                    <button type="button" disabled class="py-3 bg-gray-200 text-gray-400 font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed" title="Shift ends at {{ $shiftEnd->format('g:i A') }}">
                                        <i data-lucide="lock" class="h-5 w-5"></i> {{ $shiftEnd->format('g:i A') }}
                                    </button>
                                @endif
                            </div>
                            <p class="text-xs text-green-600 mt-3 font-bold flex items-center justify-center gap-1">
                                <i data-lucide="activity" class="h-3 w-3"></i> Currently Working
                            </p>

                        @else
                            <!-- 3. CLOCKED OUT -->
                            <div class="text-3xl font-black text-gray-800 mb-1">{{ $displayTime }}</div>
                            <div class="mt-4 py-3 bg-gray-200 text-gray-500 font-bold rounded-xl flex items-center justify-center gap-2 cursor-not-allowed border border-gray-300">
                                <i data-lucide="check-circle" class="h-5 w-5"></i> Shift Completed
                            </div>
                        @endif

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
      lucide.createIcons();
    </script>
</body>
</html>