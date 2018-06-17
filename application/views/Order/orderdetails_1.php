<?php
$this->load->view('layout/layoutTop');
?>



<section class="content" style="min-height: auto;">

    <div class="row">
        <!-- title row -->
        <div class="col-md-12">
            <div class="col-xs-12">
<!--                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#order_process" data-toggle="tab" aria-expanded="false">Order Processing</a>
                        </li>
                        <li>
                            <a href="#order_shipping" data-toggle="tab" aria-expanded="false">Order Shipping</a>
                        </li>
                        <li>
                            <a href="#order_complete" data-toggle="tab" aria-expanded="true">Order Close/Delivered</a>
                        </li>
                        <li>
                            <a href="#order_pending" data-toggle="tab" aria-expanded="true">Order Return/Pending</a>
                        </li>
                        <li>
                            <a href="#order_cancel" data-toggle="tab" aria-expanded="true">Order Cancel</a>
                        </li>
                        <li class="pull-left header">
                            <i class="fa fa-hashtag"></i> Invoice
                            <small>#<?php echo $order_data->id; ?></small>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="order_process">
                            <h2>Process Order</h2>
                            <div>
                                <form role="form">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputFile">File input</label>
                                            <input type="file" id="exampleInputFile">

                                            <p class="help-block">Example block-level help text here.</p>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> Check me out
                                            </label>
                                        </div>
                                    </div>
                                     /.box-body 

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                         /.tab-pane 


                        <div class="tab-pane" id="order_shipping">
                            <h2>Order Shipping</h2>
                            <div></div>
                        </div>
                         /.tab-pane 


                        <div class="tab-pane" id="order_pending">
                            <h2>Order Return/Pending</h2>
                            <div></div>
                        </div>
                         /.tab-pane 


                        <div class="tab-pane" id="order_complete">
                            <h2>Order Close/Delivered</h2>
                            <div></div>
                        </div>
                         /.tab-pane 


                        <div class="tab-pane" id="order_cancel">
                            <h2>Order Cancel</h2>
                            <div></div>
                        </div>
                         /.tab-pane 

                    </div>
                     /.tab-content 
                </div>-->
            </div>
        </div>
    </div>
</section>



<!-- Main content -->
<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                Class Apart Store.
                <small class="pull-right">Date: <?php echo $order_data->order_date; ?></small>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            To
            <address>
                <strong><?php echo $order_data->name; ?></strong><br>
                <?php echo $order_data->address; ?><br/>
                <?php echo $order_data->state; ?>  <?php echo $order_data->city; ?> <?php echo $order_data->pincode; ?><br/>
                <i class="fa fa-phone"></i> <?php echo $order_data->contact_no; ?><br>
                <i class="fa fa-envelope"></i> <?php echo $order_data->email; ?>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">

        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice #<?php echo $order_data->id; ?></b><br><br/>
            <b>Order ID:</b> <?php echo $order_data->order_no; ?><br>
            <b>Date:</b> <?php echo $order_data->order_date; ?><br>
            <b>Time:</b>  <?php echo $order_data->order_time; ?>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr style="font-weight: bold">
                        <td style="width: 20px;text-align: center">S.No.</td>
                        <td colspan="2"  style="text-align: center">Product</td>

                        <td style="text-align: right;width: 100px"">Price<br/><span style="font-size: 10px">(In INR)</span></td>
                        <td style="text-align: right;width: 60px"">Qnty.</td>
                        <td style="text-align: right;width: 100px">Total<br/><span style="font-size: 10px">(In INR)</span></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cart_data as $key => $product) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $key + 1; ?>
                            </td>

                            <td style="width: 60px"> 
                                <img src=" <?php echo $product->file_name; ?>" style="height: 50px;"/>
                            </td>

                            <td style="width: 200px;">
                                <?php echo $product->title; ?>
                            </td>

                            <td style="text-align: right">
                                <?php echo $product->price; ?>
                            </td>

                            <td style="text-align: right">
                                <?php echo $product->quantity; ?>
                            </td>

                            <td style="text-align: right;">
                                <?php echo $product->total_price; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>


                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <p class="lead" style="text-align: right">Amount Description</p>

        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td colspan="3"  rowspan="4" style="font-size: 12px;text-align: right">
                        <b>Total Amount in Words:</b><br/>
                        <span style="text-transform: capitalize"> <?php echo $order_data->amount_in_word; ?></span>
                    </td>

                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="2" style="text-align: right">Total</td>
                    <td style="text-align: right;width: 60px"><?php echo $order_data->sub_total_price; ?> </td>
                </tr>
                <tr  style="font-weight: bold;">
                    <td colspan="2" style="text-align: right">Credit Used</td>
                    <td style="text-align: right;width: 60px"><?php echo $order_data->credit_price; ?> </td>
                </tr>
                <tr  style="font-weight: bold;">
                    <td colspan="2" style="text-align: right">Total Amount</td>
                    <td style="text-align: right;width: 60px"><?php echo $order_data->total_price; ?> </td>
                </tr>

            </table>
        </div>
    </div>




    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-xs-12">
            <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
            </button>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
    </div>
</section>
<!-- /.content -->
<div class="clearfix"></div>




<?php
$this->load->view('layout/layoutFooter');
?> 