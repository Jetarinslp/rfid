<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="search-box">
            <input type="text" id="txt_search" placeholder="search" style="margin-right: 10px;">
            <select id="sel_search">
                <option value="stdNo">Student</option>
                <option value="tagNo">Tag</option>
            </select>
            <div onclick="search()" class="btn-search">Search</div>
            <!-- <button type="submit" onclick="search()" class="btn btn-primary">Search</button> -->
        </div>
    </div>
    <table class="table table-bordered ">
        <thead>
            <tr>
                <th>id</th>
                <th>tag</th>
                <th>student</th>
                <th>date_borrow</th>
                <th>date_return</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody id="tbody">
            
        </tbody>
    </table>

    

    <script>
        function search() {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "search_api.php",
                data: {
                    stdNo: $('#txt_search').val(),
                    tagNo: $("#sel_search").val()
                }, success: function(response) {
                    console.log("good", response)
                    var html = '';
                    for(var i=0; i<response.result.length; i++) {
                        html += `
                        <tr>
                            <td>${response.result[i].id}</td>
                            <td>${response.result[i].tag_id}</td>
                            <td>${response.result[i].id_student}</td>
                            <td>${response.result[i].date_borrow}</td>
                            <td>${response.result[i].date_return}</td>
                            <td>${response.result[i].status}</td>
                        </tr>
                        `;
                    }
                    $("#tbody").html(html)
                }, erro: function(err) {
                    console.log("bad", err)
                }
            })
        }
    </script>
</body>

</html>