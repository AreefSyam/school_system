<div class="clear-both"></div>

{{-- Success Message --}}
@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
</div>
@endif

{{-- Error Message --}}
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
</div>
@endif

{{-- Payment Error --}}
@if (session()->has('payment error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('payment error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Warning Message --}}
@if (session()->has('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Info Message --}}
@if (session()->has('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    {{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Secondary Message --}}
@if (session()->has('secondary'))
<div class="alert alert-secondary alert-dismissible fade show" role="alert">
    {{ session('secondary') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Primary Message --}}
@if (session()->has('primary'))
<div class="alert alert-primary alert-dismissible fade show" role="alert">
    {{ session('primary') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Light Message --}}
@if (session()->has('light'))
<div class="alert alert-light alert-dismissible fade show" role="alert">
    {{ session('light') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
