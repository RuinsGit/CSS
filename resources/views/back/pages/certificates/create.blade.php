@extends('back.layouts.master')

@section('title', 'Sertifikat Yarat')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sertifikat Yarat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('back.pages.certificates.index') }}">Sertifikalar</a></li>
                        <li class="breadcrumb-item active">Yeni Sertifika</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <form action="{{ route('back.pages.certificates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <div class="form-group">
                                    <label for="title">Başlıq</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="image">Şəkil Yükləyin</label>
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" required>
                                    @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Şəkilin həcmi 2MB-dan çox ola bilməz. İzin verilen formatlar: JPG, JPEG, PNG, SVG, WEBP</small>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Yarat</button>
                                <a href="{{ route('back.pages.certificates.index') }}" class="btn btn-secondary">İptal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 