@extends('layouts.sneat')

@section('content')
<div class="container">
    <h1>Admins</h1>
    <a href="{{ route('admins.create') }}" class="btn btn-primary mb-3">Add Admin</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Super</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->name }}</td>
                <td>{{ $a->email }}</td>
                <td>{{ $a->is_super ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('admins.edit', $a) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('admins.destroy', $a) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
