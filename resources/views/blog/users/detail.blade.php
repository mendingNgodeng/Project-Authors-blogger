@extends('layouts.template')

@section('title', 'Welcome')

@section('content')

<!-- card body -->

    <!-- card header -->

      <h4 class="text-3xl text-gray-700 mb-5 flex items-center">User Sneak peak </h4>
    <!-- body -->
   <!-- <div class="min-h-screen grid grid-cols-1 justify-center"> -->
    <div class="container-lg flex justify-center">

 
    <!-- Inner wrapper with min width to enable scrolling -->
      <!-- <div class="flex flex-col justify-center container"> -->
     <div class="rounded-lg border bg-white px-4 pt-8 pb-10 shadow-lg">
    <div class="mx-auto w-36 rounded-full">
      <!-- @if(isset(Auth::user()->_id))
      <span class="absolute right-0 m-3 h-3 w-3 rounded-full bg-green-500 ring-2 ring-green-300 ring-offset-2"></span>
      @endif -->
      @if($data->pic)
      <img class="mx-auto h-auto w-full rounded-full" src="{{ $data->pic }}" alt="" />
      @else
      <p class="mx-auto h-auto w-full rounded-full"><i class=" fas fa-user "></i></p>
      @endif
    </div>
    <h1 class="my-1 text-center text-xl font-bold leading-8 text-gray-900">{{ $data->name}}</h1>
    <h3 class="font-lg text-semibold text-center leading-6 text-gray-600">{{ $data->username}}</h3>
    <p class="text-center text-sm leading-6 text-gray-500 hover:text-gray-600">Capybara senggol</p>

    <ul class="mt-3 divide-y rounded bg-gray-100 py-2 px-3 text-gray-600 shadow-sm hover:text-gray-700 hover:shadow">
      <li class="flex items-center py-3 text-sm">
        <span>Status</span>
        <span class="ml-auto"><span class="rounded-full bg-green-200 py-1 px-2 text-xs font-medium text-green-700">Open for side gigs</span></span>
      </li>
      <li class="flex items-center py-3 gap-3 text-sm">
        <span>Bergabung pada</span>
        <span class="ml-auto">{{ $data->created_at}} </span>
      </li>

      <li class="flex items-center py-3  text-sm">
        <span>Role</span>
        <span class="ml-auto">{{ $data->role}} </span>
      </li>

      <li class="flex items-center py-3 text-sm">
        <span>E-mail</span>
        <span class="ml-auto">{{ $data->email}} </span>
      </li>
    </ul>
      <a href="{{ route('users.index') }}" class="w-full text-center px-4 py-3 bg-gray-500 rounded-md shadow-md text-white font-semibold"><i class="fa fa-arrow-left"></i> Back </a>
  </div>
  
</div>

<hr class="my-4">

<div class="container">
   <div class="w-2/4 rounded-2xl border-2 mx-auto my-5 border-gray-200 min-h-20 min-w-96 p-4 bg-gray-300">
        <h3 class="text-center text-2xl font-semibold mb-2">User's Recent post</h3>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Est voluptas amet voluptates itaque voluptatum dicta. Dolorem facere nesciunt voluptatem nostrum.

        </p>
        </div>

         
           <div class="w-4/6 my-5 mx-auto grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4">
          <div class="h-40 border-2 rounded-lg text-2xl border-gray-400  hover:shadow-lg hover:duration-500 active:bg-gray-300">
            <h3>Title</h3>
            <hr class="my-2">
            <div class="p">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure iusto libero atque, sed, illum autem dolorem vel exercitationem rem perspiciatis alias? Et, veritatis molestias explicabo blanditiis iure dignissimos ipsa aliquam?</div>
          </div>
         
           </div>
</div>


@endsection
