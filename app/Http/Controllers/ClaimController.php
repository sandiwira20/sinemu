<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    // Form klaim barang (mahasiswa)
    public function create(Item $item)
    {
        if (optional(auth()->user()->role)->name === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Admin tidak dapat mengajukan klaim.');
        }

        return view('user.claims.create', compact('item'));
    }

    // Simpan klaim
    public function store(Request $request, Item $item)
    {
        if (optional(auth()->user()->role)->name === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Admin tidak dapat mengajukan klaim.');
        }

        $data = $request->validate([
            'description' => 'nullable|string',
            'contact'     => 'required|string|max:255',
            'evidence'    => 'nullable|file|max:4096',
        ]);

        $data['user_id']    = auth()->id();
        $data['item_id']    = $item->id;
        $data['claimed_at'] = now();

        if ($request->hasFile('evidence')) {
            $data['evidence_file'] = $request->file('evidence')->store('claims', 'public');
            $this->syncPublicStorageCopy($data['evidence_file']);
        }

        Claim::create($data);

        return redirect()->route('dashboard')->with('success', 'Klaim barang berhasil diajukan.');
    }

    // Halaman admin: daftar klaim
    public function indexAdmin()
    {
        $claims = Claim::with(['user', 'item'])->latest()->paginate(10);
        return view('admin.claims.index', compact('claims'));
    }

    public function show(Claim $claim)
    {
        $claim->load(['user', 'item', 'verifier']);
        return view('admin.claims.show', compact('claim'));
    }

    // Admin verifikasi klaim
    public function verify(Request $request, Claim $claim)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $claim->update([
            'status'      => $request->status,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        if ($request->status === 'approved') {
            $claim->item->update(['status' => 'claimed']);
        }

        return back()->with('success', 'Klaim telah diverifikasi.');
    }

    // Hapus klaim (user pemilik atau admin)
    public function destroy(Claim $claim)
    {
        $isAdmin = optional(auth()->user()->role)->name === 'admin';
        $isOwner = $claim->user_id === auth()->id();

        if (! $isAdmin && ! $isOwner) {
            abort(403);
        }

        if ($claim->evidence_file) {
            Storage::disk('public')->delete($claim->evidence_file);
            $this->deletePublicStorageCopy($claim->evidence_file);
        }

        $claim->delete();

        if ($isAdmin) {
            return redirect()->route('admin.claims.index')->with('success', 'Klaim dihapus.');
        }

        return redirect()->route('dashboard')->with('success', 'Klaim dihapus.');
    }

    private function syncPublicStorageCopy(string $path): void
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return;
        }

        $source = $disk->path($path);
        $destination = public_path('storage/' . $path);
        $destinationDir = dirname($destination);

        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (! file_exists($destination)) {
            copy($source, $destination);
        }
    }

    private function deletePublicStorageCopy(string $path): void
    {
        $destination = public_path('storage/' . $path);
        if (file_exists($destination)) {
            unlink($destination);
        }
    }
}
