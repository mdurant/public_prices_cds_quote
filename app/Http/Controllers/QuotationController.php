<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationMail;
use Illuminate\Support\Facades\Log;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $quotations = Quotation::withTrashed()->latest()->paginate(10);
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        return view('quotations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $products = [];
        $totalFonasa = 0;
        $totalPrivate = 0;

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $products[] = [
                'id' => $product->id,
                'description' => $product->description,
                'fonasa_code' => $product->fonasa_code,
                'fonasa_patient_price' => $product->fonasa_patient_price,
                'private_price' => $product->private_price,
                'quantity' => $item['quantity'],
            ];
            $totalFonasa += $product->fonasa_patient_price * $item['quantity'];
            $totalPrivate += $product->private_price * $item['quantity'];
        }

        $quotation = Quotation::create([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'quotation_date' => now(),
            'products' => $products,
            'total_fonasa_price' => $totalFonasa,
            'total_private_price' => $totalPrivate,
        ]);

        try {
            Mail::to($quotation->client_email)->send(new QuotationMail($quotation));
        } catch (\Exception $e) {
            Log::error("Error enviando correo de cotización: {$e->getMessage()}");
        }

        return redirect()->route('quotations.index')->with('success', 'Cotización creada y enviada al cliente.');
    }

    public function edit(Quotation $quotation)
    {
        return view('quotations.edit', compact('quotation'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $products = [];
        $totalFonasa = 0;
        $totalPrivate = 0;

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $products[] = [
                'id' => $product->id,
                'description' => $product->description,
                'fonasa_code' => $product->fonasa_code,
                'fonasa_patient_price' => $product->fonasa_patient_price,
                'private_price' => $product->private_price,
                'quantity' => $item['quantity'],
            ];
            $totalFonasa += $product->fonasa_patient_price * $item['quantity'];
            $totalPrivate += $product->private_price * $item['quantity'];
        }

        $quotation->update([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'products' => $products,
            'total_fonasa_price' => $totalFonasa,
            'total_private_price' => $totalPrivate,
        ]);

        try {
            Mail::to($quotation->client_email)->send(new QuotationMail($quotation));
        } catch (\Exception $e) {
            Log::error("Error enviando correo de cotización: {$e->getMessage()}");
        }

        return redirect()->route('quotations.index')->with('success', 'Cotización actualizada y reenviada.');
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Cotización eliminada (soft delete).');
    }

    public function restore($id)
    {
        $quotation = Quotation::withTrashed()->findOrFail($id);
        $quotation->restore();
        return redirect()->route('quotations.index')->with('success', 'Cotización restaurada.');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('description', 'LIKE', "%{$query}%")
            ->orWhere('fonasa_code', 'LIKE', "%{$query}%")
            ->select('id', 'description', 'fonasa_code', 'fonasa_patient_price', 'private_price')
            ->take(10)
            ->get();
        return response()->json($products);
    }
}
