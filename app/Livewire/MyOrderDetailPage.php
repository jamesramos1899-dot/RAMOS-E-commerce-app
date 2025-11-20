<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Order;

#[Title('Order Detail')]
class MyOrderDetailPage extends Component
{
    public $order_id;
    public $order_items;
    public $address;
    public $order;

    public function mount($order_id)
    {
        $this->order_id = $order_id;

        // Load the data safely
        $this->order_items = OrderItem::with('product')
            ->where('order_id', $order_id)
            ->get();

        $this->address = Address::where('order_id', $order_id)->first();

        $this->order = Order::find($order_id);
    }

    public function render()
    {
        return view('livewire.my-order-detail-page');
    }
}
