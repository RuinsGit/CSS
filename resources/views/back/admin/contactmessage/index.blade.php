@extends('back.layouts.master')

@section('title', 'Müraciətlər')

@section('content')
<style>
    .swal2-popup {
        border-radius: 50px;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .unread {
        font-weight: 600;
        background-color: rgba(52, 152, 219, 0.1);
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

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "{{ session('error') }}",
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
                    <h4 class="mb-sm-0">Müraciətlər</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Səhifə</a></li>
                            <li class="breadcrumb-item active">Müraciətlər</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="card-title">
                                Müraciətlər Siyahısı
                                @if($unreadCount > 0)
                                <span class="badge rounded-pill bg-danger">{{ $unreadCount }} Müraciət</span>
                                @endif
                            </h4>
                            
                            @if(count($messages) > 0)
                            <div>
                                <button id="deleteAllButton" class="btn btn-danger">
                                    <i class="ri-delete-bin-line align-bottom me-1"></i> Seçilen Müraciətləri Sil
                                </button>
                            </div>
                            @endif
                        </div>

                        @if(count($messages) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="messagesTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                </div>
                                            </th>
                                            <th width="80">ID</th>
                                            <th>Ad</th>
                                            <th>E-poçt</th>
                                            <th>Movzu</th>
                                            <th width="150">Təqvim</th>
                                            <th width="50">Status</th>
                                            <th width="120">İşlər</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($messages as $message)
                                            <tr class="{{ $message->is_read ? '' : 'unread' }}">
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input message-checkbox" type="checkbox" value="{{ $message->id }}">
                                                    </div>
                                                </td>
                                                <td>{{ $message->id }}</td>
                                                <td>{{ $message->first_name }} {{ $message->last_name }}</td>
                                                <td>{{ $message->email }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($message->subject, 50) }}</td>
                                                <td>{{ $message->created_at->format('d.m.Y H:i') }}</td>
                                                <td class="text-center">
                                                    @if($message->is_read)
                                                        <span class="badge bg-success">Oxundu</span>
                                                    @else
                                                        <span class="badge bg-danger">Oxunmadi</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('back.pages.contactmessage.show', $message->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Görüntüle">
                                                            <i class="ri-eye-line font-size-16"></i>
                                                        </a>
                                                        <form action="{{ route('back.pages.contactmessage.toggle-read', $message->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-{{ $message->is_read ? 'warning' : 'success' }} btn-sm" data-bs-toggle="tooltip" title="{{ $message->is_read ? 'Okunmadı olarak işaretle' : 'Okundu olarak işaretle' }}">
                                                                <i class="ri-{{ $message->is_read ? 'mail-line' : 'mail-check-line' }} font-size-16"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $message->id }}" data-bs-toggle="tooltip" title="Sil">
                                                            <i class="ri-delete-bin-line font-size-16"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-3">
                                {{ $messages->links() }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                Hələ heç müraciət yoxdur.
                            </div>
                        @endif
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
                <form action="" method="POST" id="deleteForm">
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
        // Tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Check All / Uncheck All
        $("#checkAll").click(function() {
            $('.message-checkbox').prop('checked', $(this).prop('checked'));
        });
        
        // Delete button click
        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            
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
                    var url = "{{ route('back.pages.contactmessage.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    
                    $('#deleteForm').attr('action', url);
                    $('#deleteForm').submit();
                }
            });
        });
        
        // Delete selected messages
        $('#deleteAllButton').click(function() {
            var selectedIds = [];
            
            $('.message-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Uyarı',
                    text: 'Lütfen en az bir mesaj seçin!',
                });
                return;
            }
            
            Swal.fire({
                title: 'Seçilen mesajları silmek istediğinizden emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('back.pages.contactmessage.bulk-delete') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: selectedIds.join(',')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.success,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Bir hata oluştu!',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush 