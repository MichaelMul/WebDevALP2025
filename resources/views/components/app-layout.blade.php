@extends('layouts.app')

@section('content')
<div class="app-layout-wrapper">
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</div>
@endsection
