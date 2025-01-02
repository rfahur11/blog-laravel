@props(['title','date','user','description','link']);
<div class="post-preview">
    <a href="{{ $link }}">
        <h2 class="post-title">{{ $title }}</h2>
        <h3 class="post-subtitle">
            @isset($description)
                {{ $description }}
            @endisset
        </h3>
    </a>
    <p class="post-meta">
        Posted by
        <a href="#!">{{ $user }}</a>
        on {{ $date }}
    </p>
</div>
<hr class="my-4"/>