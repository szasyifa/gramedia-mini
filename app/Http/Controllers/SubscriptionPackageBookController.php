<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackageBook;
use App\Models\SubscriptionPackage;
use App\Models\Book;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $packageId)
    {
        if ($request->ajax()) {
            $books = Book::with('bookCategory');

            return DataTables::of($books)
                ->addIndexColumn()

                ->editColumn('cover', function ($row) {
                    if ($row->cover) {
                        return '<img src="' . asset($row->cover) .
                            '" alt="' . e($row->title) .
                            '" class="img-thumbnail" style="max-height: 50px; width: auto;">';
                    }

                    return '<span class="badge bg-secondary">No Cover</span>';
                })

                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })

                ->addColumn('category_name', function ($row) {
                    return $row->bookCategory
                        ? $row->bookCategory->name
                        : '-';
                })
                ->addColumn('checkBook', function ($row) use ($packageId) {
                    $bookIds = SubscriptionPackageBook::where('subscription_package_id', $packageId)->pluck('book_id')->toArray();
                    // pluck mengambil hanya dari 1 atrtribute
                    return in_array($row->id, $bookIds) ? true : false;
                })
                ->rawColumns(['cover'])
                ->make(true);
        }
        $subscriptionPackage = SubscriptionPackage::find($packageId);
        return view('admin.subscription-package-books.index', compact('subscriptionPackage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $packageId)
    {
        $request->validate([
            'bookIds' => 'required'
        ]);

        foreach ($request->bookIds as $key => $value) {
            SubscriptionPackageBook::create([
                'book_id' => $value,
                'subscription_package_id' => $packageId
            ]);
        }
        return redirect()->route('admin.subscription-package-books', ['packageId' => $packageId])->with('success', 'Berhasil menambahkan buku ke paket langganan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $search = $request->q;

        $packageBooks = $subscriptionPackage->subscriptionPackageBooks()
            ->with('book')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('book', function ($book) use ($search) {
                    $book->where('title', 'LIKE', '%' . $search . '%');
                });
            })
            ->get();

        return view('show-subscription-package', compact('subscriptionPackage', 'packageBooks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPackageBook $subscriptionPackageBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPackageBook $subscriptionPackageBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPackageBook $subscriptionPackageBook)
    {
        //
    }
}
