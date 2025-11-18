<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use App\Models\Product;

class CartManagement
{
    // Add an item to the cart
    static public function addItemToCart($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            // If item exists, increase quantity
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] =
                $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];

        } else {
            // Add new item
            $product = Product::find($product_id);

            if ($product) {
                $cart_items[] = [
                    'product_id'   => $product->id,
                    'name'         => $product->name,
                    'quantity'     => 1,
                    'unit_amount'  => $product->price,
                    'total_amount' => $product->price,
                    'image'        => $product->images[0] ?? null,
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);

        return count($cart_items);
    }

    // Remove an item
    static public function removeCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // Save items to cookie
    static public function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30); // 30 days
    }

    // Clear all items
    static public function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // FIXED NAME — THIS IS THE ONE NAVBAR USES
    static public function getCartItemsFromCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true);

        return $cart_items ?: [];
    }

    // Increase item quantity
    static public function incrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] =
                    $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // Decrease item quantity
    static public function decrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {

                if ($cart_items[$key]['quantity'] > 1) {
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] =
                        $cart_items[$key]['quantity'] *
                        $cart_items[$key]['unit_amount'];
                }
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // Grand total
    static public function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}
