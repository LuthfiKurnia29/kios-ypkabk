<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Remove the specified product image from storage.
     */
    public function destroy(Request $request, Product $product, ProductImage|int|string $image): JsonResponse|RedirectResponse
    {
        if ($image === 'cover' || $image === 'primary') {
            return $this->destroyCover($request, $product);
        }

        $productImage = $image instanceof ProductImage
            ? $image
            : ProductImage::where('product_id', $product->id)->findOrFail((int) $image);

        if ((int) $productImage->product_id !== (int) $product->id) {
            abort(404);
        }

        $deletedImageContent = $productImage->image;
        $productImage->delete();

        // If the deleted image was the product's primary cover image, update to the next available image
        if ($product->image === $deletedImageContent) {
            $nextImage = $product->images()->first();
            $product->update([
                'image' => $nextImage ? $nextImage->image : null,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Foto produk berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', 'Foto produk berhasil dihapus.');
    }

    /**
     * Remove the cover image from the product.
     */
    public function destroyCover(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $coverContent = $product->image;
        if ($coverContent) {
            $product->images()->where('image', $coverContent)->delete();
        }

        $nextImage = $product->images()->first();
        $product->update([
            'image' => $nextImage ? $nextImage->image : null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Foto produk berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', 'Foto produk berhasil dihapus.');
    }
}
