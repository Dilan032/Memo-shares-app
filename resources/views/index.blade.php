<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>All Post</title>

  <!-- bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>



  <div class="container p-4 ">
    <div class="text-center">
      <h1 class="">All Post</h1>
    </div>
    <a href="/create" class="btn btn-md btn-primary fs-2">Add new Post</a>
    <hr>

      {{-- show all post --}}
      @foreach ($posts as $post)
        <div class="border border-success px-3 mb-5 pb-5 pt-3">

          <div class="d-flex justify-content-end mb-2">
            <a href="edit/{{ $post->id }}" class="btn btn-success fs-4 me-2 px-4">Edit</a>
            <a href="delete/{{ $post->id }}" class="btn btn-danger fs-4">Delete</a> 
          </div>

          <div class="p-3 mb-2 bg-primary text-white fs-2">{{ $post->title }} </div>
          
          <p>{!! $post->description !!}</p>

        </div>
      @endforeach

    </div>
  
</body>
</html>