<?php

namespace App\Http\Resources\Pos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PosAppOrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (isset($this->delivery_time)) {
            $time_slot = $this->delivery_time->from_time . '-' . $this->delivery_time->to_time;
        } else {
            $time_slot = '';
        }
        if (isset($this->shipping_location->region_id) && !empty($this->shipping_location->region_id)) {
            $address =  $this->shipping_location->region->name;
        } else {

            if (isset($this->shipping_location->address) && !empty($this->shipping_location->address)) {

                $address =  $this->shipping_location->address;
            } else {
                $address = "";
            }
        }
        $total_saving = number_format((($this->total_amount - $this->offer_total - $this->coupon_amount) / 100), 2, '.', '');
        $total = $this->offer_total;
        $items_price = $this->offer_total - $this->delivery_charge;
        $discount =  number_format($this->offer_total - $this->total_amount, 2, '.', '');
        $date = Carbon::parse($this->created_at)->format('d/m/Y, H:i');

        return [
            "id" => $this->id,
            "customer_id"=>$this->user_id,
            "order_status" => $this->order_status,
            "order_code" => $this->order_code,
            "delivery_date" => $this->delivery_date,
            "delivery_charge" => $this->delivery_charge,
            "time_slot" => $time_slot,
            "address" => $address,
            "coupon" => $this->coupon,
            "coupon_amount" => $this->coupon_amount,
            "total_saving" => $total_saving,
            "total" => $total,
            "items_price" => $items_price,
            "discount" => $discount,
            "date" => $date,
            "payment_mode" => $this->PaymentMode ? $this->PaymentMode->name : '',
            "items" => $this->ProductOrderItem()->count(),
            // "products"=>$this->ProductOrderItem()->vendorProduct->Product
            "products"=>PosAppProductResource::collection($this->ProductOrderItem->where('order_id',$this->id)),
        ];
    }
}
