@extends('layouts.app')
@section('title', 'Revisi Pengajuan')
@section('crumbs', 'Menu Dosen / Riwayat Pengajuan / Revisi')
@section('content')
<link rel="stylesheet" href="{{ asset('css/wizard.css') }}">
<style>
/* ====== MultiSelect Anggota Tim ====== */
.ms { position: relative; }

.ms-box {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    min-height: 44px;
    padding: 6px 34px 6px 8px;
    border: 1px solid #d8dee3;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    position: relative;
}
.ms-box:hover { border-color: #00875A; }

.ms.open .ms-box {
    border-color: #00875A;
    box-shadow: 0 0 0 3px rgba(0,135,90,0.15);
}

.ms-placeholder {
    color: #9ca3af;
    font-size: 13px;
    padding: 4px 4px;
}

.ms-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #00875A;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 6px 5px 10px;
    border-radius: 999px;
    max-width: 200px;
}
.ms-chip span.txt {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ms-chip .x {
    flex-shrink: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    line-height: 1;
    cursor: pointer;
}
.ms-chip .x:hover { background: rgba(255,255,255,0.45); }

.ms-arrow {
    position: absolute;
    top: 50%;
    right: 12px;
    width: 9px;
    height: 9px;
    border-right: 2px solid #6b7280;
    border-bottom: 2px solid #6b7280;
    transform: translateY(-70%) rotate(45deg);
    transition: transform .15s ease;
    pointer-events: none;
}
.ms.open .ms-arrow { transform: translateY(-30%) rotate(-135deg); }

.ms-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 12px 28px rgba(16,24,40,0.12);
    z-index: 40;
    overflow: hidden; /* biar search box tidak keluar border-radius */
}
.ms.open .ms-dropdown { display: block; }

/* ── Search box ── */
.ms-search-wrap {
    padding: 8px 10px;
    border-bottom: 1px solid #f1f3f5;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 1;
}
.ms-search {
    width: 100%;
    box-sizing: border-box;
    padding: 7px 10px 7px 30px;
    border: 1px solid #d8dee3;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E") no-repeat 9px center;
}
.ms-search:focus {
    border-color: #00875A;
    box-shadow: 0 0 0 3px rgba(0,135,90,0.12);
}

/* ── List area ── */
.ms-list {
    max-height: 240px;
    overflow-y: auto;
    padding: 4px 0 6px;
}

.ms-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    cursor: pointer;
    font-size: 13px;
}
.ms-option:hover { background: #f8fafc; }
.ms-option input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #00875A;
    flex-shrink: 0;
}
.ms-option .meta {
    color: #9ca3af;
    font-size: 11px;
}
.ms-select-all {
    font-weight: 700;
    border-bottom: 1px solid #f1f3f5;
    padding-bottom: 10px;
    margin-bottom: 2px;
}
.ms-option-empty {
    padding: 14px;
    text-align: center;
    color: #9ca3af;
    font-size: 12.5px;
}
</style>

