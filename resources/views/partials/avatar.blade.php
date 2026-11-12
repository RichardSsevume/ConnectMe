@php $size = $size ?? 44; @endphp
@if($u->photoUrl())
    <img class="avatar" src="{{ $u->photoUrl() }}" alt="{{ $u->displayName() }}" style="width:{{ $size }}px;height:{{ $size }}px">
@else
    <span class="avatar avatar-initials" aria-hidden="true" style="width:{{ $size }}px;height:{{ $size }}px;font-size:{{ round($size * 0.38) }}px">{{ $u->initials() }}</span>
@endif
