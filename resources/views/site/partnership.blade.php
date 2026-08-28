@extends('site.layout')
@section('title', 'Kemitraan Usaha Toko Plastik – '.setting('site_title', 'Multi Plastik'))
@section('meta_description', 'Daftar kemitraan Multi Plastik untuk memulai atau mengembangkan usaha toko plastik dan kemasan.')

@section('content')
    <section class="partnership-hero">
        <div class="partnership-hero-inner">
            <div class="sec-label">Kemitraan Multi Plastik</div>
            <h1>Mulai Usaha Toko Plastik<br><em>Bersama Kami</em></h1>
            <p>Dapatkan akses produk kemasan dari brand terpercaya dan dukungan untuk menyiapkan usaha toko plastik Anda.</p>
            <a href="#form-kemitraan" class="btn-p"><i class="fas fa-handshake"></i> Daftar Kemitraan</a>
        </div>
    </section>

    <section class="partnership-content">
        <div class="partnership-intro">
            <div>
                <div class="sec-label">Peluang Usaha</div>
                <h2 class="sec-title">Bangun Toko Plastik dengan Produk yang Tepat</h2>
                <div class="sec-div"></div>
                <p>Kami membantu calon mitra memahami kebutuhan awal berdasarkan lokasi, modal, dan target usahanya. Tim kami akan menghubungi Anda untuk membahas pilihan produk dan langkah selanjutnya.</p>
            </div>
            <div class="partnership-benefits">
                <div class="partnership-benefit">
                    <i class="fas fa-boxes-stacked"></i>
                    <div><strong>Produk Beragam</strong><span>Gelas, sendok, tusuk bambu, styrofoam, dan kemasan lainnya.</span></div>
                </div>
                <div class="partnership-benefit">
                    <i class="fas fa-chart-line"></i>
                    <div><strong>Sesuai Skala Modal</strong><span>Rekomendasi awal disesuaikan dengan kesiapan dan target usaha.</span></div>
                </div>
                <div class="partnership-benefit">
                    <i class="fas fa-headset"></i>
                    <div><strong>Konsultasi Tim</strong><span>Prospek akan ditindaklanjuti langsung melalui WhatsApp.</span></div>
                </div>
            </div>
        </div>

        <div id="form-kemitraan" class="partnership-form-wrap">
            <div class="partnership-form-head">
                <span>Form Calon Mitra</span>
                <h2>Ceritakan Rencana Usaha Anda</h2>
                <p>Isi data berikut agar tim kami dapat memberikan arahan yang lebih sesuai.</p>
            </div>

            @if (session('partnership_success'))
                <div class="partnership-success">
                    <i class="fas fa-circle-check"></i>
                    <div><strong>Pengajuan berhasil dikirim.</strong><span>{{ session('partnership_success') }}</span></div>
                </div>
            @endif

            @if ($errors->any())
                <div class="partnership-errors">
                    <strong>Mohon periksa kembali data berikut:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('site.partnership.store') }}" class="partnership-form">
                @csrf
                <div class="partnership-honeypot" aria-hidden="true">
                    <label for="website">Website</label>
                    <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="partnership-field">
                    <label for="partner_name">Nama Lengkap <b>*</b></label>
                    <input id="partner_name" type="text" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name">
                </div>
                <div class="partnership-field">
                    <label for="partner_whatsapp">Nomor WhatsApp <b>*</b></label>
                    <input id="partner_whatsapp" type="tel" name="whatsapp" value="{{ old('whatsapp') }}" required maxlength="30" placeholder="Contoh: 0812 3456 7890" autocomplete="tel">
                </div>
                <div class="partnership-field">
                    <label for="partner_email">Email</label>
                    <input id="partner_email" type="email" name="email" value="{{ old('email') }}" maxlength="190" autocomplete="email">
                </div>
                <div class="partnership-field">
                    <label for="partner_city">Kota/Kabupaten <b>*</b></label>
                    <input id="partner_city" type="text" name="city" value="{{ old('city') }}" required maxlength="120" autocomplete="address-level2">
                </div>
                <div class="partnership-field partnership-field-full">
                    <label for="partner_address">Rencana Lokasi/Alamat Usaha</label>
                    <textarea id="partner_address" name="address" rows="3" maxlength="1000" placeholder="Tuliskan area atau alamat rencana toko">{{ old('address') }}</textarea>
                </div>
                <div class="partnership-field">
                    <label for="partner_stage">Kondisi Usaha Saat Ini <b>*</b></label>
                    <select id="partner_stage" name="business_stage" required>
                        <option value="">— Pilih kondisi usaha —</option>
                        @foreach ($businessStages as $value => $label)
                            <option value="{{ $value }}" @selected(old('business_stage') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="partnership-field">
                    <label for="partner_capital">Kisaran Modal Awal <b>*</b></label>
                    <select id="partner_capital" name="capital_range" required>
                        <option value="">— Pilih kisaran modal —</option>
                        @foreach ($capitalRanges as $value => $label)
                            <option value="{{ $value }}" @selected(old('capital_range') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="partnership-field partnership-field-full">
                    <label for="partner_timeline">Target Mulai Usaha <b>*</b></label>
                    <select id="partner_timeline" name="start_timeline" required>
                        <option value="">— Pilih target waktu —</option>
                        @foreach ($startTimelines as $value => $label)
                            <option value="{{ $value }}" @selected(old('start_timeline') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <fieldset class="partnership-field partnership-field-full">
                    <legend>Produk yang Diminati</legend>
                    <div class="partnership-checks">
                        @foreach (['Gelas Plastik', 'Gelas Printing', 'Tusuk Bambu', 'Sendok Plastik', 'Styrofoam', 'Produk Lainnya'] as $product)
                            <label><input type="checkbox" name="preferred_products[]" value="{{ $product }}" @checked(in_array($product, old('preferred_products', [])))><span>{{ $product }}</span></label>
                        @endforeach
                    </div>
                </fieldset>
                <div class="partnership-field partnership-field-full">
                    <label for="partner_message">Pertanyaan atau Catatan</label>
                    <textarea id="partner_message" name="message" rows="4" maxlength="2000" placeholder="Ceritakan kebutuhan atau pertanyaan Anda">{{ old('message') }}</textarea>
                </div>
                <div class="partnership-consent partnership-field-full">
                    <label>
                        <input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
                        <span>Saya menyetujui data ini digunakan oleh Multi Plastik untuk menghubungi saya terkait kemitraan. <b>*</b></span>
                    </label>
                </div>
                <div class="partnership-submit partnership-field-full">
                    <button type="submit"><i class="fas fa-paper-plane"></i> Kirim Pengajuan Kemitraan</button>
                    <small>Tim kami akan menghubungi Anda melalui WhatsApp.</small>
                </div>
            </form>
        </div>
    </section>
@endsection
