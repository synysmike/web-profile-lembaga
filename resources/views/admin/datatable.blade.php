@extends('layouts.admin')

@section('title', $spec['heading'])

@section('summary', $spec['summary'])

@section('actions')
    <button type="button" id="open-{{ $spec['key'] ?? 'item' }}" class="inline-flex rounded-full bg-amber-400 px-4 py-2 text-sm font-semibold text-amber-950">{{ $spec['add'] }}</button>
@endsection

@section('content')
    @include('admin.partials.datatable', ['spec' => $spec, 'items' => $items])
@endsection
