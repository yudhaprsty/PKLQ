<?php session()->put('flag', 1); ?>
@extends('templates.admins.master')

@section('content')
  <div class="col-md-12 col-sm-12 col-xs-12">
    @if (session()->has('deleteNotif'))
      <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <strong> {{session()->get('deleteNotif')}} </strong>
      </div>
    @endif

    @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <strong> {{session()->get('success')}} </strong>
      </div>
    @endif

    <div class="x_panel">
      <div class="x_title">
        <h2>Daftar Anggota</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th style="width: 1%; text-align:center;">Nomor</th>
              <th style="text-align:center;"> Nama Anggota</th>
              <th style="text-align:center;">Email</th>
              <th style="text-align:center;">NIK</th>
              <th style="text-align:center;">Jabatan Anggota</th>
              <th style="width: 1%; text-align:center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
              <?php $no = 0;?>
                @foreach($readUser as $read)
              <?php $no++ ;?>
            <tr>
              <td style="text-align:center;">{{ $no }}</td>
              <td style="text-align:center;">{{ $read->name }}</td>
              <td style="text-align:center;">{{ $read->email }}</td>
              <td style="text-align:center;">{{ $read->identitas }}</td>
              <?php
                if($read->isAdmin == 0) {
                  $jabatan = 'Peneliti';
                }
                else if($read->isAdmin == 1) {
                  $jabatan = 'Admin';
                }
                else if($read->isAdmin ==2) {
                  $jabatan = 'Kepala Pusat';
                }
              ?>
              <td style="text-align:center;">{{ $jabatan }}</td>
              <td style="text-align:center;">
                <?php
                  if($read->id != Auth::id()) {
                ?>

                <form action="{{ route('lihatStaff.destroy', $read->id) }}" method="post" id="deleteButton">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button type="submit"  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>Delete</button>

                    <script>
                      document.getElementById('deleteButton').onclick = function(event){
                        event.preventDefault();
                      	swal({
                      		title: "Apakah anda yakin ingin menghapus?",
                      		text: "Anda tidak dapat mengembalikan kembali.",
                      		type: "warning",
                      		showCancelButton: true,
                      		confirmButtonColor: '#DD6B55',
                      		confirmButtonText: 'Ya',
                      		closeOnConfirm: false,
                      		//closeOnCancel: false
                      	},
                      	function(){
                          // swal("Terhapus", "Akun telah terhapus!", "Sukses");
                          document.getElementById("deleteButton").submit();
                      	});
                      };
                    </script>

                  </form>

                <?php
                  }
                ?>

              </td>
            </tr>

            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
