<?php session()->put('flag', 2); ?>
@extends('templates.admins.master')

@section('stylesheets')
  <link href= "{{ asset('css/parsley.css') }}" rel="stylesheet">
  <link href= "{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
  <div class="x_panel">
    <div class="x_title">
      <h2>Tambah Anggota</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <br/>

      {{-- alert --}}
      @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <strong>Errors:</strong>
          <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
          </ul>
        </div>
      @endif

      @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <strong> {{session()->get('success')}} </strong>
        </div>
      @endif
      {{-- end alert --}}

      <form class="form-horizontal form-label-left" action="{{ route('tambahCabang.create') }}" method="post"  enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group">
          <label class="control-label col-md-2">Nama Cabang
            <span class="required">*</span>
          </label>
          <div class="col-md-9">
            <input type="text" class="form-control" required="required" name="nama_cabang" placeholder="Nama Cabang" class="form-control col-md-9 col-xs-12">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">IP Server
            <span class="required">*</span>
          </label>
          <div class="col-md-9">
            <input type="text" class="form-control" required="required" name="ip_server" placeholder="IP Server" class="form-control col-md-9 col-xs-12">
          </div>
        </div>


          <div class="form-group">
              <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
              <button type="submit" class="btn btn-success">Submit</button>
              </div>
          </div>

      </form>
    </div>
  </div>
@endsection

@section('script')
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <script>
    CKEDITOR.replace( 'konten' );
  </script>
	<script type="text/javascript">
		$('.select2-multi').select2();
	</script>
@endsection
