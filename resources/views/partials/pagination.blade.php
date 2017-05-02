<div class="body">
    <nav>
        <ul class="pagination">
            <li class="disabled">
                <a href="javascript:void(0);">
                    <i class="material-icons">chevron_left</i>
                </a>
            </li>
            @for($i = 1, $n = 0; $i <= ceil($count/$limit); $i++, $n+=10 )
                <li class="{{$i == 1 ? 'active' : 'waves-effect'}}"><a href="{{route('home', ['offset' => $n ])}}">{{$i}}</a></li>
            @endfor
            <li>
                <a href="javascript:void(0);" class="waves-effect">
                    <i class="material-icons">chevron_right</i>
                </a>
            </li>
        </ul>
    </nav>
</div>