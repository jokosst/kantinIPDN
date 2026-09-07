@foreach($data as $d)
<tr @if((int)$d->stok <= 0) style="background-color:#f2dede;" @endif>
 <td>[{{$d->kode}}] {{$d->nama}}</td>
  <td>Rp. {{number_format((float)$d->harga_reseller)}}</td>
  <td>{{$d->nama_katagori}}</td>
  <td>
    @if((int)$d->stok == 0)
      <span class="label label-danger">{{$d->stok}}</span>
    @else
      <span class="label label-default">{{$d->stok}}</span>
    @endif
  </td>
<td>
  <a href="{{url('cari_item_member/tambah/'.$id_meja.'/'.$d->id_item)}}" class="btn btn-block btn-default btn-flat ">Pilih</a>
</td>                         
</tr>
@endforeach