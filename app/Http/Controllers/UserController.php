<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Book;
use App\Models\SubscriptionPackage;
use App\Models\BookCategory;
use App\Models\Checkout;
use App\Models\SubscriptionPackageUser;

class UserController extends Controller
{
    public function index()
    {
        return view('login');
    }


    public function store(Request $request)
    {
        $validatedDate = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:12']
        ], [
            'name.required' => 'Nama Lengkap harus diisi',
            'name.min' => 'Nama Lengkap harus diisi dengan minimal 3 huruf',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar, gunakan email lainnya',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password harus diisi dengan minimal 8 karakter',
            'password.max' => 'Password harus diisi dengan maximal 12 karakter',
        ]);

        User::create([
            'name' => $validatedDate['name'],
            'email' => $validatedDate['email'],
            'password' => Hash::make($validatedDate['password'])
        ]);

        return redirect()->route('login')->with('success', 'berhasil membuat akun! Silahkan login');
    }

    public function authentication(Request $request)
    {
        $validatedDate = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validatedDate)) {
            $request->session()->regenerate();

            if (Auth::user()->role == "admin") {
                return redirect()->route('admin.dashboard')->with('success', 'Anda telah login!');
            } else {
                return redirect()->route('home')->with('success', 'Anda teelah login!');
            }
        } else {
            return redirect()->route('login')->with('error', 'Email dan password salah. Coba lagi!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda sudah logout');
    }

    public function home()
    {
        $latesBooks = Book::orderBy('created_at', 'desc')->limit(10)->get();
        $freeBooks = Book::where('price', 0)->orderBy('created_at', 'desc')->limit(6)->get();

        $subscriptionPackages = SubscriptionPackage::orderBy('created_at', 'desc')->limit(3)->get();

        $navbarCategories = BookCategory::all();

        return view('home', compact('latesBooks', 'freeBooks', 'subscriptionPackages', 'navbarCategories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $books = Book::where('title', 'LIKE', '%' . $query . '%')->orWhere('writer', 'LIKE', '%' . $query . '%')->get();

        return view('search-result', compact('books', 'query'));
    }

    public function categoryBooks(Request $request, $id)
    {
        $category = BookCategory::findOrFail($id);

        $query = Book::where('book_category_id', $id);
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('writer', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->filled('sort')) {
            if ($request->sort == 'cheapest') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'expensive') {
                $query->orderBy('price', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $books = $query->get();
        $navbarCategories = BookCategory::all();
        return view('category-books', compact('category', 'books', 'navbarCategories'));
    }

    public function profile(Request $request)
    {
        $user = Auth::user();

        $totalBooks = Checkout::where('user_id', $user->id)->count();
        $totalSubscriptions = SubscriptionPackageUser::where('user_id', $user->id)->count();

        $type = $request->input('type', 'subscription');
        if (!in_array($type, ['subscription', 'book'])) {
            $type = 'subscription';
        }

        $subscriptionTransactions = collect();
        if ($type == 'subscription') {
            $subscriptionTransactions = SubscriptionPackageUser::with('subscriptionPackage')->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')->get();
        }

        return view('user.profile', compact('user', 'totalBooks', 'totalSubscriptions', 'type', 'subscriptionTransactions'));
    }
}
