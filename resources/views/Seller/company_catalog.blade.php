<?php
function stock($id)
{
    $array = array();
    $stock_ins = DB::table("stock_ins")->where('product_id',$id)->get();
    foreach ($stock_ins as $stock_in)
    {
        $array[] = $stock_in->id;
    }
    $stock_ins_list = DB::table("stock_ins_list")->whereIn('stock_ins_id',$array)->sum('stock');
    return $stock_ins_list;
}
use App\Models\Category;
class categories{
public function format($id)
{
$data = Category::where('parent_id',$id)->with('children')->get();
foreach ($data as $category)
{
echo '<li>';
?>
<input type="checkbox" name="category[]" value="{{$category->id}}" {{ (is_array(old('category')) && in_array($category->id, old('category'))) ? ' checked' : '' }}>
<a href="javascript:void(0);">{{\App\MyClasses\Helpers::get_lang($category->category_name,$category->id,"category",App::getLocale())}}</a>

<?php
if ($category->children->isNotEmpty())
{
    echo "<ul>";
    self::format($category->id);
    echo "</ul>";
}
echo "</li>";
}
}
}
$chaild = new categories();
?>
@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.company-catalog')}}</h4>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        <button onclick="show_hide()" class="btn btn-primary"><i class="fa fa-filter"></i>
                            {{__('messages.filter')}}</button>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                <div id="success"></div>
                <div id="filter" style="display: none;" class="col-sm-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 ">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="card-title">{{__('messages.categories-&amp;-fliters')}}</div>
                                </div>
                                @if (Session::has('danger'))
                                    <div class="alert alert-light-danger" role="alert">
                                        <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                        <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                                    </div>
                                @endif
                                <form action="{{route('seller.company.catalog.filter')}}" method="post">
                                    @csrf
                                    <div class="card-body">
                                        <ul id="tree1">
                                            @foreach($categories as $category)
                                                <li>
                                                    <input type="checkbox" name="category[]" value="{{$category->id}}" {{ (is_array(old('category')) && in_array($category->id, old('category'))) ? ' checked' : '' }}>
                                                    <a href="javascript:void(0);">{{\App\MyClasses\Helpers::get_lang($category->category_name,$category->id,"category",App::getLocale())}}</a>
                                                    @if ($category->children->isNotEmpty())
                                                        <ul>
                                                            {{$chaild->format($category->id)}}
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="form-group">
                                            <label class="font-weight-bold mt-2">{{__('messages.business-model')}}</label>
                                            <select name="business_model[]" class="form-control select2 select2-hidden-accessible @if($errors->has('business_model'))is-invalid @endif" data-placeholder=" Choose Model" multiple="" tabindex="-1" aria-hidden="true">
                                                @foreach($models as $model)
                                                    <option value="{{$model->name}}" {{ (is_array(old('business_model')) && in_array($model->name, old('business_model'))) ? ' selected' : '' }}>{{\App\MyClasses\Helpers::get_lang($model->name,$model->id,"business_model",App::getLocale())}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <input type="submit" class="btn btn-primary" value="{{__('messages.apply-filter')}}">
                                        <a href="{{route('seller.company.catalog')}}" class="btn btn-gray">{{__('messages.clear-filter')}}</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        @if($results->IsNotEmpty())
                            @foreach($results as $result)
                                <div class="col-sm-3 alert">
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $data = $result->created_at->diffInDays($now);;
                                        $class = null;
                                        $new = false;
                                    if ($data <= 30)
                                    {
                                        $class = "offer offer-radius offer-primary";
                                        $new = true;
                                    }
                                    else{
                                        $class = "card item-card";
                                    }
                                    @endphp
                                    <div class="{{$class}}">
                                        @if($new == true)
                                                <div class="shape">
                                                    <div class="shape-text">
                                                        {{__('messages.new')}}
                                                    </div>
                                                </div>
                                        @endif
                                        <div class="card-body pb-0 offer-content">
                                            <div class="text-center zoom">
                                                <img src="{{asset('uploads/featured_images/'.$result->get_products[0]->featured_image)}}" class="img-fluid w-100 br-7">
                                            </div>
                                            <div class="card-body px-0 pb-3">
                                                <div class="row">
                                                    @php
                                                        $data = \App\MyClasses\Helpers::get_lang($result->get_products[0]->product_name,$result->get_products[0]->id,"product",App::getLocale());
                                                        $product_data = json_decode($data);
                                                        $catagory_data = \App\MyClasses\Helpers::get_lang_cat($result->get_products[0]->category,"category",App::getLocale());
                                                        $total_seller = \App\Models\Drop_shipping::where('product_id',$result->get_products[0]->id)->get();
                                                        $total_order = 0;
                                                        foreach ($orders as $order)
                                                        {
                                                            $j_products = json_decode($order->product);
                                                            foreach ($j_products as $j_product)
                                                            {
                                                                if ($j_product->p_id == $result->get_products[0]->id)
                                                                {
                                                                    $total_order = $j_product->available_qty;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <div>
                                                        <h3>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$result->get_products[0]->product_name}}@endif</h3>
                                                        <h6>{{__('messages.category')}}: @if(App::getLocale() == "en"){{$result->get_products[0]->category}} @elseif($catagory_data) {{$catagory_data}} @else {{$result->get_products[0]->category}} @endif</h6>
                                                        <table class="table-striped table-hover" style="font-size: 10px;" width="100%">
                                                            @php
                                                                $rate = $result->selling_price;
                                                                $dis = $result->discount;
                                                                $net = $rate-$dis;
                                                            @endphp
                                                            <tr>
                                                                <th>{{__('messages.stock')}}</th>
                                                                <td>{{stock($result->product_id)}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{__('messages.company-rate')}}</th>
                                                                <td>{{$rate}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{__('messages.discount')}}</th>
                                                                <td>@if(empty($dis)) 0 @else {{$dis}} @endif</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{__('messages.net-rate')}}</th>
                                                                <td>{{$net}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{__('messages.retail-price')}}</th>
                                                                <td>{{$result->retail_price}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{__('messages.suggested-price')}}</th>
                                                                <td>{{$result->suggested_price}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{__('messages.total-seller')}}</th>
                                                                <td>{{count($total_seller)}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{__('messages.total-orders')}}</th>
                                                                <td>{{$total_order}}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center pb-4 ps-2 pe-2">
                                            <a href="{{route('seller.company.catalog.view',$result->get_products[0]->id)}}" class="btn bg-primary-transparent text-primary mb-2 border-primary"><i class="fe fe-eye me-1 font-weight-bold"></i>{{__('messages.view')}}</a>
                                            <?php
                                            $data = \App\Models\Drop_shipping::where('user_id',Auth::user()->id)->where('product_id',$result->get_products[0]->id)->first();
                                            if ($data)
                                            {
                                                echo '<span class="text-success">'.__('messages.already-added').'</span>';
                                            }
                                            else{
                                                if ($plan_subscriber)
                                                {
                                                    $plan = \App\Models\Plan::find($plan_subscriber->plan_id);
                                                    if ($plan->listing_product > count($drop_shipping))
                                                    {
                                            ?>
                                            <a href="javascript:void(0);" id="{{$result->get_products[0]->id}}" class="btn btn-primary mb-2 click"><i class="fe fe-shopping-cart me-2"></i>{{__('messages.add-to-my-list')}}</a>
                                            <?php
                                                    }
                                                    else{
                                                        echo '<span class="text-success">'.__('messages.limit-full').'</span>';
                                                    }
                                                }
                                                else{
                                                    ?>
                                            <a href="javascript:void(0);" id="{{$result->get_products[0]->id}}" class="btn btn-primary mb-2 click_without_subscribe"><i class="fe fe-shopping-cart me-2"></i>{{__('messages.add-to-my-list')}}</a>
                                            <?php
                                                }
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h3 class="text-muted pt-3">{{__('messages.no-products-found')}}</h3>
                        @endif
                    </div>
                    @if($filter == 0)
                        <div class="d-flex justify-content-end">
                            {{$results->links('pagination')}}
                        </div>
                    @endif
                </div>
            </div>
            <!--/div-->


        </div>
    </div>
    <!-- CONTAINER END -->
@endsection
@section('query')
    <script type="text/javascript">
        function show_hide()
        {
            var x = document.getElementById("filter");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        $(document).ready(function () {
            $(document).on('click','.click_without_subscribe',function () {
                alert("Please! Subscribe the Plan.");
            });
            $(document).on('click','.click',function () {

                var id = $(this).attr("id");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('seller.company.catalog.add_list')}}",
                    type:"POST",
                    dataType: "json",
                    data: {id:id},
                    success: function(response){
                        console.log(response.status);
                        if (response.status == 200)
                        {
                            var newmsg = document.createElement("span");
                            newmsg.className = "text-success";
                            newmsg.innerHTML = "{{__('messages.added-in-list')}}";
                            document.getElementById(id).replaceWith(newmsg);
                        }
                        else if(response.status == 402)
                        {
                            Swal.fire({
                                title: "Message!",
                                text: "Please select the product listing language from setting.",
                                icon: 'info',
                                showConfirmButton: false,
                                timer: 2300
                            })
                        }
                        else if (response.status == 'error') {
                            Swal.fire({
                            title: '<strong>Dismatch Of Admin Cities</strong>',
                            icon: 'info',
                            html:
                            'Please visit the, ' +
                            '<a href="' + response.blade_link + '"><span style="font-weight:bold">City Management/City Mapping</span></a> ' +
                            'page to associate the cities with the Admin Cities .',
                        })
                        }
                    }
                })
            });
        });
    </script>
@endsection
