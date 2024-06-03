@extends('pages.layouts.app')
@section('other')
    active open
@endsection
@section('point')
    active
@endsection
@section('contenue')
    <div class="card">
        <h5 class="card-header">Point</h5>
        @if (Session::has('fail'))
            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="table-responsive text-nowrap">
            <form action="/importPoint" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="table" value="paiements">
                <p><input type="file" name="csv_file" id="data">CSV</p>
                <p><button type="submit" class="btn btn-dark">Importer</button></p>
                @if (Session::has('csvsuccess'))
                    <div class="alert alert-success">{{ Session::get('csvsuccess') }}</div>
                @endif
            </form>
        </div>
    </div>
@endsection
