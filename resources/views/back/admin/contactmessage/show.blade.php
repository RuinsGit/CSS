@extends('back.layouts.master')

@section('title', 'Müraciət Detayı')

@section('content')
<style>
    .swal2-popup {
        border-radius: 50px;
    }
    .message-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .message-header {
        background-color: #f8f9fa;
        border-radius: 10px 10px 0 0;
    }
    .message-content {
        min-height: 200px;
        background-color: #fff;
        border-radius: 0 0 10px 10px;
    }
    .message-info-item {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    .message-info-item:last-child {
        border-bottom: none;
    }
    .message-label {
        font-weight: 600;
        color: #495057;
    }
    .message-value {
        color: #333;
    }
    .message-text {
        white-space: pre-line;
    }
</style>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: true,
                confirmButtonText: 'Yaxşı',
                timer: 1500
            });
        });
    </script>
@endif

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Müraciət Detayı</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.contactmessage.index') }}">Müraciətlər</a></li>
                            <li class="breadcrumb-item active">Müraciət Detayı</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card message-card">
                    <div class="card-header message-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                Mesaj #{{ $message->id }}
                                @if($message->is_read)
                                    <span class="badge bg-success">Oxundu</span>
                                @else
                                    <span class="badge bg-danger">Oxunmadi</span>
                                @endif
                            </h5>
                            <div>
                                <a href="{{ route('back.pages.contactmessage.index') }}" class="btn btn-primary btn-sm">
                                    <i class="ri-arrow-left-line align-middle me-1"></i> Geri Dön
                                </a>
                                <form action="{{ route('back.pages.contactmessage.toggle-read', $message->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $message->is_read ? 'warning' : 'success' }} btn-sm">
                                        <i class="ri-{{ $message->is_read ? 'mail-line' : 'mail-check-line' }} align-middle me-1"></i> 
                                        {{ $message->is_read ? 'Oxunmadi Olaraq İşaretle' : 'Oxundu Olaraq İşaretle' }}
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                    <i class="ri-delete-bin-line align-middle me-1"></i> Sil
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="message-info border rounded mb-4">
                                    <div class="message-info-item">
                                        <span class="message-label">Göndərən:</span>
                                        <span class="message-value float-end">{{ $message->first_name }} {{ $message->last_name }}</span>
                                    </div>
                                    <div class="message-info-item">
                                        <span class="message-label">E-poçt:</span>
                                        <span class="message-value float-end">{{ $message->email }}</span>
                                    </div>
                                    <div class="message-info-item">
                                        <span class="message-label">Movzu:</span>
                                        <span class="message-value float-end">{{ $message->subject }}</span>
                                    </div>
                                    <div class="message-info-item">
                                        <span class="message-label">Təqvim:</span>
                                        <span class="message-value float-end">{{ $message->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-primary">
                                        <i class="ri-mail-send-line align-middle me-1"></i> E-posta Gönder
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="message-content p-4 border rounded">
                                    <h5 class="border-bottom pb-2 mb-3">Müraciət Məzmunu</h5>
                                    <p class="message-text">{{ $message->message }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Təsdiq</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bu müraciəti silmek istədiyinizə əminsiniz?
            </div>
            <div class="modal-footer">
                <form action="{{ route('back.pages.contactmessage.destroy', $message->id) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ləğv et</button>
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Delete button click
        $('.delete-btn').on('click', function() {
            Swal.fire({
                title: 'Bu mesajı silmek istediğinizden emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').submit();
                }
            });
        });
    });
</script>
@endpush 