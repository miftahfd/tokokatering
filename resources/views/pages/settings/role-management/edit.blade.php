@extends('layouts.app')

@section('content')
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Role</h2>
        <div class="card w-full bg-base-100 shadow-xl">
            <div class="card-body">
                <form action="" method="post">
                    @csrf
                    @method('put')
                    <div class="grid gap-6 mb-8 grid-cols-1 md:grid-cols-2">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Role Name</span>
                            </label>
                            <input type="text" name="name" id="name" class="input input-bordered input-sm read-only:bg-red-50 read-only:cursor-not-allowed focus:outline-none w-full" value="{{$role->name}}" required>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Permission</span>
                            </label>
                            <select name="permissions[]" id="permissions" class="w-full select2" multiple required>
                                @foreach($permissions as $permission)
                                    <option value="{{$permission->id}}" 
                                    {{in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'selected' : ''}}>{{$permission->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-6 grid-cols-2 float-right w-full md:w-1/2">
                        <a href="{{route('role.index')}}" class="btn btn-sm capitalize">Kembali</a>
                        <button type="submit" class="btn btn-sm text-white capitalize bg-gray-800 hover:bg-gray-950">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script type="module">
    $(function() {
        $('.select2').select2()
    })
</script>
@endpush