@extends('layouts.template')

@section('title', 'Welcome')

@section('content')

<!-- card body -->

    <!-- card header -->

    <!-- body -->
     <div class="border-3">
        <div class="col-span-1 lg:col-span-6">
  <h4 class="text-3xl text-gray-700 mb-5 flex items-center">Tambah Data Users</h4>
  <div class="p-10 rounded-md shadow-md bg-white">
    <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
      @csrf
   <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Name</label>
    <input type="text" name="name"class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-wider"/>
    @error('name')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
   </div>

   <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Username</label>
    <input
     type="text" name="username" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest"/>
       @error('username')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
   </div>


    <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">E-mail</label>
    <input
     type="text" name="email" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest"/>
     @error('email')
      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
     @enderror
   </div>

   <div class="mb-6 flex flex-wrap -mx-3w-full gap-3">
 <div class="">
    <label class="block mb-3 text-gray-600" for="">Pict Profil</label>
    <input
     type="file" name="pic"class="border border-gray-500 rounded-md sm:inline-block py-2 px-3 w-full text-gray-600 tracking-widest" autocomplete="off"/>
      @error('pic')
      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
     @enderror
   </div>

    <div class="mb-6">
    <label class="block mb-3 text-gray-600" for="">Password</label>
    <input
     type="text" name="password" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest" autocomplete="off"/>
       @error('password')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
   </div>
    <!-- <div class="w-2/3 px-3">
     <label class="block mb-3 text-gray-600" for="">Expiraion date</label>

     <div class="flex">
      <select class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest mr-6">
       <option>Month</option>
      </select>

      <select class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest">
       <option>Year</option>
      </select>

     </div> -->
    <!-- </div>
    <div class="w-1/3 px-3">
     <label class="block mb-3 text-gray-600" for="">CVC</label>
     <input type="tel" class="border border-gray-500 rounded-md inline-block py-2 px-3 w-full text-gray-600 tracking-widest"/>
    </div> -->
   </div>
   <div class="mb-6 text-right">
    <span class="text-right font-bold"></span>
   </div>
   <div class="flex gap-3">
    <button @click="finishPayment" class="w-full text-center px-4 py-3 bg-blue-500 rounded-md shadow-md text-white font-semibold">
     <i class="fa fa-paper-plane"></i> Save
    </button>

    <a href="{{ route('users.index') }}" class="w-full text-center px-4 py-3 bg-gray-500 rounded-md shadow-md text-white font-semibold"><i class="fa fa-arrow-left"></i> Back </a>

    </form>
   </div>
  </div>
 </div>
</div>


@endsection
