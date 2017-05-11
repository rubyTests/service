<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class usermenumodel extends CI_Model {
		
		public function menuLink(){
			$value=[];
			$value1=[];
			$value2=[];
			$sql="SELECT * FROM `menu`";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$test[$key]=$value;
					$menuId=$value['id'];
					$sql="SELECT * FROM `submenu` WHERE menu_id='$menuId'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					array_push($value2,$result);
					//print_r($result);
					if($result){
						foreach($result as $key => $value){
							$subMenuId=$value['id'];
							$sql="SELECT * FROM `submenu_item` WHERE submenu_id='$subMenuId'";
							$result = $this->db->query($sql, $return_object = TRUE)->result_array();
							array_push($value1,$result);
						}
					}
					array_push($value,$test);
				}
				//exit;
				//return $value;
				return array(['data' => $value,'data1'=> $value2,'data2' =>$value1]);
			}
		}
		
		public function menuLink1(){
			$sql="select m.id,m.title,m.icon,m.link,sm.id as submenu_id,sm.menu_id,sm.title as submenuTitle,sm.link as submenuLink,sm.icon as submenuIcon,smi.id as item_id,smi.submenu_id as Itemsubmenu_id,smi.title as itemTitle,smi.link as itemLink from menu m LEFT JOIN submenu sm ON m.id=sm.menu_id LEFT JOIN submenu_item smi ON sm.id=smi.submenu_id ORDER BY m.id,sm.id,smi.id";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
		
		public function menuDetails(){
			$sql="SELECT * FROM menu";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
		
		public function subMenuDetails(){
			$sql="SELECT * FROM submenu";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
		
		public function subMenuItemDetails(){
			$sql="SELECT * FROM submenu_item";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
		
		public function addUserPrivileges($values){
			$data = array(
				'user_id' => $values['user_id'],
				'submenu_id' => $values['submenu_id'],
				'add_option' => $values['add_option'],
				'edit_option' => $values['edit_option'],
				'delete_option' => $values['delete_option']
			);
			$this->db->insert('user_privileges', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editUserPrivileges($Id,$values){
			$data = array(
				'user_id' => $values['user_id'],
				'submenu_id' => $values['submenu_id'],
				'add_option' => $values['add_option'],
				'edit_option' => $values['edit_option'],
				'delete_option' => $values['delete_option']
			);
			$this->db->where('id', $Id);
			$this->db->update('user_privileges', $data);
		}
		
		public function getAllUserPrivileges(){
			$sql="SELECT * FROM user_privileges";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
	}
?>