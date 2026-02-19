@extends('layout.main')

@push('styles')
<style>
.profile-page {
    max-width: 820px;
    margin: 0 auto;
    padding: 2.2rem 1.5rem 2.6rem;
}

.profile-hero {
    background: linear-gradient(135deg, #4b5bdc, #6fa2f5);
    color: #ffffff;
    border-radius: 20px;
    padding: 1.2rem 1.3rem;
    box-shadow: 0 12px 30px rgba(79, 97, 214, 0.25);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-chips {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
    margin-top: 0.8rem;
}

.chip-soft {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.75rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.14);
    color: #f1f5ff;
    font-weight: 700;
    font-size: 0.95rem;
}

.chip-soft strong {
    font-weight: 800;
}

.profile-hero__avatar {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.75);
    background: rgba(255, 255, 255, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    font-weight: 800;
}

.profile-hero__avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
    margin-top: 1.2rem;
    padding: 1.5rem 1.4rem 1.6rem;
}

.profile-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}

.profile-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.profile-field label {
    font-weight: 700;
    color: #1f2937;
}

.profile-input,
.profile-file {
    border: 1px solid #d5dae5;
    border-radius: 12px;
    padding: 0.85rem 0.9rem;
    font-size: 1rem;
    background: #f9fafb;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
}

.profile-input:focus,
.profile-file:focus {
    outline: none;
    border-color: #6fa2f5;
    box-shadow: 0 0 0 3px rgba(111, 162, 245, 0.2);
    background: #fff;
}

.profile-actions {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
    align-items: center;
}

.avatar-preview {
    width: 120px;
    height: 120px;
    border-radius: 16px;
    border: 1px dashed #cfd8ff;
    background: linear-gradient(180deg, #f8faff 0%, #f2f5ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-meta {
    color: #6b7280;
    font-size: 0.95rem;
}

.upload-inline {
    display: flex;
    gap: 0.8rem;
    align-items: center;
    flex-wrap: wrap;
}

.upload-label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 0.9rem;
    border-radius: 12px;
    border: 1px solid #d5dae5;
    background: #f9fafb;
    cursor: pointer;
    font-weight: 700;
    color: #1f2937;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
}

.upload-label:hover {
    border-color: #6fa2f5;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(111, 162, 245, 0.12);
}

.upload-label small {
    font-weight: 600;
    color: #4b5563;
}

.hidden-file {
    display: none;
}

@media (max-width: 600px) {
    .profile-page {
        padding: 1.5rem 1.1rem 2rem;
    }

    .profile-hero {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endpush

@section('content')
<div class="profile-page">
    <div class="profile-hero">
        <div class="profile-hero__avatar">
            @if(auth()->user()->avatar ?? false)
            <img src="{{ asset(auth()->user()->avatar) }}" alt="Avatar">
            @else
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <div>
            <h1 style="margin:0 0 0.15rem; font-size: 1.6rem;">Pengaturan Profil</h1>
            <p style="margin:0; opacity:0.9;">Ubah nama tampilan dan foto profil Anda.</p>
            <div class="info-chips">
                <span class="chip-soft"><strong>Email:</strong> {{ $user->email ?? '-' }}</span>
                <span class="chip-soft"><strong>Role:</strong> {{ optional($user->role)->name ?? 'User' }}</span>
            </div>
        </div>
    </div>

    <div class="profile-card">
        <form class="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-row">
                <div class="profile-field">
                    <label for="name">Nama tampilan</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                        class="profile-input">
                </div>

                <div class="profile-field">
                    <label for="avatar">Foto profil</label>
                    <div class="upload-inline">
                        <div class="avatar-preview" id="avatarPreview">
                            @if($user->avatar ?? false)
                            <img src="{{ asset($user->avatar) }}" alt="Avatar preview">
                            @else
                            <span style="color:#6b7280;">Belum ada foto</span>
                            @endif
                        </div>
                        <label class="upload-label" for="avatar">
                            <span>Unggah foto</span>
                            <small id="fileName">Pilih file</small>
                        </label>
                        <input id="avatar" type="file" name="avatar" accept="image/*" class="hidden-file">
                    </div>
                    <small class="profile-meta">Format JPG/PNG, maksimal 2MB.</small>
                </div>
            </div>

            <div class="profile-actions">
                <button type="submit" class="btn">Simpan perubahan</button>
                <a href="{{ url()->previous() }}" class="btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('avatar');
    const preview = document.getElementById('avatarPreview');
    const fileName = document.getElementById('fileName');
    if (!input || !preview) return;

    input.addEventListener('change', (e) => {
        const file = e.target.files && e.target.files[0];
        if (!file) return;
        if (fileName) fileName.textContent = file.name;
        const reader = new FileReader();
        reader.onload = (ev) => {
            preview.innerHTML = '';
            const img = document.createElement('img');
            img.src = ev.target.result;
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush