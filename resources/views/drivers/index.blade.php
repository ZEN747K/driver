@extends('layouts.layout')

@section('title', 'Drivers')

@section('style')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
@endsection

@section('content')
<div class="container">
    <h1 class="mt-4">Drivers</h1>

    <!-- <form action="{{ route('drivers.index') }}" method="GET" class="row mb-3 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="ค้นหาจากชื่อ" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
            <a href="{{ route('drivers.index') }}" class="btn btn-secondary">แสดงทั้งหมด</a>
        </div>
    </form> -->

    <a href="{{ route('drivers.create') }}" class="btn btn-primary mb-3">Add Driver</a>

    <table id="drivers-table" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Status</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
                <tr>
                    <td>{{ $driver->id }}</td>
                    <td>{{ $driver->full_name }}</td>
                    <td>{{ $driver->phone }}</td>
                    <td>{{ $driver->service_type }}</td>
                    <td>{{ $driver->status }}</td>
                    <td>{{ $driver->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <a href="{{ route('drivers.show', $driver) }}" class="btn btn-sm btn-info">More information</a>
                        <form action="{{ route('drivers.approve', $driver) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="No_approve">
                            <button class="btn btn-sm btn-danger">No approve</button>
                        </form>
                        <form action="{{ route('drivers.approve', $driver) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Pending">
                            <button class="btn btn-sm btn-warning">Pending</button>
                        </form>
                        <form action="{{ route('drivers.approve', $driver) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Approved">
                            <button class="btn btn-sm btn-success">Approve</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('script')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
  <script>
    $(document).ready(function() {
      $('#drivers-table').DataTable(); 
    });
  </script>
@endsection
