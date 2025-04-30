@extends('back.layouts.master')

@section('title', 'Xidmətlər')

@section('content')
<style>
    .swal2-popup {
        border-radius: 50px;
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
                    <h4 class="mb-sm-0">Xidmətlər</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item active">Xidmətlər</li>
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
                            <h4 class="card-title">Xidmətlər</h4>
                            <a href="{{ route('back.pages.services.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="ri-add-line align-middle me-1"></i> Yeni
                            </a>
                        </div>

                        @if(count($services) > 0)
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">AZ</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">EN</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block" style=" color: #ff8a33;">RU</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="az" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0" id="sortable-table">
                                            <thead>
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th width="100">Şəkil</th>
                                                    <th width="60">İkon</th>
                                                    <th>Başlıq (AZ)</th>
                                                    <th>Kateqoriya</th>
                                                    <th>Təsvir (AZ)</th>
                                                    <th width="80">Status</th>
                                                    <th width="150">Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody class="sortable">
                                                @foreach($services as $service)
                                                <tr data-id="{{ $service->id }}">
                                                    <td>
                                                        <span class="handle">
                                                            <i class="ri-drag-move-line" style="cursor: move;"></i>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($service->image)
                                                            <img src="{{ asset($service->image) }}" alt="{{ $service->title_az }}" class="img-thumbnail" style="max-height: 80px">
                                                        @else
                                                            <span class="text-muted">Şəkil yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($service->icon)
                                                            <img src="{{ asset($service->icon) }}" alt="İkon" class="img-thumbnail" style="max-height: 40px">
                                                        @else
                                                            <span class="text-muted">İkon yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $service->title_az }}</td>
                                                    <td>
                                                        @if($service->category)
                                                            {{ $service->category->title_az }}
                                                        @else
                                                            <span class="text-muted">Kateqoriya seçilməyib</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Illuminate\Support\Str::limit($service->description_az, 100) }}</td>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                            <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $service->id }}" data-id="{{ $service->id }}" {{ $service->status ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('back.pages.services.edit', $service->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                            <i class="mdi mdi-pencil font-size-16"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $service->id }}">
                                                            <i class="mdi mdi-trash-can font-size-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="en" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="80">ID</th>
                                                    <th width="100">Şəkil</th>
                                                    <th width="60">İkon</th>
                                                    <th>Başlıq (EN)</th>
                                                    <th>Kateqoriya</th>
                                                    <th>Təsvir (EN)</th>
                                                    <th width="80">Status</th>
                                                    <th width="150">Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($services as $service)
                                                <tr>
                                                    <td>{{ $service->id }}</td>
                                                    <td>
                                                        @if($service->image)
                                                            <img src="{{ asset($service->image) }}" alt="{{ $service->title_en }}" class="img-thumbnail" style="max-height: 80px">
                                                        @else
                                                            <span class="text-muted">Şəkil yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($service->icon)
                                                            <img src="{{ asset($service->icon) }}" alt="İkon" class="img-thumbnail" style="max-height: 40px">
                                                        @else
                                                            <span class="text-muted">İkon yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $service->title_en ?? 'Tərcümə yoxdur' }}</td>
                                                    <td>
                                                        @if($service->category)
                                                            {{ $service->category->title_en ?? $service->category->title_az }}
                                                        @else
                                                            <span class="text-muted">Kateqoriya seçilməyib</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $service->description_en ? \Illuminate\Support\Str::limit($service->description_en, 100) : 'Tərcümə yoxdur' }}</td>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                            <input class="form-check-input" type="checkbox" role="switch" {{ $service->status ? 'checked' : '' }} disabled>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('back.pages.services.edit', $service->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                            <i class="mdi mdi-pencil font-size-16"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $service->id }}">
                                                            <i class="mdi mdi-trash-can font-size-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="ru" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="80">ID</th>
                                                    <th width="100">Şəkil</th>
                                                    <th width="60">İkon</th>
                                                    <th>Başlıq (RU)</th>
                                                    <th>Kateqoriya</th>
                                                    <th>Təsvir (RU)</th>
                                                    <th width="80">Status</th>
                                                    <th width="150">Əməliyyatlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($services as $service)
                                                <tr>
                                                    <td>{{ $service->id }}</td>
                                                    <td>
                                                        @if($service->image)
                                                            <img src="{{ asset($service->image) }}" alt="{{ $service->title_ru }}" class="img-thumbnail" style="max-height: 80px">
                                                        @else
                                                            <span class="text-muted">Şəkil yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($service->icon)
                                                            <img src="{{ asset($service->icon) }}" alt="İkon" class="img-thumbnail" style="max-height: 40px">
                                                        @else
                                                            <span class="text-muted">İkon yoxdur</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $service->title_ru ?? 'Tərcümə yoxdur' }}</td>
                                                    <td>
                                                        @if($service->category)
                                                            {{ $service->category->title_ru ?? $service->category->title_az }}
                                                        @else
                                                            <span class="text-muted">Kateqoriya seçilməyib</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $service->description_ru ? \Illuminate\Support\Str::limit($service->description_ru, 100) : 'Tərcümə yoxdur' }}</td>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                            <input class="form-check-input" type="checkbox" role="switch" {{ $service->status ? 'checked' : '' }} disabled>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('back.pages.services.edit', $service->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                            <i class="mdi mdi-pencil font-size-16"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $service->id }}">
                                                            <i class="mdi mdi-trash-can font-size-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Hələ heç bir xidmət əlavə edilməyib. Yeni xidmət əlavə etmək üçün "Yeni" düyməsini klikləyin.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silmə Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Təsdiq</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bu xidməti silmək istədiyinizə əminsiniz?
            </div>
            <div class="modal-footer">
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        max-width: 100%;
        height: auto;
    }
    
    .handle {
        cursor: move;
        color: #aaa;
    }
    
    .handle:hover {
        color: #333;
    }
</style>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        // Tooltipleri etkinleştir
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Silme işlemi
        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            
            Swal.fire({
                title: 'Silmək istədiyinizdən əminsiniz?',
                text: "Bu əməliyyat geri alına bilməz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Bəli, sil!',
                cancelButtonText: 'Xeyr'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').attr('action', '{{ route("back.pages.services.destroy", "") }}/' + id);
                    $('#deleteForm').submit();
                }
            });
        });
        
        // Durum değiştirme
        $('.status-switch').on('change', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ route("back.pages.services.toggle-status", "") }}/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Status uğurla dəyişdirildi",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Xəta baş verdi",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Xəta baş verdi",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
        
        // Sıralama
        var sortable = new Sortable(document.querySelector('.sortable'), {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                var orders = [];
                $('.sortable tr').each(function() {
                    orders.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route("back.pages.services.order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orders: orders
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Sıralama uğurla yeniləndi",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            }
        });
    });
</script>
@endpush 