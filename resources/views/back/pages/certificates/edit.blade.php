@extends('back.layouts.master')

@section('title', 'Sertifkat Redaktə Et')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sertifkat Redaktə Et</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('back.pages.certificates.index') }}">Sertifikatlar</a></li>
                        <li class="breadcrumb-item active">Sertifkat Redaktə Et</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <form action="{{ route('back.pages.certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                    <label for="title">Başlık</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $certificate->title) }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="image">Şəkil</label>
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($certificate->image)
                                    <div class="mt-2">
                                        <img src="{{ asset($certificate->image) }}" alt="" class="img-thumbnail" style="max-height: 200px">
                                    </div>
                                    @endif
                                    <small class="text-muted">Şəkilin həcmi 2MB-dan çox ola bilməz. İzin verilen formatlar: JPG, JPEG, PNG, SVG, WEBP</small>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Redaktə Et</button>
                                <a href="{{ route('back.pages.certificates.index') }}" class="btn btn-secondary">Ləğv Et</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 