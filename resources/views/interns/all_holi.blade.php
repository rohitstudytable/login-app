@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Holidays to Interns</h4>
        </div>

        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('interns.add.holidays') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Select Intern --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Intern (Optional)</label>
                        <select name="intern_id" class="form-select">
                            <option value="">-- Select Intern --</option>
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}">
                                    {{ $intern->name }} ({{ $intern->city ?? 'No City' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select Location --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Location (Optional)</label>
                        <select name="city" class="form-select">
                            <option value="">-- Select City --</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Number of Holidays --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Number of Holidays to Add</label>
                        <input type="number" name="holidays" class="form-control" min="1" required>
                    </div>

                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        Add Holidays
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection