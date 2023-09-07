<html lang="en">
<head>
    <title>Bootstrap forms</title>
    <link href=
                  "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity=
                  "sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
          crossorigin="anonymous">
    <script src=
                    "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity=
                    "sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous">
    </script>
   <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
   <style>
       table {
          border-collapse: separate;
          border-spacing: 0 15px;
        }
        
        th {
          background-color: #4287f5;
          color: white;
        }
        
        th,
        td {
          width: 150px;
          text-align: center;
          border: 1px solid black;
          padding: 5px;
        }
        tr {
          background-color: #fff;
          color: black;
        }
        
        tr,
        td {
          width: 150px;
          text-align: center;
          border: 1px solid black;
          padding: 5px;
        }
        
        h2 {
          color: #4287f5;
        }
        .success-msg {
            background: #6fd36f;
            padding: 5px 10px;
            color: #ffF;
        }
        
        .error-msg {
            background: #6fd36f;
            padding: 5px 10px;
            color: #d5434a;
        }
        
        .btn-primary {
            margin-bottom: 25px;
        }
   </style>
</head>
<body>
<div class="container">
    <div class="product-form">
        <h4>Form for adding product, stock, price.</h4>
    <form action="action.php" method="post" id="pro-form">
        <!--Password input-->
        <div class="mb-3">
            <label for="product"
                   class="form-label">
                Product:
            </label>
            <input type="text"
                   class="form-control"
                   id="product"
                   placeholder="Product Name" required>
        </div>
        <!--stock-->
        <div class="mb-3">
            <label for="stock"
                   class="form-label">
                Stock:
            </label>
            <input type="number"
                   class="form-control"
                   id="stock"
                   placeholder="Product Stock" min="0" required>
        </div>
        <!--Qunatity-->
        <div class="mb-3">
            <label for="price"
                   class="form-label">
                Price:
            </label>
            <input type="number"
                   class="form-control"
                   id="price"
                   placeholder="Product Price" min="0" required step="0.01">
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="btn btn-primary">
            Submit
        </button>
        <div class="msg" id="msg"></div>
    </form>
    </div>
    <div class="product-list" id="product-list">
        <h2>Product List</h2>
        <table class="list-item" id="list-item">
           <thead>
            <tr>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Date</th>
              <th>Total Value</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody id="product-table-body">
           
          </tbody>
          <tfoot>
          </tfoot>
        </table>
    </div>
</div>
</body>

</html>

<script type="text/javascript">
    $(document).ready(function () {
        
        /*Edit record*/
        $(document.body).on('click', '.view-product' ,function(){
            var productName = $(this).attr("data-product");
            var proPrice = $(this).attr("data-price");
            var proStock = $(this).attr("data-stock");
            $("#product").val(productName);
            $("#price").val(proPrice);
            $("#stock").val(proStock);
        });
        
        /*Update List*/
        function updateList() {
            $.ajax({ url: "list.php",
                context: document.body,
                success: function(data){
                        var arrayData = $.parseJSON(data);
                        console.log(arrayData);
                        $('#product-table-body').empty();
                        var totalAmount = 0;
                       $.each(arrayData, function( index, value) {
                           var productName = value.name; 
                           var rowTotal = parseFloat(value.stock * value.price).toFixed(2);
                           totalAmount = totalAmount+(value.stock * value.price);
                           console.log("Total Amount "+ totalAmount);
                           var link = "<button id="+value.product+" data-product="+value.product+" data-price="+value.price+" data-stock="+value.stock+" class='view-product'>Edit Record</button>";
                            var tr = $('<tr>').append(
                                 $('<td>').text(value.product),
                                 $('<td>').text(value.stock),
                                 $('<td>').text(value.price),
                                 $('<td>').text(value.date),
                                 $('<td>').text(rowTotal),
                                 $('<td>').html(link)
                            );
                            $('#product-table-body').append(tr);
                        });
                        console.log("Total Amount Last"+ totalAmount);
                        if(totalAmount > 0){
                            var tr = $('<tr>').append(
                                 $('<td colspan="3">').text('Total Amount'),
                                 $('<td colspan="3">').text(totalAmount.toFixed(2))
                            );
                            $('#product-table-body').append(tr);
                        }else {
                            var tr = $('<tr>').append(
                                 $('<td colspan="6">').text('Any record is not existing.')
                            );
                             $('#product-table-body').append(tr);
                        }
                    },
                    error: function(xhr, status, error){
                        console.error(xhr);
                    }
            });
        }
        
        updateList();
        
        /*Submit Form*/
        $("form").submit(function (event) {
            event.preventDefault();
            var formData = {
                product: $("#product").val(),
                stock: $("#stock").val(),
                price: $("#price").val(),
            };
            $.ajax({
                type: "POST",
                url: 'action.php',
                data: formData,
                success: function(data){
                    $('form').trigger("reset");
                    $("#msg").empty();
                    $("#msg").html("<span class='success-msg'>Record Added Successfully</span>");
                    updateList();
                    setTimeout(function () {
                        $("#msg").fadeOut();
                 }, 2500);
                },
                error: function(xhr, status, error){
                    $('form').trigger("reset");
                    console.error(xhr);
                    $("#msg").empty();
                    $("#msg").html("<span class='error-msg'>Something went wrong.</span>");
                    setTimeout(function () {
                        $("#msg").fadeOut();
                 }, 2500);
                }
            });
        });
    });
</script>




