<?php
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT i.*, c.name as category, (COALESCE((SELECT SUM(quantity) FROM wh_stockin_list where item_id = i.id),0) - COALESCE((SELECT SUM(quantity) FROM wh_stockout_list where item_id = i.id),0)) as available from wh_item_list i inner join wh_category_list c on i.category_id = c.id where i.id = '{$_GET['id']}' and i.delete_flag = 0 ");
        if(pg_num_rows($qry) > 0){
            $result = pg_fetch_assoc($qry);
            extract($result);
        }else{
            echo '<script>alert("item ID is not valid."); location.replace("./?page=items")</script>';
        }
    }else{
        echo '<script>alert("item ID is Required."); location.replace("./?page=items")</script>';
    }
?>
     
<div class="row mt-3 justify-content-center">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        
    
        <!-- ITEM DETAILS -->
        <div class="card card-outline card-dark rounded-0 shadow printout" >
            <div class="card-header py-1">
                <div class="card-title"><b>Item Details</b></div>
                <div class="card-tools">
                    <button id="print" class="btn btn-success btn-flat bg-gradient-success btn-sm" type="button"><i class="fa fa-print"></i> Print</button>
                    <a href="./?page=items" class="btn btn-flat btn-success"><span class="fas fa-arrow-left"></span> return</a>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <fieldset>
                    <div class="d-flex w-100">
                            <div class="col-4 bg-gradient-orange text-bold text-white m-0 p-1 border">Item Name</div>
                            <div class="col-8 m-0 p-1 border"><?= isset($name) ? $name : '' ?></div>
                        </div>
                        <div class="d-flex w-100">
                            <div class="col-4 bg-gradient-orange text-bold text-white m-0 p-1 border">Category</div>
                            <div class="col-8 m-0 p-1 border"><?= isset($category) ? $category : '' ?></div>
                        </div>
                        <div class="d-flex w-100">
                            <div class="col-4 bg-gradient-orange text-bold text-white m-0 p-1 border">Unit</div>
                            <div class="col-8 m-0 p-1 border"><?= isset($unit) ? $unit : '' ?></div>
                        </div>
                        <div class="d-flex w-100">
                            <div class="col-4 bg-gradient-orange text-bold text-white m-0 p-1 border">Item Type</div>
                            <div class="col-8 m-0 p-1 border"><?= isset($item_type) ? $item_type : '' ?></div>
                        </div>
                        <div class="d-flex w-100">
                            <div class="col-4 bg-gradient-orange text-bold text-white m-0 p-1 border">Available</div>
                            <div class="col-8 m-0 p-1 border font-weight-bolder"><?= isset($available) ? format_num($available) : '' ?></div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>


        <!-- STOCK-IN TABLE -->
        <div class="card card-outline card-dark rounded-0 shadow printout">
            <div class="card-header py-1">
                <div class="card-title">Purchasing History</div>
                <!-- <div class="card-tools">
                    <button class="btn btn-sm btn-flat btn-light bg-gradient-light border" type="button" id="add_stockin"><i class="far fa-plus-square"></i> Add Stock In</button>
                </div> -->
            </div>
            <div class="card-body">
                <table class="table table-bordered table-stripped" id="stockin-tbl">
                    <thead>
                        <tr>
                            <th class="p-1 text-center">Quantity</th>
                            <th class="p-1 text-center">Date of Receipt</th> 
                            <?php if($item_type !== 'Non-Perishable'): ?>
                                <th class="p-1 text-center">Expiration Date</th>
                            <?php endif; ?>
                            <th class="p-1 text-center">Remarks</th>
                            <th class="p-1 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(isset($id)):
                            $stockins = pg_query($conn, "SELECT * FROM wh_stockin_list where item_id = '{$id}' order by date(date) asc");
                            while($row = pg_fetch_assoc($stockins)):
                            ?>
                            <tr>
                                <td class="p-1 align-middle text-center"><?= format_num($row['quantity']) ?></td>
                                <td class="p-1 align-middle text-center"><?= date("M d, Y", strtotime($row['date'])) ?></td>
                                <?php if($item_type !== 'Non-Perishable'): ?>
                                    <td class="p-1 align-middle text-center"><?= date("M d, Y", strtotime($row['expire_date'])) ?></td>
                                <?php endif; ?>
                                <td class="p-1 align-middle text-center"><?= $row['remarks'] ?></td>
                                <td class="p-1 align-middle text-center">
                                    <div class="btn-group btn-group-xs">
                                        <button class="btn btn-flat btn-primary btn-xs bg-gradient-primary edit_stockin" title="Edit Data" type="button" data-id = "<?= $row['id'] ?>"><small><i class="fa fa-edit"></i></small></button>
                                        <button class="btn btn-flat btn-danger btn-xs bg-gradient-danger delete_stockin" title="Delete Data" type="button" data-id = "<?= $row['id'] ?>"><small><i class="fa fa-trash"></i></small></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- STOCK-OUT TABLE-->
        <div class="card card-outline card-dark rounded-0 shadow printout">
            <div class="card-header py-1">
                <div class="card-title">Point of Sale History</div>
                <!-- <div class="card-tools">
                    <button class="btn btn-sm btn-flat btn-light bg-gradient-light border" type="button" id="add_stockout"><i class="far fa-plus-square"></i> Add Stock Out</button>
                </div> -->
            </div>
            <div class="card-body">
                <table class="table table-bordered table-stripped" id="stockout-tbl">
                    <thead>
                        <tr>
                            <th class="p-1 text-center">Quantity</th>
                            <th class="p-1 text-center">Date Issued</th>
                            <th class="p-1 text-center">Remarks</th>
                            <th class="p-1 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($id)) {
                                $stockouts = pg_query($conn, "SELECT * FROM wh_stockout_list WHERE item_id = '{$id}' ORDER BY date(date) ASC");
                                while ($row = pg_fetch_assoc($stockouts)) {
                        ?>
                                    <tr>
                                        <td class="p-1 align-middle text-center"><?= format_num($row['quantity']) ?></td>
                                        <td class="p-1 align-middle text-center"><?= date("M d, Y", strtotime($row['date'])) ?></td>
                                        <td class="p-1 align-middle text-center"><?= $row['remarks'] ?></td>
                                        <td class="p-1 align-middle text-center">
                                            <div class="btn-group btn-group-xs">
                                            <button class="btn btn-flat btn-primary btn-xs bg-gradient-primary edit_stockout" title="Edit Data" type="button" data-id="<?= $row['id'] ?>"><small><i class="fa fa-edit"></i></small></button>
                                            <button class="btn btn-flat btn-danger btn-xs bg-gradient-danger delete_stockout" title="Delete Data" type="button" data-id="<?= $row['id'] ?>"><small><i class="fa fa-trash"></i></small></button>
                                            </div>
                                        </td>
                                    </tr>
                        <?php
                                }
                            }
                        ?>
                        </tbody>
                </table>
            </div>
        </div>


        <!-- <div class="card card-outline card-dark rounded-0 shadow printout">
            <div class="card-header py-1">
                <div class="card-title">Waste History</div>
                <div class="card-tools">
                    <button class="btn btn-sm btn-flat btn-light bg-gradient-light border" type="button" id="add_waste"><i class="far fa-plus-square"></i> Add Waste Data</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-stripped" id="waste-tbl">
                    <thead>
                        <tr>
                            <th class="p-1 text-center">Date</th>
                            <th class="p-1 text-center">Quantity</th>
                            <th class="p-1 text-center">Remarks</th>
                            <th class="p-1 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <#php 
                        if(isset($id)):
                        $wastes = $conn->query("SELECT * FROM `wh_waste_list` where item_id = '{$id}' order by date(`date`) asc");
                        while($row = $wastes->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="p-1 align-middle text-center"><#= date("M d, Y", strtotime($row['date'])) ?></td>
                            <td class="p-1 align-middle text-center"><#= format_num($row['quantity']) ?></td>
                            <td class="p-1 align-middle text-center"><#= $row['remarks'] ?></td>
                            <td class="p-1 align-middle text-center">
                                <div class="btn-group btn-group-xs">
                                    <button class="btn btn-flat btn-primary btn-xs bg-gradient-primary edit_waste" title="Edit Data" type="button" data-id = "<#= $row['id'] ?>"><small><i class="fa fa-edit"></i></small></button>
                                    <button class="btn btn-flat btn-danger btn-xs bg-gradient-danger delete_waste" title="Delete Data" type="button" data-id = "<#= $row['id'] ?>"><small><i class="fa fa-trash"></i></small></button>
                                </div>
                            </td>
                        </tr>
                        <#php endwhile; ?>
                        <#php endif; ?>
                    </tbody>
                </table>
            </div>
        </div> -->
        
    </div>
</div>


<noscript id="print-header">
    <div>
        <style>
            html, body{
                min-height:unset !important;
            }
        </style>
        <div class="d-flex w-100 align-items-center">
            <div class="col-2 text-center">
                <img src="<?= validate_image($_settings->info('logo')) ?>" alt="" class="rounded-circle border" style="width: 5em;height: 5em;object-fit:cover;object-position:center center">
            </div>
            <div class="col-8">
                <div style="line-height:1em">
                    <h3 class="text-center font-weight-bold mb-0"><large><?= $_settings->info('name') ?></large></h3>
                    <h3 class="text-center font-weight-bold mb-0"><large>Stock Details</large></h3>
                </div>
            </div>
        </div>
       
        <hr>
    </div>
</noscript>


<script>
    var tbl1,tbl2, tbl3;
     function print_t(){
         if(!!tbl1)
         tbl1.fnDestroy();
         if(!!tbl2)
         tbl2.fnDestroy();
         if(!!tbl3)
         tbl3.fnDestroy();
        var h = $('head').clone()
        var p = $('#printout').clone()
        var ph = $($('noscript#print-header').html()).clone()
        var el = "";

        $('.printout').each(function(){
            var card = $(this).clone()
            card.removeClass('shadow')
            card.find('.btn').remove()
            card.find('td:nth-child(5)').remove()
            card.find('th:nth-child(5)').remove()
            el += card[0].outerHTML
        })
        h.find('title').text("order Details - Print View")
        var nw = window.open("", "_blank", "width="+($(window).width() * .8)+",left="+($(window).width() * .1)+",height="+($(window).height() * .8)+",top="+($(window).height() * .1))
            nw.document.querySelector('head').innerHTML = h.html()
            nw.document.querySelector('body').innerHTML = ph[0].outerHTML
            nw.document.querySelector('body').innerHTML += el
            nw.document.close()
            start_loader()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    nw.close()
                    end_loader()
                    tbl1 = $('#stockin-tbl').dataTable({
                        columnDefs: [
                                { orderable: false, targets: [3] }
                        ],
                        order:[0,'asc']
                    });
                    tbl2 = $('#stockout-tbl').dataTable({
                        columnDefs: [
                                { orderable: false, targets: [3] }
                        ],
                        order:[0,'asc']
                    });
                    // tbl3 = $('#waste-tbl').dataTable({
                    //     columnDefs: [
                    //             { orderable: false, targets: [3] }
                    //     ],
                    //     order:[0,'asc']
                    // });
                }, 200);
            }, 300);
    }
    $(function(){
       
        $('#print').click(function(){
            print_t()
        })

        // Stockin
        $('#add_stockin').click(function(){
            uni_modal("<i class='far fa-plus-square'></i> Add Stock-In Data", `stocks/manage_stockin.php?iid=<?= isset($id) ? $id : '' ?>&quantity=${$('#requested_quantity').val()}&expired_date=${$('#expired_date').val()}&manufactured_date=${$('#manufactured_date').val()}&request_id=${$('#request_id').val()}&supplier=${$('#supplier').val()}&physical_count=${$('#physical_count').val()}&date_approved=${$('#date_approved').val()}&date_received=${$('#date_received').val()}&physical_count_date=${$('#physical_count_date').val()}&personnel=${$('#personnel').val()}&personnel_role=${$('#personnel_role').val()}`)
        })


        $('.edit_stockin').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Edit Stock-In Data", 'stocks/manage_stockin.php?iid=<?= isset($id) ? $id : '' ?>&id=' + $(this).attr('data-id'))
        })
        $('.delete_stockin').click(function(){
			_conf("Are you sure to delete this stock-in data permanently?","delete_stockin",[$(this).attr('data-id')])
		})

        // Stockout
        $('#add_stockout').click(function(){
            uni_modal("<i class='far fa-plus-square'></i> Add Stock-out Data", 'stocks/manage_stockout.php?iid=<?= isset($id) ? $id : '' ?>')
        })
        $('.edit_stockout').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Edit Stock-out Data", 'stocks/manage_stockout.php?iid=<?= isset($id) ? $id : '' ?>&id=' + $(this).attr('data-id'))
        })
        $('.delete_stockout').click(function(){
			_conf("Are you sure to delete this stock-out data permanently?","delete_stockout",[$(this).attr('data-id')])
		})

        // Waste
        // $('#add_waste').click(function(){
        //     uni_modal("<i class='far fa-plus-square'></i> Add Waste Data", 'stocks/manage_waste.php?iid=<?= isset($id) ? $id : '' ?>')
        // })
        // $('.edit_waste').click(function(){
        //     uni_modal("<i class='fa fa-edit'></i> Edit Waste Data", 'stocks/manage_waste.php?iid=<?= isset($id) ? $id : '' ?>&id=' + $(this).attr('data-id'))
        // })
        // $('.delete_waste').click(function(){
		// 	_conf("Are you sure to delete this Waste data permanently?","delete_waste",[$(this).attr('data-id')])
		// })

        tbl1 = $('#stockin-tbl').dataTable({
			columnDefs: [
					{ orderable: false, targets: [3] }
			],
			order:[0,'asc']
		});
        tbl2 = $('#stockout-tbl').dataTable({
			columnDefs: [
					{ orderable: false, targets: [3] }
			],
			order:[0,'asc']
		});
        // tbl3 = $('#waste-tbl').dataTable({
		// 	columnDefs: [
		// 			{ orderable: false, targets: [3] }
		// 	],
		// 	order:[0,'asc']
		// });
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        $('.dataTables_paginate .pagination>li>a').addClass('p-1');
        $('.dataTables_filter input').addClass('rounded-0 form-control-sm py-1');
        
    })
    function delete_stockin($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_stockin",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
    
    function delete_stockout($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_stockout",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
    function delete_waste($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_waste",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>