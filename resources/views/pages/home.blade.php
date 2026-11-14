@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    @foreach ($sections as $section)
        @includeIf('home.'.$section->key, ['section' => $section])
    @endforeach
@endsection
