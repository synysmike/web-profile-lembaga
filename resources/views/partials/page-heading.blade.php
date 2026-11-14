<section @class(['band', 'has-photo' => filled($heroImageUrl)])>
    <div class="page-intro mx-auto max-w-6xl px-4 py-12">
        <p class="text-sm font-medium opacity-80">{{ $schoolName }}</p>
        <h1 class="mt-2 text-4xl font-semibold">{{ $heading }}</h1>
        @isset($summary)
            <p class="mt-3 max-w-2xl text-lg">{{ $summary }}</p>
        @endisset
    </div>
</section>
