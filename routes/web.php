<?php
Route::get('/test-error', function() {
    return response()->json([
        'status' => 'ok',
        'app_key' => substr(config('app.key'), 0, 10) . '...',
        'db' => \DB::connection()->getDatabaseName(),
        'session_driver' => config('session.driver'),
        'view_exists' => view()->exists('auth.login'),
    ]);
});
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarisController;

Route::get('/debug', function() {
    try {
        \DB::connection()->getPdo();
        return response()->json([
            'db' => 'connected',
            'app_key' => config('app.key') ? 'set' : 'missing',
            'session' => config('session.driver'),
            'env' => app()->environment(),
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [InventarisController::class, 'loginForm'])->name('login');
Route::post('/login', [InventarisController::class, 'loginPost'])->name('login.post');
Route::post('/logout', [InventarisController::class, 'logout'])->name('logout');
Route::post('/login/check', [InventarisController::class, 'loginCheck'])->name('login.check');

Route::middleware([\App\Http\Middleware\AuthMiddleware::class])->group(function () {
    Route::get('/dashboard', [InventarisController::class, 'dashboard'])->name('dashboard');
    Route::get('/inventaris', [InventarisController::class, 'inventaris'])->name('inventaris');
    Route::post('/inventaris/status', [InventarisController::class, 'updateStatus'])->name('inventaris.status');
    Route::post('/inventaris/delete', [InventarisController::class, 'deleteAset'])->name('inventaris.delete');
    Route::get('/tambah-barang', [InventarisController::class, 'tambahBarang'])->name('tambah-barang');
    Route::post('/tambah-barang', [InventarisController::class, 'storeBarang'])->name('tambah-barang.store');
    Route::get('/mutasi', [InventarisController::class, 'mutasi'])->name('mutasi');
    Route::post('/mutasi', [InventarisController::class, 'storeMutasi'])->name('mutasi.store');
    Route::post('/mutasi/approve', [InventarisController::class, 'approveMutasi'])->name('mutasi.approve');
    Route::post('/mutasi/reject', [InventarisController::class, 'rejectMutasi'])->name('mutasi.reject');
    Route::get('/jadwal', [InventarisController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal', [InventarisController::class, 'storeJadwal'])->name('jadwal.store');
    Route::post('/jadwal/mulai', [InventarisController::class, 'mulaiJadwal'])->name('jadwal.mulai');
    Route::post('/jadwal/selesai', [InventarisController::class, 'selesaiJadwal'])->name('jadwal.selesai');
    Route::get('/stok', [InventarisController::class, 'stok'])->name('stok');
    Route::post('/stok', [InventarisController::class, 'storeStok'])->name('stok.store');
    Route::post('/stok/update', [InventarisController::class, 'updateStok'])->name('stok.update');
    Route::post('/stok/delete', [InventarisController::class, 'deleteStok'])->name('stok.delete');
    Route::get('/qr-label', [InventarisController::class, 'qrLabel'])->name('qr-label');
    Route::get('/laporan', [InventarisController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/ekspor/pdf', [InventarisController::class, 'eksporPdf'])->name('laporan.ekspor.pdf');
    Route::get('/laporan/ekspor/excel', [InventarisController::class, 'eksporExcel'])->name('laporan.ekspor.excel');
    Route::get('/pengaturan', [InventarisController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [InventarisController::class, 'updatePengaturan'])->name('pengaturan.update');
    Route::get('/manajemen-user', [InventarisController::class, 'manajemenUser'])->name('manajemen-user');
    Route::post('/manajemen-user', [InventarisController::class, 'storeUser'])->name('manajemen-user.store');
    Route::post('/manajemen-user/delete', [InventarisController::class, 'deleteUser'])->name('manajemen-user.delete');
    Route::post('/manajemen-user/reset', [InventarisController::class, 'resetPassword'])->name('manajemen-user.reset');
    Route::get('/audit-log', [InventarisController::class, 'auditLog'])->name('audit-log');
});