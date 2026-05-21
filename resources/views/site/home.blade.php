@extends('site.layout')
@section('title', setting('site_title'))

@section('content')

    <!-- ==================== HERO SLIDER ==================== -->
    <section id="hero-s">
        <div class="slider-track" id="sliderTrack" style="width:{{ max(count($slides), 1) * 100 }}%;">
            @foreach ($slides as $slide)
                <div class="slide" style="width:{{ 100 / max(count($slides), 1) }}%;">
                    <div class="slide-bg">
                        @if ($slide->background_image)
                            <img src="{{ $slide->background_url }}" alt=""
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}" />
                        @endif
                    </div>
                    <div class="slide-color"
                        style="background:linear-gradient(105deg,rgba(17,17,17,.88) 40%,rgba(192,39,45,.3) 75%,rgba(17,17,17,.1) 100%);">
                    </div>
                    <div class="slide-accent"></div>
                    <div class="slide-content">
                        @if ($slide->tag)
                            <div class="slide-tag">{{ $slide->tag }}</div>
                        @endif
                        <h2 class="slide-title">
                            {{ $slide->title_top }}
                            @if ($slide->title_em)
                                <br><em>{{ $slide->title_em }}</em>
                            @endif
                            @if ($slide->title_bottom)
                                <br>{{ $slide->title_bottom }}
                            @endif
                        </h2>
                        @if ($slide->subtitle)
                            <p class="slide-sub">{{ $slide->subtitle }}</p>
                        @endif
                        <div class="slide-btns">
                            @if ($slide->btn_primary_text)
                                @php $primaryBlank = str_starts_with($slide->btn_primary_link ?? '', 'http') || $slide->btn_primary_link === 'wa'; @endphp
                                <a href="{{ slide_link($slide->btn_primary_link) }}" class="btn-p"
                                    {{ $primaryBlank ? 'target="_blank"' : '' }}>
                                    @if ($slide->btn_primary_icon)
                                        <i class="{{ $slide->btn_primary_icon }}"></i>
                                    @endif {{ $slide->btn_primary_text }}
                                </a>
                            @endif
                            @if ($slide->btn_secondary_text)
                                @php $secondaryBlank = str_starts_with($slide->btn_secondary_link ?? '', 'http') || $slide->btn_secondary_link === 'wa'; @endphp
                                <a href="{{ slide_link($slide->btn_secondary_link) }}" class="btn-o"
                                    {{ $secondaryBlank ? 'target="_blank"' : '' }}>
                                    @if ($slide->btn_secondary_icon)
                                        <i class="{{ $slide->btn_secondary_icon }}"></i>
                                    @endif {{ $slide->btn_secondary_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="slider-dots" id="sliderDots"></div>
        @if (count($slides) > 1)
            <button class="slider-arrow prev" onclick="sliderMove(-1)"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-arrow next" onclick="sliderMove(1)"><i class="fas fa-chevron-right"></i></button>
        @endif

        <div class="hero-stats">
            <div class="hstat">
                <div class="hstat-n">{{ setting('stat_1_number') }}</div>
                <div class="hstat-l">{{ setting('stat_1_label') }}</div>
            </div>
            <div class="hstat">
                <div class="hstat-n">{{ setting('stat_2_number') }}</div>
                <div class="hstat-l">{{ setting('stat_2_label') }}</div>
            </div>
            <div class="hstat">
                <div class="hstat-n">{{ setting('stat_3_number') }}</div>
                <div class="hstat-l">{{ setting('stat_3_label') }}</div>
            </div>
            <div class="hstat">
                <div class="hstat-n">{{ setting('stat_4_number') }}</div>
                <div class="hstat-l">{{ setting('stat_4_label') }}</div>
            </div>
        </div>
    </section>

    <!-- ==================== PROMO BAR ==================== -->
    @if (count($promos))
        <div class="promo-bar">
            <div class="promo-track">
                @foreach (array_merge($promos, $promos) as $p)
                    <span class="promo-item"><i class="fas fa-star"
                            style="font-size:9px;margin-right:8px;opacity:.7;"></i>{{ $p }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ==================== BRAND PREVIEW ==================== -->
    <section style="background:var(--g50);">
        <div class="fu" style="text-align:center;">
            <div class="sec-label">Brand yang Kami Distribusikan</div>
            <h2 class="sec-title">Temukan Produk<br>dari Brand Terpercaya</h2>
            <div class="sec-div" style="margin:16px auto 0;"></div>
        </div>
        <div class="home-brands">
            @foreach ($brands as $b)
                <a href="{{ route('site.brand', $b->slug) }}" class="home-brand-card" style="text-decoration:none;">
                    @if ($b->logo)
                        <img src="{{ $b->logo_url }}" alt="{{ $b->name }}" />
                    @endif
                    <h3>{{ $b->name }}</h3>
                    <p>{{ $b->tagline }}</p>
                    <span class="home-brand-link">Lihat Produk <i class="fas fa-arrow-right"></i></span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- ==================== ABOUT ==================== -->
    <section id="about-s">
        <div class="about-grid">
            <div class="about-imgwrap fu">
                @if (setting('about_image'))
                    <img src="{{ media_url(setting('about_image')) }}" alt="Tentang Kami" />
                @endif
                <div class="about-badge">
                    <strong>{{ setting('about_badge_number') }}</strong>
                    <span>{{ setting('about_badge_text') }}</span>
                </div>
            </div>
            <div class="fu">
                <div class="sec-label">{{ setting('about_label') }}</div>
                <h2 class="sec-title">{!! nl2br(e(str_replace('\n', "\n", setting('about_title')))) !!}</h2>
                <div class="sec-div"></div>
                <p>{{ setting('about_paragraph_1') }}</p>
                <p>{{ setting('about_paragraph_2') }}</p>
                <div class="about-feats">
                    @for ($i = 1; $i <= 4; $i++)
                        @if (setting("about_feat_{$i}_title"))
                            <div class="about-feat">
                                <div class="about-feat-icon"><i class="{{ setting("about_feat_{$i}_icon") }}"></i></div>
                                <div class="about-feat-text">
                                    <strong>{{ setting("about_feat_{$i}_title") }}</strong>
                                    <span>{{ setting("about_feat_{$i}_text") }}</span>
                                </div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== KEUNGGULAN ==================== -->
    <section id="keunggulan-s">
        <div class="fu" style="text-align:center;">
            <div class="sec-label" style="color:#ff6b6b;">{{ setting('keung_label') }}</div>
            <h2 class="sec-title" style="color:var(--white);">{{ setting('keung_title') }}</h2>
            <div class="sec-div" style="margin:16px auto 0;"></div>
        </div>
        <div class="kung-grid fu">
            @for ($i = 1; $i <= 4; $i++)
                @if (setting("keung_{$i}_title"))
                    <div class="kung-item">
                        <div class="kung-icon"><i class="{{ setting("keung_{$i}_icon") }}"></i></div>
                        <div class="kung-title">{{ setting("keung_{$i}_title") }}</div>
                        <div class="kung-desc">{{ setting("keung_{$i}_desc") }}</div>
                    </div>
                @endif
            @endfor
        </div>
    </section>

    <!-- ==================== INSTAGRAM ==================== -->
    @if (setting('elfsight_instagram_id'))
        <section style="background:var(--white);">
            <div class="fu" style="text-align:center;">
                <div class="sec-label">Social Media</div>
                <h2 class="sec-title">Update Terbaru dari Instagram</h2>
                <div class="sec-div" style="margin:16px auto 0;"></div>
            </div>
            <div class="elf-wrap fu">
                <div class="elfsight-app-{{ setting('elfsight_instagram_id') }}" data-elfsight-app-lazy></div>
            </div>
        </section>
    @endif

    <!-- ==================== TESTIMONI ==================== -->
    @if (setting('elfsight_testimoni_id'))
        <section id="ulasan-s" style="background:var(--g50);">
            <div class="fu" style="text-align:center;">
                <div class="sec-label">Testimoni</div>
                <h2 class="sec-title">Apa Kata Pelanggan Kami</h2>
                <div class="sec-div" style="margin:16px auto 0;"></div>
            </div>
            <div class="elf-wrap fu">
                <div class="elfsight-app-{{ setting('elfsight_testimoni_id') }}" data-elfsight-app-lazy></div>
            </div>
        </section>
    @endif

    <!-- ==================== NEWS PREVIEW ==================== -->
    @if ($news->count())
        <section style="background:var(--white);">
            <div class="fu" style="text-align:center;">
                <div class="sec-label">Berita & Info</div>
                <h2 class="sec-title">News & Update Terbaru</h2>
                <div class="sec-div" style="margin:16px auto 0;"></div>
            </div>
            <div class="news-grid" style="padding-top:40px;">
                @foreach ($news as $n)
                    <a href="{{ route('site.news.detail', $n->slug) }}" class="news-card" style="text-decoration:none;">
                        @if ($n->image)
                            <img src="{{ $n->image_url }}" class="news-card-img" alt="{{ $n->title }}" />
                        @endif
                        <div class="news-card-body">
                            <div class="news-card-meta">
                                @if ($n->category)
                                    <span class="news-card-cat">{{ $n->category }}</span>
                                @endif
                                <span class="news-card-date">{{ $n->date_formatted }}</span>
                            </div>
                            <div class="news-card-title">{{ $n->title }}</div>
                            <div class="news-card-excerpt">{{ Str::limit($n->excerpt, 110) }}</div>
                            <span class="news-card-link">Baca Selengkapnya <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ==================== KONTAK ==================== -->
    <section id="kontak-s">
        <div class="fu">
            <div class="sec-label">Hubungi Kami</div>
            <h2 class="sec-title">Lokasi & Kontak</h2>
            <div class="sec-div"></div>
        </div>
        <div class="kontak-grid fu">
            <div>
                <div class="ki">
                    <div class="ki-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="ki-text"><strong>Alamat</strong><span>{{ setting('contact_address') }}</span></div>
                </div>
                <div class="ki">
                    <div class="ki-icon"><i class="fab fa-whatsapp"></i></div>
                    <div class="ki-text"><strong>WhatsApp</strong><a href="{{ wa_link() }}"
                            target="_blank">{{ setting('contact_whatsapp_display') }}</a></div>
                </div>
                <div class="ki">
                    <div class="ki-icon"><i class="fas fa-envelope"></i></div>
                    <div class="ki-text"><strong>Email</strong><a
                            href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a></div>
                </div>
                <div class="ki">
                    <div class="ki-icon"><i class="fas fa-clock"></i></div>
                    <div class="ki-text"><strong>Jam Operasional</strong><span>{{ setting('contact_hours') }}</span></div>
                </div>
            </div>
            @if (setting('contact_map_embed'))
                <div class="map-wrap">
                    <iframe src="{{ setting('contact_map_embed') }}" width="600" height="450" style="border:0;"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script>
            const SLIDES_COUNT = {{ count($slides) }};
            let curSlide = 0,
                sliderTimer;
            const track = document.getElementById('sliderTrack');
            const dotsBox = document.getElementById('sliderDots');

            if (SLIDES_COUNT > 1 && dotsBox) {
                for (let i = 0; i < SLIDES_COUNT; i++) {
                    const d = document.createElement('button');
                    d.className = 'sdot' + (i === 0 ? ' active' : '');
                    d.onclick = () => goSlide(i);
                    dotsBox.appendChild(d);
                }
            }

            function renderSlide() {
                if (!track) return;
                track.style.transform = `translateX(-${curSlide * (100 / SLIDES_COUNT)}%)`;
                document.querySelectorAll('.sdot').forEach((d, i) => d.classList.toggle('active', i === curSlide));
            }

            function goSlide(i) {
                curSlide = (i + SLIDES_COUNT) % SLIDES_COUNT;
                renderSlide();
                resetTimer();
            }

            function sliderMove(dir) {
                goSlide(curSlide + dir);
            }

            function resetTimer() {
                clearInterval(sliderTimer);
                if (SLIDES_COUNT > 1) sliderTimer = setInterval(() => sliderMove(1), 5500);
            }
            renderSlide();
            resetTimer();

            // Fade up on scroll
            const obs = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) e.target.classList.add('vis');
                });
            }, {
                threshold: .12
            });
            document.querySelectorAll('.fu').forEach(el => obs.observe(el));
        </script>
    @endpush

@endsection