<div class="card wizard-card">
    <div style="margin-bottom:16px;">
        <h3 style="margin-bottom:2px;">{{ $pengajuan->status === 'revisi' ? 'Revisi Pengajuan' : 'Edit Pengajuan & Berkas' }}</h3>
        <div style="font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--ink-500);">
            {{ $pengajuan->kode }}
        </div>
    </div>

    @if ($pengajuan->catatan_validator)
    <div class="alert-box alert-amber">
        <b>Catatan revisi dari admin:</b><br>{{ $pengajuan->catatan_validator }}
    </div>
    @endif

    @if ($errors->any())
    <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('pengajuan.update', $pengajuan) }}"
          enctype="multipart/form-data" id="formRevisi">
        @csrf
        @method('PUT')

        <div class="field">
            <label>Judul Kegiatan</label>
            <input type="text" name="judul" value="{{ old('judul', $pengajuan->judul) }}" required>
        </div>

        <div class="field">
            <label>Skema</label>
            <div style="padding:9px 0; color:var(--ink-500); font-size:13px;">
                {{ $pengajuan->skema->nama ?? '-' }}
                <span style="font-size:11px;">(tidak bisa diubah saat revisi)</span>
            </div>
        </div>

        <div class="field">
            <label>Ketua Pengaju</label>
            <div class="check-list">
                <label style="cursor:default;">
                    <div class="av">{{ $ketua->initials() }}</div>
                    <div>
                        <b>{{ $ketua->nama }}</b><br>
                        <span style="color:var(--ink-500); font-size:11px;">
                            NIP {{ $ketua->nip }} &middot; Ketua
                        </span>
                    </div>
                </label>
            </div>
        </div>

        <!-- ===== Multiselect Anggota Tim ===== -->
        <div class="field">
            <label>Anggota Tim dari Sistem</label>
            <div class="ms" id="anggotaMs">
                <!-- Box chip -->
                <div class="ms-box" id="anggotaMsBox">
                    <div class="ms-arrow"></div>
                </div>
                <!-- Dropdown -->
                <div class="ms-dropdown" id="anggotaMsDropdown">
                    <!-- search + list dirender via JS -->
                </div>
            </div>
            <div id="anggotaHiddenInputs"></div>
        </div>

        <div class="field">
            <label>Anggota di Luar Sistem</label>
            <div id="timLuarWrap">
                @forelse ($timLuarExisting as $luar)
                <div class="grid g2" style="margin-bottom:8px;" data-row>
                    <input type="text" name="tim_luar_nama[]" placeholder="Nama lengkap"
                           value="{{ $luar['nama'] }}">
                    <div style="display:flex; gap:8px;">
                        <input type="text" name="tim_luar_instansi[]"
                               placeholder="Asal institusi (opsional)"
                               value="{{ $luar['instansi'] }}">
                        <button type="button" class="btn btn-outline"
                                onclick="this.closest('[data-row]').remove()"
                                style="flex-shrink:0; padding:0 14px;">✕</button>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
            <button type="button" class="btn btn-outline" onclick="tambahBarisLuar()">
                + Tambah Anggota Luar
            </button>
        </div>

        <div class="field" style="margin-top:16px;">
            <label>Dokumen Proposal Saat Ini</label>
            <div class="file-chip">
                <span>
                    📄 <a href="{{ asset('storage/' . $pengajuan->proposal_path) }}" target="_blank"
                         style="color:var(--green-700); font-weight:700;">
                        {{ $pengajuan->proposal_nama_asli }}
                    </a>
                    &middot; {{ number_format($pengajuan->proposal_size / 1024, 0) }} KB
                </span>
            </div>
            <div class="sub" style="margin:8px 0; font-size:11.5px; color:var(--ink-500);">
                Unggah dokumen baru kalau proposal perlu diganti (PDF, maks. 2MB).
            </div>
            <input type="file" name="proposal" accept="application/pdf">
        </div>

        <div class="field" style="margin-top:16px;">
            <label>Dokumen Kontrak Saat Ini <span style="font-size:11px; color:#6b7280; font-weight:600;">(Opsional)</span></label>
            @if ($pengajuan->kontrak_path)
                <div class="file-chip">
                    <span>
                        📄 <a href="{{ asset('storage/' . $pengajuan->kontrak_path) }}" target="_blank"
                             style="color:var(--green-700); font-weight:700;">
                            {{ $pengajuan->kontrak_nama_asli }}
                        </a>
                        &middot; {{ number_format($pengajuan->kontrak_size / 1024, 0) }} KB
                    </span>
                </div>
            @else
                <div class="sub" style="margin-bottom:6px; font-size:12px; color:var(--ink-500);">Belum ada dokumen kontrak yang diunggah.</div>
            @endif
            <div class="sub" style="margin:8px 0; font-size:11.5px; color:var(--ink-500);">
                Unggah dokumen kontrak baru atau ganti dokumen kontrak yang lama (PDF, maks. 2MB).
            </div>
            <input type="file" name="kontrak" accept="application/pdf">
        </div>

        <div class="field" style="margin-top:16px;">
            <label>Dokumen RAB Saat Ini <span style="font-size:11px; color:#6b7280; font-weight:600;">(Opsional)</span></label>
            @if ($pengajuan->rab_path)
                <div class="file-chip">
                    <span>
                        📄 <a href="{{ asset('storage/' . $pengajuan->rab_path) }}" target="_blank"
                             style="color:var(--green-700); font-weight:700;">
                            {{ $pengajuan->rab_nama_asli }}
                        </a>
                        &middot; {{ number_format($pengajuan->rab_size / 1024, 0) }} KB
                    </span>
                </div>
            @else
                <div class="sub" style="margin-bottom:6px; font-size:12px; color:var(--ink-500);">Belum ada dokumen RAB yang diunggah.</div>
            @endif
            <div class="sub" style="margin:8px 0; font-size:11.5px; color:var(--ink-500);">
                Unggah dokumen RAB baru atau ganti dokumen RAB yang lama (PDF, maks. 2MB).
            </div>
            <input type="file" name="rab" accept="application/pdf">
        </div>

        <div class="field">
            <label>Total Biaya Usulan (Rp)</label>
            <input type="text" id="total_biaya_display" placeholder="10.000.000"
                   autocomplete="off" inputmode="numeric">
            <input type="hidden" name="total_biaya" id="total_biaya"
                   value="{{ old('total_biaya', $pengajuan->total_biaya) }}">
        </div>

        <div class="alert-box alert-info">
            ℹ️ Setelah disimpan, status pengajuan menjadi <b>Dalam Proses</b>
            dan akan divalidasi oleh admin.
        </div>

        <div style="display:flex; justify-content:space-between; margin-top:20px;">
            <a href="{{ route('pengajuan.detail', $pengajuan) }}" class="btn btn-outline">Batal</a>
            <button class="btn btn-primary" type="submit">{{ $pengajuan->status === 'revisi' ? 'Kirim Ulang Revisi' : 'Simpan Perubahan' }}</button>
        </div>
    </form>
