@extends('layouts.app')

@php
    $hero = $sections->firstWhere('key', 'hero');
    $homeDescription = trim((string) ($hero->excerpt ?? ''));
    if ($homeDescription === '') {
        $homeDescription = $school['tagline'] !== '' ? $school['tagline'] : $schoolName;
    }
    $organization = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => $school['name'],
        'url' => route('home'),
        'description' => $homeDescription,
        'logo' => $school['logo'] ?: null,
        'telephone' => $school['phone'] ?: null,
        'email' => $school['email'] ?: null,
        'address' => $school['address'] !== '' ? $school['address'] : null,
    ]);
@endphp

@section('document_title', $schoolName)
@section('share_description', $homeDescription)
@section('canonical', route('home'))

@push('head')
    <script type="application/ld+json">{!! json_encode($organization, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endpush

@section('content')
    @foreach ($sections as $section)
        @includeIf('home.'.$section->key, ['section' => $section])
    @endforeach
@endsection
