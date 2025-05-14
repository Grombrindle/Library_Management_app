<style>
    .infoContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>
@php
    $timeRange = request()->query('range', 'month'); // Default to month view
    $currentYear = now()->format('Y');
    $currentMonth = now()->format('m');

    // Calculate total users this month
    $totalUsersThisMonth = \App\Models\User::whereYear('created_at', $currentYear)
        ->whereMonth('created_at', $currentMonth)
        ->count();

    switch ($timeRange) {
        case 'week':
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
            $signups = \App\Models\User::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('strftime("%w", created_at) as day, COUNT(*) as count')
                ->groupBy('day')
                ->get()
                ->pluck('count', 'day')
                ->toArray();

            $completeData = [];
            for ($day = 0; $day < 7; $day++) {
                $completeData[$day] = $signups[$day] ?? 0;
            }
            $labels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            break;

        case 'month':
            $signups = \App\Models\User::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->selectRaw('strftime("%d", created_at) as day, COUNT(*) as count')
                ->groupBy('day')
                ->get()
                ->pluck('count', 'day')
                ->toArray();

            $daysInMonth = now()->daysInMonth;
            $completeData = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $completeData[$day] = $signups[str_pad($day, 2, '0', STR_PAD_LEFT)] ?? 0;
            }
            $labels = range(1, $daysInMonth);
            break;

        case 'year':
            $signups = \App\Models\User::whereYear('created_at', $currentYear)
                ->selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();

            $completeData = [];
            for ($month = 1; $month <= 12; $month++) {
                $completeData[$month] = $signups[str_pad($month, 2, '0', STR_PAD_LEFT)] ?? 0;
            }
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            break;
    }
@endphp

<x-layout :nav="false">
    <div style="display: flex; flex-direction: column; align-items: center; margin: 20px;">
        <h2 style="color: var(--text-color)">{{ __('messages.userSignups') }}</h2>
        <div style="margin-bottom: 20px;">
            <a href="?range=week"
                class="time-range-btn {{ $timeRange === 'week' ? 'active' : '' }}">{{ __('messages.oneWeek') }}</a>
            <a href="?range=month"
                class="time-range-btn {{ $timeRange === 'month' ? 'active' : '' }}">{{ __('messages.oneMonth') }}</a>
            <a href="?range=year"
                class="time-range-btn {{ $timeRange === 'year' ? 'active' : '' }}">{{ __('messages.oneYear') }}</a>
        </div>
        <canvas id="diagram" width="800" height="400"
            style="border: 1px solid var(--text-color); background-color: var(--dropdown-bg);"></canvas>
    </div>

    <style>
        .time-range-btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            background-color: var(--dropdown-bg);
            color: var(--text-color);
            border: 1px solid var(--text-color);
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .time-range-btn:hover {
            background-color: var(--nav-hover);
        }

        .time-range-btn.active {
            background-color: var(--nav-hover);
            font-weight: bold;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('diagram');
            const ctx = canvas.getContext('2d');

            // Set colors based on theme
            const textColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color');
            const gridColor = 'black';
            const barColor = getComputedStyle(document.documentElement).getPropertyValue('--diagram-bar');

            // Set up the diagram
            const padding = 40;
            const width = canvas.width;
            const height = canvas.height;

            // Draw X and Y axes
            ctx.beginPath();
            ctx.strokeStyle = textColor;
            ctx.lineWidth = 2;

            // Y axis
            ctx.moveTo(padding, padding);
            ctx.lineTo(padding, height - padding);

            // X axis
            ctx.moveTo(padding, height - padding);
            ctx.lineTo(width - padding, height - padding);
            ctx.stroke();

            // Draw grid lines
            ctx.strokeStyle = gridColor;
            ctx.lineWidth = 0.5;

            // Vertical grid lines
            const dataLength = Object.keys(@json($completeData)).length;
            const barWidth = (width - 2 * padding) / dataLength;
            for (let x = padding + barWidth; x < width - padding; x += barWidth) {
                ctx.beginPath();
                ctx.moveTo(x, padding);
                ctx.lineTo(x, height - padding);
                ctx.stroke();
            }

            // Horizontal grid lines
            const maxUsers = Math.max(...Object.values(@json($completeData)));
            const yStep = Math.ceil(maxUsers / 5); // 5 horizontal lines
            for (let y = height - padding - (height - 2 * padding) / 5; y > padding; y -= (height - 2 * padding) / 5) {
                ctx.beginPath();
                ctx.moveTo(padding, y);
                ctx.lineTo(width - padding, y);
                ctx.stroke();
            }

            // Draw axis labels
            ctx.fillStyle = textColor;
            ctx.font = '12px Arial';
            ctx.textAlign = 'center';

            // X axis labels
            const labels = @json($labels);
            let labelIndex = 0;
            for (let x = padding + barWidth / 2; x < width - padding; x += barWidth) {
                ctx.fillText(labels[labelIndex].toString(), x, height - padding + 15);
                labelIndex++;
            }

            // Y axis labels (number of users)
            for (let y = height - padding; y > padding; y -= (height - 2 * padding) / 5) {
                const value = Math.round(((height - padding - y) / (height - 2 * padding)) * maxUsers);
                ctx.fillText(value.toString(), padding - 15, y);
            }

            // Draw bars
            const data = @json($completeData);
            let x = padding;

            ctx.fillStyle = barColor;
            Object.values(data).forEach(count => {
                const barHeight = (count / maxUsers) * (height - 2 * padding);
                ctx.fillRect(x, height - padding - barHeight, barWidth - 2, barHeight);
                x += barWidth;
            });

            // Add title
            ctx.font = 'bold 16px Arial';
            ctx.textAlign = 'center';
        });
    </script>
    <div class="infoContainer">
        <div style="display:flex; flex-direction: row; align-self: flex-start; gap:25%">
            <div style="display:flex; flex-direction: column; align-items: center; color: var(--text-color)">
                <h2 style="font-size: 24px; font-weight: bold;">{{ __('messages.totalUsersThisMonth') }}:</h2>
                <h3 style="font-size: 24px; align-self: flex-start;">{{ $totalUsersThisMonth }}</h3>
            </div>
            <div style="display:flex; flex-direction: column; align-items: center; color: var(--text-color)">
                <h2 style="font-size: 24px; font-weight: bold;">{{ __('messages.totalUsersOfAllTime') }}:</h2>
                <h3 style="font-size: 24px; align-self: flex-start;">{{ App\Models\User::count() }}</h3>
            </div>
        </div>
    </div>
</x-layout>