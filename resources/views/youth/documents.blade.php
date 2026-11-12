@extends('layouts.dashboard')
@section('title', 'Upload Documents')
@section('content')
<div class="page-head">
    <h1>My Documents</h1>
    <p>Upload your CV, academic documents and professional certificates to strengthen your employment applications.</p>
</div>
<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Document</th><th>File</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @foreach(\App\Models\Document::TYPES as $type => [$label, $button])
                @php $doc = $documents->get($type); @endphp
                <tr>
                    <td><i class="fa-solid fa-file-lines text-teal me-2"></i><strong>{{ $label }}</strong></td>
                    <td>@if($doc)<span class="text-muted">{{ $doc->original_name }}</span>@else<span class="text-muted">Not uploaded yet</span>@endif</td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end flex-wrap">
                            <form class="d-flex gap-2" method="POST" action="{{ route('youth.documents.upload', $type) }}" enctype="multipart/form-data">@csrf
                                <input class="form-control form-control-sm" style="max-width:210px" type="file" name="file" required aria-label="{{ $label }} file">
                                <button class="btn btn-teal btn-sm">{{ $doc ? 'Replace Document' : $button }}</button>
                            </form>
                            @if($doc)
                                <a class="btn btn-outline-navy btn-sm" href="{{ $doc->url() }}" target="_blank" rel="noopener">View Document</a>
                                <form method="POST" action="{{ route('youth.documents.delete', $type) }}" onsubmit="return confirm('Delete this document?')">@csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Delete Document</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <p class="form-text mt-3 mb-0">Accepted files: PDF, Word, JPG, PNG or ZIP, up to 5 MB each.</p>
</div>
@endsection
