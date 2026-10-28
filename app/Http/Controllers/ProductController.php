<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /** Columnas por las que se permite ordenar (opcional 2). */
    private const SORTABLE = ['name', 'price', 'stock'];

    private function rules(?Product $product = null): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'sku' => [
                'required', 'string', 'max:50',
                $product
                    ? Rule::unique('products', 'sku')->ignore($product->id)
                    : 'unique:products,sku',
            ],
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Consulta base con búsqueda (nombre o SKU), filtros y orden.
     * Se reutiliza en el listado y en la exportación a CSV.
     */
    private function filteredQuery(Request $request): Builder
    {
        $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'sort' => ['nullable', 'in:' . implode(',', self::SORTABLE)],
            'dir' => ['nullable', 'in:asc,desc'],
        ]);

        $query = Product::with(['category', 'supplier']);

        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($subquery) use ($term) {
                $subquery->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }

        if ($request->boolean('low')) {
            $query->whereColumn('stock', '<=', 'min_stock');
        }

        $sort = $request->input('sort', 'name');
        $dir = $request->input('dir', 'asc');
        $query->orderBy($sort, $dir);

        return $query;
    }

    public function index(Request $request)
    {
        $products = $this->filteredQuery($request)->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();

        return view('products.index', compact(
            'products', 'categories', 'suppliers', 'totalProducts', 'lowStockCount'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules($product));

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->stockMovements()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'No se puede eliminar: el producto tiene movimientos de stock registrados.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    /** Exporta a CSV el listado con los filtros aplicados (opcional 4). */
    public function export(Request $request)
    {
        $products = $this->filteredQuery($request)->get();

        return response()->streamDownload(function () use ($products) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para que Excel lea las tildes
            fputcsv($out, ['SKU', 'Producto', 'Categoría', 'Proveedor', 'Precio', 'Stock', 'Stock mínimo', 'Estado']);

            foreach ($products as $product) {
                fputcsv($out, [
                    $product->sku,
                    $product->name,
                    $product->category->name,
                    $product->supplier->name,
                    number_format($product->price, 2, '.', ''),
                    $product->stock,
                    $product->min_stock,
                    $product->isLowStock() ? 'Stock bajo' : 'Stock disponible',
                ]);
            }

            fclose($out);
        }, 'productos-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
