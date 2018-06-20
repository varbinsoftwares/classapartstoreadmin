<?php
$this->load->view('layout/layoutTop');
?>
<style>
    .product_image{
        height: 200px!important;
    }
    .product_image_back{
        background-size: contain!important;
        background-repeat: no-repeat!important;
        height: 200px!important;
        background-position-x: center!important;
        background-position-y: center!important;
    }
</style>
<!-- Main content -->
<section class="content" ng-controller="productController">
    <div class="">

        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Add Product</h3>
            </div>
            <div class="box-body">

                <?php echo $this->session->flashdata('success_msg'); ?>
                <?php echo $this->session->flashdata('error_msg'); ?>
                <form action="#" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label >Title</label>
                        <input type="text" class="form-control" name="title"  placeholder="">
                    </div>
                    <div class="form-group">
                        <label >Line 1</label>
                        <input type="text" class="form-control" name="line1"  placeholder="">
                    </div>
                    <div class="form-group">
                        <label >Line 2</label>
                        <input type="text" class="form-control" name="line2"   placeholder="">
                    </div>

                    <div class="form-group">
                        <label >Link</label>
                        <input type="text" class="form-control" name="link"  placeholder="">
                    </div>

                    <div class="form-group">
                        <label >Link Text</label>
                        <input type="text" class="form-control" name="link_text"   placeholder="">
                    </div>

                    <div class="form-group">
                        <label >Position</label>
                        <select name="position" class="form-control">
                            <option>Left</option>
                            <option>Right</option>
                        </select>

                    </div>


                    <!--pictures-->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="thumbnail">
                                <div class="product_image product_image_back" style="background: url(<?php echo (base_url() . "assets_main/" . default_image); ?>)">
                                </div>
                                <div class="caption">
                                    <div class="form-group">
                                        <label for="image1">Upload Primary Image</label>
                                        <input type="file" name="picture" />           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--pictures-->
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
                <table>
                    <?php
                    foreach ($sliders as $key => $value) {
                        
                        ?>

                        <tr>
                            <td>
                                <img src="<?php echo (base_url() . "assets_main/sliderimages/".$value->file_name ); ?>" style="height: 120px;">
                            </td>
                            <td>
                                <h3>
                                    <?php
                                    echo $value->title;
                                    ?>
                                </h3>
                                <p>
                                    <?php
                                    echo $value->line1;
                                    ?>
                                </p>
                                <p>
                                    <?php
                                    echo $value->line2;
                                    ?>
                                </p>
                            </td>
                            <td>
                               <button class="btn btn-primary" style="margin: 10px;" href=" <?php echo $value->link; ?>">
                                   <?php echo $value->link_text; ?>
                               </button>
                            </td> 
                        </tr>
                        <?php
                    }
                    ?>
                </table>


            </div>
        </div>


    </div>



</section>
<!-- end col-6 -->










<script src="<?php echo base_url(); ?>assets_main/tinymce/js/tinymce/tinymce.min.js"></script>
<?php
$this->load->view('layout/layoutFooter');
?> 
<script>
    tinymce.init({selector: 'textarea', plugins: 'advlist autolink link image lists charmap print preview'});
    $(function () {
        $(".price_tag_text").keyup(function () {
            var rprice = Number($("#regular_price").val());
            var sprice = Number($("#sale_price").val());
            console.log(sprice, rprice)
            if (sprice) {
                if (rprice > sprice) {
                    $("#finalprice").text(sprice);
                    $("#finalprice1").val(sprice);
                }
                else {
                    $("#finalprice").text(rprice);
                    $("#finalprice1").val(rprice);
                    $("#sale_price").val(0)
                }
            }
            else {
                $("#finalprice").text(rprice);
                $("#finalprice1").val(rprice);
                $("#sale_price").val(0)
            }
        })
    });

</script>

<script>
    HASALE.controller('productController', function ($scope, $http, $filter, $timeout) {
        $scope.selectedCategory = {'category_string': '', 'category_id': ""};
        var url = "<?php echo base_url(); ?>index.php/ProductManager/category_api";
        $http.get(url).then(function (rdata) {
            $scope.categorydata = rdata.data;
            $('#using_json_2').jstree({'core': {
                    'data': $scope.categorydata.tree
                }});

            $('#using_json_2').bind('ready.jstree', function (e, data) {
                $timeout(function () {
                    $scope.getCategoryString(4);
                }, 100);
            })
        });



        $scope.getCategoryString = function (catid) {
            console.log(catid)
            var objdata = $('#using_json_2').jstree('get_node', catid);
            var catlist = objdata.parents;
            $timeout(function () {
                $scope.selectedCategory.selected = objdata;
                var catsst = [];
                for (i = catlist.length + 1; i >= 0; i--) {
                    var catid = catlist[i];
                    var catstr = $scope.categorydata.list[catid];
                    if (catstr) {
                        catsst.push(catstr.text);
                    }
                }
                catsst.push(objdata.text);
                $("#category_id").val(objdata.id);
                console.log(objdata.id)
                $scope.selectedCategory.category_string = catsst.join("->")
            }, 100)
        }

        $(document).on("click", "[selectcategory]", function (event) {
            var catid = $(this).attr("selectcategory");
            $scope.getCategoryString(catid);
        })


    })




</script>

