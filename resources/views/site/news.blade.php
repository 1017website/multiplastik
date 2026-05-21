@extends('site.layout')
@section('title', 'News & Update – '.setting('site_title'))
@section('meta_description', 'Berita, promo, dan info produk terbaru dari '.setting('site_title'))

@section('content')
<div class="bc">
  <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <span>News & Update</span>
</div>

<section style="padding:56px 8% 40px;">
  <div class="sec-label">Berita & Info</div>
  <h1 class="sec-title">News & Update</h1>
  <div class="sec-div"></div>
</section>

<div class="news-grid" style="padding-top:0;">
  @forelse($news as $n)
    <a href="{{ route('site.news.detail', $n->slug) }}" class="news-card" style="text-decoration:none;">
      @if($n->image)<img src="{{ $n->image_url }}" class="news-card-img" alt="{{ $n->title }}"/>@endif
      <div class="news-card-body">
        <div class="news-card-meta">
          @if($n->category)<span class="news-card-cat">{{ $n->category }}</span>@endif
          <span class="news-card-date">{{ $n->date_formatted }}</span>
        </div>
        <div class="news-card-title">{{ $n->title }}</div>
        <div class="news-card-excerpt">{{ Str::limit($n->excerpt, 110) }}</div>
        <span class="news-card-link">Baca Selengkapnya <i class="fas fa-arrow-right"></i></span>
      </div>
    </a>
  @empty
    <p class="text-muted" style="grid-column:1/-1;text-align:center;padding:40px;">Belum ada artikel.</p>
  @endforelse
</div>

<div style="padding:0 8% 60px;">{{ $news->links() }}</div>
@endsection
