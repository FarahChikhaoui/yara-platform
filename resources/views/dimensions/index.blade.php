<h1>Dimensions</h1>

<ul>
    @foreach($dimensions as $dimension)
        <li>{{ $dimension->name }}</li>
    @endforeach
</ul>