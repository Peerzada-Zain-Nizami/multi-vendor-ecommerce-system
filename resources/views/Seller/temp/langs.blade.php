<tr>
    <td>
        <div class="w-100" style="overflow: auto; height: 50px;">
            @if(count($langs) > 0)
                <table class="table-striped table-hover table-sm">
                    @foreach($langs as $lang)
                        <tr>
                            <td><img src="{{asset ('assets/images/flags/'.Config::get('languages')[$lang->language]['flag-icon'].'.svg')}}" width="30px" height="20px" class="me-2">{{Config::get('languages')[$lang->language]['display']}}</td>
                            <td>
                                <button id="{{$lang->id}}" class="btn text-warning btn-sm update_lang_model"><i class="fa fa-edit"></i></button>
                                <a href="{{route('seller.category.lang.del',$lang->id)}}" class="btn text-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <p class="text-danger">{{__('messages.language-not-set')}}</p>
            @endif

        </div>
    </td>
</tr>