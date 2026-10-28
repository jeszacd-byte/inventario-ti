<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('product')
            ->latest()
            ->latest('id')
            ->paginate(10);

        $products = Product::orderBy('name')->get();

        return view('stock_movements.index', compact('movements', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:entrada,salida'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        // La transacción garantiza que el movimiento y el nuevo stock se guarden juntos.
        DB::transaction(function () use ($data) {
            $product = Product::whereKey($data['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $before = $product->stock;
            $quantity = (int) $data['quantity'];

            if ($data['type'] === 'salida' && $quantity > $before) {
                throw ValidationException::withMessages([
                    'quantity' => 'No hay stock suficiente para esta salida.',
                ]);
            }

            $after = $data['type'] === 'entrada'
                ? $before + $quantity
                : $before - $quantity;

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $data['type'],
                'quantity' => $quantity,
                'stock_before' => $before,
                'stock_after' => $after,
                'reason' => $data['reason'] ?? null,
            ]);

            $product->update(['stock' => $after]);
        });

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Movimiento registrado correctamente.');
    }
}
