@extends('layouts.dashboard')
@section('subtitle', 'Dashboard')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Total Logs counts by Time</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Pages:</div>
                            <a class="dropdown-item" href="{{ route('logs') }}">Logs</a>
                            <a class="dropdown-item" href="{{ route('log-counts') }}">Logs count</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
                <div class="card-footer">
                    <form method="GET" action="{{ route('charts') }}">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">

                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}">

                        <button type="submit" class="btn btn-primary">Filter</button>

                        <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset</button>
                    </form>

                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Logs counts by Type</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="myPieChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("myPieChart").getContext('2d');

            var logTypes = @json(array_keys($logCountsByType)); // Log types
            var logCounts = @json(array_values($logCountsByType)); // Log counts
            var logPercentages = @json(array_values($logPercentages)); // Percentages

            var myPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: logTypes.map((label, index) =>
                    `${label} (${logPercentages[index]}%)`), // Append percentage to label
                    datasets: [{
                        data: logCounts,
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'left',
                            labels: {
                                fontSize: 14, // Increase font size
                                boxWidth: 20, // Adjust legend box size
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    let index = tooltipItem.dataIndex;
                                    return `${logTypes[index]}: ${logCounts[index]} logs (${logPercentages[index]}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("myAreaChart").getContext('2d');

            var logDates = @json(array_keys($logCountsByDate));
            var logDailyCounts = @json(array_values($logCountsByDate));

            var myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: logDates,
                    datasets: [{
                        label: "Total Logs Per Day",
                        data: logDailyCounts,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)"
                    }]
                }
            });
        });

        function resetFilters() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            window.location.href = "{{ route('charts') }}"; // Redirect to the logs page without filters
        }
    </script>

@endsection
