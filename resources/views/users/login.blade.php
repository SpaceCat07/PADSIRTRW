@extends('layouts.landingNavbar')

@section('content')
<h1>ini laman login</h1><br>
<a href="{{route('warga.masuk')}}">Warga</a><br>
<a href="{{route('rt.masuk')}}">RT</a><br>
<a href="{{route('rw.masuk')}}">RW</a>
@endsection