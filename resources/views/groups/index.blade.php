@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-6 offset-3">
                <h3 class="h3">Sorting Exam</h3>
                <hr>
                <div class="card">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Group Name</strong>
                    </li>
                    @foreach($groups as $group)
                        <a href="{{ route('groups.show', $group) }}">
                            <li class="list-group-item">{{ $group->name }}</li>
                        </a>
                    @endforeach
                  </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
