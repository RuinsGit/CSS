@extends('back.layouts.master')

@section('title', 'Sertifikatlar')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sertifikatlar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item active">Sertifikatlar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('back.pages.certificates.create') }}" class="btn btn-primary">Yeni Sertifikat</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th>Şəkil</th>
                                <th>Başlıq</th>
                                <th>Status</th>
                                <th style="width: 200px">Emeliyyatlar</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            @foreach($certificates as $certificate)
                            <tr id="{{ $certificate->id }}">
                                <td>{{ $certificate->id }}</td>
                                <td>
                                    @if($certificate->image)
                                        <img src="{{ asset($certificate->image) }}" alt="" style="max-height: 100px" class="img-thumbnail">
                                    @endif
                                </td>
                                <td>{{ $certificate->title }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch{{ $certificate->id }}" 
                                            {{ $certificate->status ? 'checked' : '' }}
                                            onchange="toggleStatus({{ $certificate->id }})">
                                        <label class="custom-control-label" for="customSwitch{{ $certificate->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('back.pages.certificates.edit', $certificate->id) }}" class="btn btn-sm btn-warning">Redaktə Et</a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteCertificate({{ $certificate->id }})">Sil</button>
                                    <form id="delete-form-{{ $certificate->id }}" action="{{ route('back.pages.certificates.destroy', $certificate->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    function deleteCertificate(id) {
        if (confirm('Silmek istediğinize emin misiniz?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
    
    function toggleStatus(id) {
        $.ajax({
            url: '{{ route("back.pages.certificates.toggle-status", ":id") }}'.replace(':id', id),
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Durum başarıyla değiştirildi');
                }
            },
            error: function() {
                toastr.error('Bir hata oluştu');
            }
        });
    }
    
    $(function() {
        $("#sortable").sortable({
            handle: 'td',
            update: function(event, ui) {
                var data = $(this).sortable('toArray');
                var certificates = [];
                
                $.each(data, function(index, id) {
                    certificates.push({
                        id: id,
                        order: index
                    });
                });
                
                $.ajax({
                    url: '{{ route("back.pages.certificates.order") }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "certificates": certificates
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Sıralama başarıyla güncellendi');
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu');
                    }
                });
            }
        });
    });
</script>
@endpush 