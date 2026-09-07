@extends('layouts.index')
@section('title', '- Generate Kode Retur')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Generate Kode Retur</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('master/item') }}"><i class="fa fa-dashboard"></i> Master</a></li>
            <li class="active">Generate Kode Retur</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ url('master/generateKodeRetur') }}" style="margin-left:8px;margin-bottom: 10px;">
                    @csrf
                    <input type="hidden" name="masa_berlaku" value="5">
                    <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-key"></i> Generate Kode Retur
                    </button>
                    </form>
            </div>

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-body table-responsive">
                        <table id="datatable1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Tgl dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td><strong>{{ $item->code }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-fill btn-sm" onclick="copyKode('{{ $item->code }}')">
                                            Copy Kode Retur
                                        </button>
                                        <a href="{{ url('master/generate_kode/hapus/'.$item->id) }}" class="btn btn-danger btn-fill btn-sm">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script>
async function copyKode(kode) {
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(kode);
        } else {
            var textArea = document.createElement('textarea');
            textArea.value = kode;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            var copied = document.execCommand('copy');
            document.body.removeChild(textArea);

            if (!copied) {
                throw new Error('Copy command gagal');
            }
        }

        Swal.fire({
            title: 'Berhasil!',
            text: 'Kode retur berhasil dicopy ke clipboard',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    } catch (err) {
        Swal.fire({
            title: 'Gagal!',
            text: 'Browser menolak akses clipboard. Coba HTTPS atau copy manual.',
            icon: 'error'
        });
    }
}

@if(Session::has('success'))
Swal.fire({title: 'Sukses!', text: '{{ Session::get('success') }}', icon: 'success'});
@endif

@if(Session::has('error'))
Swal.fire({title: 'Gagal!', text: '{{ Session::get('error') }}', icon: 'warning'});
@endif
</script>
@endsection
