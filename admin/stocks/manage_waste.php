<?php 
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `waste_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
    <form action="" id="waste-form">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
        <input type="hidden" name="item_id" value="<?= isset($item_id) ? $item_id : (isset($_GET['iid']) ? $_GET['iid'] : '') ?>">
        <div class="form-group">
            <label for="date" class="control-label">Expiration Date</label>
            <input type="date" name="date" id="date" class="form-control form-control-sm rounded-0" value="<?= isset($date) ? $date : '' ?>" max="<?= date("Y-m-d") ?>" required>
        </div>
        <div class="form-group">
            <label for="quantity" class="control-label">Quantity</label>
            <input type="number" step="any" name="quantity" id="quantity" class="form-control form-control-sm rounded-0 text-right" value="<?= isset($quantity) ? format_num($quantity) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="remarks" class="control-label">Remarks</label>
            <textarea type="3" name="remarks" id="remarks" class="form-control form-control-sm rounded-0" required><?= isset($remarks) ? ($remarks) : '' ?></textarea>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#waste-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
             if(_this[0].checkValidity() == false){
                _this[0].reportValidity() 
                return false
             }
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_waste",
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