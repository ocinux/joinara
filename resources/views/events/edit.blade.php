@extends('layouts.app')

@section('title', 'Ubah event — Joinara')
@section('content')
    <h1>Ubah event</h1>
    <div class="card">
        <form method="POST" action="{{ route('events.update', $event) }}">
            @csrf
            @method('PUT')
            @include('events._form', ['event' => $event])
            <button type="submit">Simpan perubahan</button>
            <a href="{{ route('events.show', $event) }}">Batal</a>
        </form>
    </div>
@endsection
