<div class="modal fade" id="paymentModal" data-controls-modal="paymentModal" role="dialog" data-backdrop="static"
data-keyboard="false">
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <b class="modal-title" style="font-size: 18px">Payment</b>
        </div>
        <div class="modal-body">
            <form id="paymentform">
                <div class="row">
                    <div class="col-md-12 text-center">

                        <label class="radio-inline">
                            <input type="radio" name="payment_mode" value="cash" id="cashMode" checked>Cash
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payment_mode" value="online" id="onlineMode">Online
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payment_mode" value="cheque" id="chequeMode">Cheque
                        </label>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="amount">Amount:</label>
                        <div class="input-group input-group-lg m-input-group m-input-group--air">
                            {{-- <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fa fa-inr currency_style" aria-hidden="true"></i></span></div> --}}
                            <input type="number" min="0"
                                class="form-control m-input m-input--air m--font-boldest m--regular-font-size-lg5"
                                name="amount" id="amount" placeholder="Amount">
                        </div>
                    </div>

                </div>
                <div class="row mt-3 display-hide" id="paymentDateSection">
                    <div class="col-md-5">
                        <label for="payment_date">Payment Date</label>
                        <div class="input-group input-group-lg">
                            <input type="form-contorl" name="payment_date" id="payment_date">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="transaction_no">Transaction No./Cheque No.</label>
                        <div class="input-group input-group-lg">
                            <input
                                type="form-contorl m-input m-input--air m--font-boldest m--regular-font-size-lg5"
                                name="transaction_no" id="transaction_no" style="width: 100% !important;"
                                placeholder="Transaction Number">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label for="description">Description</label>
                        <textarea class="form-control m-input m-input--air m--font-boldest m--regular-font-size-lg5" name="description"
                            id="description" style="width: 569px; height: 129px; resize:none;" placeholder="Write here..."></textarea>
                    </div>
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="butotn" class="btn btn-success" id="saveBtn">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
    </div>

</div>
</div>