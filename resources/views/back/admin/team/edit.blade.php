@extends('back.layouts.master')

@section('title', 'Komanda Üzvü Düzənlə')

@section('content')
<style>
    .swal2-popup {
        border-radius: 50px;
    }
    .social-account-item {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }
    .remove-social-account {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        color: #dc3545;
    }
    .social-icon-preview {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        font-size: 16px;
        margin-right: 10px;
    }
    .social-preview {
        display: flex;
        align-items: center;
        margin-top: 10px;
        padding: 8px;
        background-color: #fff;
        border-radius: 4px;
        border: 1px solid #dee2e6;
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
                    <h4 class="mb-sm-0">Komanda Üzvü Düzənlə</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana səhifə</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('back.pages.team.index') }}">Komanda Üzvləri</a></li>
                            <li class="breadcrumb-item active">Düzənlə</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('back.pages.team.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Ana Sekmeler -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#basic_info" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-information-line me-1 align-middle"></i> Əsas Məlumatlar
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#language_info" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-translate-2 me-1 align-middle"></i> Dil Məlumatları
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#social_info" role="tab" style="color:rgb(0, 0, 0);">
                                        <i class="ri-share-line me-1 align-middle"></i> Sosial Hesablar
                                    </a>
                                </li>
                            </ul>

                            <!-- Ana Sekme İçerikleri -->
                            <div class="tab-content p-3">
                                <!-- Əsas Məlumatlar Sekmesi -->
                                <div class="tab-pane active" id="basic_info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Şəkil</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">Profil Şəkli</label>
                                                        @if($team->image)
                                                            <div class="mb-2">
                                                                <img src="{{ asset($team->image) }}" alt="{{ $team->name_az }}" class="img-thumbnail" style="max-height: 200px; max-width: 200px;">
                                                            </div>
                                                        @endif
                                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                                        <div class="form-text">Tövsiyə olunan şəkil ölçüsü: 400x400px</div>
                                                        @error('image')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div id="image-preview" class="mt-3 d-none">
                                                        <p class="text-muted">Yeni şəkil:</p>
                                                        <img src="" alt="Şəkil önizləmə" class="img-thumbnail" style="max-height: 200px; max-width: 200px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Status</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-check form-switch form-switch-success mb-3">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ old('status', $team->status) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="status">Aktiv</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dil Məlumatları Sekmesi -->
                                <div class="tab-pane" id="language_info" role="tabpanel">
                                    <!-- Dil Sekmeleri -->
                                    <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#lang_az" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">Azərbaycan</span>
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-bs-toggle="tab" href="#lang_en" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">İngilis</span>
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-bs-toggle="tab" href="#lang_ru" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">Rus</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Dil Sekme İçerikleri -->
                                    <div class="tab-content p-3 text-muted">
                                        <!-- Az tab -->
                                        <div class="tab-pane active" id="lang_az" role="tabpanel">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Azərbaycan Dili Məlumatları</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="name_az" class="form-label">Ad Soyad (AZ) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name_az') is-invalid @enderror" id="name_az" name="name_az" value="{{ old('name_az', $team->name_az) }}" required>
                                                        @error('name_az')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="position_az" class="form-label">Vəzifə (AZ) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('position_az') is-invalid @enderror" id="position_az" name="position_az" value="{{ old('position_az', $team->position_az) }}" required>
                                                        @error('position_az')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="biography_az" class="form-label">Bioqrafiya (AZ)</label>
                                                        <textarea class="form-control @error('biography_az') is-invalid @enderror" id="biography_az" name="biography_az" rows="5">{{ old('biography_az', $team->biography_az) }}</textarea>
                                                        @error('biography_az')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- En tab -->
                                        <div class="tab-pane" id="lang_en" role="tabpanel">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">İngilis Dili Məlumatları</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="name_en" class="form-label">Ad Soyad (EN)</label>
                                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $team->name_en) }}">
                                                        @error('name_en')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="position_en" class="form-label">Vəzifə (EN)</label>
                                                        <input type="text" class="form-control @error('position_en') is-invalid @enderror" id="position_en" name="position_en" value="{{ old('position_en', $team->position_en) }}">
                                                        @error('position_en')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="biography_en" class="form-label">Bioqrafiya (EN)</label>
                                                        <textarea class="form-control @error('biography_en') is-invalid @enderror" id="biography_en" name="biography_en" rows="5">{{ old('biography_en', $team->biography_en) }}</textarea>
                                                        @error('biography_en')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ru tab -->
                                        <div class="tab-pane" id="lang_ru" role="tabpanel">
                                            <div class="card border shadow-none mb-4">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">Rus Dili Məlumatları</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="name_ru" class="form-label">Ad Soyad (RU)</label>
                                                        <input type="text" class="form-control @error('name_ru') is-invalid @enderror" id="name_ru" name="name_ru" value="{{ old('name_ru', $team->name_ru) }}">
                                                        @error('name_ru')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="position_ru" class="form-label">Vəzifə (RU)</label>
                                                        <input type="text" class="form-control @error('position_ru') is-invalid @enderror" id="position_ru" name="position_ru" value="{{ old('position_ru', $team->position_ru) }}">
                                                        @error('position_ru')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="biography_ru" class="form-label">Bioqrafiya (RU)</label>
                                                        <textarea class="form-control @error('biography_ru') is-invalid @enderror" id="biography_ru" name="biography_ru" rows="5">{{ old('biography_ru', $team->biography_ru) }}</textarea>
                                                        @error('biography_ru')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Sosial Hesablar Sekmesi -->
                                <div class="tab-pane" id="social_info" role="tabpanel">
                                    <div class="card border shadow-none mb-4">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Sosial Media Hesabları</h5>
                                            <button type="button" class="btn btn-sm btn-primary" id="add-social-account">
                                                <i class="ri-add-line align-middle"></i> Yeni Hesab Əlavə Et
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info">
                                                <i class="ri-information-line me-2"></i> Minimum 1, maksimum 5 sosial hesab əlavə edə bilərsiniz.
                                            </div>
                                            
                                            <div id="social-accounts-container">
                                                <!-- Mevcut hesapları göster -->
                                                @if(!empty($team->social_accounts))
                                                    @foreach($team->social_accounts as $index => $account)
                                                    <div class="social-account-item" data-index="{{ $index }}">
                                                        <span class="remove-social-account"><i class="ri-close-circle-line"></i></span>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label for="social_platform{{ $index }}" class="form-label">Platform</label>
                                                                    <select class="form-select social-platform" id="social_platform{{ $index }}" name="social_accounts[{{ $index }}][platform]" required>
                                                                        <option value="">Seçin</option>
                                                                        <option value="LinkedIn" {{ $account['platform'] == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                                                                        <option value="Instagram" {{ $account['platform'] == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                                                        <option value="Facebook" {{ $account['platform'] == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                                                        <option value="Twitter" {{ $account['platform'] == 'Twitter' ? 'selected' : '' }}>Twitter</option>
                                                                        <option value="YouTube" {{ $account['platform'] == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                                                                        <option value="WhatsApp" {{ $account['platform'] == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                                                        <option value="Telegram" {{ $account['platform'] == 'Telegram' ? 'selected' : '' }}>Telegram</option>
                                                                        <option value="Website" {{ $account['platform'] == 'Website' ? 'selected' : '' }}>Şəxsi Vebsayt</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="mb-3">
                                                                    <label for="social_url{{ $index }}" class="form-label">URL</label>
                                                                    <input type="url" class="form-control social-url" id="social_url{{ $index }}" name="social_accounts[{{ $index }}][url]" value="{{ $account['url'] }}" placeholder="https://" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label for="social_icon_file{{ $index }}" class="form-label">İkon Şəkli</label>
                                                                    <input type="file" class="form-control social-icon-file" id="social_icon_file{{ $index }}" name="social_accounts[{{ $index }}][icon_file]">
                                                                    @if(!empty($account['icon_image']))
                                                                        <div class="mt-2">
                                                                            <img src="{{ asset($account['icon_image']) }}" alt="{{ $account['platform'] }}" class="img-thumbnail" style="max-height: 40px; max-width: 40px;">
                                                                        </div>
                                                                    @endif
                                                                    <input type="hidden" name="social_accounts[{{ $index }}][icon]" value="{{ $account['icon'] ?? '' }}">
                                                                    <input type="hidden" name="social_accounts[{{ $index }}][icon_image]" value="{{ $account['icon_image'] ?? '' }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="social-preview">
                                                            <div class="social-icon-preview" style="background-color: 
                                                                @if(strtolower($account['platform']) == 'linkedin') #0077b5
                                                                @elseif(strtolower($account['platform']) == 'instagram') #e4405f
                                                                @elseif(strtolower($account['platform']) == 'facebook') #3b5999
                                                                @elseif(strtolower($account['platform']) == 'twitter') #55acee
                                                                @elseif(strtolower($account['platform']) == 'youtube') #cd201f
                                                                @elseif(strtolower($account['platform']) == 'whatsapp') #25D366
                                                                @elseif(strtolower($account['platform']) == 'telegram') #0088cc
                                                                @else #333
                                                                @endif
                                                            ;">
                                                                @if(!empty($account['icon_image']))
                                                                    <img src="{{ asset($account['icon_image']) }}" alt="{{ $account['platform'] }}" style="width: 20px; height: 20px;">
                                                                @else
                                                                    <i class="ri-{{ !empty($account['icon']) ? $account['icon'] : strtolower($account['platform']).'-fill' }}"></i>
                                                                @endif
                                                            </div>
                                                            <div class="social-link-preview">{{ $account['url'] }}</div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-center py-3 text-muted" id="no-accounts-message">
                                                        Hələ heç bir sosial hesab əlavə edilməyib.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line align-bottom me-1"></i> Yadda saxla
                                    </button>
                                    <a href="{{ route('back.pages.team.index') }}" class="btn btn-secondary">
                                        <i class="ri-close-line align-bottom me-1"></i> Ləğv et
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs-custom .nav-item .nav-link {
        position: relative;
        padding: 15px 20px;
        border: 0;
        color: #495057;
        font-weight: 500;
        border-radius: 4px 4px 0 0;
        transition: all 0.3s;
    }
    
    .nav-tabs-custom .nav-item .nav-link.active {
        color: #3498db;
        background-color: #f8f9fa;
        border-bottom: 2px solid #3498db;
    }
    
    .nav-tabs-custom .nav-item .nav-link:hover:not(.active) {
        color: #3498db;
        background-color: rgba(52, 152, 219, 0.1);
    }
    
    .nav-pills .nav-link.active {
        background-color: #3498db;
    }
    
    .card-header {
        padding: 12px 20px;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
</style>

<!-- Sosyal Medya Hesabı Template -->
<template id="social-account-template">
    <div class="social-account-item" data-index="__INDEX__">
        <span class="remove-social-account"><i class="ri-close-circle-line"></i></span>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="social_platform__INDEX__" class="form-label">Platform</label>
                    <select class="form-select social-platform" id="social_platform__INDEX__" name="social_accounts[__INDEX__][platform]" required>
                        <option value="">Seçin</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Twitter">Twitter</option>
                        <option value="YouTube">YouTube</option>
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Telegram">Telegram</option>
                        <option value="Website">Şəxsi Vebsayt</option>
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="mb-3">
                    <label for="social_url__INDEX__" class="form-label">URL</label>
                    <input type="url" class="form-control social-url" id="social_url__INDEX__" name="social_accounts[__INDEX__][url]" placeholder="https://" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="social_icon_file__INDEX__" class="form-label">İkon Şəkli</label>
                    <input type="file" class="form-control social-icon-file" id="social_icon_file__INDEX__" name="social_accounts[__INDEX__][icon_file]">
                    <input type="hidden" name="social_accounts[__INDEX__][icon]" value="">
                    <input type="hidden" name="social_accounts[__INDEX__][icon_image]" value="">
                </div>
            </div>
        </div>
        <div class="social-preview d-none">
            <div class="social-icon-preview"></div>
            <div class="social-link-preview"></div>
        </div>
    </div>
</template>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Şəkil önizləmə
        $('#image').change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').removeClass('d-none');
                    $('#image-preview img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Sosyal hesap sayacı
        let socialAccountIndex = {{ !empty($team->social_accounts) ? count($team->social_accounts) : 0 }};
        const maxAccounts = 5;
        
        // Eğer maksimum hesap sayısına ulaşıldıysa buton devre dışı bırak
        if (socialAccountIndex >= maxAccounts) {
            $('#add-social-account').attr('disabled', true);
        }
        
        // Sosyal hesap ekleme
        $('#add-social-account').on('click', function() {
            if (socialAccountIndex >= maxAccounts) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Diqqət!',
                    text: 'Maksimum 5 sosial hesab əlavə edə bilərsiniz.'
                });
                return;
            }
            
            // No accounts message gizle
            $('#no-accounts-message').addClass('d-none');
            
            // Template'i kopyala ve container'a ekle
            const template = $('#social-account-template').html();
            const newItem = template.replace(/__INDEX__/g, socialAccountIndex);
            $('#social-accounts-container').append(newItem);
            
            // Hesap sayacını arttır
            socialAccountIndex++;
            
            // Eğer maksimum hesap sayısına ulaşıldıysa buton devre dışı bırak
            if (socialAccountIndex >= maxAccounts) {
                $('#add-social-account').attr('disabled', true);
            }
            
            // Sosyal hesap silme event'ini aktif et
            initRemoveSocialAccount();
            
            // Sosyal hesap ikon resim önizlemeyi aktif et
            initSocialIconPreview();
            
            // Sosyal hesap preview fonksiyonunu aktif et
            initSocialAccountPreview();
        });
        
        // Hesap yoksa ilk hesabı ekle
        if (socialAccountIndex === 0) {
            $('#add-social-account').trigger('click');
        }
        
        // Sosyal hesap silme fonksiyonu
        function initRemoveSocialAccount() {
            $('.remove-social-account').off('click').on('click', function() {
                $(this).closest('.social-account-item').remove();
                socialAccountIndex--;
                
                // Buton aktif et
                $('#add-social-account').attr('disabled', false);
                
                // Eğer hiç hesap kalmadıysa mesajı göster
                if (socialAccountIndex === 0) {
                    $('#no-accounts-message').removeClass('d-none');
                }
                
                // Kalan hesapların indekslerini güncelle
                $('#social-accounts-container .social-account-item').each(function(index) {
                    const oldIndex = $(this).data('index');
                    $(this).attr('data-index', index);
                    
                    // Form alanlarını güncelle
                    $(this).find('select, input').each(function() {
                        const name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(`[${oldIndex}]`, `[${index}]`));
                        }
                        
                        const id = $(this).attr('id');
                        if (id) {
                            $(this).attr('id', id.replace(oldIndex, index));
                        }
                    });
                });
            });
        }
        
        // Mevcut hesaplar için sosyal hesap silme fonksiyonunu aktif et
        initRemoveSocialAccount();
        
        // Sosyal hesap ikon resim önizleme
        function initSocialIconPreview() {
            $('.social-icon-file').off('change').on('change', function() {
                if (this.files && this.files[0]) {
                    const item = $(this).closest('.social-account-item');
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = item.find('.social-preview');
                        preview.removeClass('d-none');
                        preview.find('.social-icon-preview').html(`<img src="${e.target.result}" alt="İkon" style="width: 20px; height: 20px;">`);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        
        // Mevcut hesaplar için sosyal hesap ikon resim önizlemeyi aktif et
        initSocialIconPreview();
        
        // Sosyal hesap preview fonksiyonu
        function initSocialAccountPreview() {
            $('.social-platform, .social-url').off('change keyup').on('change keyup', function() {
                const item = $(this).closest('.social-account-item');
                const platform = item.find('.social-platform').val();
                const url = item.find('.social-url').val();
                
                if (platform && url) {
                    // Preview'i göster
                    const preview = item.find('.social-preview');
                    preview.removeClass('d-none');
                    
                    // Icon rengini belirle
                    let iconColor = '#333';
                    switch(platform.toLowerCase()) {
                        case 'linkedin': iconColor = '#0077b5'; break;
                        case 'instagram': iconColor = '#e4405f'; break;
                        case 'facebook': iconColor = '#3b5999'; break;
                        case 'twitter': iconColor = '#55acee'; break;
                        case 'youtube': iconColor = '#cd201f'; break;
                        case 'whatsapp': iconColor = '#25D366'; break;
                        case 'telegram': iconColor = '#0088cc'; break;
                        case 'website': iconColor = '#333'; break;
                    }
                    
                    // Preview içeriğini güncelle (sadece icon dosyası yoksa)
                    preview.find('.social-icon-preview').css('background-color', iconColor);
                    if (!item.find('.social-icon-file')[0].files.length && !item.find('input[name$="[icon_image]"]').val()) {
                        preview.find('.social-icon-preview').html(`<i class="ri-${platform.toLowerCase()}-fill"></i>`);
                    }
                    preview.find('.social-link-preview').text(url);
                } else {
                    // Preview'i gizle
                    item.find('.social-preview').addClass('d-none');
                }
            });
        }
        
        // Mevcut hesaplar için sosyal hesap preview fonksiyonunu aktif et
        initSocialAccountPreview();
    });
</script>
@endpush 