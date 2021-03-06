@extends('frontend.layouts.app')

@section('meta_title'){{ $blog->meta_title }}@stop

@section('meta_description'){{ $blog->meta_description }}@stop

@section('meta_keywords'){{ $blog->meta_keywords }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $blog->meta_title }}">
    <meta itemprop="description" content="{{ $blog->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($blog->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $blog->meta_title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($blog->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $blog->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('product', $blog->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($blog->meta_img) }}" />
    <meta property="og:description" content="{{ $blog->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
    <div class="container space-top-2 space-bottom-2">
        <div class="w-lg-60 mx-lg-auto">
            <x-blog.news-article-header :blog="$blog">
                </x-blog-news-article-header>

                <!-- Author -->
                <x-blog.news-article-author :blog="$blog"></x-blog.news-article-author>
                <!-- End Author -->

                {{-- TODO: Do we need an intro text before image? --}}
                <p>
                    {!! $blog->short_description !!}
                </p>
                {{-- <p>At Front, our mission has always been focused on bringing openness and transparency to the design process.
                We've always believed that by providing a space where designers can share ongoing work not only empowers
                them to make better products, it also helps them grow. We're proud to be a part of creating a more open
                culture and to continue building a product that supports this vision.</p> --}}
        </div>

        {{-- Blog Content --}}
        <div class="mb-4 w-lg-60 mx-lg-auto">
            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                data-src="{{ uploaded_asset($blog->banner) }}" alt="{{ $blog->title }}"
                class="img-fluid lazyload w-100 border-radius-3">
        </div>
        <div class="blog-content w-lg-60 mx-lg-auto">
            {!! $blog->description !!}
        </div>

        {{-- Blog Content END --}}

        <div class="blog-footer w-lg-60 mx-lg-auto">
            <x-affiliate-banner></x-affiliate-banner>
            <x-blog.news-article-subscribe></x-blog.news-article-subscribe>

            <!-- Badges -->


            <div class="mt-5">
                @foreach (explode($blog->meta_keywords, ',') as $item)
                    <a class="btn btn-xs btn-soft-secondary mb-1" href="#">{{ $item }}</a>
                @endforeach
            </div>
            <!-- End Badges -->

            <!-- Share -->
            <x-blog.news-article-share> </x-blog.news-article-share>
            <!-- End Share -->

            <x-blog.news-article-author :blog="$blog"></x-blog.news-article-author>

        </div>







    </div>
    @guest
        @php
        $button_text = 'Register Your Company For Free';
        $image_source = 'assets/img/img1.jpg';
        $heading = 'Register to B2BWood';
        $body = 'Join the global network of verified forestry and wood industry companies, to step into the digital age with B2BWood Club!';
        @endphp
        <x-promo-banner :heading="$heading" :body="$body" :buttonText="$button_text" :imageSource="$image_source">
        </x-promo-banner>

    @endguest

@endsection
