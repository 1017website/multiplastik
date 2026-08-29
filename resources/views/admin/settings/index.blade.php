@extends('admin.layout')
@section('title', 'Site Settings')
@section('content')

@php
    $tabLabels = [
        'general' => ['Umum & SEO', 'fas fa-cog'],
        'homepage' => ['Bagian Beranda', 'fas fa-home'],
        'contact' => ['Kontak & Sosmed', 'fas fa-phone'],
        'about' => ['Section Tentang', 'fas fa-info-circle'],
        'keunggulan' => ['Section Keunggulan', 'fas fa-trophy'],
        'hero_stats' => ['Hero Stats Bar', 'fas fa-chart-bar'],
        'sosmed_embed' => ['Embed Sosmed', 'fab fa-instagram'],
        'analytics' => ['Analytics (GA, GTM, Pixel)', 'fas fa-chart-line'],
        'ads' => ['Iklan & Custom Script', 'fas fa-bullseye'],
        'partnership' => ['Form Kemitraan', 'fas fa-handshake'],
    ];
@endphp

<ul class="nav nav-pills mb-3 flex-wrap">
    @foreach($tabLabels as $key => $info)
        <li class="nav-item">
            <a class="nav-link {{ $group === $key ? 'active' : '' }}" href="{{ route('admin.settings.index', $key) }}" style="{{ $group === $key ? 'background:#C0272D;' : '' }}">
                <i class="{{ $info[1] }}"></i> {{ $info[0] }}
            </a>
        </li>
    @endforeach
</ul>

<form method="POST" action="{{ route('admin.settings.update', $group) }}" enctype="multipart/form-data">
    @csrf

    @if($group === 'partnership')
        <div class="alert alert-info border-0 d-flex gap-3 align-items-start mb-3 partnership-intro">
            <i class="fas fa-circle-info mt-1"></i>
            <div>
                <strong>Atur pertanyaan pada form calon mitra</strong>
                <div class="small mt-1">Ubah judul pertanyaan atau pilihan jawabannya. Tekan <strong>Tambah Pilihan</strong> untuk membuat jawaban baru.</div>
            </div>
        </div>
    @endif

    <div class="card p-4">
        <div class="row g-3">
            @foreach($fields as $key => $config)
                @php
                    $type = $config['type'] ?? 'text';
                    $val = $values[$key] ?? '';
                    $col = in_array($type, ['textarea', 'option_list']) ? 'col-12' : 'col-md-6';
                @endphp
                <div class="{{ $col }} {{ $type === 'option_list' ? 'partnership-option-section' : '' }}">
                    <label class="form-label">{{ $config['label'] }}</label>

                    @if($type === 'textarea')
                        <textarea name="{{ $key }}" class="form-control" rows="3">{{ $val }}</textarea>
                    @elseif($type === 'option_list')
                        @php
                            $parsedOptions = \App\Models\PartnershipApplication::parseOptions($val);
                            $optionLabels = old($key.'_labels', array_values($parsedOptions));
                            $optionValues = old($key.'_values', array_keys($parsedOptions));
                        @endphp
                        <div class="option-list-editor" data-option-editor>
                            <div class="option-list" data-option-list>
                                @foreach($optionLabels as $optionIndex => $optionLabel)
                                    <div class="option-row" data-option-row>
                                        <span class="option-number" data-option-number>{{ $loop->iteration }}</span>
                                        <input type="hidden" name="{{ $key }}_values[]" value="{{ $optionValues[$optionIndex] ?? '' }}">
                                        <input type="text" name="{{ $key }}_labels[]" class="form-control"
                                            value="{{ $optionLabel }}" placeholder="Tulis pilihan jawaban" maxlength="150" required>
                                        <div class="option-actions" aria-label="Atur pilihan">
                                            <button type="button" class="btn btn-light btn-sm" data-move-up title="Geser ke atas" aria-label="Geser ke atas">
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                            <button type="button" class="btn btn-light btn-sm" data-move-down title="Geser ke bawah" aria-label="Geser ke bawah">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-remove-option title="Hapus pilihan" aria-label="Hapus pilihan">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-add-option data-field-key="{{ $key }}">
                                <i class="fas fa-plus me-1"></i> Tambah Pilihan
                            </button>
                        </div>
                    @elseif($type === 'boolean')
                        <input type="hidden" name="{{ $key }}" value="0">
                        <div class="form-check form-switch border rounded p-3 ps-5 bg-light">
                            <input class="form-check-input" type="checkbox" role="switch" id="{{ $key }}"
                                name="{{ $key }}" value="1" @checked((string) $val !== '0')>
                            <label class="form-check-label fw-semibold" for="{{ $key }}">Aktif</label>
                        </div>
                    @elseif($type === 'image')
                        @if($val)
                            <div class="mb-2">
                                <img src="{{ str_starts_with($val, 'http') ? $val : asset($val) }}" style="max-height:60px;border:1px solid #ddd;border-radius:4px;padding:3px;background:#fff;">
                            </div>
                        @endif
                        <input type="file" name="{{ $key }}" class="form-control mb-2" accept="image/*">
                        <input type="text" name="{{ $key }}_url_manual" class="form-control" placeholder="...atau paste URL gambar">
                    @else
                        <input type="text" name="{{ $key }}" class="form-control" value="{{ $val }}">
                    @endif

                    @if(!empty($config['help']))
                        <small class="text-muted d-block mt-1">{{ $config['help'] }}</small>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
    </div>
