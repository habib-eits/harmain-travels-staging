@extends('tmp')

@section('title', 'Invoice')
 

@section('content') 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>

 

 

 
 
 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<br>
  <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

<select id="select2" style="width: 70%">
        <option value="">Select</option>
        <option value="40">Fairy Floss Machine</option>
        <option value="30">Popcorn machine</option>
        <option value="20">Bubble Machine</option>
        <option value="10">Smoke Machine</option>
        <option value="0">party Effect Light</option>
</select>
 
</div>
</div>
</div>



<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script  >

$(document).ready(function() {

$('#select2').select2({
       allowClear: true,
       placeholder: 'This is my placeholder',    
         language: {
             noResults: function() {
              console.log('no record ounf');
            return `<button style="width: 100%" type="button"
            class="btn btn-primary" 
            onClick='task()'>+ Add New Item</button>
            </li>`;
            }
         },
       
        escapeMarkup: function (markup) {
            return markup;
        }
    });
    });



    
  function task()
  {
  alert("Hello world! ");
  }
    


  </script>

  
   @endsection