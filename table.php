<section class="institute_list">
    <table class="table table-bordered" border="1">
        <thead>
            <tr>
                <th scope="col">EIIN</th>
                <th scope="col">Institute</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>000000</td>
                <td>Name</td>
            </tr>
            <tr>
                <td>111111</td>
                <td>Name</td>
            </tr>
            <tr>
                <td>222222</td>
                <td>Name</td>
            </tr>
            <tr>
                <td>333333</td>
                <td>Name</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>444444</td>
                <td>Name 2</td>
            </tr>
            <tr>
                <td>555555</td>
                <td>Name 2</td>
            </tr>
            <tr>
                <td>666666</td>
                <td>Name 2</td>
            </tr>
            <tr>
                <td>666666</td>
                <td>Name 2</td>
            </tr>
        </tbody>
    </table>
</section>



<script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>

<script type="text/javascript">
    var target = $(".institute_list > table");

    var nobject = [];
    if( $(target).length != 0 ) {
        $(target).find("tbody").each(function(i, tbody){ // each diye koyta tobdy ache check dichen? check na... actually every<tbody> tag k separate kore array loop a nia ashe eta mainly html tag k loop kore... Loop ==array same
            // i, tsbody ei 2 ta ki pass kore? i means index/key... & tsbody means value function(key, value) means array[key] = value
            // separate <tbody> tag as a loop
            var ni = (i+1); // make custom key (increase +1)
            if( $(tbody).length ) {
                // check if <tbody> if not empty

                nobject[ni] = []; // now add array into the object

                $(tbody).find("tr").each(function(j, tr){
                    var nj = (j + 1); // new tr key... use can use custom 

                    nobject[ni][nj] = [];

                    if( $(tr).length ) {

                        $(tr).find("td").each(function(k, td){ 

                            var nk = (k + 1);
                            if ( k == 0 ) {
                                nk = "first";
                            } else if ( k == 1 ) {
                                nk = "second";
                            } else if ( k == 2 ) {
                                nk = "third";
                            } else if ( k == 3 ) {
                                nk = "fourth";
                            } else if ( k == 4 ) {
                                nk = "fifth";
                            } else if ( k == 5 ) {
                                nk = "sixth";
                            }

                            var nk = (k + 1); //<td> key. Use can use custom as you want
                            nobject[ni][nj][nk] = $(td).text();

                        });

                    }
                });
            }
        });
    }
    console.log(nobject);


    /*$(target).find("tr").each(function(index, tr) {
            target[index] = $("td", tr).map(function(index, td) {return $(td).text();});
        });*/
</script>




<!-- <script>
    var rows = '', the_row='', the_xrow={}, get_row_values={}, xtd_obj={};
    tbodys = ($(".institute_list .table tbody").length);

    for( var x=0; tbodys > x; x++)  {
        rows = $('.institute_list .table tbody:nth-child('+(x+2)+') tr').length;
        the_row = '.institute_list .table tbody:nth-child('+(x+2)+') tr:nth-child(';
       
        for( var i=1; rows >= i; i++ ){
            tr_values = {
                'eiin'          : $(the_row+i+') td:first-child').text(),
                'name'          : $(the_row+i+') td:nth-child(2)').text()
            };

            the_xrow[i] = tr_values;
        }  
        xtd_obj[x+1] = the_xrow;
    }
    console.log(xtd_obj);
</script> -->


<!-- 
<script>
    var rows = '', the_row='', xtd_obj={};
    var tbodys = ($(".institute_list .table tbody").length)+1;

    for( var x=1; tbodys > x; x++)  {
        rows = $('.institute_list .table tbody:nth-child('+(x+1)+') tr').length;
        the_row = '.institute_list .table tbody:nth-child('+(x+1)+') tr:nth-child(';
        var the_xrow = {};
        for( var i=0; rows > i; i++ ){
            var tr_values = {
                'eiin'   : $(the_row+i+1+') td:first-child').text(),
                'name'   : $(the_row+i+1+') td:nth-child(2)').text()
            };

            the_xrow[i] = tr_values;
        }  
        xtd_obj[x] = the_xrow;
    }
    console.log(xtd_obj);
</script> -->