</form>

@if($group === 'analytics')
    <div class="alert alert-info mt-4">
        <h6 class="mb-2"><i class="fas fa-lightbulb"></i> Cara Pasang</h6>
        <ul class="mb-0 small">
            <li><strong>Google Analytics 4</strong>: cari Measurement ID di GA4 → Admin → Data Streams. Format: <code>G-XXXXXXX</code></li>
            <li><strong>Google Tag Manager</strong>: cari Container ID di GTM. Format: <code>GTM-XXXXX</code></li>
            <li><strong>Meta Pixel</strong>: dari Meta Events Manager → ambil Pixel ID (angka 15-16 digit)</li>
            <li><strong>TikTok Pixel</strong>: dari TikTok Events Manager</li>
        </ul>
    </div>
@elseif($group === 'ads')
    <div class="alert alert-info mt-4">
        <h6 class="mb-2"><i class="fas fa-lightbulb"></i> Tips Tracking Konversi</h6>
        <ul class="mb-0 small">
            <li><strong>Google Ads Conversion ID</strong>: dari Google Ads → Tools → Conversions. Format: <code>AW-XXXXXXXXX</code></li>
            <li>Pasang UTM di link iklan agar bisa dilihat di menu <strong>Analytics</strong>: <code>?utm_source=meta&utm_medium=cpc&utm_campaign=promo-2025</code></li>
            <li><strong>Custom Script</strong>: untuk script tambahan yang belum tersedia di field di atas</li>
        </ul>
    </div>
@endif
@endsection

@if($group === 'partnership')
    @push('styles')
        <style>
            .partnership-intro {
                background: #eef6ff;
                color: #35526f;
            }

            .partnership-option-section {
                border-top: 1px solid #edf0f3;
                padding-top: 18px;
            }

            .partnership-option-section > .form-label {
                font-weight: 600;
                margin-bottom: 4px;
            }

            .option-list-editor {
                max-width: 900px;
            }

            .option-list {
                display: grid;
                gap: 8px;
            }

            .option-row {
                display: grid;
                grid-template-columns: 30px minmax(0, 1fr) auto;
                align-items: center;
                gap: 9px;
                padding: 8px;
                background: #f8f9fb;
                border: 1px solid #e8ebef;
                border-radius: 8px;
            }

            .option-number {
                width: 26px;
                height: 26px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: #e9ecef;
                color: #6b7280;
                font-size: 12px;
                font-weight: 600;
            }

            .option-actions {
                display: flex;
                gap: 5px;
            }

            .option-actions .btn {
                width: 34px;
                height: 34px;
                padding: 0;
            }

            @media (max-width: 576px) {
                .option-row {
                    grid-template-columns: 26px minmax(0, 1fr);
                }

                .option-actions {
                    grid-column: 2;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.querySelectorAll('[data-option-editor]').forEach((editor) => {
                const list = editor.querySelector('[data-option-list]');
                const addButton = editor.querySelector('[data-add-option]');
                const fieldKey = addButton.dataset.fieldKey;

                const refreshRows = () => {
                    const rows = [...list.querySelectorAll('[data-option-row]')];

                    rows.forEach((row, index) => {
                        row.querySelector('[data-option-number]').textContent = index + 1;
                        row.querySelector('[data-move-up]').disabled = index === 0;
                        row.querySelector('[data-move-down]').disabled = index === rows.length - 1;
                    });
                };

                const createRow = () => {
                    const row = document.createElement('div');
                    row.className = 'option-row';
                    row.dataset.optionRow = '';
                    row.innerHTML = `
                        <span class="option-number" data-option-number></span>
                        <input type="hidden" name="${fieldKey}_values[]" value="">
                        <input type="text" name="${fieldKey}_labels[]" class="form-control"
                            placeholder="Tulis pilihan jawaban" maxlength="150" required>
                        <div class="option-actions" aria-label="Atur pilihan">
                            <button type="button" class="btn btn-light btn-sm" data-move-up title="Geser ke atas" aria-label="Geser ke atas"><i class="fas fa-arrow-up"></i></button>
                            <button type="button" class="btn btn-light btn-sm" data-move-down title="Geser ke bawah" aria-label="Geser ke bawah"><i class="fas fa-arrow-down"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-remove-option title="Hapus pilihan" aria-label="Hapus pilihan"><i class="fas fa-trash-alt"></i></button>
                        </div>`;

                    return row;
                };

                addButton.addEventListener('click', () => {
                    const row = createRow();
                    list.appendChild(row);
                    refreshRows();
                    row.querySelector('input[type="text"]').focus();
                });

                list.addEventListener('click', (event) => {
                    const row = event.target.closest('[data-option-row]');
                    if (!row) return;

                    if (event.target.closest('[data-remove-option]')) {
                        row.remove();
                    } else if (event.target.closest('[data-move-up]') && row.previousElementSibling) {
                        list.insertBefore(row, row.previousElementSibling);
                    } else if (event.target.closest('[data-move-down]') && row.nextElementSibling) {
                        list.insertBefore(row.nextElementSibling, row);
                    }

                    refreshRows();
                });

                refreshRows();
            });
        </script>
    @endpush
@endif
