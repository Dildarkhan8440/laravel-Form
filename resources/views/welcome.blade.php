  <!DOCTYPE html>
  <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <title>Laravel</title>
     <meta name="_token" content="{{ csrf_token() }}">

     <!-- Fonts -->
     <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <style>
            .err-msg{
           color: red;
       }
       body {
           font-family: 'Nunito', sans-serif;
       }
   </style>
</head>
<body class="antialiased">
 <div class="container col-md-12 border mt-2 pt-3" style="padding: 2%;">
    <div class="text-center">Add User</div>
    <form id="frmAppl" class="frmAppl">
       <div class="row">
          <div class="col-md-4">
             <div class="mb-2">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp">
            </div>
        </div>
        <div class="col-md-4">
         <div class="mb-2">
            <label for="email" class="form-label">Email </label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
        </div>
    </div>
    <div class="col-md-4">
     <div class="mb-2">
        <label for="phone" class="form-label">Phone</label>
        <input type="Number" class="form-control" id="phone" name="phone">
    </div>
</div>
<div class="col-md-4">
 <div class="mb-2">
    <label for="role" class="form-label">Role</label>
    <select class="form-control" id="role" name="role" aria-label="Default select example">
       <option style="display:none;" value=" ">Select Role</option>
       @foreach($roles as $role)
       <option  value="{{$role->id}}">{{$role->role}}</option>
       @endforeach

   </select>
</div>
</div>
<div class="col-md-4">
 <div class="mb-2">
    <label for="formFile" class="form-label">Select Profile Image</label>
    <input class="form-control" type="file" id="image" name="image">
</div>
</div>
<div class="col-md-4">
 <div class="mb-2">
    <label for="exampleInputPassword1" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description"></textarea> 
</div>
</div>
<div class="col-md-2">
 <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
</div>
<div id="msg" class="col-md-10"></div>
</div>
</form>
</div>
<div class="pt-3 container col-md-12 border mt-2">
    <div class="text-center">User List</div>
    <table class="table table-striped table-bordered ">
       <thead>
          <tr>
             <th scope="col">#</th>
             <th scope="col">Name</th>
             <th scope="col">Email</th>
             <th scope="col">Phone</th>
             <th scope="col">Role</th>
             <th scope="col">Image</th>
             <th scope="col">Description</th>
         </tr>
     </thead>
     <tbody id="tbody">

     </tbody>
 </table>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script> 
    $( document ).ready(function() { 
        var APP_URL = {!! json_encode(url('/')) !!}
        function addTable(){
          $.ajax({
             url: "{{ url('api/user') }}",
             type: "GET",
             dataType: 'json',
             contentType: false,
             processData: false,
             cache: false,
             success: function(data){
                var i=1;
                $("#tbody").empty();
                $.each(data.user,function(index,item){
                   var el ='<tr><td>'+i+'</td><td>'+item['name']+'</td><td>'+item['email']+'</td><td>'+item['phone']+'</td><td>'+item['role'].role+'</td><td style="width: 6%">'+'<img src="'+ APP_URL+'/images/'+item['image']+'" class="img-fluid" alt="..."></td><td>'+item['description']+'</td></tr>';
                   $('#tbody').append(el);
                   i++;
               });
            }
        }); 
      } 
      addTable();

        $("#frmAppl").on("submit", function(event) {
            event.preventDefault();
            var error_ele = document.getElementsByClassName('err-msg');
            if (error_ele.length > 0) {
                for (var i=error_ele.length-1;i>=0;i--){
                    error_ele[i].remove();
                }
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
             url: "{{ url('api/user') }}",
             type: "POST",
             data: new FormData(this),
             dataType: 'json',
             contentType: false,
             processData: false,
             cache: false,
             beforeSend: function() {
                $("#submitBtn").prop('disabled', true);
            },
            success: function(data) {
                if (data.success) {

                } 
                else {
                   $.each(data.error, function(key, value) {
                      var el = $(document).find('[name="'+key + '"]');
                      el.after($('<span class= "err-msg">' + value[0] + '</span>'));
                  });
               }
               $("#submitBtn").prop('disabled', false);
            },
            complete: function (data) {
                if(data.responseJSON.st=='success'){
                   $("#frmAppl")[0].reset();
                   $("#msg").fadeIn("slow").fadeOut(5000).html('<div class="alert alert-success" role="alert">User Added Successfully</div>');
                   addTable();
               }
            },
            error: function (err) {
                $("#message").html("Some Error Occurred!")
            }
        });

    });
});
</script>
</body>
</html>
