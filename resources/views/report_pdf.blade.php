<!DOCTYPE html>
<html>
<head>
    <title>Weekly Attendance Report</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1e3a8a; }
        .header p { color: #666; margin: 5px 0; }
        
        /* Stats Grid */
        .stats { width: 100%; margin-bottom: 30px; }
        .stats td { width: 25%; text-align: center; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .stat-label { display: block; font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .stat-value { display: block; font-size: 24px; font-weight: bold; margin-top: 5px; }
        .text-blue { color: #1e3a8a; }
        .text-green { color: #16a34a; }
        .text-orange { color: #f97316; }
        .text-red { color: #dc2626; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #1e3a8a; color: white; padding: 10px; text-align: left; font-size: 12px; text-transform: uppercase; }
        td { border-bottom: 1px solid #ddd; padding: 10px; font-size: 14px; }
        tr:nth-child(even) { background-color: #f9fafb; }
    </style>
</head>
<body>

    <div class="header">
        <h1>DJLN Attendance System</h1>
        <p>Weekly Activity & Attendance Report</p>
        <p>Date: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
    </div>

    <!-- Statistics Section -->
    <table class="stats">
        <tr>
            <td>
                <span class="stat-label">Total Employees</span>
                <span class="stat-value text-blue">{{ $totalEmployees }}</span>
            </td>
            <td>
                <span class="stat-label">On Time Today</span>
                <span class="stat-value text-green">{{ $onTime }}</span>
            </td>
            <td>
                <span class="stat-label">Late Today</span>
                <span class="stat-value text-orange">{{ $late }}</span>
            </td>
            <td>
                <span class="stat-label">Absent</span>
                <span class="stat-value text-red">{{ $onLeave }}</span>
            </td>
        </tr>
    </table>

    <!-- Recent Activity Table -->
    <h3>Recent System Activities</h3>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentActivities as $activity)
            <tr>
                <td>{{ $activity->description }}</td>
                <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('h:i A') }}</td>
                <td style="color: green; font-weight: bold;">Completed</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>