</div>

<script>
@php
    $anggotaListArr = $anggotaTersedia
        ->map(fn($a) => [
            'id'      => $a->id,
            'nama'    => $a->nama,
            'nip'     => $a->nip,
            'jurusan' => $a->jurusan,
        ])
        ->values();
@endphp

const ANGGOTA_LIST      = @json($anggotaListArr);
const TIM_TERPILIH_AWAL = @json(array_values($timTerpilih));

let selectedIds  = [...TIM_TERPILIH_AWAL];
let searchQuery  = '';   // ← state pencarian

/* ── helper ── */
function getInitials(name) {
    if (!name) return '?';
    const parts   = name.replace(/^(Dr\.|Prof\.|drg\.)\s*/i, '').trim().split(/\s+/);
    const letters = (parts[0]?.[0] || '') + (parts.length > 1 ? (parts[1][0] || '') : '');
    return letters.toUpperCase() || '?';
}

/* ══════════════════════════════════════════
   RENDER UTAMA
══════════════════════════════════════════ */
function renderAnggotaMs() {
    const box        = document.getElementById('anggotaMsBox');
    const dropdown   = document.getElementById('anggotaMsDropdown');
    const hiddenWrap = document.getElementById('anggotaHiddenInputs');

    /* ── 1. Chips di dalam box ── */
    let chipsHtml = '';
    if (selectedIds.length === 0) {
        chipsHtml = '<span class="ms-placeholder">Pilih anggota dari sistem...</span>';
    } else {
        chipsHtml = selectedIds.map(id => {
            const a = ANGGOTA_LIST.find(x => x.id == id);
            if (!a) return '';
            return `<span class="ms-chip">
                        <span class="txt">${a.nama}</span>
                        <span class="x" data-removeid="${a.id}">✕</span>
                    </span>`;
        }).join('');
    }
    box.innerHTML = chipsHtml + '<div class="ms-arrow"></div>';

    /* ── 2. Hidden inputs ── */
    hiddenWrap.innerHTML = selectedIds
        .map(id => `<input type="hidden" name="tim[]" value="${id}">`)
        .join('');

    /* ── 3. Filter list berdasarkan searchQuery ── */
    const q        = searchQuery.trim().toLowerCase();
    const filtered = q
        ? ANGGOTA_LIST.filter(a =>
            a.nama.toLowerCase().includes(q) ||
            (a.nip     && a.nip.toLowerCase().includes(q)) ||
            (a.jurusan && a.jurusan.toLowerCase().includes(q))
          )
        : ANGGOTA_LIST;

    /* ── 4. Dropdown HTML ── */
    const allChecked = filtered.length > 0 && filtered.every(a => selectedIds.includes(a.id));

    let listHtml = '';
    if (filtered.length === 0) {
        listHtml = `<div class="ms-option-empty">
                        Tidak ada dosen yang cocok dengan "<b>${escHtml(q)}</b>"
                    </div>`;
    } else {
        // "Select All" hanya muncul jika tidak ada filter / ada hasil filter
        listHtml += `<label class="ms-option ms-select-all">
                        <input type="checkbox" id="anggotaSelectAll" ${allChecked ? 'checked' : ''}>
                        <span>Pilih Semua${q ? ' Hasil' : ''} (${filtered.length})</span>
                    </label>`;

        listHtml += filtered.map(a => {
            const checked = selectedIds.includes(a.id);
            // Highlight teks yang cocok
            const namaHl    = highlight(a.nama,    q);
            const nipHl     = highlight(a.nip,     q);
            const jurusanHl = highlight(a.jurusan, q);
            return `<label class="ms-option">
                        <input type="checkbox" data-id="${a.id}" ${checked ? 'checked' : ''}>
                        <span>
                            <b>${namaHl}</b><br>
                            <span class="meta">
                                NIP ${nipHl}${a.jurusan ? ' &middot; ' + jurusanHl : ''}
                            </span>
                        </span>
                    </label>`;
        }).join('');
    }

    dropdown.innerHTML = `
        <div class="ms-search-wrap">
            <input class="ms-search"
                   id="anggotaSearch"
                   type="text"
                   placeholder="Cari nama, NIP, atau jurusan..."
                   value="${escHtml(searchQuery)}"
                   autocomplete="off">
        </div>
        <div class="ms-list" id="anggotaMsList">
            ${listHtml}
        </div>`;

    /* ── 5. Pasang event pada search input (setiap render ulang) ── */
    const searchEl = document.getElementById('anggotaSearch');
    if (searchEl) {
        // Fokus & posisi kursor ke akhir
        if (document.getElementById('anggotaMs').classList.contains('open')) {
            searchEl.focus();
            const len = searchEl.value.length;
            searchEl.setSelectionRange(len, len);
        }
        searchEl.addEventListener('input', function () {
            searchQuery = this.value;
            renderAnggotaMs();
        });
        // Jangan tutup dropdown saat ketik
        searchEl.addEventListener('click', e => e.stopPropagation());
        searchEl.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.getElementById('anggotaMs').classList.remove('open');
            }
        });
    }
}

