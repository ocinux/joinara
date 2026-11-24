@extends('layouts.app')

@section('title', 'Buat event — Joinara')
@section('content')
    <h1>Buat event</h1>
    <div class="card">
        <form method="POST" action="{{ route('events.store') }}">
            @csrf
            @include('events._form', ['event' => $event])
            <button type="submit">Simpan</button>
            <a href="{{ route('events.index') }}">Batal</a>
        </form>
    </div>
@endsection
