@extends('publicLayout')
@section('main-content')

<div
    class="home-main"
    x-data="{
        showFirst: false,
        showSecond: false
    }"
    x-init="
        setTimeout(() => showFirst = true, 500);
        setTimeout(() => showSecond = true, 2000);
    "
>
    <div class="text-center w-25 home-main-inner d-flex justify-content-center">
            <div class="fade-in d-flex align-items-center text-end inner-one w-25" :class="{ 'show': showFirst }">
                <h1 class="me-2">
                    Iai,
                </h1>
            </div>
            <div class="fade-in d-flex align-items-center text-start inner-two w-50" :class="{ 'show': showSecond }">
                <h1
                x-show="showSecond"
                x-transition:enter="transition-opacity ease-in-out duration-2000"
                x-transition:leave="transition-opacity ease-in-out duration-2000"
                class="title-passei"
                >
                passei
                </h1>
            </div>
    </div>
    <h5>
        Site em desenvolvimento
    </h5>
</div>
@endsection
