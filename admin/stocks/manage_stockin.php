<?php 
    require_once('../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = pg_query($conn, "SELECT * FROM stockin_list where id = '{$_GET['id']}' ");
        if(pg_num_rows($qry) > 0){
            foreach(pg_fetch_assoc($qry) as $k => $v){
                $$k=$v;
            }
        }
    }

    $item_type = "";
    $item_id = isset($item_id) ? $item_id : (isset($_GET['iid']) ? $_GET['iid'] : '');
    if(!empty($item_id)) {
        $item_qry = pg_query($conn, "SELECT item_type FROM item_list WHERE id = '$item_id'");
        if(pg_num_rows($item_qry) > 0) {
            $item_type = pg_fetch_assoc($item_qry)['item_type'];
        }
    }
?>


<div class="container-fluid">
    <form action="" id="stockin-form">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
        <input type="hidden" name="item_id" value="<?= $item_id ?>">

        <div class="form-group">
            <label for="quantity" class="control-label">Quantity</label>
            <input type="number" step="any" name="quantity" id="quantity" class="form-control form-control-sm rounded-0 text-left" value="<?= isset($quantity) ? format_num($quantity) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="date" class="control-label">Date of Receipt</label>
            <input type="date" name="date" id="date" class="form-control form-control-sm rounded-0" value="<?= isset($date) ? $date : '' ?>" max="<?= date("m-d-Y") ?>" required>
        </div>
        
        <?php if ($item_type != "Non-Perishable"): ?>
        <div class="form-group">
            <label for="expire_date" class="control-label">Expiration Date</label>
            <input type="date" name="expire_date" id="expire_date" class="form-control form-control-sm rounded-0 " value="<?= isset($expire_date) ? $expire_date : '' ?>" max="<?= date("m-d-Y") ?>" required>
        </div>
        <?php else: ?>
        <input type="hidden" name="expire_date" value="">
        <?php endif; ?>

        <div class="form-group">
            <label for="remarks" class="control-label">Remarks</label>
            <textarea type="3" name="remarks" id="remarks" class="form-control form-control-sm rounded-0" required><?= isset($remarks) ? ($remarks) : '' ?></textarea>
        </div>
    </form>
</div>


<script>
    $(function(){
        $('#stockin-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
             if(_this[0].checkValidity() == false){
                _this[0].reportValidity() 
                return false
             }
			start_loader();
            
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_stockin",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload()
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body, .modal").scrollTop(0);
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
    })
</script>