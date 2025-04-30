@extends('back.layouts.master')

@section('title', 'Komanda Üzvləri')

@section('content')
<style>
    .swal2-popup {
        border-radius: 50px;
    }
    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 5px;
        font-size: 16px;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 50%;
        color: #fff;
    }
    .social-icon.linkedin { background-color: #0077b5; }
    .social-icon.instagram { background-color: #e4405f; }
    .social-icon.facebook { background-color: #3b5999; }
    .social-icon.twitter { background-color: #55acee; }
    .social-icon.youtube { background-color: #cd201f; }
    .social-icon.whatsapp { background-color: #25D366; }
    .social-icon.telegram { background-color: #0088cc; }
    .social-icon.website { background-color: #333; }
    
    .social-icon-img {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 5px;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 50%;
        overflow: hidden;
    }
    .social-icon-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
                    <h4 class="mb-sm-0">Komanda Üzvləri</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item active">Komanda Üzvləri</li>
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
                            <h4 class="card-title">Komanda Üzvləri Siyahısı</h4>
                            <a href="{{ route('back.pages.team.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="ri-add-line align-middle me-1"></i> Yeni Üzv
                            </a>
                        </div>

                        <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#az" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block" style="color: #ff8a33;">AZ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#en" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block" style="color: #ff8a33;">EN</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ru" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block" style="color: #ff8a33;">RU</span>
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
                                                <th width="80">ID</th>
                                                <th width="150">Şəkil</th>
                                                <th>Ad Soyad (AZ)</th>
                                                <th>Vəzifə (AZ)</th>
                                                <th>Sosial Hesablar</th>
                                                <th width="100">Status</th>
                                                <th width="150">Əməliyyatlar</th>
                                            </tr>
                                        </thead>
                                        <tbody class="sortable">
                                            @foreach($teams as $team)
                                            <tr data-id="{{ $team->id }}" data-order="{{ $team->order }}">
                                                <td class="handle"><i class="ri-drag-move-fill" style="cursor: move;"></i></td>
                                                <td>{{ $team->id }}</td>
                                                <td>
                                                    @if($team->image)
                                                        <img src="{{ asset($team->image) }}" alt="{{ $team->name_az }}" class="img-thumbnail" style="max-height: 80px">
                                                    @else
                                                        <span class="text-muted">Şəkil yoxdur</span>
                                                    @endif
                                                </td>
                                                <td>{{ $team->name_az }}</td>
                                                <td>{{ $team->position_az }}</td>
                                                <td>
                                                    @if(!empty($team->social_accounts))
                                                        @foreach($team->social_accounts as $account)
                                                            @if(!empty($account['icon_image']))
                                                                <a href="{{ $account['url'] }}" target="_blank" title="{{ $account['platform'] }}" class="social-icon {{ strtolower($account['platform']) }}" style="background-color: transparent; overflow: hidden;">
                                                                    <img src="{{ asset($account['icon_image']) }}" alt="{{ $account['platform'] }}" style="width: 30px; height: 30px; object-fit: cover;">
                                                                </a>
                                                            @else
                                                                <a href="{{ $account['url'] }}" target="_blank" title="{{ $account['platform'] }}" class="social-icon {{ strtolower($account['platform']) }}">
                                                                    <i class="ri-{{ $account['icon'] ? $account['icon'] : strtolower($account['platform']) }}-fill"></i>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Sosial hesab yoxdur</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                        <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $team->id }}" data-id="{{ $team->id }}" {{ $team->status ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('back.pages.team.edit', $team->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                        <i class="mdi mdi-pencil font-size-16"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $team->id }}">
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
                                                <th width="150">Şəkil</th>
                                                <th>Ad Soyad (EN)</th>
                                                <th>Vəzifə (EN)</th>
                                                <th>Sosial Hesablar</th>
                                                <th width="100">Status</th>
                                                <th width="150">Əməliyyatlar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teams as $team)
                                            <tr>
                                                <td>{{ $team->id }}</td>
                                                <td>
                                                    @if($team->image)
                                                        <img src="{{ asset($team->image) }}" alt="{{ $team->name_en }}" class="img-thumbnail" style="max-height: 80px">
                                                    @else
                                                        <span class="text-muted">Şəkil yoxdur</span>
                                                    @endif
                                                </td>
                                                <td>{{ $team->name_en ?? 'Tərcümə yoxdur' }}</td>
                                                <td>{{ $team->position_en ?? 'Tərcümə yoxdur' }}</td>
                                                <td>
                                                    @if(!empty($team->social_accounts))
                                                        @foreach($team->social_accounts as $account)
                                                            @if(!empty($account['icon_image']))
                                                                <a href="{{ $account['url'] }}" target="_blank" title="{{ $account['platform'] }}" class="social-icon {{ strtolower($account['platform']) }}" style="background-color: transparent; overflow: hidden;">
                                                                    <img src="{{ asset($account['icon_image']) }}" alt="{{ $account['platform'] }}" style="width: 30px; height: 30px; object-fit: cover;">
                                                                </a>
                                                            @else
                                                                <a href="{{ $account['url'] }}" target="_blank" title="{{ $account['platform'] }}" class="social-icon {{ strtolower($account['platform']) }}">
                                                                    <i class="ri-{{ $account['icon'] ? $account['icon'] : strtolower($account['platform']) }}-fill"></i>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Sosial hesab yoxdur</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                        <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $team->id }}_en" data-id="{{ $team->id }}" {{ $team->status ? 'checked' : '' }} disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('back.pages.team.edit', $team->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                        <i class="mdi mdi-pencil font-size-16"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $team->id }}">
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
                                                <th width="150">Şəkil</th>
                                                <th>Ad Soyad (RU)</th>
                                                <th>Vəzifə (RU)</th>
                                                <th>Sosial Hesablar</th>
                                                <th width="100">Status</th>
                                                <th width="150">Əməliyyatlar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teams as $team)
                                            <tr>
                                                <td>{{ $team->id }}</td>
                                                <td>
                                                    @if($team->image)
                                                        <img src="{{ asset($team->image) }}" alt="{{ $team->name_ru }}" class="img-thumbnail" style="max-height: 80px">
                                                    @else
                                                        <span class="text-muted">Şəkil yoxdur</span>
                                                    @endif
                                                </td>
                                                <td>{{ $team->name_ru ?? 'Tərcümə yoxdur' }}</td>
                                                <td>{{ $team->position_ru ?? 'Tərcümə yoxdur' }}</td>
                                                <td>
                                                    @if(!empty($team->social_accounts))
                                                        @foreach($team->social_accounts as $account)
                                                            @if(!empty($account['icon_image']))
                                                                <a href="{{ $account['url'] }}" target="_blank" title="{{ $account['platform'] }}" class="social-icon {{ strtolower($account['platform']) }}" style="background-color: transparent; overflow: hidden;">
                                                                    <img src="{{ asset($account['icon_image']) }}" alt="{{ $account['platform'] }}" style="width: 30px; height: 30px; object-fit: cover;">
                                                                </a>
                                                            @else
                                                                <a href="{{ $account['url'] }}" target="_blank" title="{{ $account['platform'] }}" class="social-icon {{ strtolower($account['platform']) }}">
                                                                    <i class="ri-{{ $account['icon'] ? $account['icon'] : strtolower($account['platform']) }}-fill"></i>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Sosial hesab yoxdur</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch form-switch-success mb-3" style="margin-bottom: 0 !important">
                                                        <input class="form-check-input status-switch" type="checkbox" role="switch" id="statusSwitch_{{ $team->id }}_ru" data-id="{{ $team->id }}" {{ $team->status ? 'checked' : '' }} disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('back.pages.team.edit', $team->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Düzənlə">
                                                        <i class="mdi mdi-pencil font-size-16"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" title="Sil" data-id="{{ $team->id }}">
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
                Bu komanda üzvünü silmək istədiyinizə əminsiniz?
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
    }
</style>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        // İkon görüntüleme sorununun tespiti
        $('.social-icon img').each(function() {
            var img = $(this);
            img.on('error', function() {
                console.error('Resim yüklenemiyor: ' + img.attr('src'));
                // Resim yüklenmezse varsayılan ikonu göster
                var platform = img.closest('.social-icon').attr('title').toLowerCase();
                img.parent().css('background-color', getSocialColor(platform));
                img.replaceWith('<i class="ri-' + platform + '-fill"></i>');
            });
        });

        // Platform rengi
        function getSocialColor(platform) {
            switch(platform.toLowerCase()) {
                case 'linkedin': return '#0077b5';
                case 'instagram': return '#e4405f';
                case 'facebook': return '#3b5999';
                case 'twitter': return '#55acee';
                case 'youtube': return '#cd201f';
                case 'whatsapp': return '#25D366';
                case 'telegram': return '#0088cc';
                default: return '#333';
            }
        }
        
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
                    $('#deleteForm').attr('action', '{{ route("back.pages.team.destroy", "") }}/' + id);
                    $('#deleteForm').submit();
                }
            });
        });
        
        // Durum değiştirme
        $('.status-switch').on('change', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ route("back.pages.team.toggle-status", "") }}/' + id,
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
        
        // Sıralama işlemi
        var sortable = new Sortable(document.querySelector('.sortable'), {
            handle: '.handle',
            animation: 150,
            onEnd: function(evt) {
                var items = [];
                $('.sortable tr').each(function(index) {
                    items.push({
                        id: $(this).data('id'),
                        order: index
                    });
                });
                
                $.ajax({
                    url: '{{ route("back.pages.team.order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: items
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
            }
        });
    });
</script>
@endpush 