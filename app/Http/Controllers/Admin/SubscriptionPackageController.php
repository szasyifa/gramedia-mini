<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPackageUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\SubscriptionPackagesExport;
use Maatwebsite\Excel\Facades\Excel;

class SubscriptionPackageController extends Controller
{
    private function rules(): array
    {
        return [
            'name_package' => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'color'        => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'price'        => ['required', 'integer', 'min:0'],
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = SubscriptionPackage::query();

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->editColumn('price', fn($row) => 'Rp ' . number_format($row->price, 0, ',', '.'))
                ->editColumn('description', fn($row) => Str::limit($row->description, 80))
                ->editColumn('color', fn($row) => '<span class="badge text-white" style="background:' . e($row->color) . '">' . e($row->color) . '</span>')
                ->addColumn('action', function ($row) {
                    $editUrl    = route('admin.subscription-packages.edit', $row->id);
                    $destroyUrl = route('admin.subscription-packages.destroy', $row->id);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                        <form action="' . $destroyUrl . '" method="POST" class="d-inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger " onclick="return confirm(\'Apakah Anda yakin ingin menghapus paket ini?\')">Hapus</button>
                        </form>'
                        . '<a class="btn btn-sm btn-success" href="' . route(
                            'admin.subscription-package-books',
                            ['packageId' => $row->id]
                        ) . '">Buku</a>';
                })
                // untuk mengeceka apakah buku sudah ditambahkan kepaket langganan yang dipilih
                ->rawColumns(['color', 'action'])
                ->toJson();
        }

        return view('admin.subscription-packages.index');
    }

    public function create()
    {
        return view('admin.subscription-packages.create');
    }

    public function store(Request $request)
    {
        SubscriptionPackage::firstOrCreate($request->validate($this->rules()));

        return redirect()->route('admin.subscription-packages.index')
            ->with('success', 'Paket langganan berhasil ditambahkan.');
    }

    public function show(Request $request, $id) {}

    public function edit($id)
    {
        $package = SubscriptionPackage::findOrFail($id);

        return view('admin.subscription-packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = SubscriptionPackage::findOrFail($id);
        $package->update($request->validate($this->rules()));

        return redirect()->route('admin.subscription-packages.index')
            ->with('success', 'Paket langganan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        SubscriptionPackage::findOrFail($id)->delete();

        return redirect()->route('admin.subscription-packages.index')
            ->with('success', 'Paket langganan berhasil dihapus.');
    }

    public function payment($id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        return view('user.subscription-package-payment', compact('subscriptionPackage'));
    }

    public function paymentSend(Request $request, $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        SubscriptionPackageUser::create([
            'subscription_package_id' => $subscriptionPackage->id,
            'user_id' => Auth::id(),
            'expired_date' => now()->addDays(30)->format('Y-m-d')
        ]);
        return redirect()->route('home')->with('success', 'Berhasil membeli paket langganan! Silahkan cek halaman profile untuk detail transaksi');
    }

    public function export()
    {
        return Excel::download(new SubscriptionPackagesExport, 'Subcsription_Packages.xlsx');
    }
}
