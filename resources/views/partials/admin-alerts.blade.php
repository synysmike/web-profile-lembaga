@if (session('status'))
    <p class="rounded-2xl bg-white px-4 py-3 text-sm">{{ session('status') }}</p>
@endif
@if ($errors->any())
    <p class="rounded-2xl bg-white px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</p>
@endif
