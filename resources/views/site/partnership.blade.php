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
                <p>Kami melayani kebutuhan pembelian pribadi maupun usaha retail. Tim kami akan menghubungi Anda untuk membahas kebutuhan produk dan langkah selanjutnya.</p>
            </div>
            <div class="partnership-benefits">
                <div class="partnership-benefit">
                    <i class="fas fa-boxes-stacked"></i>
                    <div><strong>Produk Beragam</strong><span>Gelas, sendok, tusuk bambu, styrofoam, dan kemasan lainnya.</span></div>
                </div>
                <div class="partnership-benefit">
                    <i class="fas fa-users"></i>
                    <div><strong>End User & Retail</strong><span>Layanan disesuaikan untuk kebutuhan pribadi maupun toko retail.</span></div>
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
                <h2>Hubungi Tim Kemitraan Kami</h2>
                <p>Isi data singkat berikut dan tim kami akan segera menghubungi Anda.</p>
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
                    <label for="partner_address">Alamat Lengkap <b>*</b></label>
                    <textarea id="partner_address" name="address" rows="3" maxlength="1000" required placeholder="Tuliskan alamat lengkap Anda">{{ old('address') }}</textarea>
                </div>
                <fieldset class="partnership-field partnership-field-full">
                    <legend>{{ $fieldLabels['customer_type'] }} <b>*</b></legend>
                    <div class="partnership-checks partnership-customer-types">
                        @foreach ($customerTypes as $value => $label)
                            <label>
                                <input type="radio" name="customer_type" value="{{ $value }}" required @checked(old('customer_type') === $value)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
                <div class="partnership-field partnership-field-full">
                    <label for="partner_message">Pertanyaan atau Catatan</label>
                    <textarea id="partner_message" name="message" rows="4" maxlength="2000" placeholder="Ceritakan kebutuhan atau pertanyaan Anda">{{ old('message') }}</textarea>
                </div>
                <div class="partnership-submit partnership-field-full">
                    <button type="submit"><i class="fas fa-paper-plane"></i> Kirim Pengajuan Kemitraan</button>
                    <small>Dengan mengirim form, Anda menyetujui tim kami menghubungi Anda melalui WhatsApp.</small>
                </div>
            </form>
        </div>
    </section>
@endsection
