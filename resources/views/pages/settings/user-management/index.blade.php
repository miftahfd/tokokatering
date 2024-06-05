@extends('layouts.app')

@section('content')
  <div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">User Management</h2>
    <div class="card mt-3 overflow-auto w-full bg-base-100 shadow-xl">
      <div class="card-body">
        <table id="datatable-user" class="w-full py-4 rounded-lg stripe hover">
          <thead>
            <tr
              class="text-xs font-semibold tracking-wide text-left text-white uppercase dark:border-gray-700 bg-red-600 dark:text-gray-400 dark:bg-gray-800"
            >
              <th class="px-4 py-3 rounded-tl-lg">#</th>
              <th class="px-4 py-3">Username</th>
              <th class="px-4 py-3">Name</th>
              <th class="px-4 py-3">Role</th>
              <th class="px-4 py-3 rounded-tr-lg"></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y text-gray-700 dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800">
            @php $no = 1 @endphp
            @foreach($users as $user)
              <tr>
                <td class="px-4 py-3 text-sm">{{$no}}</td>
                <td class="px-4 py-3 text-sm">{{$user->username}}</td>
                <td class="px-4 py-3 text-sm">{{$user->name}}</td>
                <td class="px-4 py-3 text-sm">{{implode(', ', $user->roles->pluck('name')->toArray())}}</td>
                <td class="px-4 py-3 text-center text-sm">
                  <select class="datatable-select-action rounded-lg text-xs">
                    <option value="" selected disabled>Pilih Aksi</option>
                    <option value="Edit" data-url="{{route('user.edit', $user)}}">Edit</option>
                  </select>
                </td>
              </tr>
              @php $no++ @endphp
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('js')
<script type="module">
  $(function() {
    $('#datatable-user').dataTable({
      ordering: false,
      lengthChange: false,
      language: {
        paginate: {
          next: ">>",
          previous: "<<"
        }
      }
    })

    $('#datatable-user').find('.datatable-select-action').change(function() {
      let value = $(this).val()
      let selected_option = $(this).find(':selected')

      if(value == 'Edit') {
        let url = selected_option.data('url')
        $(this).val('')
        location.href = url
      }
    })
  })
</script>
@endpush