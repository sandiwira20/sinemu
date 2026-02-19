<?php

namespace App\Http\Controllers;

use App\Models\LostReport;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    // =========================
    // ADMIN: DAFTAR SEMUA LAPORAN
    // =========================
    public function index()
    {
        $reports = LostReport::with([
            'user:id,name,email',
            'category:id,name',
        ])->latest()->paginate(10);

        $statusTotals = LostReport::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'open' => $statusTotals['open'] ?? 0,
            'matched' => $statusTotals['matched'] ?? 0,
            'closed' => $statusTotals['closed'] ?? 0,
        ];

        return view('admin.reports.index', compact('reports', 'stats'));
    }

    // =========================
    // USER: LAPORAN MILIK SENDIRI
    // =========================
    public function userIndex()
    {
        $reports = LostReport::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.reports.index', compact('reports'));
    }

    // =========================
    // USER: FORM BUAT LAPORAN
    // =========================
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('user.reports.create', compact('categories'));
    }

    // =========================
    // USER: SIMPAN LAPORAN
    // =========================
    public function store(Request $request)
    {
        $data = $request->validate([
            'report_type' => 'required|in:lost,found',
            'category_id' => 'nullable|exists:categories,id',
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lost_location' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'lost_at' => 'required|date',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 'open';

        if ($request->hasFile('evidence')) {
            $data['evidence_file'] = $request
                ->file('evidence')
                ->store('lost_reports', 'public');
            $this->syncPublicStorageCopy($data['evidence_file']);
        }

        $report = LostReport::create($data);

        if ($report->report_type === 'found' && $report->category_id) {
            $mainImage = $this->copyEvidenceToItemImage($report->evidence_file) ?? $report->evidence_file;

            Item::create([
                'category_id' => $report->category_id,
                'created_by' => auth()->id(),
                'name' => $report->item_name,
                'description' => $report->description,
                'status' => 'stored',
                'found_location' => $report->lost_location,
                'found_at' => $report->lost_at,
                'contact' => $report->contact,
                'main_image' => $mainImage,
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Laporan barang hilang berhasil dikirim.');
    }

    // =========================
    // USER / ADMIN: FORM EDIT
    // =========================
    public function edit(LostReport $report)
    {
        $this->authorizeAccess($report, allowAdmin: true);

        $categories = Category::orderBy('name')->get();
        if (auth()->user()?->role?->name === 'admin') {
            return view('admin.reports.edit', compact('report', 'categories'));
        }

        return view('user.reports.edit', compact('report', 'categories'));
    }

    // =========================
    // USER / ADMIN: UPDATE
    // =========================
    public function update(Request $request, LostReport $report)
    {
        $this->authorizeAccess($report, allowAdmin: true);

        $data = $request->validate([
            'report_type' => 'required|in:lost,found',
            'category_id' => 'nullable|exists:categories,id',
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lost_location' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'lost_at' => 'required|date',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'create_item' => 'nullable|boolean',
            'remove_evidence' => 'nullable|boolean',
            'item_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('evidence')) {
            if ($report->evidence_file) {
                Storage::disk('public')->delete($report->evidence_file);
                $this->deletePublicStorageCopy($report->evidence_file);
            }

            $data['evidence_file'] = $request
                ->file('evidence')
                ->store('lost_reports', 'public');
            $this->syncPublicStorageCopy($data['evidence_file']);
        } elseif ($request->boolean('remove_evidence') && $report->evidence_file) {
            Storage::disk('public')->delete($report->evidence_file);
            $this->deletePublicStorageCopy($report->evidence_file);
            $data['evidence_file'] = null;
        }

        $report->update($data);

        // ADMIN: BUAT BARANG TEMUAN DARI LAPORAN
        if (
            auth()->user()?->role?->name === 'admin' &&
            $request->boolean('create_item') &&
            $report->category_id
        ) {
            $mainImage = $this->copyEvidenceToItemImage($report->evidence_file);
            if (! $mainImage && $request->hasFile('item_image')) {
                $mainImage = $request->file('item_image')->store('items', 'public');
                $this->syncPublicStorageCopy($mainImage);
            }

            Item::create([
                'category_id' => $report->category_id,
                'created_by' => auth()->id(),
                'name' => $report->item_name,
                'description' => $report->description,
                'status' => 'stored',
                'found_location' => $report->lost_location,
                'found_at' => $report->lost_at,
                'contact' => $report->contact,
                'main_image' => $mainImage,
            ]);
        }

        if (auth()->user()?->role?->name === 'admin') {
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Laporan berhasil diperbarui.');
        }

        return redirect()->back()->with('success', 'Laporan berhasil diperbarui.');
    }

    // =========================
    // USER / ADMIN: HAPUS
    // =========================
    public function destroy(LostReport $report)
    {
        $this->authorizeAccess($report, allowAdmin: true);

        if ($report->evidence_file) {
            Storage::disk('public')->delete($report->evidence_file);
            $this->deletePublicStorageCopy($report->evidence_file);
        }

        $report->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
    }

    // =========================
    // ADMIN: UPDATE STATUS
    // =========================
    public function updateStatus(Request $request, LostReport $report)
    {
        if (auth()->user()?->role?->name !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:open,matched,closed',
        ]);

        $report->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status laporan diperbarui.');
    }

    // =========================
    // ADMIN: LIHAT BUKTI (ANTI 403)
    // =========================
    public function bukti(LostReport $report)
    {
        if (auth()->user()?->role?->name !== 'admin') {
            abort(403);
        }

        if (
            !$report->evidence_file ||
            !Storage::disk('public')->exists($report->evidence_file)
        ) {
            abort(404, 'Bukti tidak ditemukan');
        }

        return response()->file(
            storage_path('app/public/' . $report->evidence_file)
        );
    }

    // =========================
    // HELPER: CEK AKSES
    // =========================
    private function authorizeAccess(LostReport $report, bool $allowAdmin = false): void
    {
        if ($allowAdmin && auth()->user()?->role?->name === 'admin') {
            return;
        }

        if ($report->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }

    private function copyEvidenceToItemImage(?string $evidenceFile): ?string
    {
        if (! $evidenceFile) {
            return null;
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($evidenceFile)) {
            return null;
        }

        $extension = strtolower(pathinfo($evidenceFile, PATHINFO_EXTENSION));
        if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            return null;
        }
        $filename = (string) Str::uuid();
        $target = 'items/' . $filename . ($extension ? '.' . $extension : '');

        if (! $disk->copy($evidenceFile, $target)) {
            return null;
        }

        $this->syncPublicStorageCopy($target);
        return $target;
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
