@extends('layouts.dashboard')
@section('subtitle', 'Dashboard')

@section('content')
    <!-- Content Row -->
    <div class="row">
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Log Table</h1>
            <p class="mb-4">You can see here all the logs from your project</p>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Logs Count Table</h6>
                </div>
                <div class="card-body">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <form method="GET" action="{{ route('log-counts') }}">
                            <div>
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">
                            </div>
                            <div>
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-info">Filter</button>
                                <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Total Count</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Type</th>
                                    <th>Total</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($counts as $type => $count)
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td>{{ $count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination links -->
                    {{-- <div class="d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div> --}}
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        function resetFilters() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            window.location.href = "{{ route('log-counts') }}"; // Redirect to logs page without filters
        }
    </script>

@endsection
