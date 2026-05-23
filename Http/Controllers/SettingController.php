
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display profile page
     */
    public function profile()
    {
        return view('profile.index');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh user lain.',
            'avatar.image' => 'Berkas harus berupa gambar.',
            'avatar.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return back()->with('toast_success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('toast_success', 'Password Anda berhasil diubah.');
    }

    /**
     * Display settings and reset dashboard
     */
    public function index()
    {
        $settings = [
            'shop_name' => Setting::get('shop_name', 'EXOTIC BIRD STORE'),
            'shop_address' => Setting::get('shop_address', ''),
            'shop_phone' => Setting::get('shop_phone', ''),
            'shop_email' => Setting::get('shop_email', ''),
            'shop_currency' => Setting::get('shop_currency', 'Rp'),
        ];
        return view('settings.index', compact('settings'));
    }

    /**
     * Update website global settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'nullable|string',
            'shop_phone' => 'required|string|max:20',
            'shop_email' => 'nullable|email|max:255',
            'shop_currency' => 'required|string|max:10',
        ], [
            'shop_name.required' => 'Nama toko wajib diisi.',
            'shop_phone.required' => 'Nomor HP/WA wajib diisi.',
            'shop_currency.required' => 'Mata uang wajib diisi.',
        ]);

        // Clean phone number: replace starting '0' with '62' for WhatsApp compatibility
        $phone = $request->shop_phone;
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        $phone = preg_replace('/[^0-9]/', '', $phone); // numbers only

        Setting::set('shop_name', $request->shop_name);
        Setting::set('shop_address', $request->shop_address);
        Setting::set('shop_phone', $phone);
        Setting::set('shop_email', $request->shop_email);
        Setting::set('shop_currency', $request->shop_currency);

        return back()->with('toast_success', 'Pengaturan website berhasil disimpan.');
    }

    /**
     * Reset Website / System Data
     */
    public function resetSystem(Request $request)
    {
        $request->validate([
            'reset_type' => 'required|string|in:transactions,stock,all',
        ]);

        $type = $request->reset_type;

        try {
            DB::beginTransaction();

            if ($type === 'transactions' || $type === 'all') {
                // Disable foreign key checks to truncate smoothly
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                TransactionDetail::truncate();
                Transaction::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            if ($type === 'stock' || $type === 'all') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                StockIn::truncate();
                StockOut::truncate();
                // Reset all product stocks to 0
                Product::query()->update(['stock' => 0, 'status' => 'habis']);
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            DB::commit();
            
            $msg = '';
            if ($type === 'transactions') $msg = 'Semua data transaksi penjualan berhasil di-reset.';
            if ($type === 'stock') $msg = 'Semua data stok masuk/keluar berhasil di-reset dan stok produk dikosongkan.';
            if ($type === 'all') $msg = 'Seluruh data transaksi dan catatan stok berhasil dibersihkan dari sistem.';

            return back()->with('toast_success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast_error', 'Gagal mereset sistem: ' . $e->getMessage());
        }
    }

    /**
     * Pure PHP Database Backup Generator (.sql dump download)
     */
    public function backupDb()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $tables = DB::select('SHOW TABLES');
            $tablesKey = "Tables_in_{$dbName}";
            
            $sqlDump = "-- EXOTIC BIRD STORE Database Backup\n";
            $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sqlDump .= "-- Database: `{$dbName}`\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tablesKey;
                
                // Get CREATE TABLE
                $createStatement = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                $createTableSql = "Create Table";
                $sqlDump .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlDump .= $createStatement->$createTableSql . ";\n\n";
                
                // Get Inserts
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sqlDump .= "-- Dumping data for table `{$tableName}`\n";
                    foreach ($rows as $row) {
                        $rowArray = (array)$row;
                        $columns = array_keys($rowArray);
                        $escapedColumns = array_map(function($col) { return "`{$col}`"; }, $columns);
                        
                        $escapedValues = array_map(function($val) {
                            if (is_null($val)) return "NULL";
                            // Basic escaping
                            $escapedStr = str_replace(
                                array("\\", "\0", "\n", "\r", "'", '"', "\x1a"),
                                array("\\\\", "\\0", "\\n", "\\r", "\'", '\\"', "\\Z"),
                                $val
                            );
                            return "'{$escapedStr}'";
                        }, array_values($rowArray));
                        
                        $sqlDump .= "INSERT INTO `{$tableName}` (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $escapedValues) . ");\n";
                    }
                    $sqlDump .= "\n";
                }
            }
            
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            $filename = 'backup_exotic_bird_store_' . date('Y_m_d_His') . '.sql';
            
            return response($sqlDump)
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            return back()->with('toast_error', 'Gagal mencadangkan database: ' . $e->getMessage());
        }
    }

    /**
     * List all users
     */
    public function usersIndex()
    {
        $users = User::with('role')->where('id', '!=', Auth::id())->paginate(10);
        $roles = Role::all();
        return view('settings.users', compact('users', 'roles'));
    }

    /**
     * Create a user (Admin only)
     */
    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role_id.required' => 'Role wajib dipilih.',
        ]);

        User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('toast_success', 'User baru berhasil dibuat.');
    }

    /**
     * Delete a user (Admin only)
     */
    public function userDestroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return back()->with('toast_error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return back()->with('toast_success', 'User berhasil dihapus.');
    }
}
