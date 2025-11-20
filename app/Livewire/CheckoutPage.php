<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attribute\Title;
use App\Helpers\CartManagement;


#[Title('Checkout')]
class CheckoutPage extends Component
{

        public $first_name; 
        public $last_name;
        public $phone;
        public $street_address;
        public $city;
        public $state;
        public $zip_code;
        public $payment_method;

        public function placeOrder(){
            $this->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'street_address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'zip_code' => 'required|string|max:20',
                'payment_method' => 'required|in:cod,stripe',
            ]);
        }
    public function render()
    {

        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
