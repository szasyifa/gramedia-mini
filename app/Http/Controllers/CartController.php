<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\SubscriptionPackageUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\SubscriberTypeAlreadyRegisteredException;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $carts = Cart::where('user_id', $userId)->with('book')->get();

        $activePackageIds = SubscriptionPackageUser::where('user_id', $userId)
        ->whereDate('expired_date', '>', today())
        ->pluck('subscription_package_id');

        $packageNotBought = $carts
        ->whereNotNull('subscription_package_id')
        ->pluck('subscription_package_id')
        ->unique()
        ->diff($activePackageIds)
        ->values();

        $carts->each(function ($cart) use ($packageNotBought) {
            $cart->display_price = $cart->subscriptionPackage ? $cart->subscriptionPackage->price :
            $cart->book->price;
            $cart->needs_package = $packageNotBought->contains($cart->subscription_package_id);
        });

        $totalPrice = $carts->sum('display_price');
        return view('user.books.cart', compact('carts', 'totalPrice'));
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
    public function store(Request $request)
    {
        // cek data apa aja yang
        // dd($request->all());
        if (!$request->purchase_option) {
            return redirect()->back()->with('error', 'Pastikan anda memilih jenis harga pembayaran');
        }

        if ($request->action == "add_to_cart") {
            // acuan apa yang di update -> [] pertama
            // apa yang mau di update -> [] kedua
            Cart::updateOrCreate([
                'user_id' => Auth::user()->id,
                'book_id' => $request->book_id
            ], [
                'subscription_package_id' => $request->purchase_option == "none" ? null : $request->purchase_option
            ]);
            return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke keranjang Anda');
        } else {
            dd('proses beli sekarang');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart, $id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->route('cart')->with('success', 'Buku berhasil dihapus dari keranjang');
    }
}
