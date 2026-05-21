@extends('site.layout')
@section('title', $article->title.' – '.setting('site_title'))
@section('meta_description', Str::limit(strip_tags($article->excerpt ?? $article->content), 155))

@section('content')
<div class="bc">
  <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <a href="{{ route('site.news') }}">News</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <span>{{ Str::limit($article->title, 40) }}</span>
</div>

<div class="news-detail-body">
  <div class="news-detail-grid">
    <article>
      @if($article->image)<img src="{{ $article->image_url }}" class="news-detail-img" alt="{{ $article->title }}"/>@endif
      <div class="news-detail-meta">
        @if($article->category)<span class="news-detail-cat">{{ $article->category }}</span>@endif
        <span class="news-detail-date">{{ $article->date_formatted }}</span>
      </div>
      <h1 class="news-detail-title">{{ $article->title }}</h1>
      <div class="news-detail-content">{!! $article->content !!}</div>

      <div style="margin-top:36px;padding-top:24px;border-top:1px solid var(--g200);">
        <a href="{{ wa_link('Halo, saya membaca artikel "'.$article->title.'" dan ingin bertanya lebih lanjut.') }}" target="_blank" class="btn-p">
          <i class="fab fa-whatsapp"></i> Hubungi Kami
        </a>
      </div>
    </article>

    <aside>
      <h3 style="font-family:'Barlow Condensed';font-weight:800;font-size:20px;text-transform:uppercase;margin-bottom:20px;">Artikel Lainnya</h3>
      @foreach($recent as $r)
        <a href="{{ route('site.news.detail', $r->slug) }}" style="display:flex;gap:12px;margin-bottom:18px;text-decoration:none;">
          @if($r->image)<img src="{{ $r->image_url }}" style="width:80px;height:64px;object-fit:cover;border-radius:4px;flex-shrink:0;"/>@endif
          <div>
            <div style="font-size:14px;font-weight:600;color:var(--g800);line-height:1.3;">{{ Str::limit($r->title, 55) }}</div>
            <div style="font-size:12px;color:var(--g400);margin-top:4px;">{{ $r->date_formatted }}</div>
          </div>
        </a>
      @endforeach
    </aside>
  </div>
</div>
@endsection
