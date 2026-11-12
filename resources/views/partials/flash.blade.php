@if(session('success'))
    <div class="alert alert-success d-flex gap-2 align-items-start" role="alert"><i class="fa-solid fa-circle-check mt-1"></i><div>{{ session('success') }}</div></div>
@endif
@if(session('error'))
    <div class="alert alert-danger d-flex gap-2 align-items-start" role="alert"><i class="fa-solid fa-circle-exclamation mt-1"></i><div>{{ session('error') }}</div></div>
@endif
@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