/* ── Helpers ── */
function escHtml(str) {
    if (!str) return '';
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function highlight(text, q) {
    if (!text) return '';
    if (!q)    return escHtml(text);
    const idx = text.toLowerCase().indexOf(q.toLowerCase());
    if (idx === -1) return escHtml(text);
    return escHtml(text.slice(0, idx))
         + `<mark style="background:#d1fae5; color:#065f46; border-radius:2px; padding:0 1px;">${escHtml(text.slice(idx, idx + q.length))}</mark>`
         + escHtml(text.slice(idx + q.length));
}

/* ══════════════════════════════════════════
   TOGGLE PILIHAN
══════════════════════════════════════════ */
function toggleAnggota(id, checked) {
    id = parseInt(id);
    if (checked) {
        if (!selectedIds.includes(id)) selectedIds.push(id);
    } else {
        selectedIds = selectedIds.filter(x => x !== id);
    }
    renderAnggotaMs();
}

/* ══════════════════════════════════════════
   EVENT LISTENERS
══════════════════════════════════════════ */
const msEl        = document.getElementById('anggotaMs');
const msBoxEl     = document.getElementById('anggotaMsBox');
const msDropdownEl = document.getElementById('anggotaMsDropdown');

/* Buka/tutup dropdown saat klik box */
msBoxEl.addEventListener('click', function () {
    const isOpen = msEl.classList.toggle('open');
    if (isOpen) {
        // Reset search setiap kali buka (opsional — hapus baris ini kalau ingin persistent)
        // searchQuery = '';
        renderAnggotaMs();
        // Fokus ke search setelah render
        setTimeout(() => {
            const s = document.getElementById('anggotaSearch');
            if (s) s.focus();
        }, 30);
    }
});

/* Klik ✕ pada chip */
msEl.addEventListener('click', function (e) {
    const removeId = e.target.getAttribute('data-removeid');
    if (removeId) {
        e.stopPropagation();
        toggleAnggota(removeId, false);
    }
});

/* Jangan tutup dropdown saat klik di dalamnya */
msDropdownEl.addEventListener('click', e => e.stopPropagation());

/* Checkbox change (delegasi ke dropdown) */
msDropdownEl.addEventListener('change', function (e) {
    if (e.target.id === 'anggotaSelectAll') {
        // Filter yang sedang tampil
        const q        = searchQuery.trim().toLowerCase();
        const filtered = q
            ? ANGGOTA_LIST.filter(a =>
                a.nama.toLowerCase().includes(q) ||
                (a.nip     && a.nip.toLowerCase().includes(q)) ||
                (a.jurusan && a.jurusan.toLowerCase().includes(q))
              )
            : ANGGOTA_LIST;

        if (e.target.checked) {
            // Tambahkan semua hasil filter ke selectedIds
            filtered.forEach(a => {
                if (!selectedIds.includes(a.id)) selectedIds.push(a.id);
            });
        } else {
            // Hapus semua hasil filter dari selectedIds
            const filteredIds = filtered.map(a => a.id);
            selectedIds = selectedIds.filter(id => !filteredIds.includes(id));
        }
        renderAnggotaMs();
    } else if (e.target.hasAttribute('data-id')) {
        toggleAnggota(e.target.getAttribute('data-id'), e.target.checked);
    }
});

/* Tutup saat klik di luar */
document.addEventListener('click', function (e) {
    if (!msEl.contains(e.target)) {
        msEl.classList.remove('open');
    }
});

/* ══════════════════════════════════════════
   INIT
══════════════════════════════════════════ */
renderAnggotaMs();

/* ── Tambah baris anggota luar ── */
function tambahBarisLuar() {
    const wrap = document.getElementById('timLuarWrap');
    const row  = document.createElement('div');
    row.className         = 'grid g2';
    row.style.marginBottom = '8px';
    row.setAttribute('data-row', '');
    row.innerHTML = `
        <input type="text" name="tim_luar_nama[]" placeholder="Nama lengkap">
        <div style="display:flex; gap:8px;">
            <input type="text" name="tim_luar_instansi[]" placeholder="Asal institusi (opsional)">
            <button type="button" class="btn btn-outline"
                    onclick="this.closest('[data-row]').remove()"
                    style="flex-shrink:0; padding:0 14px;">✕</button>
        </div>`;
    wrap.appendChild(row);
}

/* ── Format Rupiah ── */
document.addEventListener('DOMContentLoaded', function () {
    const display = document.getElementById('total_biaya_display');
    const hidden  = document.getElementById('total_biaya');

    function formatRupiah(angka) {
        if (!angka) return '';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    if (hidden.value) display.value = formatRupiah(hidden.value);

    display.addEventListener('input', function (e) {
        const angka   = e.target.value.replace(/\D/g, '');
        hidden.value  = angka;
        e.target.value = formatRupiah(angka);
    });
});
</script>
@endsection