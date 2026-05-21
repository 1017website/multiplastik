@php
    $gtm = \App\Models\SiteSetting::get('google_tag_manager_id');
    $customBody = \App\Models\SiteSetting::get('custom_body_scripts');
@endphp

@if ($gtm)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtm }}" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
@endif

@if ($customBody)
    {!! $customBody !!}
@endif
