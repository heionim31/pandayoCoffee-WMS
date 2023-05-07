<?php 
    $month = isset($_GET['month']) ? $_GET['month'] : date("Y-m");
?>

<style>
    .bg-gradient-coffee {
        background-image: linear-gradient(to right,  #8B4513 30%, #f7b360  100%);
    }
</style>

<div class="container mt-4 ml-5">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card rounded-5 mb-2 shadow">
                <div class="card-header bg-gradient-coffee text-white py-3">
                    <h2 class="card-title mb-0 font-weight-bold">MONTHLY STOCK IN REPORTS</h2>
                </div>
                <div class="card-body">
                    <form action="" id="filter-form">
                        <div class="row align-items-end">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <label for="month" class="form-label">Choose Month</label>
                                <input type="month" class="form-control" name="month" id="month" value="<?= $month ?>" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-3">
                                <button class="btn btn-primary bg-gradient-primary" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                <button class="btn btn-light bg-gradient-dark border text-white" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card rounded-0 mb-2 shadow">
                <div class="card-body">
                    <div class="container-fluid" id="printout">
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="1%">
                                <col width="10%">
                                <col width="20%">
                                <col width="5%">
                                <col width="14%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="20%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="px-1 py-1 align-middle text-center">#</th>
                                    <th class="px-1 py-1 align-middle text-center">Request ID</th>
                                    <th class="px-1 py-1 align-middle text-center">Ingredient</th>
                                    <th class="px-1 py-1 align-middle text-center">Quantity</th>
                                    <th class="px-1 py-1 align-middle text-center">MFG./EXP.</th>
                                    <th class="px-1 py-1 align-middle text-center">Supplier</th>
                                    <th class="px-1 py-1 align-middle text-center">Received</th>
                                    <th class="px-1 py-1 align-middle text-center">Request By</th>
                                    <th class="px-1 py-1 align-middle text-center">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $g_total = 0;
                                $i = 1;
                                $stock = pg_query($conn, "SELECT s.*, i.name as item, c.name as category, u.abbreviation as unit_name, s.date, s.expire_date, s.request_id, s.supplier, s.personnel, s.personnel_role, s.physical_count, s.physical_count_date, s.date_approved, s.date_received
                                    FROM wh_stockin_list s 
                                    INNER JOIN wh_item_list i ON s.item_id = i.id 
                                    INNER JOIN wh_category_list c ON i.category_id = c.id 
                                    INNER JOIN wh_unit_list u ON i.unit = u.id
                                    WHERE to_char(s.date_created, 'YYYY-MM') = '{$month}'
                                    ORDER BY date_created DESC");
                                
                                while($row = pg_fetch_assoc($stock)):
                            ?>
                                <tr>
                                    <td class="px-1 py-1 align-middle text-center"><?= $i++ ?></td>
                                    <td class="px-1 py-1 align-middle text-center"><?= $row['request_id'] ?></td>
                                    <td class="px-1 py-1 align-middle text-center">
                                        <div line-height="1em">
                                            <div class="font-weight-bold"><?= $row['item'] ?> [<?= $row['unit_name'] ?>]</div>
                                            <div class="font-weight-light"><?= $row['category'] ?></div>
                                        </div>
                                    </td>
                                    <td class="px-1 py-1 align-middle text-center"><?= format_num($row['quantity']) ?></td>
                                    <td class="px-1 py-1 align-middle text-center">
                                        <span class="font-weight-bold">MFG.:</span><span> <?= date("Y-m-d",strtotime($row['date'])) ?></span><br>
                                        <span class="font-weight-bold">EXP.:</span><span> <?= date("Y-m-d",strtotime($row['expire_date'])) ?></span>
                                    </td>
                                    <td class="px-1 py-1 align-middle text-center"><?= $row['supplier'] ?></td>
                                    <td class="px-1 py-1 align-middle text-center"><?= $row['date_received'] ?></td>
                                    <td class="px-1 py-1 align-middle text-center">
                                        <div line-height="1em">
                                            <div class="font-weight-bold"><?= $row['personnel'] ?></div>
                                            <?php if ($row['personnel_role'] === 'Warehouse_manager'): ?>
                                            <div class="font-weight-light text-mute">Manager</div>
                                            <?php elseif ($row['personnel_role'] === 'Warehouse_staff'): ?>
                                            <div class="font-weight-light text-mute">Staff</div>
                                            <?php else: ?>
                                            <div class="font-weight-light text-mute"><?= $row['personnel_role'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
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