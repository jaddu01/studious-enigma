<div class="modal fade" id="addProductModal" data-controls-modal="addProductModal" role="dialog"
data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <b class="modal-title" style="font-size: 18px">Add New Product</b>
        </div>
        <div class="modal-body">
            <form id="addNewProductForm">
                <div class="row">
                    <div class="col-md-6">
                        <label for="prodcutName">Name<small class="startTxt">*</small></label>
                        <input type="text" class="form-control custom-form-input" placeholder="Product Name"
                            name="name:en" id="prodcutName">
                    </div>

                    <div class="col-md-6">
                        <label for="printName">Print Name</label>
                        <input type="text" class="form-control custom-form-input" placeholder="Print Name"
                            name="print_name:en" id="printName">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="productCategories">Category<small class="startTxt">*</small></label>
                        {!! Form::select('category_id[]', $product_categories, null, [
                            'placeholder' => 'Select Category',
                            'class' => 'form-control',
                            'id' => 'productCategories',
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        <label for="brandsList">Brands</label><br>
                        {!! Form::select('brand_id', $brands, null, [
                            'placeholder' => 'Select Brand',
                            'class' => 'form-control',
                            'id' => 'brandsList',
                        ]) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="gstList">GST<small class="startTxt">*</small></label>
                        {!! Form::select(
                            'gst',
                            [
                                0 => '0%',
                                5 => '5%',
                                12 => '12%',
                                18 => '18%',
                            ],
                            null,
                            [
                                'placeholder' => 'Select GST',
                                'class' => 'form-control custom-form-input',
                                'id' => 'gstList',
                            ],
                        ) !!}
                    </div>

                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        
                        <label for="measurementClass">Measurement Class<small
                                class="startTxt">*</small></label><br>
                        {!! Form::select('measurement_class', $measurementClass, null, [
                            'placeholder' => 'Select Measurement',
                            'class' => 'form-control',
                            'id' => 'measurementClass',
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        <label for="measurement_value">Measurement Value<small class="startTxt">*</small></label>
                        {!! Form::text('measurement_value', null, [
                            'placeholder' => 'keyword',
                            'class' => 'form-control custom-form-input',
                            'id' => 'measurement_value',
                        ]) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="hsn_code">HSN Code</label>
                        {!! Form::text('hsn_code', null, [
                            'placeholder' => 'DAR-0000',
                            'class' => 'form-control custom-form-input',
                            'id' => 'hsn_code',
                        ]) !!}
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="status">Status</label>
                        {!! Form::select(
                            'status',
                            [
                                1 => 'Active',
                                0 => 'Inactive',
                            ],
                            1,
                            [
                                'class' => 'form-control custom-form-input',
                                'id' => 'status',
                            ],
                        ) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="show_in_cart_page">Show In Cart Page</label>
                        {!! Form::select(
                            'show_in_cart_page',
                            [
                                0 => 'No',
                                1 => 'Yes',
                            ],
                            0,
                            [
                                'class' => 'form-control custom-form-input',
                                'id' => 'show_in_cart_page',
                            ],
                        ) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="returnable">Returnable</label>
                        {!! Form::select(
                            'returnable',
                            [
                                0 => 'No',
                                1 => 'Yes',
                            ],
                            0,
                            [
                                'class' => 'form-control custom-form-input',
                                'id' => 'returnable',
                            ],
                        ) !!}
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="expire_date">Expire Date</label>
                        {!! Form::date('expire_date', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'expire_date',
                        ]) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="add_new_product_barcode">Barcode</label>
                        {!! Form::text('barcode', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'add_new_product_barcode',
                            'placeholder' => '890000000000',
                        ]) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="product_qty">Qty<small class="startTxt">*</small></label>
                        {!! Form::text('qty', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'product_qty',
                            'placeholder' => 'Quantity',
                        ]) !!}
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="prodcut_price">MRP<small class="startTxt">*</small></label>
                        {!! Form::text('price', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'prodcut_price',
                            'placeholder' => 'Enter Mrp',
                        ]) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="best_price">Best Price<small class="startTxt">*</small></label>
                        {!! Form::text('best_price', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'best_price',
                            'placeholder' => 'Enter Best Price',
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        <label for="purchase_price">Purchase Price<small class="startTxt">*</small></label>
                        {!! Form::text('purchase_price', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'purchase_price',
                            'placeholder' => 'Enter Purchase Price',
                        ]) !!}
                    </div>

                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="membership_p_price">Membership Price <small class="startTxt">*</small></label>
                        {!! Form::text('membership_p_price', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'membership_p_price',
                            'placeholder' => 'Enter membership price',
                        ]) !!}
                    </div>

                    <div class="col-md-4">
                        <label for="per_order">Max. per order <small class="startTxt">*</small></label>
                        {!! Form::text('per_order', null, [
                            'class' => 'form-control custom-form-input',
                            'id' => 'per_order',
                            'placeholder' => 'Enter max. per order ',
                        ]) !!}
                    </div>


                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="product_description">Description<small class="startTxt">*</small></label>
                        <textarea class="form-control custom-form-input description-size" id="product_description" name="description:en"
                            placeholder="Enter description"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="prodcut_disclaimer">Disclaimer<small class="startTxt">*</small></label>
                        <textarea class="form-control custom-form-input description-size" id="prodcut_disclaimer" name="disclaimer:en"
                            placeholder="Enter disclaimer"></textarea>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="shelf_life">Shelf Life <small class="startTxt">*</small></label>
                        <textarea class="form-control custom-form-input description-size" id="shelf_life" name="self_life:en"
                            placeholder="Enter shelf life"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="prodcut_disclaimer">Manufacture Details<small
                                class="startTxt">*</small></label>
                        <textarea class="form-control custom-form-input description-size" id="prodcut_disclaimer"
                            name="manufacture_details:en" placeholder="Enter manufacture details"></textarea>
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="markedted_by">Marketed By <small class="startTxt">*</small></label>
                        <textarea class="form-control custom-form-input description-size" id="markedted_by" name="marketed_by:en"
                            placeholder="Enter markedted by"></textarea>
                    </div>

                    <div class="col-md-6 product-image-uploder">
                        <label for="product_image">Uplaod Prodcut Image</label><br>
                        <div class="text-center">
                            <img src="{{ asset('public/assets/icons/cloud-upload-fill.svg') }}" id="productImgUploader">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="related_products">Related Products</label>
                        <select class="form-control select2-related-products custom-form-input" 
                        id="related_products"  name="related_products[]" placeholder="Search Related Products Name">
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="variant_products[]">Variant Products</label>
                        <select class="form-control select2-variant-products custom-form-input" 
                        id="variant_products"  name="variant_products[]" placeholder="Search Variant Products Name">
                        </select>

                    </div>
                  
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="butotn" class="btn btn-success" id="addproductModalBtn">Add Product</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>

</div>
</div>