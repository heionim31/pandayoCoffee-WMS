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
					$v = pg_escape_string(htmlspecialchars($v));
					$data .= " {$k}='{$v}' ";
				}
			}
			$check = pg_query($this->conn, "SELECT * FROM wh_category_list WHERE name = '{$name}' ".(!empty($id) ? " AND id != {$id} " : "")." ");
			if(!$check)
				return pg_last_error($this->conn);
			$num_rows = pg_num_rows($check);
			if($num_rows > 0){
				$resp['status'] = 'failed';
				$resp['msg'] = "Category already exists.";
				return json_encode($resp);
				exit;
			}
			if(empty($id)){
				$sql = "INSERT INTO wh_category_list (name, description, status) VALUES ('{$name}', '{$description}', '{$status}')";
			}else{
				$sql = "UPDATE wh_category_list SET {$data} WHERE id = '{$id}' ";
			}
			$save = pg_query($this->conn, $sql);
			if($save){
				$cid = !empty($id) ? $id : pg_last_oid($save);
				$resp['cid'] = $cid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "New Category successfully saved.";
				else
					$resp['msg'] = "Category successfully updated.";
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = pg_last_error($this->conn)."[{$sql}]";
			}
			return json_encode($resp);
		}

		// CATEGORY - DELETE
		function delete_category(){
			extract($_POST);
			$del = pg_query($this->conn, "DELETE FROM wh_category_list WHERE id = '{$id}'");
			if($del){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success', 'Category successfully deleted.');
			} else {
				$resp['status'] = 'failed';
				$resp['error'] = pg_last_error($this->conn);
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
					$v = pg_escape_string(htmlspecialchars($v));
					$data .= " {$k}='{$v}' ";
				}
			}
			$check = pg_query($this->conn, "SELECT * FROM wh_unit_list WHERE name = '{$name}'".(!empty($id) ? " AND id != {$id} " : "")." ");
			if(!$check)
				return pg_last_error($this->conn);
			$num_rows = pg_num_rows($check);
			if($num_rows > 0){
				$resp['status'] = 'failed';
				$resp['msg'] = "unit already exists.";
				return json_encode($resp);
				exit;
			}
			if(empty($id)){
				$sql = "INSERT INTO wh_unit_list (name, abbreviation, description, status) VALUES ('{$name}', '{$abbreviation}', '{$description}', '{$status}')";
			}else{
				$sql = "UPDATE wh_unit_list SET {$data} WHERE id = '{$id}' ";
			}
			$save = pg_query($this->conn, $sql);
			if($save){
				$cid = !empty($id) ? $id : pg_last_oid($save);
				$resp['cid'] = $cid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "New Unit successfully saved.";
				else
					$resp['msg'] = "Unit successfully updated.";
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = pg_last_error($this->conn)."[{$sql}]";
			}
			return json_encode($resp);
		}
		
		// UNIT - DELETE
		function delete_unit(){
			extract($_POST);
			$del = pg_query($this->conn, "DELETE FROM wh_unit_list WHERE id = '{$id}'");
			if($del){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success', 'Unit successfully deleted.');
			} else {
				$resp['status'] = 'failed';
				$resp['error'] = pg_last_error($this->conn);
			}
			return json_encode($resp);
		}
		
		

		// ITEM - SAVE
		function save_item(){
			extract($_POST);
			$data = "";
			foreach($_POST as $k => $v){
				if(!in_array($k, array('id'))){
					if(!empty($data)) $data .= ",";
					$v = pg_escape_string(htmlspecialchars($v));
					$data .= " {$k}='{$v}' ";
				}
			}
			$check = pg_query($this->conn, "SELECT * FROM wh_item_list WHERE name = '{$name}' AND delete_flag = 0 ".(!empty($id) ? " AND id != {$id} " : "")." ");
			if(!$check)
				return pg_last_error($this->conn);
			$num_rows = pg_num_rows($check);
			if($num_rows > 0){
				$resp['status'] = 'failed';
				$resp['msg'] = "item already exists.";
				return json_encode($resp);
				exit;
			}
			if(empty($id)){
				$sql = "INSERT INTO wh_item_list (name, category_id, item_type, unit, description, status) VALUES ('{$name}', '{$category_id}', '{$item_type}', '{$unit}', '{$description}', '{$status}')";
			}else{
				$sql = "UPDATE wh_item_list set {$data} where id = '{$id}' ";
			}
			$save = pg_query($this->conn, $sql);
			if($save){
				$iid = !empty($id) ? $id : pg_last_oid($save);
				$resp['iid'] = $iid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "New item successfully saved.";
				else
					$resp['msg'] = "Item successfully updated.";

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
									pg_query($this->conn, "UPDATE wh_item_list set image_path = CONCAT('{$spath}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$iid}' ");
								}
		
								imagedestroy($temp);
							}
						}
					}
				}else{
					$resp['status'] = 'failed';
					$resp['err'] = pg_last_error($this->conn)."[{$sql}]";
				}
				if($resp['status'] == 'success')
					$this->settings->set_flashdata('success',$resp['msg']);
					return json_encode($resp);
		}

		// ITEM - DELETE
		function delete_item(){
			extract($_POST);
			$del = pg_query($this->conn, "UPDATE wh_item_list SET delete_flag = 1 WHERE id = '{$id}'");
			if($del){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success',"Item successfully deleted.");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = pg_last_error($this->conn);
			}
			return json_encode($resp);
		}

		
		// STOCK-IN -  SAVE
		function save_stockin(){
			extract($_POST);
			$data = "";
			$values = "";
			foreach($_POST as $k =>$v){
				if(!in_array($k,array('id'))){
					if(!empty($data)) $data .=",";
					if(!empty($values)) $values .= ",";
					$v = pg_escape_string(htmlspecialchars($v));
					$data .= " {$k}";
					$values .= "'{$v}'";
				}
			}
			if(empty($id)){
				$sql = "INSERT INTO wh_stockin_list ({$data}) VALUES ({$values})";
			}else{
				$set_clause = "";
				foreach($_POST as $k => $v){
					if(!in_array($k, array('id'))){
						if(!empty($set_clause)) $set_clause .= ",";
						$v = pg_escape_string(htmlspecialchars($v));
						$set_clause .= "{$k} = '{$v}'";
					}
				}
				$sql = "UPDATE wh_stockin_list SET {$set_clause} WHERE id = '{$id}' ";
			}
			
			$save = pg_query($this->conn, $sql);
			if($save){
				$cid = !empty($id) ? $id : pg_last_oid($save);
				$resp['status'] = 'success';
				if(empty($id))
					$this->settings->set_flashdata('success'," Item has been added successfully.");
				else
					$this->settings->set_flashdata('success'," Item successfully updated");
				
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = pg_last_error($this->conn)."[{$sql}]";
			}
			return json_encode($resp);
		}

		// STOCK-IN - DELETE
		function delete_stockin(){
			extract($_POST);
			$del = pg_query($this->conn, "DELETE FROM wh_stockin_list where id = '{$id}'");
			if($del){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success'," Item has been deleted successfully.");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = pg_last_error($this->conn);
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
					
					$v = pg_escape_string(htmlspecialchars($v));
					$data .= " {$k}='{$v}' ";
				}
			}
			if(empty($id)){
				$sql = "INSERT INTO stockout_list (item_id, quantity, date, remarks) VALUES ('{$item_id}', '{$quantity}', '{$date}', '{$remarks}')";

			}else{
				$set_clause = "";
				foreach($_POST as $k => $v){
					if(!in_array($k, array('id'))){
						if(!empty($set_clause)) $set_clause .= ",";
						$v = pg_escape_string(htmlspecialchars($v));
						$set_clause .= "{$k} = '{$v}'";
					}
				}
				$sql = "UPDATE stockout_list SET {$set_clause} WHERE id = '{$id}' ";
			}
			$save = pg_query($this->conn, $sql);
			if($save){
				$cid = !empty($id) ? $id : pg_last_oid($save);
				$resp['status'] = 'success';
				if(empty($id))
					$this->settings->set_flashdata('success'," Stock-out Data has been added successfully.");
				else
					$this->settings->set_flashdata('success'," Stock-out Data successfully updated");
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = pg_last_error($this->conn)."[{$sql}]";
			}
			return json_encode($resp);
		}
		
		// STOCK-OUT - DELETE
		function delete_stockout(){
			extract($_POST);
			$del = pg_query($this->conn, "DELETE FROM stockout_list where id = '{$id}'");
			if($del){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success'," Stock-out Data has been deleted successfully.");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = pg_last_error($this->conn);
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
				$sql = "INSERT INTO `wh_waste_list` set {$data} ";
			}else{
				$sql = "UPDATE `wh_waste_list` set {$data} where id = '{$id}' ";
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
			$del = $this->conn->query("DELETE FROM `wh_waste_list` where id = '{$id}'");
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