<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}

	
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}

	// IMAGE - DELETE
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}


	// CATEGORY - SAVE
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `category_list` where `name` = '{$name}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Category already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `category_list` set {$data} ";
		}else{
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['cid'] = $cid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Category successfully saved.";
			else
				$resp['msg'] = " Category successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		// if($resp['status'] == 'success')
		// 	$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}

	// CATEGORY - DELETE
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `category_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}


	// UNIT - SAVE
	function save_unit(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `unit_list` where `name` = '{$name}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "unit already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `unit_list` set {$data} ";
		}else{
			$sql = "UPDATE `unit_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['cid'] = $cid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Unit successfully saved.";
			else
				$resp['msg'] = "Unit successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	
	// UNIT - DELETE
	function delete_unit(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `unit_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Unit successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	

	// ITEM - SAVE
	function save_item(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `item_list` where `name` = '{$name}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Item already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `item_list` set {$data} ";
		}else{
			$sql = "UPDATE `item_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$iid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['iid'] = $iid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New item successfully saved.";
			else
				$resp['msg'] = " item successfully updated.";

				if(!empty($_FILES['img']['tmp_name'])){
					$img_path = "uploads/items/";
					if(!is_dir(base_app.$img_path)){
						mkdir(base_app.$img_path);
					}
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['img']['type'],$accept)){
						$resp['msg'] += " Image file type is invalid";
					}else{
						if($_FILES['img']['type'] == 'image/jpeg')
							$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
						elseif($_FILES['img']['type'] == 'image/png')
							$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
						if(!$uploadfile){
							$resp['msg'] +=  " Image is invalid";
						}else{
							list($width, $height) =getimagesize($_FILES['img']['tmp_name']);
							if($width > 250 || $height > 250){
								if($width > $height){
									$perc = ($width - 250) / $width;
									$width = 250;
									$height = $height - ($height * $perc);
								}else{
									$perc = ($height - 250) / $height;
									$height = 250;
									$width = $width - ($width * $perc);
								}
							}
							$temp = imagescale($uploadfile,$width,$height);
							$spath = $img_path.'/'.$_FILES['img']['name'];
							$i = 1;
							while(true){
								if(is_file(base_app.$spath)){
									$spath = $img_path.'/'.($i++).'_'.$_FILES['img']['name'];
								}else{
									break;
								}
							}
							if($_FILES['img']['type'] == 'image/jpeg')
							$upload = imagejpeg($temp,base_app.$spath,60);
							elseif($_FILES['img']['type'] == 'image/png')
							$upload = imagepng($temp,base_app.$spath,6);
							if($upload){
								$this->conn->query("UPDATE item_list set image_path = CONCAT('{$spath}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$iid}' ");
							}
	
							imagedestroy($temp);
						}
					}
				}
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}

	// ITEM - DELETE
	function delete_item(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `item_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," item successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}


	// STOCK-IN -  SAVE
	function save_stockin(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `stockin_list` set {$data} ";
		}else{
			$sql = "UPDATE `stockin_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success'," Item has been added successfully.");
			else
				$this->settings->set_flashdata('success'," Item successfully updated");
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}

	// STOCK-IN - DELETE
	function delete_stockin(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `stockin_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Item has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}


	// STOCK-OUT - SAVE
	function save_stockout(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				
				// Check for negative or zero value
				if($v <= 0){
					$resp['status'] = 'failed';
					$resp['err'] = "Invalid value for {$k}. Please enter a positive value.";
					return json_encode($resp);
				}
				
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `stockout_list` set {$data} ";
		}else{
			$sql = "UPDATE `stockout_list` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success'," Stock-out Data has been added successfully.");
			else
				$this->settings->set_flashdata('success'," Stock-out Data successfully updated");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}

	// STOCK-OUT - DELETE
	function delete_stockout(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `stockout_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Stock-out Data has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}


	// WASTE - SAVE
	function save_waste(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `waste_list` set {$data} ";
		}else{
			$sql = "UPDATE `waste_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success'," Waste Data has been added successfully.");
			else
				$this->settings->set_flashdata('success'," Waste Data successfully updated");
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}

	// WASTE - DELETE
	function delete_waste(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `waste_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Waste Data has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
}


	$Master = new Master();
	$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
	$sysset = new SystemSettings();
	switch ($action) {
		case 'delete_img':
			echo $Master->delete_img();
		break;
		case 'save_category':
			echo $Master->save_category();
		break;
		case 'delete_category':
			echo $Master->delete_category();
		break;
		case 'save_unit':
			echo $Master->save_unit();
		break;
		case 'delete_unit':
			echo $Master->delete_unit();
		break;
		case 'save_item':
			echo $Master->save_item();
		break;
		case 'delete_item':
			echo $Master->delete_item();
		break;
		case 'save_stockin':
			echo $Master->save_stockin();
		break;
		case 'delete_stockin':
			echo $Master->delete_stockin();
		break;
		case 'save_stockout':
			echo $Master->save_stockout();
		break;
		case 'delete_stockout':
			echo $Master->delete_stockout();
		break;
		case 'save_waste':
			echo $Master->save_waste();
		break;
		case 'delete_waste':
			echo $Master->delete_waste();
		break;
		default:
			// echo $sysset->index();
			break;
	}
?>