<?php

namespace App\Http\Resources\Pos;

use Illuminate\Http\Resources\Json\JsonResource;

class PosOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'order_id'=>$this->id,
            'customerID'=>$this->customer_id,
            'deliveryCharge'=>$this->delivery_charge,
            'extraDiscount'=>$this->extra_discount,
            'mode'=>$this->mode,
            'payment'=>$this->payment,
            'payment_mode'=>$this->PosCustomerPayment->payment_mode,
            'transaction_no'=>$this->PosCustomerPayment->transaction_no,
            'due_amount'=>$this->due_amount,
            'bill_amount'=>$this->bill_amount,
            'changes'=>$this->changes,
            'description'=>$this->description,
            'order_date'=>$this->order_date,
            'order_time'=>$this->order_time,
            'products'=>PosOrderProductResource::collection($this->PosCustomerOrderItem),
        ];
    }
}
