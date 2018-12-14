<h3>New signal received!</h3>
@foreach($signal->toArray() as $attr => $value)
    <p>
        <strong>
            {{$attr}}
        </strong>
        <span>
            {{$value}}
        </span>
    </p>
@endforeach