<?php
// app/Models/Cart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    protected $sessionKey = 'shopping_cart';

    public function getItems()
    {
        return Session::get($this->sessionKey, []);
    }

    public function addItem($productId, $productData, $quantity = 1)
    {
        $cart = $this->getItems();
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $productData['name'],
                'price' => $productData['price'],
                'quantity' => $quantity,
                'type' => $this->determineProductType($productData)
            ];
        }
        
        Session::put($this->sessionKey, $cart);
        return $cart;
    }

    public function removeItem($productId)
    {
        $cart = $this->getItems();
        unset($cart[$productId]);
        Session::put($this->sessionKey, $cart);
        return $cart;
    }

    public function updateQuantity($productId, $quantity)
    {
        $cart = $this->getItems();
        
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
        }
        
        Session::put($this->sessionKey, $cart);
        return $cart;
    }

    public function clear()
    {
        Session::forget($this->sessionKey);
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getTotalItems()
    {
        return count($this->getItems());
    }

    private function determineProductType($product)
    {
        $drinkKeywords = ['kopi', 'coffee', 'tea', 'espresso', 'latte', 'cappuccino', 'mocha', 'americano', 'es', 'jus'];
        $productName = strtolower($product['name']);
        
        return collect($drinkKeywords)->contains(function ($keyword) use ($productName) {
            return str_contains($productName, $keyword);
        }) ? 'drink' : 'food';
    }
}