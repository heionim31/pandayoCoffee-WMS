<?php 
    $month = isset($_GET['month']) ? $_GET['month'] : date("Y-m");
?>


<div class="container mt-4 ml-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
            <div class="card rounded-0 mb-2 shadow">
                <div class="card-header bg-gradient-dark text-white py-3">
                    <h2 class="card-title mb-0">Monthly Stock-In Reports</h2>
                </div>
                <div class="card-body">
                    <form action="" id="filter-form">
                        <fieldset>
                            <legend>Filter</legend>
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <label for="month" class="form-label">Choose Month</label>
                                <input type="month" class="form-control form-control-sm rounded-0" name="month" id="month" value="<?= $month ?>" required>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <button class="btn btn-sm btn-primary bg-gradient-primary" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
            <div class="card rounded-0 mb-2 shadow">
                <div class="card-header bg-white py-2">
                    <div class="card-tools">
                        <button class="btn btn-sm btn-light bg-gradient-light border text-dark" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid" id="printout">
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="5%">
                                <col width="20%">
                                <col width="5%">
                                <col width="15%">
                                <col width="15%">
                                <col width="40%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="px-1 py-1 text-center">#</th>
                                    <th class="px-1 py-1 text-center">Item</th>
                                    <th class="px-1 py-1 text-center">Quantity</th>
                                    <th class="px-1 py-1 text-center">Date of Receipt</th>
                                    <th class="px-1 py-1 text-center">Expiration Date</th>
                                    <th class="px-1 py-1 text-center">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $g_total = 0;
                                $i = 1;
                                $stock = pg_query($conn, "SELECT s.*, i.name as item, c.name as category, i.unit, s.date, s.expire_date 
                                                        FROM wh_stockin_list s 
                                                        INNER JOIN wh_item_list i ON s.item_id = i.id 
                                                        INNER JOIN wh_category_list c ON i.category_id = c.id 
                                                        WHERE to_char(s.date_created, 'YYYY-MM') = '{$month}'
                                                        UNION ALL
                                                        SELECT s.*, i.name as item, c.name as category, i.unit, s.date, s.expire_date 
                                                        FROM wh_stockin_list_deleted s 
                                                        INNER JOIN wh_item_list i ON s.item_id = i.id 
                                                        INNER JOIN wh_category_list c ON i.category_id = c.id 
                                                        WHERE to_char(s.date_created, 'YYYY-MM') = '{$month}'
                                                        ORDER BY date_created DESC");

                                while($row = pg_fetch_assoc($stock)):
                            ?>

                                <tr>
                                    <td class="px-1 py-1 align-middle text-center"><?= $i++ ?></td>
                                    <td class="px-1 py-1 align-middle text-center">
                                        <div line-height="1em">
                                            <div class="font-weight-bold"><?= $row['item'] ?> [<?= $row['unit'] ?>]</div>
                                            <div class="font-weight-light"><?= $row['category'] ?></div>
                                        </div>
                                    </td>
                                    <td class="px-1 py-1 align-middle text-center"><?= format_num($row['quantity']) ?></td>
                                    <td class="px-1 py-1 align-middle text-center"><?= date("Y-m-d",strtotime($row['date'])) ?></td>
                                    <td class="px-1 py-1 align-middle text-center"><?= date("Y-m-d",strtotime($row['expire_date'])) ?></td>
                                    <td class="px-1 py-1 align-middle text-center"><?= $row['remarks'] ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if(pg_num_rows($stock)<= 0): ?>
                                    <tr>
                                        <td class="py-1 text-center" colspan="6">No records found</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<noscript id="print-header">
    <div>
        <style>
            html{
                min-height:unset !important;
            }
        </style>
        <div class="d-flex w-100 align-items-center">
            <div class="col-2 text-center">
                <img src="<?= validate_image($_settings->info('logo')) ?>" alt="" class="rounded-circle border" style="width: 5em;height: 5em;object-fit:cover;object-position:center center">
            </div>
            <div class="col-8">
                <div style="line-height:1em">
                    <div class="text-center font-weight-bold h5 mb-0"><large><?= $_settings->info('name') ?></large></div>
                    <div class="text-center font-weight-bold h5 mb-0"><large>Monthly Stock-In Report</large></div>
                    <div class="text-center font-weight-bold h5 mb-0">as of <?= date("F Y", strtotime($month."-01")) ?></div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</noscript>


<script>
    function print_r(){
        var h = $('head').clone()
        var el = $('#printout').clone()
        var ph = $($('noscript#print-header').html()).clone()
        h.find('title').text("Monthly Stock-In Report - Print View")
        var nw = window.open("", "_blank", "width="+($(window).width() * .8)+",left="+($(window).width() * .1)+",height="+($(window).height() * .8)+",top="+($(window).height() * .1))
            nw.document.querySelector('head').innerHTML = h.html()
            nw.document.querySelector('body').innerHTML = ph[0].outerHTML
            nw.document.querySelector('body').innerHTML += el[0].outerHTML
            nw.document.close()
            start_loader()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    nw.close()
                    end_loader()
                }, 200);
            }, 300);
    }
    
    $(function(){
        $('#filter-form').submit(function(e){
            e.preventDefault()
            location.href = './?page=reports/stockin&'+$(this).serialize()
        })
        $('#print').click(function(){
            print_r()
        })

    })
</script>